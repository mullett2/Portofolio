<!DOCTYPE html>
<html>
<head>
    <title>Registrasi Akun</title>
    <link rel="stylesheet" type="text/css" href="assets/style_register.css">
</head>
<body>
    <div class="register-box">
        <img src="assets/lautanberlian-removebg-preview.png" alt="Mitsubishi Logo">
        <h2>Registrasi Akun</h2>
        
        <?php
        if (isset($_GET['pesan'])) {
            if ($_GET['pesan'] == "gagal") {
                echo "<div class='message'>Username sudah digunakan!</div>";
            } else if ($_GET['pesan'] == "berhasil") {
                echo "<div class='message success'>Registrasi berhasil! Silakan login.</div>";
            }
        }
        ?>

        <form method="post" action="proses_register.php">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label>Role:</label>
            <select name="role" required>
                <option value="pengirim">Pengirim</option>
                <option value="penerima">Penerima</option>
            </select>

            <button type="submit" name="register">Daftar</button>
        </form>
        <p style="text-align:center; margin-top: 15px;">
            <a href="index.php">← Kembali ke Login</a>
        </p>
    </div>
</body>
</html>
