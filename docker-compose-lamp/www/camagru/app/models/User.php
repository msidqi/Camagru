<?php

class User {
	private $db;

	public function __construct(){
		$this->db = new Database;
	}

	public function emailExists($user_email){
		$this->db->query('SELECT * FROM `users` WHERE `email` = :email');
		$this->db->bind(':email', $user_email, PDO::PARAM_STR);

		// object containing the found user row.
		if ($this->db->getSingleResult())
			return (true);
		return (false);
	}

	public function userNameExists($user_name){
		$this->db->query('SELECT * FROM `users` WHERE `user_name` = :user_name');
		$this->db->bind(':user_name', $user_name, PDO::PARAM_STR);

		// object containing the found user row.
		if ($this->db->getSingleResult())
			return (true);
		return (false);
	}

	public function registerUser($data){

		$this->db->query("INSERT INTO `users`(user_name, email, password) VALUES(:user_name, :email, :password)");
		$this->db->bind(':user_name', $data['user_name'], PDO::PARAM_STR);
		$this->db->bind(':email', $data['email'], PDO::PARAM_STR);
		$this->db->bind(':password', $data['password'], PDO::PARAM_STR);

		if ($this->db->execute())
			return (true);
		return (false);
	}

	public function loginUser($data){
		if (!empty($data['user_name'])){
			$this->db->query("SELECT * FROM `users` WHERE `user_name` = :user_name");
			$this->db->bind(':user_name', $data['user_name'], PDO::PARAM_STR);
			$result = $this->db->getSingleResult();
			if (password_verify($data['password'], $result->password)) // verifies password against hashed password and protects againts timing attacks!
				return ($result);
		} else if (!empty($data['email'])){
			$this->db->query("SELECT * FROM `users` WHERE `email` = :email");
			$this->db->bind(':email', $data['email'], PDO::PARAM_STR);
			$result = $this->db->getSingleResult();
			if (password_verify($data['password'], $result->password))
				return ($result);
		}
		return (false);
	}
}
