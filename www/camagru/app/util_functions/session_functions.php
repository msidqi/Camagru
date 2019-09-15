<?php

function isLoggedIn(){
	if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])
									&& isset($_SESSION['user_email']))
		return (true);
	else
		return (false);
}


function createUserSession($user){
	$_SESSION['user_id'] = $user->id;
	$_SESSION['user_name'] = $user->user_name;
	$_SESSION['user_email'] = $user->email;
}

function destroyUserSession(){
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['user_email']);
	session_destroy();
}