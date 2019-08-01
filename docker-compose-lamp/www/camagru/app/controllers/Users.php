<?php

class Users extends Controller {
	public function __construct(){

	}

	public function register(){ // should redirect to index page if user already registered
		// if the incoming request is of type $_POST[], process it.
		// else load the form

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){ // if POST request, run the validation proccess.
			$user_name = trim($_POST['user_name']);
			if (empty($user_name)){
				$data['name_error'] = 'Choose a User name.';
			} else {
				switch (1337) {
					case !preg_match('/^[A-Z]([a-z0-9])*([_-])*([a-z0-9])+$/', $user_name) :
						$data['name_error'] = 'User name must start with capital letter and contain characters from a-z, 0-9, _- (no ending hyphens).';
						break ;
					case strlen($user_name) < 4 : 
						$data['name_error'] = 'User name must be 4 characters or longer.';
						break ;
					case strlen($user_name) > 16 :
						$data['name_error'] = 'User name must be 15 characters or shorter.';
						break ;
					default :
						$data['user_name'] = $user_name;
						$data['name_error'] = '';
				}
			}
			$email = trim($_POST['email']);
			if (empty($email)){
				$data['email_error'] = 'Please povide your email address.';
			} else {
				switch (1337) {
					case !filter_var($email, FILTER_VALIDATE_EMAIL) :
						$data['email_error'] = 'Please provide a valid email address.';
						break ;
					case $email !== filter_var($email, FILTER_SANITIZE_EMAIL) :
						$data['email_error'] = 'Please provide a valid email address!';
					default :
						$data['email'] = $email;
						$data['email_error'] = '';
				}
			}
			if (empty($_POST['password'])){
				$data['password_error'] = 'Password can\'t be empty';
			} else {
				switch (1337) {
					case !preg_match('/^[0-9a-zA-Z_+=-]{5,25}$/', $_POST['password']) :
						$data['password_error'] = 'Your password should contain only character from A-Z, a-z, 0-9, _, -, +, =';
						break ;
					case strlen($_POST['password'] < 5) :
						$data['password_error'] = 'Your password should be 5 characters or longer';
						break ;
					case strlen($_POST['password'] > 25) :
						$data['password_error'] = 'Your password should be 25 characters or shorter';
						break ;
					default :
						$data['password'] = $_POST['pasword'];
						$data['password_error'] = '';
				}
			}
			if (empty($_POST['confirm_password'])){
				$data['confirm_password_error'] = 'Please confirm your password.';
			} else {
				switch (1337) {
					case $_POST['confirm_password'] !== $data['password']:
						$data['password_confirm_error'] = 'Password does not match.';
						break ;
					default :
						$data['confirm_password'] = $_POST['confirm_password'];
						$data['confirm_password_error'] = '';
				}
			}
			// registration data is good.
			

		} else { // not POST request => reload register page with empty data
			$data = [
				'user_name'			=> '', //store values here so the user doesnt have to retype it.
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
			echo 'login_process';
		} else {
			$data = [
				'user_name'			=> '',
				'password'			=> '',
				'name_error'		=> '',
				'password_error'	=> '',
			];

			$this->view('users/login', $data);
		}
	}
}