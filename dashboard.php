<?php 
session_start();
if(!isset($_SESSION['login'])) header("location: index.php");
include 'db.php';

// 1. Query untuk menghitung jumlah total karyawan
$sql_count = "SELECT COUNT(*) AS total_id FROM karyawan";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_id = $row_count["total_id"] ?? 0; 

// 2. Proses pencarian
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_jabatan = isset($_GET['jabatan']) ? trim($_GET['jabatan']) : '';
$search_status = isset($_GET['status']) ? trim($_GET['status']) : '';

// 3. Query untuk data karyawan dengan pencarian
$sql_karyawan = "SELECT id, nama_karyawan, id_mmksi, jabatan, status_karyawan, kontrak_selesai FROM karyawan";
$conditions = [];
$params = [];
$types = '';

// Tambahkan kondisi pencarian
if (!empty($search_keyword)) {
    $conditions[] = "(nama_karyawan LIKE ? OR id_mmksi LIKE ? OR jabatan LIKE ?)";
    $keyword_param = "%$search_keyword%";
    $params[] = $keyword_param;
    $params[] = $keyword_param; 
    $params[] = $keyword_param;
    $types .= 'sss';
}

if (!empty($search_jabatan)) {
    $conditions[] = "jabatan = ?";
    $params[] = $search_jabatan;
    $types .= 's';
}

if (!empty($search_status)) {
    $conditions[] = "status_karyawan = ?";
    $params[] = $search_status;
    $types .= 's';
}

if (!empty($conditions)) {
    $sql_karyawan .= " WHERE " . implode(" AND ", $conditions);
}

$sql_karyawan .= " ORDER BY nama_karyawan ASC";

// Eksekusi query dengan prepared statement
$stmt_karyawan = $conn->prepare($sql_karyawan);
if (!empty($params)) {
    $stmt_karyawan->bind_param($types, ...$params);
}
$stmt_karyawan->execute();
$result_karyawan = $stmt_karyawan->get_result();

// 4. Proses data untuk kontrak yang akan habis
$all_karyawan = [];
$expiring_contracts = [];
$now = new DateTime();

while($row = $result_karyawan->fetch_assoc()) {
    $all_karyawan[] = $row;
    
    // Pastikan status_karyawan tidak null sebelum menggunakan strtolower()
    $status = safeString($row['status_karyawan']);
    if(safeStrtolower($status) == "kontrak" && !empty($row['kontrak_selesai'])) {
        $selesai = new DateTime($row['kontrak_selesai']);
        $diff = $now->diff($selesai);

        // masih berlaku dan <= 30 hari
        if($diff->invert == 0 && $diff->days <= 30) {
            $expiring_contracts[] = $row;
        }
    }
}

// 5. Query untuk mendapatkan list jabatan dan status untuk dropdown
$jabatan_list = $conn->query("SELECT DISTINCT jabatan FROM karyawan ORDER BY jabatan")->fetch_all(MYSQLI_ASSOC);
$status_list = $conn->query("SELECT DISTINCT status_karyawan FROM karyawan ORDER BY status_karyawan")->fetch_all(MYSQLI_ASSOC);

// Tentukan data yang akan ditampilkan berdasarkan pencarian
$is_search_mode = !empty($search_keyword) || !empty($search_jabatan) || !empty($search_status);

if ($is_search_mode) {
    // Kalau ada pencarian → tampilkan hasil pencarian
    $display_data = $all_karyawan;
} else {
    // Kalau tidak ada pencarian → tampilkan semua karyawan
    $display_data = $all_karyawan;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Karyawan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .highlight {
      background-color: #fef08a;
      font-weight: bold;
    }
  </style>
</head>
<body class="bg-blue-300">
    <nav class="bg-white border-gray-200"> 
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4 "> 
            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse"> 
                <span class="self-center text-3xl font-semibold whitespace-nowrap">Lautan Berlian Utama Motor</span> 
            </a> 
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"> 
                <a href="tambah_karyawan.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-block">
                Tambah Karyawan
                </a>
            </div> 
        </div> 
    </nav>

    <!-- Section Pencarian -->
    <section class="pt-6 px-10">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">🔍 Pencarian Karyawan</h2>
            
            <!-- Form Pencarian -->
            <form method="GET" action="" class="space-y-4">
                <!-- Pencarian Keyword -->
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama/ID MMKSI/Jabatan</label>
                        <input type="text" 
                               name="search" 
                               value="<?= htmlspecialchars($search_keyword) ?>"
                               placeholder="Ketik nama karyawan, ID MMKSI, atau jabatan..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <!-- Filter Jabatan -->
                    <div class="md:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Jabatan</label>
                        <select name="jabatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Jabatan</option>
                            <?php foreach($jabatan_list as $jabatan): ?>
                                <option value="<?= htmlspecialchars($jabatan['jabatan']) ?>" 
                                        <?= $search_jabatan == $jabatan['jabatan'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($jabatan['jabatan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Filter Status -->
                    <div class="md:w-48">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Status</option>
                            <?php foreach($status_list as $status): ?>
                                <option value="<?= htmlspecialchars($status['status_karyawan']) ?>" 
                                        <?= $search_status == $status['status_karyawan'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($status['status_karyawan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        🔍 Cari
                    </button>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium">
                        🔄 Reset
                    </a>
                </div>
                
                <!-- Info hasil pencarian -->
                <?php if ($is_search_mode): ?>
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-blue-800">
                            📊 Ditemukan <strong><?= count($all_karyawan) ?></strong> karyawan 
                            <?php if (!empty($search_keyword)): ?>
                                dengan kata kunci "<strong><?= htmlspecialchars($search_keyword) ?></strong>"
                            <?php endif; ?>
                            <?php if (!empty($search_jabatan)): ?>
                                di jabatan "<strong><?= htmlspecialchars($search_jabatan) ?></strong>"
                            <?php endif; ?>
                            <?php if (!empty($search_status)): ?>
                                dengan status "<strong><?= htmlspecialchars($search_status) ?></strong>"
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <!-- Card jumlah karyawan dan kontrak habis -->
    <section class="py-4">
        <div class="flex flex-row gap-6 px-10">
            <div class="basis-1/3">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full h-auto min-h-64">
                    <h2 class="text-2xl font-semibold text-center py-4">Jumlah Karyawan</h2>
                    <p class="text-6xl text-center py-10"><b><?= $total_id ?></b></p>
                </div>
            </div>

            <!-- List kontrak habis (hanya tampil jika tidak sedang search) -->
            <?php if (!$is_search_mode): ?>
            <div class="basis-2/3">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden w-full h-auto min-h-64 p-4">
                    <h2 class="text-2xl font-bold text-center text-red-900">Kontrak Akan Berakhir (&lt; 30 Hari)</h2>
                    <ul class="list-decimal list-inside py-10 text-center">
                        <?php if (!empty($expiring_contracts)): ?>
                            <?php foreach ($expiring_contracts as $karyawan): ?>
                                <li class="text-red-700 font-semibold">
                                    <?= htmlspecialchars($karyawan['nama_karyawan']); ?> 
                                    (habis: <?= htmlspecialchars($karyawan['kontrak_selesai']); ?>)
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500 py-10">Tidak ada karyawan yang kontraknya akan berakhir dalam waktu dekat.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php else: ?>
                <!-- Info pencarian -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-bold text-blue-800 mb-2">📊 Data Karyawan</h3>
                    <p class="text-blue-700">
                        <?php if ($is_search_mode): ?>
                            Menampilkan hasil pencarian dari <strong><?= count($all_karyawan) ?></strong> karyawan yang sesuai kriteria.
                        <?php else: ?>
                            Menampilkan <strong><?= count($all_karyawan) ?></strong> karyawan. 
                            <?php if (!empty($expiring_contracts)): ?>
                                <span class="text-red-600 font-semibold"><?= count($expiring_contracts) ?> kontrak akan berakhir!</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                    <div class="mt-2 text-sm text-blue-600">
                        <p><strong>Info:</strong> Tabel di bawah menampilkan semua data karyawan dengan opsi pencarian dan filter.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tabel detail -->
        <div class="bg-white m-10 rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="text-lg font-semibold text-gray-800">
                    📋 Data Seluruh Karyawan 
                    <?php if ($is_search_mode): ?>
                        - Hasil Pencarian (<?= count($display_data) ?> dari <?= $total_id ?> total)
                    <?php else: ?>
                        (<?= count($display_data) ?> karyawan)
                    <?php endif; ?>
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-center">
                    <thead class="bg-blue-700 text-white">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">ID MMKSI</th>
                            <th class="px-4 py-3">Posisi</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Kontrak Selesai</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>   
                    <tbody class="divide-y divide-gray-200">
                        <?php if(count($display_data) > 0): ?>
                            <?php $no = 1; foreach($display_data as $karyawan): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3"><?= $no++ ?></td>
                                    <td class="px-4 py-3 font-medium">
                                        <?= !empty($search_keyword) ? highlightKeyword(htmlspecialchars($karyawan['nama_karyawan']), $search_keyword) : htmlspecialchars($karyawan['nama_karyawan']) ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= !empty($search_keyword) ? highlightKeyword(htmlspecialchars($karyawan['id_mmksi']), $search_keyword) : htmlspecialchars($karyawan['id_mmksi']) ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?= !empty($search_keyword) ? highlightKeyword(htmlspecialchars($karyawan['jabatan']), $search_keyword) : htmlspecialchars($karyawan['jabatan']) ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php 
                                            $status = safeString($karyawan['status_karyawan']);
                                            $status_class = safeStrtolower($status) == 'kontrak' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
                                        ?>
                                        <span class="px-2 py-1 text-xs rounded-full <?= $status_class ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if (!empty($karyawan['kontrak_selesai'])): ?>
                                            <?php
                                                $selesai = new DateTime($karyawan['kontrak_selesai']);
                                                $diff = $now->diff($selesai);
                                                $is_expiring = ($diff->invert == 0 && $diff->days <= 30);
                                            ?>
                                            <span class="<?= $is_expiring ? 'text-red-600 font-bold' : 'text-gray-600' ?>">
                                                <?= htmlspecialchars($karyawan['kontrak_selesai']) ?>
                                                <?php if ($is_expiring): ?>
                                                    <br><small class="text-red-500">(<?= $diff->days ?> hari lagi)</small>
                                                <?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="RUD.php?detail=<?php echo $karyawan['id']; ?>"
                                           class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
                                            👁️ Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <?php if ($is_search_mode): ?>
                                        🔍 Tidak ada karyawan yang sesuai dengan kriteria pencarian
                                    <?php else: ?>
                                        📊 Belum ada data karyawan
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody> 
                </table>
            </div>
        </div>
    </section>

    <!-- Script untuk fitur tambahan -->
    <script>
        // Auto-submit form ketika dropdown berubah (opsional)
        document.querySelectorAll('select[name="jabatan"], select[name="status"]').forEach(select => {
            select.addEventListener('change', function() {
                // Uncomment line berikut jika ingin auto-submit saat dropdown berubah
                // this.form.submit();
            });
        });
        
        // Fokus ke search input saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput && !searchInput.value) {
                searchInput.focus();
            }
        });
    </script>
</body>
</html>

<?php
// Fungsi untuk highlight keyword dalam text
function highlightKeyword($text, $keyword) {
    if (empty($keyword)) return $text;
    return preg_replace('/(' . preg_quote($keyword, '/') . ')/i', '<span class="highlight">$1</span>', $text);
}

// Fungsi helper untuk menangani nilai null
function safeString($value) {
    return $value ?? '';
}

// Fungsi untuk safe strtolower
function safeStrtolower($string) {
    return strtolower($string ?? '');
}
?>