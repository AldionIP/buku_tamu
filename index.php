<?php
session_start(); 
require_once 'koneksi.php'; 

// Opsi dropdown bisa ditaruh di sini atau diinclude dari file lain
$opsi_keperluan = [
    'Bertamu' => 'Bertamu', 'Belajar' => 'Belajar', 'Mitra' => 'Mitra', 
    'Kolaborasi' => 'Kolaborasi', 'Meeting' => 'Meeting', 
    'Mabar' => 'Mabar (Main Bareng)', 'Lainnya' => 'Lainnya'
];
$opsi_pekerjaan = [
    'Explaner' => 'Explaner', 'Goldlane' => 'Goldlane', 'Hyper' => 'Hyper', 
    'Roamer' => 'Roamer', 'Jungler' => 'Jungler', 
    'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa', 'Karyawan Swasta' => 'Karyawan Swasta',
    'Wiraswasta' => 'Wiraswasta', 'PNS/ASN' => 'PNS/ASN', 'Lainnya' => 'Lainnya' 
];

// Cek data popup dari session
$trigger_popup_data = null;
if (isset($_SESSION['popup_data_manual'])) {
    $trigger_popup_data = $_SESSION['popup_data_manual'];
    unset($_SESSION['popup_data_manual']); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Buku Tamu & Pengaduan Digital XYZ</title>
    <link rel="stylesheet" href="style.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <style>
        /* === CSS WAJIB UNTUK index.php === */
        /* --- Variabel (Contoh, sesuaikan dengan style.css Anda) --- */
        :root { 
            --primary-color: #007bff; --secondary-color: #6c757d; --success-color: #28a745; 
            --danger-color: #dc3545; --light-bg: #f8f9fa; --dark-text: #343a40; 
            --light-text: #ffffff; --border-color-light: #dee2e6; --star-color: #f39c12; 
        } 
        /* --- Basic Reset & Body --- */
        body.public-body { margin: 0; font-family: 'Segoe UI', sans-serif; background-color: #e9ecef; color: var(--dark-text); line-height: 1.6; } 
        * { box-sizing: border-box; } 
        a { color: var(--primary-color); text-decoration: none; } 
        a:hover { text-decoration: underline; } 
        
        /* --- Header --- */
        .public-header { background-color: var(--light-text); padding: 15px 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000;} 
        .logo a { text-decoration: none; color: var(--primary-color); font-size: 1.8em; font-weight: bold; } 
        .login-button a { background-color: var(--primary-color); color: var(--light-text); padding: 10px 18px; border-radius: 5px; text-decoration: none; font-size: 0.95em; transition: background-color 0.2s ease;} 
        .login-button a:hover { background-color: #0056b3; } 
        .login-button a i { margin-right: 5px; } 
        
        /* --- Container & Section --- */
        .container-public { max-width: 900px; margin: 30px auto; padding: 0 20px; } 
        .section { background-color: #fff; padding: 25px 30px; margin-bottom: 35px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); } 
        .section h2 { margin-top: 0; color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 12px; margin-bottom: 25px; font-size: 1.7em; display: flex; align-items: center;} 
        .section h2 i { margin-right: 12px; font-size: 0.9em; } 
        .section p.description { color: #555; margin-bottom: 25px; font-size: 1.05em; } 

        /* --- Action Chooser --- */
        .action-chooser { display: flex; justify-content: space-around; flex-wrap: wrap; gap: 25px; margin-bottom: 40px; text-align: center; }
        .action-card { background-color: #fff; padding: 30px 25px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); width: calc(33.333% - 20px); min-width: 250px; cursor: pointer; transition: transform 0.2s ease-out, box-shadow 0.2s ease-out; display: flex; flex-direction: column; align-items: center; border: 1px solid var(--border-color-light);}
        .action-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); border-color: var(--primary-color); }
        .action-card i.fa-3x { font-size: 3em; color: var(--primary-color); margin-bottom: 20px; width: 60px; height: 60px; line-height: 60px; border-radius: 50%; background-color: rgba(0, 123, 255, 0.1); text-align: center;}
        .action-card h3 { margin-top: 0; margin-bottom: 10px; font-size: 1.3em; color: var(--dark-text); }
        .action-card p { font-size: 0.9em; color: #555; line-height: 1.5; }

        /* --- Form Sections (Hidden by Default) --- */
        .form-section-wrapper {
            display: none; /* SANGAT PENTING: Sembunyikan section form awalnya */
            /* Style section lain jika perlu (padding, margin sudah ada dari .section) */
        }
        .form-section-wrapper.active-section {
            display: block !important; /* Tampilkan section yang aktif */
        }
        .btn-back-to-chooser { display: inline-block; margin-bottom: 25px; background-color: var(--secondary-color); color: white; padding: 8px 15px; font-size: 0.9em; border:none; border-radius: 4px; cursor: pointer;}
        .btn-back-to-chooser:hover { background-color: #5a6268; }
        .btn-back-to-chooser i { margin-right: 5px; }

        /* --- Styling Form Umum --- */
        .form-container { /* Bisa pakai class yg sama dg admin atau beda */ } 
        .form-group { margin-bottom: 20px; } 
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #495057; font-size: 0.95em; } 
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="datetime-local"], .form-group textarea, .form-group select, .form-group input[type="file"] { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color-light); border-radius: 6px; box-sizing: border-box; font-size: 1em; transition: border-color 0.2s ease, box-shadow 0.2s ease; } 
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2); } 
        .form-group textarea { resize: vertical; min-height: 120px;} 
        .form-group small { font-size: 0.85em; color: #6c757d; margin-top: 6px; display: block; } 
        .btn-submit-public { background-color: var(--success-color); color: white; padding: 12px 30px; font-size: 1.1em; border:none; border-radius: 6px; cursor: pointer; transition: background-color 0.2s ease;} 
        .btn-submit-public:hover { background-color: #1e7e34; } 
        .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-start; margin-bottom: 10px;} 
        .rating-input input[type="radio"] { display: none; } 
        .rating-input label { color: #ddd; font-size: 2em; cursor: pointer; padding: 0 0.1em; transition: color 0.2s ease-in-out;} 
        .rating-input input[type="radio"]:checked ~ label, .rating-input label:hover, .rating-input label:hover ~ label { color: var(--star-color); }

        /* --- QR Scanner --- */
        #qr-reader-public { width: 100%; max-width: 380px; margin: 25px auto; border: 2px dashed var(--primary-color); padding: 15px; border-radius: 8px; background-color: #fdfdff;} 
        #qr-reader-results-public { text-align: center; margin-top: 15px; font-weight: bold; min-height: 45px;} 

        /* --- Modals --- */
        .modal { display: none; /* SANGAT PENTING */ position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); padding-top: 60px; } 
        .modal-content { background-color: #fff; margin: 10% auto; padding: 25px 30px; border-radius: 10px; width: 90%; max-width: 480px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); position: relative; } 
        .modal-close-btn { color: #888; position: absolute; top: 15px; right: 20px; font-size: 2em; font-weight: bold; cursor: pointer; line-height: 1;} .modal-close-btn:hover { color: #333; } 
        /* Antrian Popup Modal */
        #antrianPopupModal .modal-content { text-align: center; } 
        #antrianPopupModal h3 { font-size: 1.5em; color: var(--success-color); margin-bottom: 15px; font-weight: 600; } 
        #antrianPopupModal h3 i { margin-right: 10px; } 
        #antrianPopupModal p:first-of-type { margin-top: 10px; margin-bottom: 8px; font-size: 1.05em; color: #444; } 
        #antrianPopupModal .antrian-number { font-size: 5em; font-weight: 700; color: var(--primary-color); margin: 15px 0 25px 0; line-height: 1; display: block; padding: 20px 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee; } 
        #antrianPopupModal .detail-tamu { font-size: 0.95em; color: #555; margin-top: 20px; text-align: left; border-top: 1px solid #eee; padding-top: 15px; width: 100%; } 
        #antrianPopupModal .detail-tamu p { margin: 8px 0; display: flex; justify-content: space-between; } 
        #antrianPopupModal .detail-tamu strong { font-weight: 600; margin-right: 10px; color: #333; min-width: 80px; } 
        #antrianPopupModal .detail-tamu span { text-align: right; flex-grow: 1; word-break: break-word; } 
        #antrianPopupModal .antrianPopup-close-btn { margin-top: 30px; padding: 10px 25px; font-size: 1em; background-color: var(--primary-color); color: white; border:none; cursor:pointer; } 
        #antrianPopupModal .antrianPopup-close-btn:hover { background-color: #0056b3; }
        /* QR Confirm Modal */
        #qrScanConfirmKeperluanModal .modal-content { text-align: left;}
        #qrScanConfirmKeperluanModal .form-container { margin-top: 0; padding-top: 10px; border: none; box-shadow: none;}
        #qrScanConfirmKeperluanModal #qrScanConfirmDataDisplay p { margin: 5px 0; font-size: 0.95em;}
        #qrScanConfirmKeperluanModal #qrScanConfirmDataDisplay p strong { display: inline-block; width: 90px; color:#333;}
        #qrScanConfirmKeperluanModal #qrScanConfirmModalPesan { margin-top: 15px; }
        /* Rating Modal */
        #ratingAfterCheckinModal .modal-content { text-align: center; }
        #ratingAfterCheckinModal h3 { color: var(--primary-color); }
        #ratingAfterCheckinModal #ratingGuestInfo { margin-bottom:15px; font-size:1em; color:#555; }
        #ratingAfterCheckinModal #ratingGuestInfo strong { font-weight:bold; color:#333; }
        #ratingAfterCheckinModal .rating-input label { font-size: 2.5em; }
        #ratingAfterCheckinModal .form-group label { text-align:center; margin-bottom:10px; }
        .btn-full-width { display: block; width: 100%; margin-top: 15px; text-align:center; }
        #ratingAfterCheckinModalPesan { margin-top: 15px; }

        /* --- Responsif Umum index.php --- */
        @media (max-width: 992px) { .action-card { width: calc(50% - 15px); } } 
        @media (max-width: 600px) { .action-chooser { flex-direction: column; align-items: center; } .action-card { width: 90%; max-width: 350px; } .public-header { padding: 10px 20px; flex-direction: column; } .logo { margin-bottom: 10px;} .login-button a { padding: 8px 12px; font-size: 0.85em; } .container-public { margin-top: 20px; padding: 0 15px; } .section { padding: 20px; margin-bottom: 25px; } .section h2 { font-size: 1.5em; } #qr-reader-public { max-width: 280px; } #antrianPopupModal .modal-content { margin-top: 20%; padding: 20px; } #antrianPopupModal .antrian-number { font-size: 3.5em; } }
        
    </style>
</head>
<body class="public-body">

    <header class="public-header">
        <div class="logo">
            <a href="index.php">BUKU TAMU DIGITAL BPS</a> 
        </div>
        <div class="login-button">
            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login Admin</a>
        </div>
    </header>

    <div class="container-public">
         <?php 
            if (isset($_SESSION['public_message'])) {
                $msg_type = $_SESSION['public_message_type'] ?? 'sukses';
                echo '<div class="pesan ' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($_SESSION['public_message']) . '</div>';
                unset($_SESSION['public_message']); 
                unset($_SESSION['public_message_type']); 
            }
        ?>

        <div id="actionChooser" class="action-chooser">
            <div class="action-card" data-target="manualFormSection">
                <i class="fas fa-user-edit fa-3x"></i>
                <h3>Isi Data Tamu Manual</h3>
                <p>Daftarkan kunjungan Anda dengan mengisi formulir secara manual.</p>
            </div>
            <div class="action-card" data-target="scanQRSection">
                <i class="fas fa-qrcode fa-3x"></i>
                <h3>Scan QR Code</h3>
                <p>Check-in dengan cepat menggunakan QR Code yang sudah Anda miliki.</p>
            </div>
            <div class="action-card" data-target="complaintFormSection">
                <i class="fas fa-bullhorn fa-3x"></i>
                <h3>Sampaikan Pengaduan</h3>
                <p>Berikan masukan atau sampaikan pengaduan Anda kepada kami.</p>
            </div>
        </div>
        <div id="manualFormSection" class="form-section-wrapper section">
            <button class="btn btn-sm btn-secondary btn-back-to-chooser"><i class="fas fa-arrow-left"></i> Kembali ke Pilihan</button>
            <h2><i class="fas fa-user-edit"></i> Isi Data Kunjungan Anda</h2>
            <p class="description">Selamat datang! Silakan lengkapi formulir di bawah ini.</p>
            <div class="form-container">
                 <form action="proses_tamu_manual_public.php" method="post">
                    <div class="form-group"><label for="nama_manual_idx">Nama Lengkap:</label><input type="text" id="nama_manual_idx" name="nama_manual" required></div>
                    <div class="form-group"><label for="alamat_manual_idx">Alamat (Opsional):</label><textarea id="alamat_manual_idx" name="alamat_manual" rows="3"></textarea></div>
                    <div class="form-group">
                        <label for="keperluan_manual_idx">Keperluan:</label>
                        <select id="keperluan_manual_idx" name="keperluan_manual" required>
                            <option value="">-- Pilih Keperluan --</option>
                            <?php foreach ($opsi_keperluan as $value_opt => $text_opt): ?> <option value="<?php echo htmlspecialchars($value_opt); ?>"><?php echo htmlspecialchars($text_opt); ?></option> <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pekerjaan_manual_idx">Pekerjaan/Role:</label>
                        <select id="pekerjaan_manual_idx" name="pekerjaan_manual">
                            <option value="">-- Pilih Pekerjaan/Role (Opsional) --</option>
                            <?php foreach ($opsi_pekerjaan as $value_opt => $text_opt): ?> <option value="<?php echo htmlspecialchars($value_opt); ?>"><?php echo htmlspecialchars($text_opt); ?></option> <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group"><label for="no_telp_manual_idx">No. Telepon (Opsional):</label><input type="text" id="no_telp_manual_idx" name="no_telp_manual"></div>
                    <div class="form-group">
                        <label>Berikan Rating Pelayanan Kami (Opsional):</label>
                        <div class="rating-input">
                            <input type="radio" id="star5_manual_idx" name="rating_manual" value="5"><label for="star5_manual_idx" title="Sangat Puas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star4_manual_idx" name="rating_manual" value="4"><label for="star4_manual_idx" title="Puas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star3_manual_idx" name="rating_manual" value="3"><label for="star3_manual_idx" title="Cukup Puas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star2_manual_idx" name="rating_manual" value="2"><label for="star2_manual_idx" title="Kurang Puas"><i class="fas fa-star"></i></label>
                            <input type="radio" id="star1_manual_idx" name="rating_manual" value="1"><label for="star1_manual_idx" title="Tidak Puas"><i class="fas fa-star"></i></label>
                        </div>
                    </div>
                    <button type="submit" name="submit_manual" class="btn btn-submit-public">Kirim & Dapatkan No Antrian</button>
                </form>
            </div>
        </div>

        <div id="scanQRSection" class="form-section-wrapper section">
            <button class="btn btn-sm btn-secondary btn-back-to-chooser"><i class="fas fa-arrow-left"></i> Kembali ke Pilihan</button>
            <h2><i class="fas fa-qrcode"></i> Scan QR Code Kehadiran</h2>
            <p class="description">Arahkan QR Code Anda pada kamera di bawah ini.</p>
            <p class="pesan warning" style="text-align: center; font-size:0.9em;"><small><i class="fas fa-info-circle"></i> Memerlukan akses kamera & HTTPS.</small></p>
            <div id="qr-reader-public"></div>
            <div id="qr-reader-results-public"></div>
        </div>

        <div id="complaintFormSection" class="form-section-wrapper section">
             <button class="btn btn-sm btn-secondary btn-back-to-chooser"><i class="fas fa-arrow-left"></i> Kembali ke Pilihan</button>
             <h2><i class="fas fa-bullhorn"></i> Sampaikan Pengaduan atau Masukan</h2>
             <p class="description">Setiap masukan Anda sangat berharga untuk peningkatan layanan kami.</p>
             <div class="form-container">
                 <form action="proses_pengaduan_public.php" method="post" enctype="multipart/form-data">
                    <div class="form-group"><label for="nama_pelapor_idx">Nama Anda:</label><input type="text" id="nama_pelapor_idx" name="nama_pelapor" required></div>
                    <div class="form-group"><label for="kontak_pelapor_idx">Kontak (Email/No. HP):</label><input type="text" id="kontak_pelapor_idx" name="kontak_pelapor"></div>
                    <div class="form-group"><label for="kategori_pengaduan_idx">Kategori:</label><select id="kategori_pengaduan_idx" name="kategori_pengaduan" required><option value="">-- Pilih --</option><option value="Fasilitas">Fasilitas</option><option value="Layanan">Layanan</option><option value="Keamanan">Keamanan</option><option value="Kebersihan">Kebersihan</option><option value="Saran">Saran</option><option value="Lainnya">Lainnya</option></select></div>
                    <div class="form-group"><label for="judul_pengaduan_idx">Judul/Ringkasan:</label><input type="text" id="judul_pengaduan_idx" name="judul_pengaduan" required></div>
                    <div class="form-group"><label for="detail_pengaduan_idx">Detail Pengaduan/Masukan:</label><textarea id="detail_pengaduan_idx" name="detail_pengaduan" rows="5" required></textarea></div>
                    <div class="form-group"><label for="lokasi_kejadian_idx">Lokasi Terkait (jika ada):</label><input type="text" id="lokasi_kejadian_idx" name="lokasi_kejadian"></div>
                    <div class="form-group"><label for="lampiran_idx">Lampiran (Opsional, Max 2MB):</label><input type="file" id="lampiran_idx" name="lampiran"><small>JPG, PNG, PDF.</small></div>
                    <button type="submit" name="submit_pengaduan" class="btn btn-submit-public">Kirim Pengaduan/Masukan</button>
                </form>
             </div>
        </div>
        </div> 

    <div id="antrianPopupModal" class="modal">
       <div class="modal-content">
            <span class="modal-close-btn">&times;</span>
            <h3><i class="fas fa-check-circle"></i> Data Berhasil Disimpan!</h3>
            <p>Nomor Antrian Tamu Hari Ini:</p>
            <div id="antrianPopupNumber" class="antrian-number">--</div>
            <div id="antrianPopupDetail" class="detail-tamu">
                 <p><strong>Nama:</strong> <span id="popupNama">-</span></p>
                 <p><strong>Alamat:</strong> <span id="popupAlamat">-</span></p>
                 <p><strong>No. Telp:</strong> <span id="popupTelp">-</span></p>
            </div>
            <button type="button" class="btn btn-utama antrianPopup-close-btn" style="margin-top: 20px;">OK</button>
       </div>
   </div>

    <div id="qrScanConfirmKeperluanModal" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn">&times;</span>
            <h3><i class="fas fa-user-check"></i> Konfirmasi Data & Keperluan</h3>
            <hr style="margin: 15px 0;">
            <p>Data Anda terdeteksi. Mohon lengkapi keperluan kunjungan:</p>
            <div id="qrScanConfirmDataDisplay" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 6px; border: 1px solid #eee;">
                <p><strong>Nama:</strong> <span id="qrScanConfirmNama">-</span></p>
                <p><strong>Alamat:</strong> <span id="qrScanConfirmAlamat">-</span></p>
                <p><strong>Pekerjaan:</strong> <span id="qrScanConfirmPekerjaan">-</span></p>
                <p><strong>No. Telp:</strong> <span id="qrScanConfirmTelp">-</span></p>
            </div>
            <form id="qrScanConfirmKeperluanForm">
                <input type="hidden" id="qrScanConfirmGuestId" name="guestId">
                <div class="form-group">
                    <label for="qrScan_keperluan">Keperluan Kunjungan Anda:</label>
                    <select id="qrScan_keperluan" name="keperluan" required>
                        <option value="">-- Pilih Keperluan --</option>
                        <?php foreach ($opsi_keperluan as $value_opt => $text_opt): ?>
                            <option value="<?php echo htmlspecialchars($value_opt); ?>"><?php echo htmlspecialchars($text_opt); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="text-align: right; margin-top:20px;">
                    <button type="button" class="btn btn-secondary qrScanConfirm-close-btn">Batal</button> 
                    <button type="submit" class="btn btn-success">Lanjut & Dapatkan No Antrian</button>
                </div>
            </form>
            <div id="qrScanConfirmModalPesan" class="pesan" style="display:none; margin-top:15px;"></div>
        </div>
    </div>

    <div id="ratingAfterCheckinModal" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn">&times;</span>
            <h3><i class="fas fa-star"></i> Berikan Rating Pelayanan Kami</h3>
            <hr style="margin: 15px 0;">
            <div id="ratingGuestInfo" style="margin-bottom:15px; font-size:1em; color:#555;">
                 <p>Terima kasih atas kunjungan Anda, <strong id="ratingGuestName">-</strong>!</p>
            </div>
            <form id="ratingAfterCheckinForm">
                <input type="hidden" id="ratingGuestId" name="guestId">
                <div class="form-group">
                    <label style="text-align:center; margin-bottom:10px;">Seberapa puaskah Anda dengan pelayanan kami hari ini?</label>
                    <div class="rating-input" style="justify-content:center;"> 
                        <input type="radio" id="star5_rating_public" name="rating_pelayanan" value="5" required><label for="star5_rating_public"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4_rating_public" name="rating_pelayanan" value="4"><label for="star4_rating_public"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3_rating_public" name="rating_pelayanan" value="3"><label for="star3_rating_public"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2_rating_public" name="rating_pelayanan" value="2"><label for="star2_rating_public"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1_rating_public" name="rating_pelayanan" value="1"><label for="star1_rating_public"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                 <div style="text-align: center; margin-top:20px;">
                    <button type="submit" class="btn btn-success btn-full-width">Kirim Rating</button>
                    <button type="button" class="btn btn-secondary btn-full-width rating-skip-btn" style="margin-top:10px; background-color:#aaa;">Lewati</button>
                </div>
            </form>
            <div id="ratingAfterCheckinModalPesan" class="pesan" style="display:none; margin-top:15px;"></div>
        </div>
    </div>

    <script>
        function htmlspecialchars(str) { const map = {'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'}; if (typeof str !== 'string') return ''; return str.replace(/[&<>"']/g, m => map[m]); }
        
        let currentGuestIdForRating = null; 

        function showAntrianPopup(data) { 
            const popup = document.getElementById('antrianPopupModal');
            if (!popup || !data) { console.error("Popup modal atau data antrian tidak ditemukan", data); return; }
            
            const noAntrianEl = document.getElementById('antrianPopupNumber');
            const namaEl = document.getElementById('popupNama');
            const alamatEl = document.getElementById('popupAlamat');
            const telpEl = document.getElementById('popupTelp');

            if(noAntrianEl) noAntrianEl.textContent = data.no_antrian || '-';
            if(namaEl) namaEl.textContent = htmlspecialchars(data.nama || '-');
            if(alamatEl) alamatEl.textContent = htmlspecialchars(data.alamat || '-');
            if(telpEl) telpEl.textContent = htmlspecialchars(data.no_telp || '-');
            
            popup.style.display = 'block';
            
            const closeBtns = popup.querySelectorAll('.modal-close-btn, .antrianPopup-close-btn');
            let antrianClickOutsideHandler = function(event) { 
                 if (event.target == popup) closeAntrianAndShowRating(); // Panggil fungsi gabungan
            };

            function closeAntrianAndShowRating() {
                popup.style.display = 'none';
                window.removeEventListener('click', antrianClickOutsideHandler);
                // Panggil modal rating setelah popup antrian ditutup
                if (currentGuestIdForRating && data.nama) { 
                    showRatingAfterCheckinModal(currentGuestIdForRating, data.nama);
                } else {
                    console.log("Tidak ada guest ID atau nama untuk rating.");
                    currentGuestIdForRating = null; // Reset ID jika tidak ada data
                }
            }

            closeBtns.forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn); 
                newBtn.addEventListener('click', closeAntrianAndShowRating);
            });
            
            window.removeEventListener('click', antrianClickOutsideHandler); 
            window.addEventListener('click', antrianClickOutsideHandler);
        }

        function showRatingAfterCheckinModal(guestId, guestName) {
            const modal = document.getElementById('ratingAfterCheckinModal');
            if (!modal) return;
            const guestIdInput = document.getElementById('ratingGuestId');
            const guestNameSpan = document.getElementById('ratingGuestName');
            const ratingForm = document.getElementById('ratingAfterCheckinForm');
            const ratingPesan = document.getElementById('ratingAfterCheckinModalPesan');

            if(guestIdInput) guestIdInput.value = guestId;
            if(guestNameSpan) guestNameSpan.textContent = htmlspecialchars(guestName);
            
            if(ratingForm) ratingForm.reset(); // Reset pilihan rating sebelumnya
            if(ratingPesan) ratingPesan.style.display = 'none';

            modal.style.display = 'block';

            const closeBtns = modal.querySelectorAll('.modal-close-btn, .rating-skip-btn'); // Tombol lewati juga menutup
             let ratingClickOutsideHandler = function(event) {
                 if (event.target == modal) {
                    closeRatingModal();
                 }
            };
            
            function closeRatingModal() {
                 modal.style.display = 'none';
                 window.removeEventListener('click', ratingClickOutsideHandler);
                 currentGuestIdForRating = null; // Reset ID setelah selesai
            }

            closeBtns.forEach(btn => {
                const newBtn = btn.cloneNode(true);
                btn.parentNode.replaceChild(newBtn, btn);
                newBtn.addEventListener('click', closeRatingModal);
            });
            
            window.removeEventListener('click', ratingClickOutsideHandler); // Hapus listener lama
            window.addEventListener('click', ratingClickOutsideHandler); // Tambah listener baru
        }

        function processScanResultPublic(decodedText) { 
            const resultContainer = document.getElementById('qr-reader-results-public'); 
            const confirmModal = document.getElementById('qrScanConfirmKeperluanModal');
            const confirmPesan = document.getElementById('qrScanConfirmModalPesan');
            
            if (!resultContainer || !confirmModal) { console.error("Elemen scan/modal konfirmasi QR tidak ditemukan."); return; }
            
            resultContainer.innerHTML = '<p class="pesan warning"><i>Mencocokkan data QR...</i></p>'; 
            let guestId = null; 
            const lines = decodedText.split('\\n'); 
            for (const line of lines) { 
                const trimmedLine = line.trim(); 
                if (trimmedLine.toLowerCase().startsWith('id tamu:')) { 
                    guestId = parseInt(trimmedLine.split(':')[1].trim(), 10); break; 
                } 
            } 
            
            if (guestId && !isNaN(guestId)) { 
                currentGuestIdForRating = guestId; // Simpan ID untuk rating nanti
                
                fetch('proses_scan_public.php', { 
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify({ guestId: guestId }) 
                })
                .then(response => response.json()) // Asumsi backend selalu kirim JSON
                .then(data => { 
                    if (data.success && data.tamuData) {
                        resultContainer.innerHTML = ''; 
                        document.getElementById('qrScanConfirmGuestId').value = data.tamuData.id || '';
                        document.getElementById('qrScanConfirmNama').textContent = htmlspecialchars(data.tamuData.nama || '-');
                        document.getElementById('qrScanConfirmAlamat').textContent = htmlspecialchars(data.tamuData.alamat || '-');
                        document.getElementById('qrScanConfirmPekerjaan').textContent = htmlspecialchars(data.tamuData.pekerjaan || '-');
                        document.getElementById('qrScanConfirmTelp').textContent = htmlspecialchars(data.tamuData.no_telp || '-');
                        document.getElementById('qrScan_keperluan').value = ''; 
                        if(confirmPesan) confirmPesan.style.display = 'none'; 
                        confirmModal.style.display = 'block'; 
                    } else {
                        resultContainer.innerHTML = `<p class="pesan error">${htmlspecialchars(data.message || 'Gagal memproses data scan.')}</p>`;
                         currentGuestIdForRating = null; // Reset jika gagal
                    }
                })
                .catch(error => { 
                    console.error('Error during initial scan fetch:', error); 
                    resultContainer.innerHTML = `<p class="pesan error">Error: Tidak dapat memproses scan (${htmlspecialchars(error.message)}).</p>`; 
                    currentGuestIdForRating = null; // Reset jika gagal
                }); 
            } else { 
                resultContainer.innerHTML = `<p class="pesan warning">Format QR Code tidak dikenali.</p>`; 
                currentGuestIdForRating = null;
            } 
        }

        document.addEventListener('DOMContentLoaded', function() {
            const actionChooser = document.getElementById('actionChooser');
            const formSections = document.querySelectorAll('.form-section-wrapper');
            const actionCards = document.querySelectorAll('.action-card');
            const backButtons = document.querySelectorAll('.btn-back-to-chooser');
            window.html5QrcodeScannerInstancePublic = null; // Jadikan global scope window

            function showSection(targetId) {
                if (actionChooser) actionChooser.style.display = 'none';
                formSections.forEach(section => { section.style.display = 'none'; section.classList.remove('active-section'); });
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.style.display = 'block'; targetSection.classList.add('active-section');
                    if (targetId === 'scanQRSection') { initializeQrScanner(); }
                     else { stopQrScanner(); } // Stop scanner jika section lain yg aktif
                } else {
                     showActionChooser(); // Kembali ke pilihan jika target tidak ada
                }
            }

            function showActionChooser() {
                formSections.forEach(section => { section.style.display = 'none'; section.classList.remove('active-section'); });
                if (actionChooser) actionChooser.style.display = 'flex'; 
                stopQrScanner(); // Stop scanner saat kembali ke pilihan
            }

             function stopQrScanner() {
                  if (window.html5QrcodeScannerInstancePublic && typeof window.html5QrcodeScannerInstancePublic.getState === 'function' && window.html5QrcodeScannerInstancePublic.getState() === 2) { // STATE_SCANNING = 2
                      window.html5QrcodeScannerInstancePublic.clear()
                          .then(_ => { console.log("QR Scanner stopped/cleared."); })
                          .catch(err => { /* Abaikan error jika sudah di-clear */ });
                      // Jangan set null dulu, mungkin bisa dirender ulang
                  }
             }

            actionCards.forEach(card => { card.addEventListener('click', function() { showSection(this.getAttribute('data-target')); }); });
            backButtons.forEach(button => { button.addEventListener('click', showActionChooser); });

            const qrReaderElement = document.getElementById('qr-reader-public');
            function initializeQrScanner() {
                if (qrReaderElement && (!window.html5QrcodeScannerInstancePublic || (typeof window.html5QrcodeScannerInstancePublic.getState === 'function' && window.html5QrcodeScannerInstancePublic.getState() !== 2))) { 
                    function onScanSuccessPublic(decodedText, decodedResult) { 
                         if (window.html5QrcodeScannerInstancePublic) { 
                            stopQrScanner(); // Panggil fungsi stop
                         } 
                         processScanResultPublic(decodedText); 
                     }
                    function onScanFailurePublic(error) { /* Abaikan */ }
                    if (location.protocol !== 'https:') { qrReaderElement.innerHTML = "<p class='pesan error' style='text-align:center;'>Akses kamera butuh HTTPS.</p>"; } 
                    else { 
                        try { 
                            window.html5QrcodeScannerInstancePublic = new Html5QrcodeScanner("qr-reader-public", { fps: 5, qrbox: { width: 220, height: 220 }, rememberLastUsedCamera: false, supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA] }, false ); 
                            window.html5QrcodeScannerInstancePublic.render(onScanSuccessPublic, onScanFailurePublic); 
                            console.log("Public QR Scanner rendered.");
                        } catch (e) { console.error("Public Scanner init err:", e); qrReaderElement.innerHTML = "<p class='pesan error' style='text-align:center;'>Gagal memulai QR Scanner.</p>"; } 
                    }
                }
            }
            
            // Handler untuk submit Modal Konfirmasi Keperluan
            const qrScanConfirmForm = document.getElementById('qrScanConfirmKeperluanForm');
            const qrScanConfirmPesanEl = document.getElementById('qrScanConfirmModalPesan');
            if (qrScanConfirmForm && qrScanConfirmPesanEl) {
                qrScanConfirmForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    qrScanConfirmPesanEl.textContent = 'Memproses...'; qrScanConfirmPesanEl.className = 'pesan warning'; qrScanConfirmPesanEl.style.display = 'block';
                    const submitButton = qrScanConfirmForm.querySelector('button[type="submit"]'); if(submitButton) submitButton.disabled = true;
                    const formData = new FormData(qrScanConfirmForm); 
                    fetch('proses_final_checkin.php', { method: 'POST', body: formData }) 
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.tamuPopupData) {
                            document.getElementById('qrScanConfirmKeperluanModal').style.display = 'none'; 
                            showAntrianPopup(data.tamuPopupData); // Tampilkan popup antrian
                             const resultContainer = document.getElementById('qr-reader-results-public');
                            if(resultContainer) resultContainer.innerHTML = `<p class="pesan sukses">Check-in & Keperluan dikonfirmasi.</p>`;
                        } else {
                            qrScanConfirmPesanEl.textContent = data.message || 'Gagal menyimpan.'; qrScanConfirmPesanEl.className = 'pesan error'; qrScanConfirmPesanEl.style.display = 'block'; 
                        }
                    })
                    .catch(error => { qrScanConfirmPesanEl.textContent = 'Error: ' + error.message; qrScanConfirmPesanEl.className = 'pesan error'; qrScanConfirmPesanEl.style.display = 'block'; })
                    .finally(() => { if(submitButton) submitButton.disabled = false; });
                });
            }

            // Handler untuk submit Modal Rating
            const ratingForm = document.getElementById('ratingAfterCheckinForm');
            const ratingPesanEl = document.getElementById('ratingAfterCheckinModalPesan');
            if (ratingForm && ratingPesanEl) {
                ratingForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    ratingPesanEl.textContent = 'Menyimpan rating...'; ratingPesanEl.className = 'pesan warning'; ratingPesanEl.style.display = 'block';
                    const submitBtn = ratingForm.querySelector('button[type="submit"]'); if(submitBtn) submitBtn.disabled = true;
                    const formData = new FormData(ratingForm);
                    
                    fetch('proses_submit_rating_public.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        ratingPesanEl.textContent = data.message || 'Operasi selesai.';
                        ratingPesanEl.className = 'pesan ' + (data.success ? 'sukses' : 'error');
                         ratingPesanEl.style.display = 'block';
                        if (data.success) {
                            setTimeout(() => {
                                document.getElementById('ratingAfterCheckinModal').style.display = 'none';
                                alert("Terima kasih atas penilaian Anda!"); 
                                showActionChooser(); // Kembali ke pilihan utama setelah rating
                            }, 1500); // Tunda sedikit sebelum menutup
                        }
                    })
                    .catch(error => { ratingPesanEl.textContent = 'Gagal: ' + error.message; ratingPesanEl.className = 'pesan error'; ratingPesanEl.style.display = 'block';})
                    .finally(() => { if(submitBtn) submitBtn.disabled = false; });
                });
            }
            
            // Trigger popup antrian dari session (jika ada)
            const dataPopupManual = <?php echo isset($trigger_popup_data) ? json_encode($trigger_popup_data) : 'null'; ?>;
            if (dataPopupManual) {
                 console.log("Trigger popup manual from session:", dataPopupManual);
                 currentGuestIdForRating = dataPopupManual.id_tamu || null; // Simpan ID jika perlu rating setelah manual
                 showAntrianPopup(dataPopupManual);
                 // Perlu cara untuk mendapatkan ID tamu di sini jika mau rating,
                 // Mungkin proses_tamu_manual_public.php perlu menyimpannya juga di session popup data
                 // Contoh: $_SESSION['popup_data_manual']['id_tamu'] = $last_id;
            }

            // Handle direct hash links
            if(window.location.hash) {
                const targetSectionId = window.location.hash.substring(1); 
                let sectionIdToShow = '';
                if(targetSectionId === 'input-manual') sectionIdToShow = 'manualFormSection';
                else if(targetSectionId === 'scan-qr') sectionIdToShow = 'scanQRSection';
                else if(targetSectionId === 'input-pengaduan') sectionIdToShow = 'complaintFormSection';
                if(sectionIdToShow) showSection(sectionIdToShow);
            } else {
                 if (actionChooser) actionChooser.style.display = 'flex'; // Tampilkan chooser jika tidak ada hash
            }

        }); 
    </script>
</body> 
</html>