<?php
session_start();
require_once 'koneksi.php';

$type = $_GET['type'] ?? null;
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = null;
$error_message = '';
$nama_tabel = '';

if ($id > 0 && $koneksi) {
    if ($type === 'master') {
        $nama_tabel = 'master_tamu';
    } elseif ($type === 'tamu') {
        $nama_tabel = 'tamu';
    }

    if (!empty($nama_tabel)) {
        $sql = "SELECT * FROM {$nama_tabel} WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
    } else {
        $error_message = "Tipe QR tidak valid.";
    }

    if (!$data) {
        $error_message = "Data tidak ditemukan di database untuk ID yang di-scan.";
    }
} else {
    $error_message = "ID dari QR Code tidak valid atau koneksi database gagal.";
}

$opsi_keperluan = ['Koordinasi AntarInstansi' => 'Koordinasi AntarInstansi', 'Penawaran Kerja Sama' => 'Penawaran Kerja Sama', 'Pelayanan Statistik Terpadu' => 'Pelayanan Statistik Terpadu', 'Rapat/Pertemuan' => 'Rapat/Pertemuan', 'Diskusi/Koordinasi Kegiatan Statistik' => 'Diskusi/Koordinasi Kegiatan Statistik', 'Lainnya' => 'Lainnya'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Kunjungan</title>
    <link rel="stylesheet" href="desain_utama.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="main-header">
        <div class="logo-container">
            <a href="index.php"><img src="logo/bpslogo.png" alt="Logo BPS"></a>
            <a href="index.php" class="logo-text">BPS KOTA BANDAR LAMPUNG</a>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="form-wrapper">
                <?php if ($error_message): ?>
                    <h2><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan</h2>
                    <p class="pesan error"><?php echo htmlspecialchars($error_message); ?></p>
                    <a href="index.php" style="text-decoration:none;"><button class="btn btn-secondary">Kembali ke Halaman Scan</button></a>

                <?php elseif ($data): ?>
                    <h2><i class="fas fa-user-check"></i> Konfirmasi Kunjungan</h2>
                     <div style="margin-bottom:20px;background:#f5f5f5;padding:15px;border-radius:5px;line-height:1.6;">
                        <strong>Nama:</strong> <?php echo htmlspecialchars($data['nama']); ?><br>
                        <strong>Pekerjaan:</strong> <?php echo htmlspecialchars($data['pekerjaan'] ?? '-'); ?>
                    </div>
                    <form action="proses_final_checkin.php" method="post">
                        <input type="hidden" name="guestId" value="<?php echo htmlspecialchars($data['id']); ?>">
                        <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                        
                        <div class="form-group">
                            <label>Keperluan Kunjungan Anda:</label>
                            <select name="keperluan" required><option value="">-- Pilih --</option><?php foreach ($opsi_keperluan as $v => $t) echo "<option value='".htmlspecialchars($v)."'>".htmlspecialchars($t)."</option>"; ?></select>
                        </div>
                        <div class="form-group">
                            <label>Rating Pelayanan (Opsional):</label>
                            <select name="rating"><option value="">-- Beri Rating --</option><option value="5">5 Bintang</option><option value="4">4 Bintang</option><option value="3">3 Bintang</option><option value="2">2 Bintang</option><option value="1">1 Bintang</option></select>
                        </div>
                        <button type="submit" class="btn btn-submit">Simpan Kunjungan & Dapatkan Antrian</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>