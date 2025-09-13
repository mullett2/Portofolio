<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data file lama jika ingin sekalian hapus file
    $query = "SELECT file FROM laporan WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    // Hapus file di folder jika ada
    if (!empty($data['file']) && file_exists("uploads/" . $data['file'])) {
        unlink("uploads/" . $data['file']);
    }

    // Hapus data dari database
    $sql = "DELETE FROM laporan WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: dashboard.php?pesan=sukses_hapus");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
