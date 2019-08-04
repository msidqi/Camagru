<?php
session_start();
class Users extends Controller {
	private $userModel;

	public function __construct(){
		$this->userModel = $this->model('User');
	}

	public function register(){ // should redirect to index page if user already registered

		// if the incoming request is of type $_POST[], process it.
		// else load the form
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){ // if POST request, run the validation proccess.
			$user_name = trim($_POST['user_name']);
			$email = trim($_POST['email']);
			$data = [
				'user_name'			=> $user_name, //store values here so the user doesnt have to retype it.
				'email'				=> $email,
				'password'			=> '',
				'confirm_password'	=> '',
				'name_error'		=> '',
				'email_error'		=> '',
				'password_error'	=> '',
				'confirm_password_error' => ''
			];

			if (empty($user_name)){
				$data['name_error'] = 'Choose a user name.';
			} else {
				switch (1337) {
					case strlen($user_name) < 4 : 
						$data['name_error'] = 'User name must be 4 characters or longer.';
						break ;
					case strlen($user_name) > 16 :
						$data['name_error'] = 'User name must be 15 characters or shorter.';
						break ;
					case !preg_match('/^[A-Z]([a-z0-9])*([_-]){0,1}([a-z0-9])+$/', $user_name) :
						$data['name_error'] = 'User name must start with capital letter and contain characters from A-Z a-z, 0-9, one non-ending hyphen at max.';
						break ;
					case $this->userModel->userNameExists($user_name) :
						$data['name_error'] = 'User name is taken';
						break ;
					default :
						$data['user_name'] = $user_name;
						$data['name_error'] = '';
				}
			}
			if (empty($email)){
				$data['email_error'] = 'Please provide your email address.';
			} else {
				switch (1337) {
					case !filter_var($email, FILTER_VALIDATE_EMAIL) :
						$data['email_error'] = 'Please provide a valid email address.';
						break ;
					case $email !== filter_var($email, FILTER_SANITIZE_EMAIL) :
						$data['email_error'] = 'Please provide a valid email address!';
						break ;
					case $this->userModel->emailExists($email) :
						$data['email_error'] = 'Email already exists.';
						break ;
					default :
						$data['email'] = $email;
						$data['email_error'] = '';
				}
			}
			if (empty($_POST['password'])){
				$data['password_error'] = 'Password can\'t be empty';
			} else {
				switch (1337) {
					case strlen($_POST['password']) > 25 :
						$data['password_error'] = 'Your password should be 25 characters or shorter';
						break ;
					case strlen($_POST['password']) < 5 :
						$data['password_error'] = 'Your password should be 5 characters or longer';
						break ;
					case !preg_match('/^[0-9a-zA-Z_+=-]{5,25}$/', $_POST['password']) :
						$data['password_error'] = 'Your password should contain only character from A-Z, a-z, 0-9, _, -, +, =';	
						break ;
					default :
						$data['password'] = $_POST['password'];
						$data['password_error'] = '';
				}
			}
			if (empty($_POST['confirm_password'])){
				$data['confirm_password_error'] = 'Please confirm your password.';
			} else {
				switch (1337) {
					case $_POST['confirm_password'] !== $data['password'] :
						$data['confirm_password_error'] = 'Password does not match.';
						break ;
					default :
						$data['confirm_password'] = $_POST['confirm_password'];
						$data['confirm_password_error'] = '';
				}
			}
			// registration data is good. send query to model. else reload register page with appropriate errors.
			if (empty($data['name_error']) && empty($data['email_error'])
				&& empty($data['password_error']) && empty($data['confirm_password_error'])){
				
				$data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
				if ($this->userModel->registerUser($data)){
					// mail($data['email'], 'Registration success', 'yeet');
					redirect('users/login'); // redirect through url to login page
				} else {
					$data['name_error'] = 'Something went wrong!!!';
					$this->view('users/register', $data); // data is valid but something went wrong in model
				}
			} else
				$this->view('users/register', $data); // data is not valid

		} else {
			$data = [
				'user_name'			=> '', // not POST request => load register page with empty data
				'email'				=> '',
				'password'			=> '',
				'confirm_password'	=> '',
				'name_error'		=> '',
				'email_error'		=> '',
				'password_error'	=> '',
				'confirm_password_error' => ''
			];
			$this->view('users/register', $data);
		}
	}

	public function login(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$user_name = trim($_POST['user_name/email']);
			$email = trim($_POST['user_name/email']);

			$data = [
				'user_name'				=> $user_name,
				'email'					=> $email,
				'password'				=> '',
				'name_or_email_error'	=> '',
				'password_error'		=> ''
			];

			if (empty($user_name) && empty($email)){
				$data['name_or_email_error'] = 'Please enter your email or user name.';
			} else {
				switch (1337) {
					case strlen($user_name) < 4 : 
						$data['name_or_email_error'] = 'Incorrect user name.';
						break ;
					case strlen($user_name) > 16 :
						$data['name_or_email_error'] = 'Incorrect user name.';
						break ;
					case !preg_match('/^[A-Z]([a-z0-9])*([_-]){0,1}([a-z0-9])+$/', $user_name) :
						$data['name_or_email_error'] = 'Incorrect User name.';
						break ;
					case !$this->userModel->userNameExists($user_name) :
						$data['name_or_email_error'] = 'User not found.';
						break ;
					default :
						$email = '';
						$data['email'] = '';
						$data['name_or_email_error'] = '';
				}
			}
			if (empty($email) && empty($user_name)){
				$data['name_or_email_error'] = 'Please enter your email or username.';
			} else if (!empty($data['name_or_email_error'])) {
				switch (1337) {
					case !filter_var($email, FILTER_VALIDATE_EMAIL) :
						$data['name_or_email_error'] = 'Incorrect email address.';
						break ;
					case $email !== filter_var($email, FILTER_SANITIZE_EMAIL) :
						$data['name_or_email_error'] = 'Incorrect email address!';
						break ;
					case !$this->userModel->emailExists($email) :
						$data['name_or_email_error'] = 'No account found with this email.';
					default :
						$data['email'] = $email;
						$user_name = '';
						$data['user_name'] = '';
						$data['name_or_email_error'] = '';
				}
			}
			if (empty($_POST['password'])){
				$data['password_error'] = 'Please enter your password.';
			} else {
				switch (1337) {
					case !preg_match('/^[0-9a-zA-Z_+=-]{5,25}$/', $_POST['password']) :
						$data['password_error'] = 'Incorrect password.';	
						break ;
					default :
						$data['password'] = $_POST['password'];
						$data['password_error'] = '';
				}
			}
			// if user input is correct, check if it exists in database then login. else reload page with appropriate errors
			if (empty($data['name_or_email_error'])	&& empty($data['password_error'])){
				$user = $this->userModel->loginUser($data);
				if ($user){
					// start session
					/*if (mail('vezoviro@mail-fix.com', 'Registration success', 'yeet'))
						echo "email sent<br>";*/
					$this->createUserSession($user);
					redirect('pages/index');
				} else {
					$data['password_error'] = 'Incorrect password';
					$this->view('users/login', $data);
				}
			} else
				$this->view('users/login', $data);
		} else {
			$data = [
				'user_name'				=> '',
				'email'					=> '',
				'password'				=> '',
				'name_or_email_error'	=> '',
				'password_error'		=> ''
			];
			$this->view('users/login', $data);
		}
	}


	public function logout(){
		$this->destroyUserSession();
		redirect('users/login');
	}

	public function profile(){
		if ($this->isLoggedIn()){

			$data = [
				'title'			=> 'profile page',
				'description'	=> 'best profile page ever',
				'user_name'		=> $_SESSION['user_name'],
			];

			$this->view('users/profile', $data);
		} else {
			$this->view('users/login');
		}
	}

	public function createUserSession($user){
		$_SESSION['user_id'] = $user->id;
		$_SESSION['user_name'] = $user->user_name;
		$_SESSION['user_email'] = $user->email;
	}

	public function destroyUserSession(){
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
		unset($_SESSION['user_email']);
		session_destroy();
	}

	public function isLoggedIn(){
		if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])
										&& isset($_SESSION['user_email']))
			return (true);
		else
			return (false);
	}

	public function changeUserName($new_user_name){
		if ($this->isLoggedIn() && 
		$this->userModel->userNameExists($_SESSION['user_name'])){
			$data = [
				'user_name'		=> $_SESSION['user_name'],
				'new_user_name'	=> $new_user_name
			];
			if ($this->userModel->changeUserName($data)){
				$_SESSION['user_name'] = $new_user_name;
				redirect('users/profile');
			}
		}
		echo "Error changing user_name<br>";
	}

	public function changeEmail($new_email){
		if ($this->isLoggedIn() && 
		$this->userModel->emailExists($_SESSION['email'])){
			$data = [
				'email'		=> $_SESSION['email'],
				'new_email'	=> $new_email
			];
			if ($this->userModel->changeEmail($data)){
				$_SESSION['email'] = $new_email;
				redirect('users/profile');
			}
		}
		echo "Error changing email<br>";
	}

	public function changePassword($email, $new_password){
		if ($this->isLoggedIn()){									// with login
			if (preg_match('/^[0-9a-zA-Z_+=-]{5,25}$/', $new_password)){
				$data = [
					'email'		=> $email,
					'new_password'	=> password_hash($new_password, PASSWORD_DEFAULT)
				];
				if ($this->userModel->changePassword($data))
					redirect('users/profile');
			}
		} else if ($this->userModel->emailExists($email)){		 // without login
			// send password reset email.

			// he connects throught that link them =>
			/*if ($this->userModel->changePassword($email, $new_password))
				return (true);*/
		}
		echo "Error changing password<br>";
	}

	public function changeProfilePhoto($new_profile_photo){
		if ($this->isLoggedIn() && 
		$this->userModel->userNameExists($_SESSION['user_name'])){
			$data = [
				'user_name'		=> $_SESSION['user_name'],
				'new_profile_photo'	=> $new_profile_photo
			];
			if ($this->userModel->changeProfilePhoto($data)){
				redirect('users/profile');
			}
		}
		echo "Error changing profile pic<br>";
	}

	public function verifyUser(){

	}

}