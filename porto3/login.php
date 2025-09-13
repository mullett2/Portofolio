<?php
session_start();
include "db.php";

$username = $_POST['username'];
$password = $_POST['password'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify the hashed password
    if (password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        header("Location: index.php?error=true");
    }
} else {
    header("Location: index.php?error=true");
}

$stmt->close();
$conn->close();
?>
