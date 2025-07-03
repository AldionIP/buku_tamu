<?php

session_start();

require_once 'koneksi.php';

// Fungsi untuk menampilkan halaman hasil yang bagus
function tampilkanHalamanHasil($data, $nomor_antrian, $tipe) {
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
            .btn-kembali {
                display: inline-block;
                margin-top: 30px;
                padding: 12px 30px;
                background-color: #0d6efd;
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 500;
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
                <strong>Email:</strong> <?php echo htmlspecialchars($data['email'] ?? '-'); ?><br>
                <strong>No. HP:</strong> <?php echo htmlspecialchars($data['no_hp'] ?? $data['no_telp'] ?? '-'); ?><br>
                <strong>Alamat:</strong> <?php echo htmlspecialchars($data['alamat'] ?? '-'); ?>
            </div>

            <a href="index.php" class="btn-kembali">Kembali ke Halaman Utama</a>
        </div>
    </main>

    </body>
    </html>
    <?php
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_manual'])) {
    $nama = trim($_POST['nama_manual'] ?? '');
    $email = trim($_POST['email_manual'] ?? null);
    $alamat = trim($_POST['alamat_manual'] ?? '');
    $keperluan = trim($_POST['keperluan_manual'] ?? '');
    $pekerjaan = trim($_POST['pekerjaan_manual'] ?? '');
    $no_telp = trim($_POST['no_telp_manual'] ?? '');
    $waktu_sekarang = date('Y-m-d H:i:s');
    $tanggal_sekarang = date('Y-m-d');

    if (empty($nama) || empty($keperluan)) {
        die("Nama dan Keperluan wajib diisi.");
    }

    try {
        $sql = "INSERT INTO tamu (nama, email, alamat, keperluan, pekerjaan, no_telp, status_kehadiran, waktu_input, tanggal_antrian)
                VALUES (?, ?, ?, ?, ?, ?, 'Hadir', ?, ?)";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssss", $nama, $email, $alamat, $keperluan, $pekerjaan, $no_telp, $waktu_sekarang, $tanggal_sekarang);
        mysqli_stmt_execute($stmt);
        $last_id = mysqli_insert_id($koneksi);

        $next_no_antrian = 1;
        $stmt_max = mysqli_prepare($koneksi, "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?");
        mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
        mysqli_stmt_execute($stmt_max);
        $res_max = mysqli_stmt_get_result($stmt_max);
        if ($max_d = mysqli_fetch_assoc($res_max)) {
            if ($max_d['max_antrian'] !== null) {
                $next_no_antrian = (int)$max_d['max_antrian'] + 1;
            }
        }

        $stmt_upd = mysqli_prepare($koneksi, "UPDATE tamu SET no_antrian_hari_ini = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_upd, "ii", $next_no_antrian, $last_id);
        mysqli_stmt_execute($stmt_upd);

        $data_hasil = [
            'id' => $last_id,
            'nama' => $nama,
            'email' => $email,
            'no_telp' => $no_telp,
            'alamat' => $alamat
        ];

        tampilkanHalamanHasil($data_hasil, $next_no_antrian, 'tamu');

    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }

    exit();
}

?>
