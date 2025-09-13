<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: dashboard.php');
    exit;
}

include 'db.php';

// Ambil data POST dan sanitasi
$id            = (int) $_POST['id'];
$nomor_dokumen = mysqli_real_escape_string($conn, $_POST['nomor_dokumen']);
$nama_dokumen  = mysqli_real_escape_string($conn, $_POST['nama_dokumen']);
$nominal       = mysqli_real_escape_string(mysql: $conn, string: $_POST['nominal']);
$jenis_dokumen = mysqli_real_escape_string($conn, $_POST['jenis_dokumen']);
$tanggal_kirim = mysqli_real_escape_string($conn, $_POST['tanggal_kirim']);
$tanggal_terima = mysqli_real_escape_string($conn, $_POST['tanggal_terima']);
$pengirim      = mysqli_real_escape_string($conn, $_POST['pengirim']);
$penerima      = mysqli_real_escape_string($conn, $_POST['penerima']);
$status_berkas = mysqli_real_escape_string($conn, $_POST['status_berkas']);

// Handle upload file jika ada
$file_name = !empty($_FILES['file']['name']) ? $_FILES['file']['name'] : '';

// Optional update file
$file_sql = !empty($file_name) ? ", file = '".mysqli_real_escape_string($conn, $file_name)."'" : "";

// Query update
$sql = "UPDATE laporan SET 
            nomor_dokumen = '$nomor_dokumen',
            nama_dokumen  = '$nama_dokumen',
            nominal       = '$nominal',
            jenis_dokumen = '$jenis_dokumen',
            tanggal_kirim = '$tanggal_kirim',
            tanggal_terima= '$tanggal_terima',
            pengirim      = '$pengirim',
            penerima      = '$penerima',
            status_berkas = '$status_berkas'"
            . $file_sql .
        " WHERE id = $id";

// Eksekusi dan arahkan
if (mysqli_query($conn, $sql)) {
    header('Location: dashboard.php?pesan=update_ok');
} else {
    echo 'Error: '. mysqli_error($conn);
}
