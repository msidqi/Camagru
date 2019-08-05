<?php

function isLoggedIn(){
	if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])
									&& isset($_SESSION['user_email']))
		return (true);
	else
		return (false);
}