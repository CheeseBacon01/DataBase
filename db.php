<?php
$host = 'localhost';
$dbuser = 'root';//D1248963
$dbpassword = '';//#NqgHtx7W
$dbname = 'teachers'; //D1248963

$mysqli = new mysqli($host, $dbuser, $dbpassword, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Set the character set to utf8
$mysqli->set_charset("utf8");
?>
