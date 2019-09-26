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


function createTokken($length = 64){
	if (function_exists('function_exists'))
		return (bin2hex(random_bytes($length)));
	if (function_exists('openssl_random_pseudo_bytes'))
		return (bin2hex(openssl_random_pseudo_bytes($length)));
}

function base64_url_encode($input) {
	return strtr(base64_encode($input), '+/=', '._-');
}
   
function base64_url_decode($input) {
	return base64_decode(strtr($input, '._-', '+/='));
}