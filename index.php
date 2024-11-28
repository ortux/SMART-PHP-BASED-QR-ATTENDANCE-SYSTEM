<?php 
if(isset($_COOKIE['username']))
{
    $username = $_COOKIE['username'];
    echo'Hello Mr.'.$username.' Nice to mmet you';
}
else{
    header("Location: auth/index.php");

}
?>
<a href="auth/logout.php">Logout</a>