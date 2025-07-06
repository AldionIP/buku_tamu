<?php
session_start();
require_once 'koneksi.php';
$opsi_keperluan = ['Koordinasi AntarInstansi' => 'Koordinasi AntarInstansi', 'Penawaran Kerja Sama' => 'Penawaran Kerja Sama', 'Pelayanan Statistik Terpadu' => 'Pelayanan Statistik Terpadu', 'Rapat/Pertemuan' => 'Rapat/Pertemuan', 'Diskusi/Koordinasi Kegiatan Statistik' => 'Diskusi/Koordinasi Kegiatan Statistik', 'Lainnya' => 'Lainnya'];
$opsi_pekerjaan = ['Aparat Desa/Kelurahan' => 'Aparat Desa/Kelurahan', 'Pegawai/Guru' => 'Pegawai/Guru', 'Mengurus Rumah Tangga' => 'Mengurus Rumah Tangga', 'Mitra BPS' => 'Mitra BPS', 'Wirausaha' => 'Wirausaha', 'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa', 'Honorer' => 'Honorer',  'Wiraswasta' => 'Wiraswasta',  'Freelance' => 'Freelance',  'Buruh' => 'Buruh',  'Lainnya' => 'Lainnya'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu Digital - BPS Kota Bandar Lampung</title>
    <link rel="stylesheet" href="desain_utama.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        :root {
            --primary-color: #0d6efd; --secondary-color: #6c757d; --success-color: #198754;
            --light-bg: #f4f7f9; --dark-bg: #2c3e50; --text-light: #ffffff; --text-dark: #212529;
            --text-muted: #adb5bd; --font-family: 'Poppins', sans-serif; --border-radius: 8px; --box-shadow: 0 4px 25px rgba(0,0,0,0.08);
        }
        body { margin: 0; font-family: var(--font-family); background-color: var(--light-bg); color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; }
        * { box-sizing: border-box; }
        .main-content { flex: 1; padding: 50px 20px; }
        .container { max-width: 1140px; margin: 0 auto; padding: 0 15px; }
        .main-header { background-color: var(--text-light); padding: 1rem 2.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.07); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1020; }
        .main-header .logo-container { display: flex; align-items: center; gap: 15px; }
        .main-header .logo-container img { height: 45px; }
        .main-header .logo-container .logo-text { font-size: 1.25rem; font-weight: 600; color: var(--text-dark); text-decoration: none; }
        .main-header .header-nav { display: flex; align-items: center; gap: 30px; }
        .main-header .header-nav a { text-decoration: none; color: var(--secondary-color); font-weight: 500; transition: color 0.2s; }
        .main-header .header-nav a:hover, .main-header .header-nav a.active { color: var(--primary-color); }
        .main-header .header-nav .btn-login { background-color: var(--primary-color); color: var(--text-light); padding: 10px 20px; border-radius: 5px; }
        .main-header .header-nav .btn-login:hover { background-color: #0b5ed7; color: var(--text-light); }
        .main-footer { background-color: var(--dark-bg); color: var(--text-muted); padding: 50px 0; margin-top: auto; }
        .main-footer .footer-content { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 30px; }
        .main-footer .footer-about, .main-footer .footer-contact { flex: 1; min-width: 300px; padding: 0 20px; }
        .main-footer .footer-about .logo-container { display: flex; align-items: center; margin-bottom: 20px; gap: 15px;}
        .main-footer .footer-about img { height: 60px; }
        .main-footer .footer-about .logo-text { color: var(--text-light); font-size: 1.3rem; font-weight: 500; line-height: 1.2;}
        .main-footer .footer-about .logo-text span { display: block; font-size: 0.8em; font-weight: 300; }
        .main-footer h3 { color: var(--text-light); margin-bottom: 20px; font-weight: 500; }
        .main-footer .footer-contact ul { list-style: none; padding: 0; margin: 0; }
        .main-footer .footer-contact li { margin-bottom: 15px; display: flex; align-items: flex-start; gap: 15px; }
        .main-footer .footer-contact li i { margin-top: 5px; width: 20px; text-align: center; color: var(--primary-color); }
        .main-footer .footer-copyright { text-align: center; margin-top: 40px; padding-top: 25px; border-top: 1px solid #4e5d6c; font-size: 0.9em; }
        .action-chooser { display: flex; justify-content: center; gap: 40px; margin: 50px 0; text-align: center; flex-wrap: wrap; }
        .action-card { background-color: #fff; padding: 40px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); width: 350px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; border: 1px solid #e9ecef; }
        .action-card:hover { transform: translateY(-10px); box-shadow: 0 8px 30px rgba(0,0,0,0.12); }
        .action-card i { font-size: 3.5rem; color: var(--primary-color); margin-bottom: 25px; }
        .action-card h3 { margin: 0 0 10px 0; font-size: 1.7rem; font-weight: 600; }
        .action-card p { font-size: 1rem; color: #555; line-height: 1.6; }
        .form-section-wrapper { display: none; } /* PENTING */
        .form-wrapper { max-width: 750px; margin: 20px auto; background-color: #fff; padding: 40px 50px; border-radius: var(--border-radius); box-shadow: var(--box-shadow); }
        .form-wrapper h2 { text-align: center; margin-bottom: 30px; font-weight: 600; display: flex; justify-content: center; align-items: center; gap: 15px; }
        .form-group { margin-bottom: 25px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 12px 15px; border: 1px solid #ced4da; border-radius: 6px; font-size: 1em; font-family: var(--font-family); }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25); }
        .form-group textarea { resize: vertical; min-height: 140px; }
        .btn { padding: 12px 30px; font-size: 1.1em; border: none; border-radius: 6px; cursor: pointer; transition: background-color 0.2s ease; }
        .btn-submit { background-color: var(--success-color); color: white; width: 100%; font-weight: 500; }
        .btn-submit:hover { background-color: #157347; }
        .btn-back { display: inline-block; margin-bottom: 25px; background: none; border: none; color: var(--secondary-color); cursor: pointer; font-size: 1em; }
        .btn-back:hover { color: var(--text-dark); }
        .btn-back i { margin-right: 5px; }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="logo-container">
            <a href="index.php"><img src="logo/logo.png" alt="Logo BPS"></a>
            <a href="index.php" class="logo-text">BPS KOTA BANDAR LAMPUNG</a>
        </div>
        <nav class="header-nav">
            <a href="index.php" class="active">Buku Tamu</a>
            <a href="login.php" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="container">
            <div id="actionChooser">
                <div class="action-chooser">
                    <div class="action-card" data-target="manualFormSection"><i class="fas fa-user-edit fa-3x"></i><h3>Isi Data Manual</h3><p>Daftarkan kunjungan Anda.</p></div>
                    <div class="action-card" data-target="scanQRSection"><i class="fas fa-qrcode fa-3x"></i><h3>Scan QR Code</h3><p>Check-in cepat dengan QR Code.</p></div>
                </div>
            </div>
            <div id="manualFormSection" class="form-section-wrapper form-wrapper">
                <button class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</button>
                <h2 style="margin-top:20px;"><i class="fas fa-user-edit"></i> Isi Data Kunjungan</h2>
                <form action="proses_tamu_manual_public.php" method="post">
                    
                    <div class="form-group"><label>Nama Lengkap:</label><input type="text" name="nama_manual" required></div>
                    <div class="form-group"><label>Email (Opsional):</label><input type="email" name="email_manual"></div>
                    <div class="form-group"><label>Alamat:</label><textarea name="alamat_manual" rows="3"></textarea></div>
                    <div class="form-group"><label>Keperluan:</label><select name="keperluan_manual" required><option value="">-- Pilih --</option><?php foreach ($opsi_keperluan as $v => $t) echo "<option value='".htmlspecialchars($v)."'>".htmlspecialchars($t)."</option>"; ?></select></div>
                    <div class="form-group"><label>Pekerjaan:</label><select name="pekerjaan_manual"><option value="">-- Pilih --</option><?php foreach ($opsi_pekerjaan as $v => $t) echo "<option value='".htmlspecialchars($v)."'>".htmlspecialchars($t)."</option>"; ?></select></div>
                    <div class="form-group"><label>No. Telepon:</label><input type="text" name="no_telp_manual"></div>
                    <div class="form-group">
   <div class="form-group">
    <label>Rating Pelayanan (Opsional):</label>
    <div class="rating-input">
        <input type="radio" id="manual_star5" name="rating_manual" value="5"><label for="manual_star5" title="Sangat Puas"><i class="fas fa-star"></i></label>
        <input type="radio" id="manual_star4" name="rating_manual" value="4"><label for="manual_star4" title="Puas"><i class="fas fa-star"></i></label>
        <input type="radio" id="manual_star3" name="rating_manual" value="3"><label for="manual_star3" title="Cukup Puas"><i class="fas fa-star"></i></label>
        <input type="radio" id="manual_star2" name="rating_manual" value="2"><label for="manual_star2" title="Kurang Puas"><i class="fas fa-star"></i></label>
        <input type="radio" id="manual_star1" name="rating_manual" value="1"><label for="manual_star1" title="Tidak Puas"><i class="fas fa-star"></i></label>
    </div>
</div>
                    <button type="submit" name="submit_manual" class="btn btn-submit">Kirim & Dapatkan Antrian</button>
                </form>
            </div>
            <div id="scanQRSection" class="form-section-wrapper form-wrapper">
                <button class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</button>
                <h2 style="margin-top:20px;"><i class="fas fa-qrcode"></i> Scan QR Code</h2>
                <div id="qr-reader-public" style="width:100%;max-width:400px;margin:auto;"></div>
                <div id="qr-reader-results-public" style="margin-top:15px;"></div>
            </div>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container footer-content">
            <div class="footer-about"><div class="logo-container"><img src="logo/logo.png" alt="Logo BPS"><span class="logo-text">Badan Pusat Statistik<span>Kota Bandar Lampung</span></span></div></div>
            <div class="footer-contact"><h3>Contact Information</h3><ul><li><i class="fas fa-phone"></i> (0721) 255980</li><li><i class="fas fa-envelope"></i> bps1800@bps.go.id</li><li><i class="fas fa-map-marker-alt"></i> Jl. Sultan Syahril No.30, Pahoman, Engal, Kota Bandar Lampung</li></ul></div>
        </div>
        <div class="footer-copyright">&copy; <?php echo date("Y"); ?> BPS Kota Bandar Lampung. All Rights Reserved.</div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionChooser = document.getElementById('actionChooser');
            const formSections = document.querySelectorAll('.form-section-wrapper');
            const actionCards = document.querySelectorAll('.action-card');
            const backButtons = document.querySelectorAll('.btn-back');
            let html5QrCodeScanner;

            function showSection(targetId) {
                if(actionChooser) actionChooser.style.display = 'none';
                formSections.forEach(s => s.style.display = 'none');
                const targetSection = document.getElementById(targetId);
                if(targetSection) {
                    targetSection.style.display = 'block';
                    if (targetId === 'scanQRSection') {
                        startQrScanner();
                    } else {
                        stopQrScanner();
                    }
                }
            }
            function showChooser() {
                if(actionChooser) actionChooser.style.display = 'block';
                formSections.forEach(s => s.style.display = 'none');
                stopQrScanner();
            }
            
            actionCards.forEach(card => card.addEventListener('click', () => showSection(card.dataset.target)));
            backButtons.forEach(button => button.addEventListener('click', showChooser));
            
            function onScanSuccess(decodedText) {
                stopQrScanner();
                const resultContainer = document.getElementById('qr-reader-results-public');
                resultContainer.innerHTML = `<p class="pesan" style="background-color:#eee;"><i>Menganalisis QR...</i></p>`;
                
                const text = decodedText.trim().toLowerCase();
                let id = null;

                if (text.startsWith('id master tamu:')) {
                    id = parseInt(text.split(':')[1].trim());
                    if (!isNaN(id)) {
                        window.location.href = `konfirmasi.php?type=master&id=${id}`;
                    } else {
                        resultContainer.innerHTML = `<p class="pesan error">Format ID Master Tamu di QR tidak valid.</p>`;
                    }
                } else if (text.startsWith('id tamu:')) {
                    id = parseInt(text.split(':')[1].trim());
                    if (!isNaN(id)) {
                        window.location.href = `konfirmasi.php?type=tamu&id=${id}`;
                    } else {
                        resultContainer.innerHTML = `<p class="pesan error">Format ID Tamu di QR tidak valid.</p>`;
                    }
                } else {
                    resultContainer.innerHTML = `<p class="pesan error">Format QR tidak dikenali.</p>`;
                }
            }

            function startQrScanner() {
                const qrReaderElement = document.getElementById("qr-reader-public");
                if (typeof Html5QrcodeScanner !== "undefined") {
                    if (!html5QrCodeScanner || (html5QrCodeScanner && !html5QrCodeScanner.isScanning)) {
                        qrReaderElement.innerHTML = '';
                        html5QrCodeScanner = new Html5QrcodeScanner("qr-reader-public", { fps: 10, qrbox: {width: 250, height: 250} }, false);
                        html5QrCodeScanner.render(onScanSuccess, () => {});
                    }
                } else {
                    console.error("Library Html5QrcodeScanner tidak ditemukan. Pastikan koneksi internet aktif.");
                    qrReaderElement.innerHTML = '<p class="pesan error">Gagal memuat komponen scanner. Periksa koneksi internet Anda.</p>';
                }
            }

            function stopQrScanner() {
                if (html5QrCodeScanner && html5QrCodeScanner.isScanning) {
                    html5QrCodeScanner.clear().catch(error => {});
                }
            }
        });
    </script>
</body>
</html>