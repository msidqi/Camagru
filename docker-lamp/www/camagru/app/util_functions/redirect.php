<?php

// redirects through url
function redirect($page){
	header('location: ' . URLROOT . '/' . $page);
}
?>