<?php

class Users extends Controller {
	public function __construct(){

	}

	public function register(){
		// if the incoming request is of type $_POST[], process it.
		// else load the form
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			echo 'process';
		} else {
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
}