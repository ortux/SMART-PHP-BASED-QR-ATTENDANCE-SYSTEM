<?php
if(isset($_COOKIE['username'])){
    header('Location: dash.php');
}else{
    header('Location: auth/index.php');
}