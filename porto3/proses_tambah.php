<?php
include 'db.php';

$nomor = $_POST['nomor_dokumen'];
$nama = $_POST['nama_dokumen'];
$jenis = $_POST['jenis_dokumen'];
$tgl_kirim = $_POST['tanggal_kirim'];
$tgl_terima = $_POST['tanggal_terima'];
$pengirim = $_POST['pengirim'];
$penerima = $_POST['penerima'];
$nominal = $_POST['nominal'];


// Proses upload file
$nama_file = '';
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $nama_file = basename($_FILES['file']['name']);
    $target_dir = "uploads/";
    $target_file = $target_dir . $nama_file;

    move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
}

// Default status = 'on proses' saat tambah data baru
$status = 'on proses';

$query = "INSERT INTO laporan (nomor_dokumen, nama_dokumen, jenis_dokumen, tanggal_kirim, tanggal_terima, pengirim, penerima, file, status_berkas, nominal)
          VALUES ('$nomor', '$nama', '$jenis', '$tgl_kirim', '$tgl_terima', '$pengirim', '$penerima', '$nama_file', '$status', '$nominal')";

if (mysqli_query($conn, $query)) {
    header("Location: dashboard.php?pesan=sukses_tambah");
} else {
    echo "Gagal tambah data: " . mysqli_error($conn);
}
?>
