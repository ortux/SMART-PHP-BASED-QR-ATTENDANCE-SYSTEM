<?php
//checking whether username is loged in or not
if (isset($_COOKIE['username']))
{
    $username = $_COOKIE['username'];
}else{
    header('Location: ../index.php');
}
?>
<!--PAGE DESIGN TAILWIND CSS -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAKE ATTENDANCE</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="background-image: url(../auth/images/bg-01.jpg);">
    
    
</body>
</html>
