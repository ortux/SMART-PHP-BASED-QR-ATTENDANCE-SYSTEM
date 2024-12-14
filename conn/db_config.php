<?php
//Database Credential
$servername = "localhost"; //for XAMPP ONLY CHANGE ACCORDINGLY
$password = "";//THERE IS NO PASSWORD FOR XAMPP
$username = "root";//DEAFULT USERNAME ON XAMPP
$dbaname = "atten";

// connection
$conn = new mysqli($servername, $username, $password, $dbaname);

if ($conn->connect_error) {
    die("Connection Failed". $conn->connect_error);
}

?>
<!--done database connection -->