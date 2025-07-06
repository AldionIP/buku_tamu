<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sampaikan Pengaduan - BADAN PUSAT SATISTIK KOTA BANDAR LAMPUNG</title>
    <link rel="stylesheet" href="desain_utama.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <header class="main-header">
        <div class="logo-container">
            <a href="index.php"><img src="logo/logo.png" alt="Logo BPS"></a>
            <a href="index.php" class="logo-text">BADAN PUSAT SATISTIK KOTA BANDAR LAMPUNG</a>
        </div>
        <nav class="header-nav">
            <a href="index.php">Buku Tamu</a>
            <a href="pengaduan.php" class="active">Pengaduan</a>
            <a href="login.php" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="form-wrapper">
                <h2><i class="fas fa-bullhorn"></i> Sampaikan Pengaduan atau Masukan</h2>
                <p style="text-align: center; margin-top: -20px; margin-bottom: 30px; color: #555;">Setiap masukan Anda sangat berharga untuk kami.</p>

                <?php 
                if (isset($_SESSION['public_message'])) {
                    $msg_type = $_SESSION['public_message_type'] ?? 'sukses';
                    echo '<div class="pesan ' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($_SESSION['public_message']) . '</div>';
                    unset($_SESSION['public_message'], $_SESSION['public_message_type']); 
                }
                ?>

                <form action="proses_pengaduan_public.php" method="post" enctype="multipart/form-data">
                    <div class="form-group"><label for="nama_pelapor">Nama Anda:</label><input type="text" id="nama_pelapor" name="nama_pelapor" required></div>
                    <div class="form-group"><label for="kontak_pelapor">Kontak (Email/No. HP):</label><input type="text" id="kontak_pelapor" name="kontak_pelapor"></div>
                    <div class="form-group"><label for="kategori_pengaduan">Kategori:</label><select id="kategori_pengaduan" name="kategori_pengaduan" required><option value="">-- Pilih Kategori --</option><option value="Fasilitas">Fasilitas</option><option value="Layanan">Layanan</option><option value="Saran">Saran</option><option value="Lainnya">Lainnya</option></select></div>
                    <div class="form-group"><label for="judul_pengaduan">Judul/Ringkasan:</label><input type="text" id="judul_pengaduan" name="judul_pengaduan" required></div>
                    <div class="form-group"><label for="detail_pengaduan">Detail Pengaduan/Masukan:</label><textarea id="detail_pengaduan" name="detail_pengaduan" rows="5" required></textarea></div>
                    <div class="form-group"><label for="lampiran">Bukti/Lampiran (Opsional, Max 2MB):</label><input type="file" id="lampiran" name="lampiran"><small>Format: JPG, PNG, PDF.</small></div>
                    <button type="submit" name="submit_pengaduan" class="btn btn-submit">Kirim Pengaduan</button>
                </form>
            </div>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container footer-content">
            <div class="footer-about"><div class="logo-container"><img src="logo/logo.png" alt="Logo BPS"><span class="logo-text">BADAN PUSAT SATISTIK<span>KOTA BANDAR LAMPUNG</span></span></div></div>
            <div class="footer-contact">
                <h3>Contact Information</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> (0721) 255980</li>
                    <li><i class="fas fa-envelope"></i> bps1800@bps.go.id</li>
                    <li><i class="fas fa-map-marker-alt"></i> Jl. Sultan Syahril No.30, Pahoman, Engal, Kota Bandar Lampung</li>
                </ul>
            </div>
        </div>
        <div class="footer-copyright">&copy; <?php echo date("Y"); ?> BADAN PUSAT SATISTIK KOTA BANDAR LAMPUNG. All Rights Reserved.</div>
    </footer>

</body>
</html>