<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Data karyawan
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

    // Data anak (array)
    $nama_anak = $_POST['nama_anak']; // array
    $tanggal_lahir_anak = $_POST['tanggal_lahir_anak']; // array

    // Data pasangan (array)
    $nama_pasangan = $_POST['nama_pasangan']; 
    $tanggal_lahir_pasangan = $_POST['tanggal_lahir_pasangan']; 
    $pendidikan_pasangan = $_POST['pendidikan_pasangan']; 
    $tanggal_menikah = $_POST['tanggal_menikah']; 

    // Transaksi biar aman
    $conn->begin_transaction();

    try {
        // 1. Insert ke tabel karyawan
        $sql1 = "INSERT INTO karyawan (
                    nik, id_mmksi, email, npwp, no_bpjs_kesehatan, no_bpjs_ketenagakerjaan,
                    nama_karyawan, tempat_lahir, tanggal_lahir, agama, alamat, no_ktp, pendidikan,
                    tanggal_masuk, masa_kerja, jabatan, kategori, sejak_tanggal, status_karyawan,
                    kontrak_mulai, kontrak_selesai, no_hp, no_wa
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param(
            "sssssssssssssssssssssss",
            $nik, $id_mmksi, $email, $npwp, $no_bpjs_kesehatan, $no_bpjs_ketenagakerjaan,
            $nama_karyawan, $tempat_lahir, $tanggal_lahir, $agama, $alamat, $no_ktp, $pendidikan,
            $tanggal_masuk, $masa_kerja, $jabatan, $kategori, $sejak_tanggal, $status_karyawan,
            $kontrak_mulai, $kontrak_selesai, $no_hp, $no_wa
        );
        $stmt1->execute();
        $karyawan_id = $conn->insert_id;

        // 2. Insert multiple anak
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

        // 3. Insert multiple pasangan
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
        // Commit semua
        $conn->commit();
        echo "<script>alert('Data berhasil ditambahkan'); window.location.href='dashboard.php';</script>";

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-300">
    <div class="container mx-auto p-10 m-6 rounded-lg shadow-lg bg-gray-300">
        <h1 class="text-2xl font-bold mb-10 text-gray-800 text-center">Form Tambah Karyawan</h1>
        
        <form action="" method="POST" class="space-y-6">
            <div class="grid grid-cols-3 gap-6">
                <!-- Nama -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">NIK</label>
                    <input type="text" name="nik" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- NIK -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">ID MMKSI</label>
                    <input type="text" name="id_mmksi" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input type="text" name="email" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Departemen -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">NPWP</label>
                    <input type="text" name="npwp" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Masuk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">No BPJS Kesehatan</label>
                    <input type="text" name="no_bpjs_kesehatan" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Kontrak -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">No BPJS Ketenagakerjaan</label>
                    <input type="text" name="no_bpjs_ketenagakerjaan" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Kontrak -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama_karyawan" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Tanggal Kontrak -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Agama</label>
                    <input type="text" name="agama" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Alamat -->
                <div class="col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                 <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">No KTP</label>
                    <input type="text" name="no_ktp" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Pendidikan -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Pendidikan</label>
                    <input type="text" name="pendidikan" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!--tgl masuk-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!--masa kerja-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Masa Kerja</label>
                    <input type="text" name="masa_kerja" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!--jabatan-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Jabatan</label>
                    <select name="jabatan" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 
                                focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled selected>Pilih jabatan</option>
                            <option value="OH">Office Head</option>
                            <option value="Sales Coordinator">Sales Coordinator</option>
                            <option value="Sales Counter">Sales Counter</option>
                            <option value="Sales Consultant">Sales Consultant</option>
                            <option value="SVC SPV">SVC Supervisor</option>
                            <option value="Mekanik Leader">Mekanik Leader</option>
                            <option value="Mekanik">Mekanik</option>
                            <option value="SC">SC</option>
                            <option value="ADM Service">Admin Service</option>
                            <option value="Toolsman">Toolsman</option>
                            <option value="Sparepart Warehouse">Sparepart Warehouse</option>
                            <option value="Customer Service Office">Customer Service Office</option>
                            <option value="Driver">Driver</option>
                            <option value="ADM Service Sparepart">Admin Service Sparepart</option>
                            <option value="Customer Service">Customer Service</option>
                            <option value="Satpam">Satpam</option>
                            <option value="ADM Umum">Administrasi Umum</option>
                            <option value="Kasir">Kasir</option>
                            <option value="OB">OB</option>
                            <option value="Washing Man">Washing Man</option>
                            <option value="Waiters">Waiters</option>
                        </select>
                </div>

                <!--kategori-->
                <div>
                     <label for="kategori" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                        <select id="kategori" name="kategori" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 
                                focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled selected>Pilih kategori</option>
                            <option value="Trainee">Trainee</option>
                            <option value="Junior">Junior</option>
                            <option value="Senior">Senior</option>
                        </select>
                </div>

                <!--Sejak tgl-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Sejak Tanggal</label>
                    <input type="date" name="sejak_tanggal" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!--status karyawan-->
                <div>
                    <label for="status_karyawan" class="block mb-2 text-sm font-medium text-gray-900">Status Karyawan</label>
                        <select id="status_karyawan" name="status_karyawan" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 
                                focus:border-blue-500 block w-full p-2.5">
                            <option value="" disabled selected>Pilih status</option>
                            <option value="Kontrak">Kontrak</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                </div>

                <!--kontrak mulai-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Kontrak Mulai</label>
                    <input type="date" name="kontrak_mulai" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!--kontrak selesai-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Kontrak Selesai</label>
                    <input type="date" name="kontrak_selesai" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!--no hp-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">No Handphone</label>
                    <input type="text" name="no_hp" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                 <!--no wa-->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">No WhatsApp</label>
                    <input type="text" name="no_wa" required 
                        class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div id="anakForm" class="grid grid-cols-3 gap-6">
                <button type="button" onclick="addAnak()">+ Tambah Anak</button> 
                    <div class="anak">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Anak</label>
                            <input type="text" name="nama_anak[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir_anak[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <button type="button" onclick="removeRow(this)" class="text-gray-700 bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none 
                            focus:ring-gray-200 font-medium rounded-sm text-sm m-2 p-2 flex items-center justif-center">Hapus</button>    
                    </div>       
            </div>
                
                <br>

            <div id="pasanganForm" class="grid grid-cols-3 gap-6">
                <button type="button" onclick="addPasangan()">+ Tambah Pasangan</button>
                    <div class="pasangan">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Pasangan</label>
                            <input type="text" name="nama_pasangan[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir_pasangan[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Pendidikan Terakhir</label>
                            <input type="text" name="pendidikan_pasangan[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tanggal Menikah</label>
                            <input type="date" name="tanggal_menikah[]" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <button type="button" onclick="removeRow(this)" class="text-gray-700 bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none 
                            focus:ring-gray-200 font-medium rounded-sm text-sm m-2 p-2 flex items-center justif-center">Hapus</button>
                    </div>
            </div>
                
                <br>

            <!-- Tombol -->
            <div class="grid grid-cols-2">
                <div class="flex space-x-4">
                    <button type="submit" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none 
                        focus:ring-blue-300 font-medium rounded-sm text-sm px-5 py-2.5 text-center">
                        Simpan
                    </button>
                    <a href="dashboard.php" 
                        class="text-gray-700 bg-gray-300 hover:bg-gray-400 focus:ring-4 focus:outline-none 
                        focus:ring-gray-200 font-medium rounded-sm text-sm px-5 py-2.5 text-center">
                        Batal
                    </a>
                </div>
            </div>    
    </div>
        </form>
    </div>

    <script src="add.js"></script>
</body>
</html>