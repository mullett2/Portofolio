<?php
include 'db.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    // Cek username
    $cek = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: register.php?pesan=gagal");
    } else {
        $insert = mysqli_query($conn, "INSERT INTO users (username, password, role) 
                                       VALUES ('$username', '$password', '$role')");
        if ($insert) {
            header("Location: register.php?pesan=berhasil");
        } else {
            echo "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>
