<?php


// require_once('../require.php');

// $dsn = 'mysql:host=' . DB_HOST;
// // echo DB_USER . PHP_EOL;
// try {
//     $database = new PDO($dsn, DB_USER, DB_PASS);
//     $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// }
// catch (PDOException $e){
//     echo 'SETUP ERROR : ' , $e->getMessage();
// }
// new Database;
//$database->query("CREATE DATABASE IF NOT EXISTS camagru_db;");
/*USE camagru_db;
CREATE TABLE IF NOT EXISTS `users` (
`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
`user_name` VARCHAR(255) NOT NULL,
`email`	VARCHAR(255) NOT NULL,
`password` VARCHAR(255) NOT NULL,
`verified` BIT NOT NULL DEFAULT B'0',
`user_image` VARCHAR(255) NOT NULL DEFAULT '/photos/default/default_profile.jpeg',
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `posts` (
`image_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
`user_id` INT NOT NULL,
`image` VARCHAR(255) NOT NULL,
`image_type` VARCHAR(25) NOT NULL,
`image_size` INT NOT NULL,
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `comments` (
	`image_id` INT NOT NULL ,
	`user_id` INT NOT NULL,
	`comment` VARCHAR(1024) NOT NULL,
	`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS `likes` (
	`image_id` INT NOT NULL,
	`user_id` INT NOT NULL
);");*/