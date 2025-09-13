<?php
// form_export.php
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Laporan</title>
    <link rel="stylesheet" href="assets/style_export.css">
</head>
<body>

    <h2>Export Laporan ke Excel</h2>

    <form method="GET" action="export_excel.php">
        <label for="pengirim">Filter Pengirim:</label>
        <input type="text" id="pengirim" name="pengirim" placeholder="Nama Pengirim (opsional)">

        <label for="tanggal">Filter Tanggal Kirim:</label>
        <input type="date" id="tanggal_kirim" name="tanggal_kirim">

        <button type="submit" class="export-btn">Export Excel</button>
        <a href="dashboard.php"><button type="button" class="back-btn">Back</button></a>
    </form>

</body>
</html>
