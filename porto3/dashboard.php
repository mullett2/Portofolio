<?php
session_start();

// Cek apakah user sudah login atau belum
if (!isset($_SESSION['username'])) {
    // Kalau belum login, redirect ke halaman login
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Data Laporan</title>
    <link rel="stylesheet" href="assets/style_dashboard.css">
</head>
<body>
    <div class="top-bar">
        <div class="logo-container">
            <img src="assets/lautanberlian.png" alt="Logo Mitsubishi">
        </div>
        <h2>Data Laporan</h2>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <div class="filter-section">
        <form action="" method="get" class="filter-form">
            <label for="filter">Filter Berdasarkan:</label>
            <select name="filter" id="filter">
                <option value="">-- Pilih Filter --</option>
                <option value="no_dokumen">No Dokumen</option>
                <option value="nama_dokumen">Nama Dokumen</option>
                <option value="jenis_dokumen">Jenis</option>
                <option value="tanggal_kirim">Tanggal Kirim</option>
                <option value="tanggal_terima">Tanggal Terima</option>
                <option value="pengirim">Pengirim</option>
                <option value="penerima">Penerima</option>
                <option value="status_berkas">Status</option>
            </select>
            <input type="text" name="keyword" placeholder="Kata kunci...">
            <button type="submit" class="btn-cari">Cari</button>
            <a href="dashboard.php" class="btn-reset">Reset</a>
        </form>
        <div class="EDANT">
            <a href="tambah_laporan.php" class="btn-tambah">+ Tambah Laporan</a>
            <a href="export_form.php" class="btn-export">Export</a>
        </div>
    </div>

    <table id="dataTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Dokumen</th>
                <th>Jenis</th>
                <th>Nama Dokumen</th>
                <th>Nominal</th>
                <th>Tanggal Kirim</th>
                <th>Tanggal Terima</th>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        include "db.php";

        // Pagination variables
        $results_per_page = 10; // Change this to set results per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $results_per_page;

        $sql = "SELECT COUNT(*) AS total FROM laporan";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $total_results = $row['total'];
        $total_pages = ceil($total_results / $results_per_page);

        // Base SQL query
        $sql = "SELECT * FROM laporan";

        // Apply filters if set
        if (isset($_GET['filter']) && $_GET['filter'] != "" && isset($_GET['keyword']) && $_GET['keyword'] != "") {
            $filter = $_GET['filter'];
            $keyword = $_GET['keyword'];
            $sql .= " WHERE $filter LIKE '%$keyword%'";
        }

        // Limit and offset for pagination
        $sql .= " LIMIT $offset, $results_per_page";
        $result = mysqli_query($conn, $sql);
        $no = $offset + 1;

        while ($data = mysqli_fetch_array($result)) {
            echo "<tr>
                    <td>$no</td>
                    <td>$data[nomor_dokumen]</td>
                    <td>$data[jenis_dokumen]</td>
                    <td>$data[nama_dokumen]</td>
                    <td>$data[nominal]</td>
                    <td>$data[tanggal_kirim]</td>
                    <td>$data[tanggal_terima]</td>
                    <td>$data[pengirim]</td>
                    <td>$data[penerima]</td>
                    <td>$data[status_berkas]</td>
                    <td>
                        <a href='edit_laporan.php?id=$data[id]'>Edit</a> |
                        <a href='hapus_laporan.php?id=$data[id]' onclick=\"return confirm('Yakin hapus data ini?')\">Hapus</a>
                    </td>
                  </tr>";
            $no++;
        }
        ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination-box">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1; ?>" class="pagination">Previous</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i; ?>" class="pagination <?= ($i == $page) ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1; ?>" class="pagination">Next</a>
        <?php endif; ?>
    </div>

     <button id="exportCurrentPage" class="btn-export-page">Export Halaman Ini</button>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="js/export-page.js"></script>
</body>
</html>
