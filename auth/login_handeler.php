<?php 
//database file import

include("../conn/db_config.php");

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT AES_DECRYPT(password,'atten') AS password FROM users WHERE username = '$username'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0)
{
    $row = $result->fetch_assoc();
    if($row["password"] == $password)
    {
        setcookie("username", $username, time()+(86400*30),"/");
        header("Location: ../index.php");
        exit;
    }
}
else{
    echo "<script>alert('No User Found')</script>";
    header("Location: index.php");
}

$conn->close();
?>