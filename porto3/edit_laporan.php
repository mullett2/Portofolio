<?php
include 'db.php';
$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM laporan WHERE id='$id'");
$row = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Laporan</title>
    <link rel="stylesheet" href="assets/style_edit.css">
</head>
<body>

<div class="form-container">
    <h2>Edit Laporan</h2>
    <form action="proses_update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $row['id']; ?>">

        <label>No Dokumen</label>
        <input type="text" name="nomor_dokumen" value="<?= $row['nomor_dokumen']; ?>" required>

        <label>Nama Dokumen</label>
        <input type="text" name="nama_dokumen" value="<?= $row['nama_dokumen']; ?>" required>

        <label>Nominal</label>
        <input type="text" name="nominal" value="<?= $row['nominal']; ?>" required>

        <label>Jenis Dokumen</label>
        <input type="text" name="jenis_dokumen" value="<?= $row['jenis_dokumen']; ?>" required>

        <label>Tanggal Kirim</label>
        <input type="date" name="tanggal_kirim" value="<?= $row['tanggal_kirim']; ?>" required>

        <label>Tanggal Terima</label>
        <input type="date" name="tanggal_terima" value="<?= $row['tanggal_terima']; ?>">

        <label>Pengirim</label>
        <input type="text" name="pengirim" value="<?= $row['pengirim']; ?>" required>

        <label>Penerima</label>
        <input type="text" name="penerima" value="<?= $row['penerima']; ?>">

        <label>Status</label>
        <select name="status_berkas" required>
            <option value="on proses" <?= $row['status_berkas'] == 'on proses' ? 'selected' : '' ?>>On Proses</option>
            <option value="selesai" <?= $row['status_berkas'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>

        <label>File (biarkan kosong jika tidak diubah)</label>
        <input type="file" name="file">
        <?php if ($row['file']) : ?>
            <p><small>File saat ini: <a href="uploads/<?= $row['file']; ?>" target="_blank"><?= $row['file']; ?></a></small></p>
        <?php endif; ?>

        <button type="submit" name="submit" class="btn-submit">Update</button>
        <a href="dashboard.php" class="btn-kembali">Kembali</a>
    </form>
</div>

</body>
</html>
