<?php
$host = 'localhost'; 
$dbname = 'loginDB';
$username = 'root';  
$password = '';     

$mysqli = mysqli_connect($host, $username, $password, $dbname);

if (mysqli_connect_error()) {
    die("Connection failed: " . mysqli_connect_error());
}
?>