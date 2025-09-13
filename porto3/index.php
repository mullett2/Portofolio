<?php session_start(); ?>
<html>
    <head>
        <title>Login - Sistem Laporan</title>
        <link rel="stylesheet" href="assets/style_index.css">
    </head>
        <body>
            <div class="login-box">
                <img src="assets/lautanberlian-removebg-preview.png" alt="Mitsubishi Logo">
                <h2>Login Sistem Laporan</h2>
                <form action="login.php" method="POST">
                    <input type="text" name="username" placeholder="Username" required><br>

                    <input type="password" name="password" placeholder="Password" required><br>
 
                    <button type="submit">Login</button>
 
                    <a href="register.php">Registrasi</a>
            </div>
        </body>
</html>
