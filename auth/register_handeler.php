<?php
// CALLING THE DATABASE CONNECTION FILE i.e. 'db_config.php'

include("../conn/db_config.php");

//working for reciving post data from register.html
$username = $_POST['username'];//$username is a variable which is getting the input username from user
$password = $_POST['password'];//$password is a variable which is getting the input password from user
$class = $_POST['class'];
$section = $_POST['section'];

//sql query building
$sql = "INSERT INTO users (username,password,class,section) VALUES ('$username',AES_ENCRYPT('$password','atten'),'$class','$section')";

//case handeling
if($conn->query($sql)){
    echo "User Added Sucessfully";
    header("Location: index.php");
}else{
    echo "Exection Failed";
}

$conn->close();
?>
<!--Lets Test Its working -->