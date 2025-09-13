<?php
session_start();
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['login'] = $username;
    header("Location: dashboard.php");
} else {
    echo "<script>alert('login gagal!');window.location='index.php';</script>";
}
?>
