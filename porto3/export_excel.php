<?php
ob_start();
include 'db.php';

$pengirim = isset($_GET['pengirim']) ? $_GET['pengirim'] : '';
$tanggal_kirim = isset($_GET['tanggal_kirim']) ? $_GET['tanggal_kirim'] : '';

// Membuat nama file berdasarkan filter
$filename = "Laporan";
if (!empty($pengirim)) {
    $filename .= "_" . preg_replace('/\s+/', '_', $pengirim);
}
if (!empty($tanggal_kirim)) {
    $filename .= "_" . preg_replace('/\s+/', '_', $tanggal_kirim);
}
$filename .= ".xls";

// Mengatur header untuk mengunduh file
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");

// Buat query filter
$query = "SELECT * FROM laporan WHERE 1=1"; // default true
if (!empty($pengirim)) {
    $pengirim = mysqli_real_escape_string($conn, $pengirim);
    $query .= " AND pengirim LIKE '%$pengirim%'";
}
if (!empty($tanggal_kirim)) {
    $tanggal_kirim = mysqli_real_escape_string($conn, $tanggal_kirim);
    $query .= " AND tanggal_kirim = '$tanggal_kirim'";
}

$result = mysqli_query($conn, $query);
?>

<table border="1">
    <tr>
        <th>No</th>
        <th>Nomor Dokumen</th>
        <th>Jenis Dokumen</th>
        <th>Nama Dokumen</th>
        <th>Tanggal Kirim</th>
        <th>Tanggal Terima</th>
        <th>Penerima</th>
        <th>Status</th>
    </tr>

    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$no}</td>
                <td>{$row['nomor_dokumen']}</td>
                <td>{$row['jenis_dokumen']}</td>
                <td>{$row['nama_dokumen']}</td>
                <td>{$row['tanggal_kirim']}</td>
                <td>{$row['tanggal_terima']}</td>
                <td>{$row['penerima']}</td>
                <td>{$row['status_berkas']}</td>
              </tr>";
        $no++;
    }
    ?>
</table>

<?php
// Mengakhiri output buffering dan mengirimkan output
ob_end_flush();
?>
