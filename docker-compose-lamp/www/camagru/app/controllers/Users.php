<?php
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
						$data['name_error'] = 'User name must start with capital letter and contain characters from A-Z a-z, 0-9, one hyphen at max.';
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
					case !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{5,25}$/', $_POST['password']) :
						$data['password_error'] = 'Your password should contain and at least 1 digit and 1 character';	
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
					$link = '<a href="' . URLROOT . '/users/login?xjk=' . base64_url_encode($this->userModel->getUserId($data['user_name'])) . '&hr=' . md5($data['user_name']) .'">Click here</a>';
					// $subject = URLROOT . '/users/login?xjk=' . base64_encode($user_id) . '&hr=' . md5($user_name);
					mail($data['email'], 'Verify email', 'Hello ' . $data['user_name'] . PHP_EOL . 'Please verify your email by visiting the following link :' . PHP_EOL . $link);
					$this->view('users/login', $data); // redirect through url to login page
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$user_name = trim($_POST['user_name/email']);
			$email = trim($_POST['user_name/email']);

			$data = [
				'user_name'				=> $user_name,
				'email'					=> $email,
				'password'				=> '',
				'name_or_email_error'	=> '',
				'password_error'		=> ''
			];

			if (empty($user_name) && empty($email)) {
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
					case !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{5,25}$/', $_POST['password']) :
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
				if ($user && $user->verified == '1'){
					// create session
					createUserSession($user);
					redirect('pages/index');
				} else {
					if ($user && $user->verified == '0')
						$data['password_error'] = 'A verification email has been sent to your email.';
					else
						$data['password_error'] = 'Incorrect password';
					$this->view('users/login', $data);
				}
			} else
				$this->view('users/login', $data);
		} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['xjk']) && isset($_GET['hr'])) {
			$user = $this->userModel->getUserById(base64_url_decode($_GET['xjk']));
			$data = [
				'user_name'				=> '',
				'email'					=> '',
				'password'				=> '',
				'name_or_email_error'	=> '',
				'password_error'		=> ''
			];
			if ($user && md5($user->user_name) === $_GET['hr']){
				$this->userModel->verifyUser($user->id);
			}
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
		destroyUserSession();
		redirect('users/login');
	}

	public function profile(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isLoggedIn()){
			$data = [
				'user_name'		=> $_SESSION['user_name'],
				'profile_photo'	=> $this->userModel->getProfilePhoto($_SESSION['user_name']),
				'description'	=> 'best profile page ever',
				'name_error'		=> '',
				'email_error'		=> '',
				'password_error'	=> '',
				'name_success'		=> '',
				'email_success'		=> '',
				'password_success'	=> '',
				'notification'		=> $this->userModel->notificationStatus($_SESSION['user_name']),
			];
			if (!empty($_POST['newusername'])){
				$newuser_name = $_POST['newusername'];
				switch (1337) {
					case strlen($newuser_name) < 4 : 
						$data['name_error'] = 'User name must be 4 characters or longer.';
						break ;
					case strlen($newuser_name) > 16 :
						$data['name_error'] = 'User name must be 15 characters or shorter.';
						break ;
					case !preg_match('/^[A-Z]([a-z0-9])*([_-]){0,1}([a-z0-9])+$/', $newuser_name) :
						$data['name_error'] = 'User name must start with capital letter and contain characters from A-Z a-z, 0-9, one non-ending hyphen at max.';
						break ;
					case $this->userModel->userNameExists($newuser_name) :
						$data['name_error'] = 'User name is taken';
						break ;
					case !($this->userModel->changeUserName([ 'user_name'	=> $_SESSION['user_name'],
															'new_user_name'	=> $newuser_name ])) :
						$data['password_error'] = 'User doesn\'t exist';
						break ;
					default :
						$data['user_name'] = $newuser_name;
						$_SESSION['user_name'] = $newuser_name;
						$data['name_error'] = '';
						$data['name_success'] = 's';
				}
			} elseif (!empty($_POST['newemail'])){
				$newemail = $_POST['newemail'];
				switch (1337) {
					case !filter_var($newemail, FILTER_VALIDATE_EMAIL) :
						$data['email_error'] = 'Please provide a valid email address.';
						break ;
					case $newemail !== filter_var($newemail, FILTER_SANITIZE_EMAIL) :
						$data['email_error'] = 'Please provide a valid email address!';
						break ;
					case $this->userModel->emailExists($newemail) :
						$data['email_error'] = 'Email already exists.';
						break ;
					case !($this->userModel->changeEmail([ 'email'		=> $_SESSION['user_email'],
														'new_email'		=> $newemail ])) :
						$data['password_error'] = 'User doesn\'t exist';
						break ;
					default :
						$data['email_error'] = '';
						$_SESSION['user_email'] = $newemail;
						$data['user_success'] = 's';
				}
			} elseif (!empty($_POST['newpassword'])){
				$newpassword = $_POST['newpassword'];
				switch (1337) {
					case strlen($newpassword) > 25 :
						$data['password_error'] = 'Your password should be 25 characters or shorter';
						break ;
					case strlen($newpassword) < 5 :
						$data['password_error'] = 'Your password should be 5 characters or longer';
						break ;
					case !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{5,25}$/', $newpassword) :
						$data['password_error'] = 'Your password should contain and at least 1 digit and 1 character.';	
						break ;
					case !($this->userModel->changePassword([ 'email'		=> $_SESSION['user_email'],
															'new_password'	=> password_hash($newpassword, PASSWORD_DEFAULT) ])) :
						$data['password_error'] = 'User doesn\'t exist';
						break ;
					default :
						$data['password_error'] = '';
						$data['password_success'] = 's';
				}
			} elseif (!empty($_POST['notification'])){
				$data['notification'] = $this->userModel->changeNotification($_SESSION['user_name']);
			}
			// var_dump($data);
			$this->view('users/profile', $data);
		} elseif (isLoggedIn()){
			if (!empty($_SESSION['user_name']))
				$user_name = $_SESSION['user_name'];
			else
				$user_name = '';
			$notification = $this->userModel->notificationStatus($_SESSION['user_name']);
			$data = [
				'description'	=> 'best profile page ever',
				'user_name'		=> $user_name,
				'profile_photo'	=> $this->userModel->getProfilePhoto($user_name),
				'notification'	=> $notification
			];
			// var_dump($data);

			$this->view('users/profile', $data);
		} else {
			redirect('users/login');
		}
	}

	public function changeUserName($new_user_name = null){
		if (isLoggedIn() && 
		$this->userModel->userNameExists($user_name)){
			$user_name = $_SESSION['user_name'];
			$data = [
				'user_name'		=> $user_name,
				'new_user_name'	=> $new_user_name
			];
			if ($this->userModel->changeUserName($data)){
				$_SESSION['user_name'] = $new_user_name;
				redirect('users/profile');
			}
		}
		redirect('users/login');
	}

	public function changeEmail($new_email){
		if (isLoggedIn() && 
		$this->userModel->emailExists($_SESSION['email'])){
			$data = [
				'email'		=> $_SESSION['email'],
				'new_email'	=> $new_email
			];
			if ($this->userModel->changeEmail($data)){
				$_SESSION['email'] = $new_email;
				return (true);
			}
		}
		return (false);
	}


	public function changepassword(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->userModel->isVerified($_SESSION['user_email'])){
			$data = [
				'password'			=> '',
				'confirm_password'	=> '',
				'password_error'	=> '',
				'confirm_password_error' => ''
				
			];

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
					case !preg_match('/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{5,25}$/', $_POST['password']) :
						$data['password_error'] = 'Your password should contain and at least 1 digit and 1 character';	
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
			if (empty($data['confirm_password_error']) && empty($data['password_error'])){
				if ($this->userModel->changePassword([ 'email'		=> $_SESSION['user_email'],
								'new_password'	=> password_hash($data['confirm_password'], PASSWORD_DEFAULT) ])){
					// createUserSession($user);
					var_dump("CHANGED");
					$this->userModel->deleteTokken($_SESSION['user_email']);
					redirect('users/login');
				} else
					$data['password_error'] = 'User doesn\'t exist';
			}
			$this->view('users/changepassword', $data);
		} else
			redirect('pages/index');
	}

	public function resetpassword() {
		if ($_SERVER['REQUEST_METHOD'] == 'GET' && !isLoggedIn() && !empty($_GET['base']) && !empty($_GET['tokken'])){
						$email_tokken = [
							'email' => base64_url_decode($_GET['base']),
						 	'tokken' =>$_GET['tokken']
						];
				if ($this->userModel->isVerified($email_tokken['email'])
						&& $user = $this->userModel->validatePasswdTokken($email_tokken)){
						$_SESSION['user_email'] = $email_tokken['email'];
					$this->view('users/changepassword');
				} else
					$this->view('users/reset', [
						'email'				=> '',
						'email_error'		=> 'An error has occured, Please try again another time.',
						'email_success'		=> '',
					]);
		} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && !isLoggedIn()){
			$this->view('users/reset');
		} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isLoggedIn() && !empty($_POST['email'])){
			$data = [
				'email'				=> 	$_POST['email'],
				'email_error'		=> '',
				'email_success'		=> '',
			];
			if ($this->userModel->emailExists($_POST['email'])){
				if ($this->userModel->isVerified($_POST['email'])){
					// var_dump('We got a request to reset your Camagru password : ' . PHP_EOL . URLROOT . '/users/resetpassword?tokken=' . $this->userModel->resetPasswdTokken($_POST['email']) . '&base=' . base64_url_encode($_POST['email']) . PHP_EOL . 'if you didnt request a password change, please ignore this email.');
				$data['email_success'] = 'A password-reset mail has been sent to your email.';
				mail($_POST['email'], 'Reset password for Camagru', 'We got a request to reset your Camagru password : ' . PHP_EOL . URLROOT . '/users/resetpassword?tokken=' . $this->userModel->resetPasswdTokken($_POST['email']) . '&base=' . base64_url_encode($_POST['email']) . PHP_EOL . 'if you didnt request a password change, please ignore this email.');
				} else
					$data['email_error'] = 'Your account is not verified.';
			} else
				$data['email_error'] = 'Your account is not registered.';
			$this->view('users/reset', $data);
		} else
			redirect('users/resetpassword');
	}
}
