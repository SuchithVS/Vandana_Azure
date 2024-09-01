<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_SERVER', 'mysql');
define('DB_USERNAME', 'user1');
define('DB_PASSWORD', 'passwd');
define('DB_NAME', 'library_management');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error() . " (Error code: " . mysqli_connect_errno() . ")");
}
?>