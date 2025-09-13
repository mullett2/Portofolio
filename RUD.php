    <?php
    include "db.php";

    if (!isset($_GET['detail'])) {
        echo "<script>alert('ID karyawan tidak ditemukan'); window.location.href='dashboard.php';</script>";
        exit;
    }

    $karyawan_id = $_GET['detail'];

    // Fetch employee data
    $sql = "SELECT * FROM karyawan WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $karyawan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_employee = $result->fetch_assoc();

    if (!$edit_employee) {
        echo "<script>alert('Data karyawan tidak ditemukan'); window.location.href='dashboard.php';</script>";
        exit;
    }

    // Fetch children
    $sql_anak = "SELECT * FROM anak WHERE karyawan_id = ?";
    $stmt_anak = $conn->prepare($sql_anak);
    $stmt_anak->bind_param("i", $karyawan_id);
    $stmt_anak->execute();
    $edit_anak = $stmt_anak->get_result()->fetch_all(MYSQLI_ASSOC);

    // Fetch spouses
    $sql_pasangan = "SELECT * FROM pasangan WHERE karyawan_id = ?";
    $stmt_pasangan = $conn->prepare($sql_pasangan);
    $stmt_pasangan->bind_param("i", $karyawan_id);
    $stmt_pasangan->execute();
    $edit_pasangan = $stmt_pasangan->get_result()->fetch_all(MYSQLI_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['karyawan_id'])) {
            // Delete employee and related data
            $conn->begin_transaction();
            try {
                $sql_anak = "DELETE FROM anak WHERE karyawan_id = ?";
                $stmt_anak = $conn->prepare($sql_anak);
                $stmt_anak->bind_param("i", $karyawan_id);
                $stmt_anak->execute();

                $sql_pasangan = "DELETE FROM pasangan WHERE karyawan_id = ?";
                $stmt_pasangan = $conn->prepare($sql_pasangan);
                $stmt_pasangan->bind_param("i", $karyawan_id);
                $stmt_pasangan->execute();

                $sql_karyawan = "DELETE FROM karyawan WHERE id = ?";
                $stmt_karyawan = $conn->prepare($sql_karyawan);
                $stmt_karyawan->bind_param("i", $karyawan_id);
                $stmt_karyawan->execute();

                $conn->commit();
                echo "<script>alert('Data karyawan berhasil dihapus'); window.location.href='dashboard.php';</script>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='dashboard.php';</script>";
            }
        } elseif (isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['karyawan_id'])) {
            // Update employee data
            $nik = $_POST['nik'];
            $id_mmksi = $_POST['id_mmksi'];
            $email = $_POST['email'];
            $npwp = $_POST['npwp'];
            $no_bpjs_kesehatan = $_POST['no_bpjs_kesehatan'];
            $no_bpjs_ketenagakerjaan = $_POST['no_bpjs_ketenagakerjaan'];
            $nama_karyawan = $_POST['nama_karyawan'];
            $tempat_lahir = $_POST['tempat_lahir'];
            $tanggal_lahir = $_POST['tanggal_lahir'];
            $agama = $_POST['agama'];
            $alamat = $_POST['alamat'];
            $no_ktp = $_POST['no_ktp'];
            $pendidikan = $_POST['pendidikan'];
            $tanggal_masuk = $_POST['tanggal_masuk'];
            $masa_kerja = $_POST['masa_kerja'];
            $jabatan = $_POST['jabatan'];
            $kategori = $_POST['kategori'];
            $sejak_tanggal = $_POST['sejak_tanggal'];
            $status_karyawan = $_POST['status_karyawan'];
            $kontrak_mulai = $_POST['kontrak_mulai'];
            $kontrak_selesai = $_POST['kontrak_selesai'];
            $no_hp = $_POST['no_hp'];
            $no_wa = $_POST['no_wa'];
            $nama_anak = isset($_POST['nama_anak']) ? $_POST['nama_anak'] : [];
            $tanggal_lahir_anak = isset($_POST['tanggal_lahir_anak']) ? $_POST['tanggal_lahir_anak'] : [];
            $nama_pasangan = isset($_POST['nama_pasangan']) ? $_POST['nama_pasangan'] : [];
            $tanggal_lahir_pasangan = isset($_POST['tanggal_lahir_pasangan']) ? $_POST['tanggal_lahir_pasangan'] : [];
            $pendidikan_pasangan = isset($_POST['pendidikan_pasangan']) ? $_POST['pendidikan_pasangan'] : [];
            $tanggal_menikah = isset($_POST['tanggal_menikah']) ? $_POST['tanggal_menikah'] : [];

            $conn->begin_transaction();
            try {
                $sql1 = "UPDATE karyawan SET nik = ?, id_mmksi = ?, email = ?, npwp = ?, no_bpjs_kesehatan = ?, no_bpjs_ketenagakerjaan = ?, 
                        nama_karyawan = ?, tempat_lahir = ?, tanggal_lahir = ?, agama = ?, alamat = ?, no_ktp = ?, pendidikan = ?, 
                        tanggal_masuk = ?, masa_kerja = ?, jabatan = ?, kategori = ?, sejak_tanggal = ?, status_karyawan = ?, 
                        kontrak_mulai = ?, kontrak_selesai = ?, no_hp = ?, no_wa = ? WHERE id = ?";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bind_param(
                    "sssssssssssssssssssssssi",
                    $nik, $id_mmksi, $email, $npwp, $no_bpjs_kesehatan, $no_bpjs_ketenagakerjaan,
                    $nama_karyawan, $tempat_lahir, $tanggal_lahir, $agama, $alamat, $no_ktp, $pendidikan,
                    $tanggal_masuk, $masa_kerja, $jabatan, $kategori, $sejak_tanggal, $status_karyawan,
                    $kontrak_mulai, $kontrak_selesai, $no_hp, $no_wa, $karyawan_id
                );
                $stmt1->execute();

                $sql_delete_anak = "DELETE FROM anak WHERE karyawan_id = ?";
                $stmt_delete_anak = $conn->prepare($sql_delete_anak);
                $stmt_delete_anak->bind_param("i", $karyawan_id);
                $stmt_delete_anak->execute();

                $sql_delete_pasangan = "DELETE FROM pasangan WHERE karyawan_id = ?";
                $stmt_delete_pasangan = $conn->prepare($sql_delete_pasangan);
                $stmt_delete_pasangan->bind_param("i", $karyawan_id);
                $stmt_delete_pasangan->execute();

                if (!empty($nama_anak)) {
                    $sql2 = "INSERT INTO anak (karyawan_id, nama_anak, tanggal_lahir_anak) VALUES (?, ?, ?)";
                    $stmt2 = $conn->prepare($sql2);
                    foreach ($nama_anak as $key => $anakNama) {
                        if (!empty($anakNama)) {
                            $anakTglLahir = $tanggal_lahir_anak[$key];
                            $stmt2->bind_param("iss", $karyawan_id, $anakNama, $anakTglLahir);
                            $stmt2->execute();
                        }
                    }
                }

                if (!empty($nama_pasangan)) {
                    $sql3 = "INSERT INTO pasangan (karyawan_id, nama_pasangan, tanggal_lahir_pasangan, pendidikan_pasangan, tanggal_menikah) 
                            VALUES (?, ?, ?, ?, ?)";
                    $stmt3 = $conn->prepare($sql3);
                    foreach ($nama_pasangan as $key => $psgNama) {
                        if (!empty($psgNama)) {
                            $psgTglLahir = $tanggal_lahir_pasangan[$key];
                            $psgPendidikan = $pendidikan_pasangan[$key];
                            $psgTglNikah = $tanggal_menikah[$key];
                            $stmt3->bind_param("issss", $karyawan_id, $psgNama, $psgTglLahir, $psgPendidikan, $psgTglNikah);
                            $stmt3->execute();
                        }
                    }
                }

                $conn->commit();
                echo "<script>alert('Data karyawan berhasil diperbarui'); window.location.href='dashboard.php';</script>";
            } catch (Exception $e) {
                $conn->rollback();
                echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.location.href='dashboard.php';</script>";
            }
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Detail Karyawan</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-blue-300">
        <div class="container mx-auto p-10 m-6 rounded-lg shadow-lg bg-gray-300">
            <h1 class="text-2xl font-bold mb-10 text-gray-800 text-center">Detail dan Edit Karyawan: <?php echo htmlspecialchars($edit_employee['nama_karyawan']); ?></h1>
            <form action="" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="karyawan_id" value="<?php echo $edit_employee['id']; ?>">
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">NIK</label>
                        <input type="text" name="nik" value="<?php echo htmlspecialchars($edit_employee['nik']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">ID MMKSI</label>
                        <input type="text" name="id_mmksi" value="<?php echo htmlspecialchars($edit_employee['id_mmksi']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                        <input type="text" name="email" value="<?php echo htmlspecialchars($edit_employee['email']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">NPWP</label>
                        <input type="text" name="npwp" value="<?php echo htmlspecialchars($edit_employee['npwp']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">No BPJS Kesehatan</label>
                        <input type="text" name="no_bpjs_kesehatan" value="<?php echo htmlspecialchars($edit_employee['no_bpjs_kesehatan']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">No BPJS Ketenagakerjaan</label>
                        <input type="text" name="no_bpjs_ketenagakerjaan" value="<?php echo htmlspecialchars($edit_employee['no_bpjs_ketenagakerjaan']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="nama_karyawan" value="<?php echo htmlspecialchars($edit_employee['nama_karyawan']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="<?php echo htmlspecialchars($edit_employee['tempat_lahir']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($edit_employee['tanggal_lahir']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Agama</label>
                        <input type="text" name="agama" value="<?php echo htmlspecialchars($edit_employee['agama']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="3" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($edit_employee['alamat']); ?></textarea>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">No KTP</label>
                        <input type="text" name="no_ktp" value="<?php echo htmlspecialchars($edit_employee['no_ktp']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Pendidikan</label>
                        <input type="text" name="pendidikan" value="<?php echo htmlspecialchars($edit_employee['pendidikan']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" value="<?php echo htmlspecialchars($edit_employee['tanggal_masuk']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Masa Kerja</label>
                        <input type="text" name="masa_kerja" value="<?php echo htmlspecialchars($edit_employee['masa_kerja']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Jabatan</label>
                        <select name="jabatan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled>Pilih jabatan</option>
                            <option value="OH" <?php echo $edit_employee['jabatan'] == 'OH' ? 'selected' : ''; ?>>Office Head</option>
                            <option value="Sales Coordinator" <?php echo $edit_employee['jabatan'] == 'Sales Coordinator' ? 'selected' : ''; ?>>Sales Coordinator</option>
                            <option value="Sales Counter" <?php echo $edit_employee['jabatan'] == 'Sales Counter' ? 'selected' : ''; ?>>Sales Counter</option>
                            <option value="Sales Consultant" <?php echo $edit_employee['jabatan'] == 'Sales Consultant' ? 'selected' : ''; ?>>Sales Consultant</option>
                            <option value="SVC SPV" <?php echo $edit_employee['jabatan'] == 'SVC SPV' ? 'selected' : ''; ?>>SVC Supervisor</option>
                            <option value="Mekanik Leader" <?php echo $edit_employee['jabatan'] == 'Mekanik Leader' ? 'selected' : ''; ?>>Mekanik Leader</option>
                            <option value="Mekanik" <?php echo $edit_employee['jabatan'] == 'Mekanik' ? 'selected' : ''; ?>>Mekanik</option>
                            <option value="SC" <?php echo $edit_employee['jabatan'] == 'SC' ? 'selected' : ''; ?>>SC</option>
                            <option value="ADM Service" <?php echo $edit_employee['jabatan'] == 'ADM Service' ? 'selected' : ''; ?>>Admin Service</option>
                            <option value="Toolsman" <?php echo $edit_employee['jabatan'] == 'Toolsman' ? 'selected' : ''; ?>>Toolsman</option>
                            <option value="Sparepart Warehouse" <?php echo $edit_employee['jabatan'] == 'Sparepart Warehouse' ? 'selected' : ''; ?>>Sparepart Warehouse</option>
                            <option value="Customer Service Office" <?php echo $edit_employee['jabatan'] == 'Customer Service Office' ? 'selected' : ''; ?>>Customer Service Office</option>
                            <option value="Driver" <?php echo $edit_employee['jabatan'] == 'Driver' ? 'selected' : ''; ?>>Driver</option>
                            <option value="ADM Service Sparepart" <?php echo $edit_employee['jabatan'] == 'ADM Service Sparepart' ? 'selected' : ''; ?>>Admin Service Sparepart</option>
                            <option value="Customer Service" <?php echo $edit_employee['jabatan'] == 'Customer Service' ? 'selected' : ''; ?>>Customer Service</option>
                            <option value="Satpam" <?php echo $edit_employee['jabatan'] == 'Satpam' ? 'selected' : ''; ?>>Satpam</option>
                            <option value="ADM Umum" <?php echo $edit_employee['jabatan'] == 'ADM Umum' ? 'selected' : ''; ?>>Administrasi Umum</option>
                            <option value="Kasir" <?php echo $edit_employee['jabatan'] == 'Kasir' ? 'selected' : ''; ?>>Kasir</option>
                            <option value="OB" <?php echo $edit_employee['jabatan'] == 'OB' ? 'selected' : ''; ?>>OB</option>
                            <option value="Washing Man" <?php echo $edit_employee['jabatan'] == 'Washing Man' ? 'selected' : ''; ?>>Washing Man</option>
                            <option value="Waiters" <?php echo $edit_employee['jabatan'] == 'Waiters' ? 'selected' : ''; ?>>Waiters</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                        <select name="kategori" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled>Pilih kategori</option>
                            <option value="Trainee" <?php echo $edit_employee['kategori'] == 'Trainee' ? 'selected' : ''; ?>>Trainee</option>
                            <option value="Junior" <?php echo $edit_employee['kategori'] == 'Junior' ? 'selected' : ''; ?>>Junior</option>
                            <option value="Senior" <?php echo $edit_employee['kategori'] == 'Senior' ? 'selected' : ''; ?>>Senior</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Sejak Tanggal</label>
                        <input type="date" name="sejak_tanggal" value="<?php echo htmlspecialchars($edit_employee['sejak_tanggal']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Status Karyawan</label>
                        <select name="status_karyawan" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled>Pilih status</option>
                            <option value="Kontrak" <?php echo $edit_employee['status_karyawan'] == 'Kontrak' ? 'selected' : ''; ?>>Kontrak</option>
                            <option value="Tetap" <?php echo $edit_employee['status_karyawan'] == 'Tetap' ? 'selected' : ''; ?>>Tetap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kontrak Mulai</label>
                        <input type="date" name="kontrak_mulai" value="<?php echo htmlspecialchars($edit_employee['kontrak_mulai']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kontrak Selesai</label>
                        <input type="date" name="kontrak_selesai" value="<?php echo htmlspecialchars($edit_employee['kontrak_selesai']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">No Handphone</label>
                        <input type="text" name="no_hp" value="<?php echo htmlspecialchars($edit_employee['no_hp']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">No WhatsApp</label>
                        <input type="text" name="no_wa" value="<?php echo htmlspecialchars($edit_employee['no_wa']); ?>" required 
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div id="anakForm" class="grid grid-cols-3 gap-6">
                    <button type="button" onclick="addAnak()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm px-5 py-2.5">+ Tambah Anak</button>
                    <?php foreach ($edit_anak as $anak): ?>
                        <div class="anak">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Anak</label>
                            <input type="text" name="nama_anak[]" value="<?php echo htmlspecialchars($anak['nama_anak']); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir_anak[]" value="<?php echo htmlspecialchars($anak['tanggal_lahir_anak']); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="removeRow(this)" class="text-gray-700 bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-sm text-sm m-2 p-2 flex items-center justify-center">Hapus</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="pasanganForm" class="grid grid-cols-3 gap-6">
                    <button type="button" onclick="addPasangan()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm px-5 py-2.5">+ Tambah Pasangan</button>
                    <?php foreach ($edit_pasangan as $pasangan): ?>
                        <div class="pasangan">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Nama Pasangan</label>
                            <input type="text" name="nama_pasangan[]" value="<?php echo htmlspecialchars($pasangan['nama_pasangan']); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir_pasangan[]" value="<?php echo htmlspecialchars($pasangan['tanggal_lahir_pasangan']); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_pasangan[]" value="<?php echo htmlspecialchars($pasangan['pendidikan_pasangan']); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Menikah</label>
                            <input type="date" name="tanggal_menikah[]" value="<?php echo htmlspecialchars($pasangan['tanggal_menikah']); ?>" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <button type="button" onclick="removeRow(this)" class="text-gray-700 bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-sm text-sm m-2 p-2 flex items-center justify-center">Hapus</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="grid grid-cols-2">
                    <div class="flex space-x-4 mt-6">
                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-sm text-sm px-5 py-2.5 text-center">Simpan</button>
                       
                        <a href="dashboard.php" class="text-gray-700 bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-sm text-sm px-5 py-2.5 text-center">Kembali</a>
                    </div>
                </div>
            </form>
            <form action="" method="POST" class="inline">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="karyawan_id" value="<?php echo $edit_employee['id']; ?>">
                <button type="submit" onclick="return confirm('Yakin ingin menghapus data ini?');" 
                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-sm text-sm px-6 py-2.5 mt-4 text-center">Hapus</button>
            </form>
        </div>
        <script src="add.js"></script>
    </body>
    </html>
    <?php $conn->close(); ?>