<?php

session_start();

require_once 'koneksi.php';

// Fungsi untuk menampilkan halaman hasil yang bagus (tidak diubah)
function tampilkanHalamanHasil($data, $nomor_antrian, $tipe, $id_kunjungan_baru) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Check-in Berhasil</title>
        <link rel="stylesheet" href="desain_utama.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            .result-container {
                text-align: center;
                max-width: 550px;
                margin: 50px auto;
                padding: 40px;
                background: #fff;
                border-radius: 10px;
                box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            }
            .result-container h2 {
                font-size: 1.8em;
                color: #198754;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }
            .nomor-antrian {
                font-size: 5em;
                font-weight: 700;
                color: #0d6efd;
                margin: 20px 0;
                padding: 20px 0;
                border-top: 1px solid #eee;
                border-bottom: 1px solid #eee;
            }
            .detail-info {
                text-align: left;
                margin-top: 20px;
                padding-top: 20px;
                border-top: 1px solid #eee;
                color: #555;
                line-height: 1.8;
            }
            .btn-group {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-top: 30px;
            }
            .btn-kembali, .btn-download {
                display: inline-block;
                padding: 12px 30px;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 500;
            }
            .btn-kembali {
                background-color: var(--secondary-color, #6c757d);
            }
            .btn-download {
                background-color: var(--primary-color, #0d6efd);
            }
        </style>
    </head>
    <body>

    <header class="main-header">
        <div class="logo-container">
            <a href="index.php"><img src="logo/bpslogo.png" alt="Logo BPS"></a>
            <a href="index.php" class="logo-text">BPS KOTA BANDAR LAMPUNG</a>
        </div>
    </header>

    <main class="main-content">
        <div class="result-container">
            <h2><i class="fas fa-check-circle"></i> Check-in Berhasil!</h2>
            <p>Nomor antrian Anda adalah:</p>
            <div class="nomor-antrian"><?php echo htmlspecialchars($nomor_antrian); ?></div>

            <div class="detail-info">
                <?php if ($tipe === 'master'): ?>
                    <strong>ID Unik:</strong> <?php echo htmlspecialchars($data['id']); ?><br>
                <?php endif; ?>
                <strong>Nama:</strong> <?php echo htmlspecialchars($data['nama']); ?><br>
                <strong>Email:</strong> <?php echo htmlspecialchars($data['email'] ?? '-'); ?>
            </div>

            <div class="btn-group">
                <a href="cetak_antrian.php?id=<?php echo $id_kunjungan_baru; ?>" class="btn-download">Download No. Antrian</a>
                <a href="index.php" class="btn-kembali">Halaman Utama</a>
            </div>
        </div>
    </main>

    </body>
    </html>
    <?php
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit();
}

// Ambil data dari form
$originalGuestId = $_POST['guestId'] ?? null;
$type = $_POST['type'] ?? null;
$keperluan = trim($_POST['keperluan'] ?? '');
$rating = isset($_POST['rating']) && !empty($_POST['rating']) ? intval($_POST['rating']) : NULL;

if (empty($originalGuestId) || empty($type) || empty($keperluan)) {
    die("Error: Data yang dikirim tidak lengkap.");
}

mysqli_begin_transaction($koneksi);

try {
    $sumber_tabel = ($type === 'master') ? 'master_tamu' : 'tamu';

    $sql_master = "SELECT * FROM {$sumber_tabel} WHERE id = ?";
    $stmt_master = mysqli_prepare($koneksi, $sql_master);
    mysqli_stmt_bind_param($stmt_master, "i", $originalGuestId);
    mysqli_stmt_execute($stmt_master);
    $data_master = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_master));

    if (!$data_master) {
        throw new Exception("Data asal tidak ditemukan di tabel '{$sumber_tabel}'.");
    }

    $next_no_antrian = 1;
    $stmt_max = mysqli_prepare($koneksi, "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?");
    $tanggal_sekarang = date('Y-m-d');
    mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
    mysqli_stmt_execute($stmt_max);
    $res_max = mysqli_stmt_get_result($stmt_max);
    if ($max_d = mysqli_fetch_assoc($res_max)) {
        if ($max_d['max_antrian'] !== null) $next_no_antrian = (int)$max_d['max_antrian'] + 1;
    }

    // === PERBAIKAN UTAMA ADA DI SINI ===
    // Siapkan semua variabel terlebih dahulu sebelum digunakan di bind_param
    $master_id_sumber = ($type === 'master') ? $originalGuestId : null;
    $nama_tamu = $data_master['nama'];
    $email_tamu = $data_master['email'] ?? null;
    $alamat_tamu = $data_master['alamat'] ?? null;
    $pekerjaan_tamu = $data_master['pekerjaan'] ?? null;
    $kontak_tamu = $data_master['no_hp'] ?? $data_master['no_telp'] ?? null;
    $waktu_sekarang = date('Y-m-d H:i:s');

    $sql_insert = "INSERT INTO tamu (master_id_sumber, nama, email, alamat, keperluan, pekerjaan, no_telp, status_kehadiran, rating, waktu_input, waktu_scan_masuk, no_antrian_hari_ini, tanggal_antrian)
                   VALUES (?, ?, ?, ?, ?, ?, ?, 'Hadir', ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);

    // Gunakan variabel yang sudah didefinisikan, bukan ekspresi langsung
    mysqli_stmt_bind_param($stmt_insert, "issssssissis",
        $master_id_sumber,
        $nama_tamu,
        $email_tamu,
        $alamat_tamu,
        $keperluan,
        $pekerjaan_tamu,
        $kontak_tamu,
        $rating,
        $waktu_sekarang,
        $waktu_sekarang,
        $next_no_antrian,
        $tanggal_sekarang
    );

    if (!mysqli_stmt_execute($stmt_insert)) {
        throw new Exception("Gagal menyimpan data kunjungan baru: " . mysqli_stmt_error($stmt_insert));
    }

    mysqli_commit($koneksi);

    // Tampilkan halaman hasil yang bagus
    tampilkanHalamanHasil($data_master, $next_no_antrian, $type, mysqli_insert_id($koneksi));

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    // Gunakan fungsi yang sama untuk menampilkan error agar rapi
    tampilkanHalamanHasil(['nama' => 'Error'], "Gagal: " . $e->getMessage(), 'error', 0);
}

mysqli_close($koneksi);
exit();
?>
