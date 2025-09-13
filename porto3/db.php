<?php
$host = "localhost"; // Nama host database
$user = "root"; // Nama pengguna database
$pass = ""; // Kata sandi database (biasanya kosong untuk localhost)
$dbname = "lbum_laporan"; // Nama database yang ingin Anda hubungkan

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
