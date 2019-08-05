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

	public function getUserByEmail($user_email){
		$this->db->query('SELECT * FROM `users` WHERE `email` = :email');
		$this->db->bind(':email', $user_email, PDO::PARAM_STR);

		// object containing the found user row.
		$user = $this->db->getSingleResult();
		if ($user)
			return ($user);
		return (false);
	}

	public function getUserByUserName($user_name){
		$this->db->query('SELECT * FROM `users` WHERE `user_name` = :user_name');
		$this->db->bind(':user_name', $user_name, PDO::PARAM_STR);

		// object containing the found user row.
		$user = $this->db->getSingleResult();
		if ($user)
			return ($user);
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

	public function changeEmail($data){
		$user = $this->getUserByEmail($data['email']);
		if ($user){
			//update email
			$this->db->query("UPDATE `users` SET `email` = :new_email WHERE `email` = $user->email");
			$this->db->bind(':new_email', $data['new_email'], PDO::PARAM_STR);
			if ($this->db->execute())
				return (true);
		}
		return (false);
	}

	public function changeUserName($data){
		$user = $this->getUserByUserName($data['user_name']);
		if ($user){
			//update user name
			$this->db->query("UPDATE `users` SET `user_name` = :new_user_name WHERE `user_name` = $user->user_name");
			$this->db->bind(':new_user_name', $data['new_user_name'], PDO::PARAM_STR);
			if ($this->db->execute())
				return (true);
		}
		return (false);
	}

	public function changePassword($data){
		$user = $this->getUserByUserEmail($data['email']);
		if ($user){
			//update password
			$this->db->query("UPDATE `users` SET `password` = :new_password WHERE `email` = $user->email");
			$this->db->bind(':new_password', $data['new_password'], PDO::PARAM_STR);
			if ($this->db->execute())
				return (true);
		}
		return (false);
	}

	public function getProfilePhoto($user_name){
		$user = $this->getUserByUserName($user_name);
		if ($user){
			$path = $user->user_image;
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$image = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(APPROOT . $path));
			return ($image);
		}
		return (false);
	}

	public function changeProfilePhoto($data){
		$user = $this->getUserByUserName($data['user_name']);
		if ($user){
			//update user name
			$this->db->query("UPDATE `users` SET `profile_photo` = :new_profile_photo WHERE `user_name` = $user->user_name");
			$this->db->bind(':new_profile_photo', $data['new_profile_photo'], PDO::PARAM_STR);
			if ($this->db->execute())
				return (true);
		}
		return (false);
	}
	
}
