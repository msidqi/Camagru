<?php


require_once('../../app/require.php');

$dsn = 'mysql:host=' . DB_HOST;

try {
    $database = new PDO($dsn, DB_USER, DB_PASS);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $querystring = file_get_contents('../../app/config/db.sql');
    $database->query($querystring);
}
catch (PDOException $e){
    echo 'SETUP ERROR : ' , $e->getMessage();
}
