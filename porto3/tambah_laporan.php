<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Laporan</title>
    <link rel="stylesheet" href="assets/style_tambah.css">
</head>
<body>
    <div class="form-container">
        <h2>Form Tambah Laporan Berkas</h2>

        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
            <label>No Dokumen:</label>
            <input type="text" name="nomor_dokumen" required>

            <label>Jenis Dokumen:</label>
            <input type="text" name="jenis_dokumen" required>

            <label>JNama Dokumen:</label>
            <input type="text" name="nama_dokumen" required>

            <label>Nominal:</label>
            <input type="text" name="nominal" required>

            <label>Tanggal Kirim:</label>
            <input type="date" name="tanggal_kirim" required>

            <label>Tanggal Terima:</label>
            <input type="date" name="tanggal_terima">

            <label>Pengirim:</label>
            <input type="text" name="pengirim" required>

            <label>Penerima:</label>
            <input type="text" name="penerima">

            <label>Upload File (opsional):</label>
            <input type="file" name="file">

            <button type="submit">Tambah Laporan</button>
        </form>

        <a href="dashboard.php" class="btn-kembali">⬅️ Kembali ke Dashboard</a>
    </div>
</body>
</html>
