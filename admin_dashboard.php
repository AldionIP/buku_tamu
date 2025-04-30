<?php
session_start();

// 1. Cek apakah petugas (admin) sudah login
if (!isset($_SESSION['id_petugas'])) {
    $_SESSION['login_error'] = "Anda harus login terlebih dahulu.";
    header('Location: login.php');
    exit();
}

// 2. Sertakan file koneksi database
require_once 'koneksi.php'; // Pastikan path ini benar

// 3. Tentukan halaman aktif, default 'lihat_tamu'
$page = isset($_GET['page']) ? $_GET['page'] : 'lihat_tamu';

// 4. Inisialisasi variabel
$pesan_simpan = ''; 
$pesan_simpan_type = '';
$qrDataStringForJs = null; 
$pesan_error_global = '';
$last_id = null; 
$nama_tersimpan = null;
$query_tamu = null; 
$rata_rata_rating = 'N/A'; 
$query_ratings_list = null; 
$query_chart_data = null;
$rating_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]; 
$total_ratings = 0;
$chart_labels = []; $chart_data = []; $chart_percentages = [];
$chart_bg_colors = ['rgba(220, 53, 69, 0.7)', 'rgba(255, 193, 7, 0.7)', 'rgba(111, 66, 193, 0.7)', 'rgba(0, 123, 255, 0.7)', 'rgba(40, 167, 69, 0.7)'];
$chart_border_colors = ['rgba(220, 53, 69, 1)', 'rgba(255, 193, 7, 1)', 'rgba(111, 66, 193, 1)', 'rgba(0, 123, 255, 1)', 'rgba(40, 167, 69, 1)'];
$query_pengaduan = null;

// 5. Logika pengambilan data / pemrosesan POST (jika ada)
if (!$koneksi) {
    $pesan_error_global = "Koneksi database gagal.";
} else {
    // Ambil data jika halaman 'lihat_tamu'
    if ($page == 'lihat_tamu' || $page == '') {
        $sql_tamu = "SELECT id, nama, alamat, keperluan, no_telp, pesan, rating, waktu_input, status_kehadiran, waktu_scan_masuk FROM tamu ORDER BY waktu_input DESC";
        $query_tamu = mysqli_query($koneksi, $sql_tamu);
        if (!$query_tamu) { $pesan_error_global = "Gagal ambil data tamu: " . mysqli_error($koneksi); }
        $sql_avg_rating = "SELECT AVG(rating) as rata_rata FROM tamu WHERE rating > 0 AND rating <= 5";
        $query_avg = mysqli_query($koneksi, $sql_avg_rating);
        if ($query_avg) { $avg_rating_data = mysqli_fetch_assoc($query_avg); $rata_rata_rating = ($avg_rating_data && $avg_rating_data['rata_rata'] !== null) ? round($avg_rating_data['rata_rata'], 1) : 'N/A'; }
        else { $pesan_error_global .= " Gagal ambil rata-rata rating."; }
    } 
    // Ambil data jika halaman 'rating'
    elseif ($page == 'rating') {
        $sql_ratings = "SELECT nama, rating FROM tamu WHERE rating > 0 AND rating <= 5 ORDER BY waktu_input DESC";
        $query_ratings_list = mysqli_query($koneksi, $sql_ratings);
        if (!$query_ratings_list) { $pesan_error_global = "Gagal ambil daftar rating: " . mysqli_error($koneksi); }
        $sql_chart = "SELECT rating, COUNT(*) as jumlah FROM tamu WHERE rating > 0 AND rating <= 5 GROUP BY rating ORDER BY rating ASC";
        $query_chart_data = mysqli_query($koneksi, $sql_chart);
        if ($query_chart_data) {
            while ($row = mysqli_fetch_assoc($query_chart_data)) {
                if (isset($rating_counts[$row['rating']])) { $rating_counts[$row['rating']] = (int)$row['jumlah']; $total_ratings += (int)$row['jumlah']; }
            }
        } else { $pesan_error_global = "Gagal ambil data chart: " . mysqli_error($koneksi); }
        for ($i = 1; $i <= 5; $i++) {
            $chart_labels[] = $i . " Bintang"; $count = $rating_counts[$i]; $chart_data[] = $count;
            $percentage = ($total_ratings > 0) ? round(($count / $total_ratings) * 100, 1) : 0; $chart_percentages[] = $percentage;
        }
    }
    // Logika simpan untuk 'input_manual'
    elseif ($page == 'input_manual' && isset($_POST['submit_manual'])) {
        $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama_manual']));
        $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat_manual']));
        $keperluan = mysqli_real_escape_string($koneksi, trim($_POST['keperluan_manual']));
        $no_telp = mysqli_real_escape_string($koneksi, trim($_POST['no_telp_manual']));
        $rating = isset($_POST['rating_manual']) ? intval($_POST['rating_manual']) : NULL;
        $waktu_sekarang = date('Y-m-d H:i:s');
        if (empty($nama)) { $pesan_simpan = "Nama wajib diisi!"; $pesan_simpan_type = "error"; } 
        elseif ($rating !== NULL && ($rating < 1 || $rating > 5)) { $pesan_simpan = "Rating tidak valid!"; $pesan_simpan_type = "error"; } 
        else {
            $sql_insert_manual = "INSERT INTO tamu (nama, alamat, keperluan, no_telp, rating, waktu_input) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_manual = mysqli_prepare($koneksi, $sql_insert_manual);
            if ($stmt_manual) {
                mysqli_stmt_bind_param($stmt_manual, "ssssis", $nama, $alamat, $keperluan, $no_telp, $rating, $waktu_sekarang);
                if (mysqli_stmt_execute($stmt_manual)) {
                    $pesan_simpan = "Data tamu '$nama' berhasil disimpan!"; $pesan_simpan_type = "sukses"; 
                    $last_id = mysqli_insert_id($koneksi); $nama_tersimpan = $nama; 
                    $qrDataStringForJs = "ID Tamu: " . $last_id . "\nNama: " . $nama . "\nWaktu: " . date('d/m/Y H:i', strtotime($waktu_sekarang)) . "\n"; if (!empty($keperluan)) $qrDataStringForJs .= "Keperluan: " . $keperluan;
                } else { $pesan_simpan = "Gagal simpan: " . htmlspecialchars(mysqli_stmt_error($stmt_manual)); $pesan_simpan_type = "error"; }
                mysqli_stmt_close($stmt_manual);
            } else { $pesan_simpan = "Gagal prepare query: " . htmlspecialchars(mysqli_error($koneksi)); $pesan_simpan_type = "error"; }
        }
    }
    // Ambil data jika halaman 'lihat_pengaduan'
    elseif ($page == 'lihat_pengaduan') {
        $sql_pengaduan = "SELECT p.id_pengaduan, p.waktu_lapor, p.judul_pengaduan, p.nama_pelapor, p.kategori_pengaduan, p.status_pengaduan, pt.nama_lengkap as nama_pencatat 
                         FROM pengaduan p LEFT JOIN petugas pt ON p.id_petugas_pencatat = pt.id_petugas ORDER BY p.waktu_lapor DESC"; 
        $query_pengaduan = mysqli_query($koneksi, $sql_pengaduan);
        if (!$query_pengaduan) { $pesan_error_global = "Gagal ambil data pengaduan: " . mysqli_error($koneksi); }
    } 
} 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script> 
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    
    <style>
        /* === Pindahkan Style ini ke style.css jika memungkinkan === */

        /* Style Dasar & Variabel (contoh) */
        :root {
            --sidebar-bg: #343a40;
            --sidebar-text-color: #f8f9fa;
            --sidebar-active-bg: #495057;
            --header-bg: #fff;
            --header-text-color: #333;
            --content-bg: #f4f6f9;
            --border-color: #dee2e6;
            --star-color: #ffc107; 
        }
        body.admin-body { margin: 0; font-family: sans-serif; background-color: var(--content-bg); }
        .admin-wrapper { display: flex; min-height: 100vh; }
        .pesan { padding: 10px 15px; margin-bottom: 15px; border-radius: 4px; border: 1px solid transparent;}
        .pesan.sukses { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .pesan.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .pesan.warning { background-color: #fff3cd; border-color: #ffeeba; color: #856404; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"], .form-group input[type="password"], .form-group input[type="email"], .form-group input[type="datetime-local"], .form-group textarea, .form-group select { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group textarea { resize: vertical; }
        .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; text-align: center;}
        .btn-utama { background-color: #007bff; color: white; } .btn-utama:hover { background-color: #0056b3; }
        .btn-secondary { background-color: #6c757d; color: white; } .btn-secondary:hover { background-color: #5a6268; }
        .btn-success { background-color: #28a745; color: white; } .btn-success:hover { background-color: #218838; }
        .btn-danger, .btn-hapus { background-color: #dc3545; color: white; } .btn-danger:hover, .btn-hapus:hover { background-color: #c82333; }
        .btn-warning { background-color: #ffc107; color: #212529; } .btn-warning:hover { background-color: #e0a800; }
        .btn-sm { padding: 4px 8px; font-size: 0.8rem; }
        .table-responsive { overflow-x: auto; margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; background-color: #fff; }
        th, td { border: 1px solid var(--border-color); padding: 8px 10px; text-align: left; vertical-align: middle;}
        th { background-color: #e9ecef; font-weight: bold; }
        tbody tr:nth-child(odd) { background-color: #f8f9fa; }
        .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; } 
        .rating-input input[type="radio"] { display: none; }
        .rating-input label { color: #ddd; font-size: 1.8em; cursor: pointer; padding: 0 0.1em; }
        .rating-input input[type="radio"]:checked ~ label, .rating-input label:hover, .rating-input label:hover ~ label { color: var(--star-color); }
        
        /* Style Sidebar */
        .admin-sidebar { width: 240px; flex-shrink: 0; background-color: var(--sidebar-bg, #343a40); color: var(--sidebar-text-color, #f8f9fa); height: 100vh; position: sticky; top: 0; transition: margin-left 0.3s ease-in-out, left 0.3s ease-in-out; }
        .sidebar-header { padding: 15px; text-align: center; border-bottom: 1px solid #4b545c; }
        .sidebar-header h3 { margin: 0; color: #fff;}
        .sidebar-nav ul { list-style: none; padding: 0; margin: 0; }
        .sidebar-nav li a { display: flex; align-items: center; padding: 12px 15px; color: var(--sidebar-text-color, #c2c7d0); text-decoration: none; transition: background-color 0.2s ease, color 0.2s ease; }
        .sidebar-nav li a i { margin-right: 10px; width: 20px; text-align: center; }
        .sidebar-nav li a:hover { background-color: var(--sidebar-active-bg, #495057); color: #fff; }
        .sidebar-nav li.active > a { background-color: #007bff; color: #fff; font-weight: bold; }

        /* Style Konten Utama */
        .admin-content { flex-grow: 1; height: 100vh; overflow-y: auto; }
        .admin-content-header { display: flex; align-items: center; padding: 15px 20px; background-color: var(--header-bg, #fff); border-bottom: 1px solid var(--border-color, #dee2e6); position: sticky; top: 0; z-index: 1000; /* Header tetap terlihat saat scroll */ }
        .admin-content-header h1 { margin: 0; flex-grow: 1; font-size: 1.5rem;}
        .admin-info { margin-left: auto; font-size: 0.9rem; color: #555;}
        .content-area { padding: 20px; }

        /* Style Tombol Toggle & Overlay Responsif */
        .sidebar-toggle-button { display: none; background: none; border: none; font-size: 1.5rem; color: var(--header-text-color, #333); cursor: pointer; padding: 0 10px; margin-right: 15px; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 1040; }
        
        /* Style Modal */
        .modal { display: none; position: fixed; z-index: 1001; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); padding-top: 50px; }
        .modal-content { background-color: #fefefe; margin: 5% auto; padding: 25px; border: 1px solid #aaa; width: 90%; max-width: 550px; text-align: center; border-radius: 8px; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .modal-content h3 { margin-top: 0; margin-bottom: 15px; }
        .modal-close-btn { color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1; }
        .modal-close-btn:hover, .modal-close-btn:focus { color: black; text-decoration: none; }
        .modal-qr-display #modal-qrcode-container { margin: 15px auto; background: white; display: inline-block; padding: 5px; border: 1px solid #ccc; }
        #editModal .modal-content, #detailPengaduanModal .modal-content { text-align: left;} 
        #editModal .form-container, #detailPengaduanModal #formTindakLanjut { margin-top: 0; padding-top: 10px; border: none; box-shadow: none;}
        #editPesan, #tindakLanjutPesan { margin-bottom: 15px; margin-top: 10px;} 
        #editModal .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-end; } 
        #editModal .rating-input input[type="radio"] { display: none; }
        #editModal .rating-input label { color: #ddd; font-size: 1.8em; cursor: pointer; padding: 0 0.1em; }
        #editModal .rating-input input[type="radio"]:checked ~ label, #editModal .rating-input label:hover, #editModal .rating-input label:hover ~ label { color: var(--star-color); }
        #detailPengaduanContent p { margin-bottom: 8px; line-height: 1.5;}
        #detailPengaduanContent p strong { display: inline-block; min-width: 120px; font-weight: bold;}
        
        /* Style Status Pengaduan */
        .status-baru { padding: 3px 6px; background-color: #ffc107; color: #333; border-radius: 4px; font-size: 0.85em; display: inline-block;}
        .status-diproses { padding: 3px 6px; background-color: #0dcaf0; color: #fff; border-radius: 4px; font-size: 0.85em; display: inline-block;}
        .status-selesai { padding: 3px 6px; background-color: #198754; color: #fff; border-radius: 4px; font-size: 0.85em; display: inline-block;}
        .status-ditolak { padding: 3px 6px; background-color: #dc3545; color: #fff; border-radius: 4px; font-size: 0.85em; display: inline-block;}

        /* Style Lainnya */
        .status-hadir { color: green; font-weight: bold; }
        .status-belum { color: grey; }
        .table-responsive th:last-child, .table-responsive td:last-child { width: auto; white-space: nowrap; text-align: center;} 
        .rating-stars i, .rating-stars-small i { color: var(--star-color); }
        .rating-stars-small i { font-size: 0.9em; }
        .rating-summary { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; align-items: flex-start;}
        .rating-chart-container { flex: 1 1 400px; min-width: 300px; position: relative; height:40vh; width:80vw; max-width:600px;}
        .rating-list-container { flex: 1 1 300px; max-height: 40vh; overflow-y: auto;}
        .rating-table-scroll table { width: 100%;}
        #qr-reader { width: 100%; max-width: 400px; margin: 15px auto; border: 1px solid #ccc; }
        #qr-reader-results { margin-top: 10px; font-weight: bold; }
        .qr-code-display #qrcode-container { margin-bottom: 15px; background: white; display: inline-block; padding: 5px; border: 1px solid #ccc; }

        /* Media Query untuk Responsif (Layar Kecil) */
        @media (max-width: 768px) {
            .admin-sidebar { position: fixed; left: -250px; /* Sembunyi di kiri */ top: 0; bottom: 0; z-index: 1050; transition: left 0.3s ease-in-out; overflow-y: auto; width: 240px; /* Pastikan lebar tetap */}
            .admin-sidebar.open { left: 0; box-shadow: 0 0 15px rgba(0,0,0,0.2); }
            .sidebar-toggle-button { display: block; /* Tampilkan tombol */ }
            .admin-sidebar.open + .sidebar-overlay { display: block; } /* Tampilkan overlay saat open */
            .sidebar-nav .menu-text { /* Pastikan teks terlihat saat sidebar muncul */ display: inline-block; }
            .admin-content { margin-left: 0; /* Konten mengisi penuh saat sidebar hilang */}
            .admin-content-header h1 { font-size: 1.2rem; /* Kecilkan judul di mobile */ }
        }

    </style>
</head>
<body class="admin-body">

    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header"><h3>Menu Admin</h3></div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?php echo ($page == 'lihat_tamu' || $page == '') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=lihat_tamu"><i class="fas fa-list-alt"></i> <span class="menu-text">Lihat Tamu</span></a></li>
                    <li class="<?php echo ($page == 'input_manual') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=input_manual"><i class="fas fa-edit"></i> <span class="menu-text">Input Tamu Manual</span></a></li>
                    <li class="<?php echo ($page == 'rating') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=rating"><i class="fas fa-star-half-alt"></i> <span class="menu-text">Rating Pelayanan</span></a></li>
                    <li class="<?php echo ($page == 'scan_qr') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=scan_qr"><i class="fas fa-qrcode"></i> <span class="menu-text">Scan QR Tamu</span></a></li>
                    <li class="<?php echo ($page == 'pengaduan') ? 'active' : ''; ?>"> <a href="admin_dashboard.php?page=pengaduan"><i class="fas fa-exclamation-triangle"></i> <span class="menu-text">Input Pengaduan</span></a></li>
                    <li class="<?php echo ($page == 'lihat_pengaduan') ? 'active' : ''; ?>"> <a href="admin_dashboard.php?page=lihat_pengaduan"><i class="fas fa-list-check"></i> <span class="menu-text">Lihat Pengaduan</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span class="menu-text">Logout</span></a></li>
                </ul>
            </nav>
        </aside>

        <div class="sidebar-overlay"></div>

        <main class="admin-content">
            <header class="admin-content-header">
                <button id="sidebarToggleBtn" class="sidebar-toggle-button"><i class="fas fa-bars"></i></button>
                <h1>Dasbor Admin</h1> 
                <div class="admin-info"><span><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username']); ?></span></div>
            </header>

            <div class="content-area">
                <?php
                    if (!empty($pesan_error_global)) { echo '<p class="pesan error">' . htmlspecialchars($pesan_error_global) . '</p>'; }

                    switch ($page) {
                        
                        // --- HALAMAN INPUT MANUAL ---
                        case 'input_manual':
                            ?>
                            <h2><i class="fas fa-edit"></i> Input Data Tamu Manual</h2>
                            <p>Gunakan form ini untuk memasukkan data tamu secara manual oleh petugas.</p>
                            <?php if (!empty($pesan_simpan)) { echo '<p class="pesan ' . htmlspecialchars($pesan_simpan_type) . '">' . htmlspecialchars($pesan_simpan) . '</p>'; } ?>
                            <?php if ($pesan_simpan_type == 'sukses' && isset($last_id) && isset($qrDataStringForJs) && isset($nama_tersimpan)) { 
                                $nama_tamu_clean = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nama_tersimpan);
                                $nama_file_download = 'QRCode_Tamu_' . $last_id . '_' . $nama_tamu_clean . '.png';
                            ?>
                                <h3>QR Code Tamu (ID: <?php echo $last_id; ?>):</h3>
                                <div class="qr-code-display">
                                    <div id="qrcode-container"></div>
                                    <input type="hidden" id="qrDataInput" value="<?php echo htmlspecialchars($qrDataStringForJs); ?>">
                                    <p><small>QR Code ini berisi detail tamu.</small></p>
                                    <button id="downloadQrBtn" class="btn btn-secondary" style="margin-top: 10px;" data-filename="<?php echo htmlspecialchars($nama_file_download); ?>"><i class="fas fa-download"></i> Download QR Code</button>
                                </div>
                            <?php } ?>
                            <div class="form-container admin-form" <?php if ($pesan_simpan_type == 'sukses') echo 'style="margin-top: 30px;"';?>>
                                <form action="admin_dashboard.php?page=input_manual" method="post">
                                    <div class="form-group"><label for="nama_manual">Nama Tamu:</label><input type="text" id="nama_manual" name="nama_manual" required></div>
                                    <div class="form-group"><label for="alamat_manual">Alamat:</label><textarea id="alamat_manual" name="alamat_manual" rows="3"></textarea></div>
                                    <div class="form-group"><label for="keperluan_manual">Keperluan:</label><textarea id="keperluan_manual" name="keperluan_manual" rows="3"></textarea></div>
                                    <div class="form-group"><label for="no_telp_manual">No. Telepon:</label><input type="text" id="no_telp_manual" name="no_telp_manual"></div>
                                    <div class="form-group"><label>Rating Kepuasan (Opsional):</label><div class="rating-input"><input type="radio" id="star5_manual" name="rating_manual" value="5"><label for="star5_manual" title="Sangat Puas"><i class="fas fa-star"></i></label><input type="radio" id="star4_manual" name="rating_manual" value="4"><label for="star4_manual" title="Puas"><i class="fas fa-star"></i></label><input type="radio" id="star3_manual" name="rating_manual" value="3"><label for="star3_manual" title="Cukup Puas"><i class="fas fa-star"></i></label><input type="radio" id="star2_manual" name="rating_manual" value="2"><label for="star2_manual" title="Kurang Puas"><i class="fas fa-star"></i></label><input type="radio" id="star1_manual" name="rating_manual" value="1"><label for="star1_manual" title="Tidak Puas"><i class="fas fa-star"></i></label></div></div>
                                    <button type="submit" name="submit_manual" class="btn btn-utama">Simpan Data Tamu</button>
                                </form>
                            </div>
                            <?php
                            break; 

                        // --- HALAMAN RATING ---
                        case 'rating':
                            ?>
                            <h2><i class="fas fa-star-half-alt"></i> Analisis Rating Kepuasan Pelayanan</h2>
                            <div class="rating-summary">
                                <div class="rating-chart-container"><canvas id="ratingChart"></canvas></div>
                                <div class="rating-list-container">
                                    <h3>Daftar Rating Tamu (<?php echo $total_ratings; ?> Total Penilai)</h3>
                                    <div class="table-responsive rating-table-scroll">
                                        <?php if ($query_ratings_list && mysqli_num_rows($query_ratings_list) > 0): ?>
                                            <table>
                                                <thead><tr><th>Nama Tamu</th><th>Rating</th></tr></thead>
                                                <tbody><?php while($item = mysqli_fetch_assoc($query_ratings_list)): ?><tr><td><?php echo htmlspecialchars($item['nama']); ?></td><td class="rating-stars-small"><?php if (!empty($item['rating'])): ?><?php for($i = 1; $i <= 5; $i++): ?><i class="fa<?php echo ($i <= $item['rating']) ? 's' : 'r'; ?> fa-star"></i><?php endfor; ?>(<?php echo $item['rating']; ?>)<?php else: echo '-'; endif; ?></td></tr><?php endwhile; ?></tbody>
                                            </table>
                                        <?php else: ?><p style="text-align: center; padding-top: 20px;">Belum ada data rating.</p><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break; 

                        // --- HALAMAN SCAN QR ---
                        case 'scan_qr':
                            ?>
                            <h2><i class="fas fa-qrcode"></i> Scan QR Code Tamu</h2>
                            <p>Arahkan QR Code tamu ke kamera di bawah ini. Pastikan halaman diakses via HTTPS.</p>
                            <div id="qr-reader"></div>
                            <div id="qr-reader-results"></div>
                            <div style="margin-top: 20px; border: 1px solid blue; padding: 10px; background: #f0f8ff; border-radius: 5px;">
                                <h4>Tes Manual (Tanpa Kamera):</h4>
                                <textarea id="manual-qr-data" rows="3" placeholder="Masukkan data QR lengkap..." style="width: 95%; margin-bottom: 5px;"></textarea><br>
                                <button id="test-scan-btn" class="btn btn-secondary btn-sm">Proses Data Tes</button>
                                <p><small>Gunakan ini untuk menguji `proses_scan.php`.</small></p>
                            </div>
                            <?php
                            break; 

                        // --- HALAMAN INPUT PENGADUAN ---
                        case 'pengaduan':
                            ?>
                            <h2><i class="fas fa-exclamation-triangle"></i> Input Pengaduan Masalah</h2>
                            <p>Formulir ini digunakan untuk mencatat pengaduan yang diterima.</p>
                            <?php if (isset($_SESSION['pengaduan_message'])) { $msg_type = $_SESSION['pengaduan_message_type'] ?? 'sukses'; echo '<p class="pesan ' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($_SESSION['pengaduan_message']) . '</p>'; unset($_SESSION['pengaduan_message'],$_SESSION['pengaduan_message_type']); } ?>
                            <div class="form-container admin-form">
                                <form action="proses_pengaduan.php" method="post" enctype="multipart/form-data">
                                    <div class="form-group"><label for="nama_pelapor">Nama Pelapor:</label><input type="text" id="nama_pelapor" name="nama_pelapor" placeholder="Nama orang yang mengadu" required></div>
                                    <div class="form-group"><label for="kontak_pelapor">Kontak Pelapor (Email/No. HP):</label><input type="text" id="kontak_pelapor" name="kontak_pelapor" placeholder="Untuk tindak lanjut jika perlu"></div>
                                    <div class="form-group"><label for="kategori_pengaduan">Kategori Pengaduan:</label><select id="kategori_pengaduan" name="kategori_pengaduan" required><option value="">-- Pilih Kategori --</option><option value="Fasilitas">Fasilitas</option><option value="Layanan">Layanan Petugas</option><option value="Keamanan">Keamanan</option><option value="Kebersihan">Kebersihan</option><option value="Saran">Saran / Masukan</option><option value="Lainnya">Lainnya</option></select></div>
                                    <div class="form-group"><label for="judul_pengaduan">Judul / Ringkasan Pengaduan:</label><input type="text" id="judul_pengaduan" name="judul_pengaduan" placeholder="Contoh: AC di Ruang Tunggu Mati" required></div>
                                    <div class="form-group"><label for="detail_pengaduan">Detail Pengaduan:</label><textarea id="detail_pengaduan" name="detail_pengaduan" rows="6" placeholder="Jelaskan detail masalah..." required></textarea></div>
                                    <div class="form-group"><label for="lokasi_kejadian">Lokasi Kejadian:</label><input type="text" id="lokasi_kejadian" name="lokasi_kejadian" placeholder="Contoh: Ruang Tunggu Lantai 2"></div>
                                    <div class="form-group"><label for="waktu_kejadian">Perkiraan Waktu Kejadian:</label><input type="datetime-local" id="waktu_kejadian" name="waktu_kejadian"><small>Kosongkan jika tidak tahu pasti.</small></div>
                                    <div class="form-group"><label for="lampiran">Lampiran Bukti (Opsional, Max 2MB):</label><input type="file" id="lampiran" name="lampiran"><small>Format: JPG, PNG, PDF</small></div>
                                    <input type="hidden" name="id_petugas_pencatat" value="<?php echo htmlspecialchars($_SESSION['id_petugas'] ?? '0'); ?>">
                                    <button type="submit" name="submit_pengaduan" class="btn btn-utama">Simpan Pengaduan</button>
                                </form>
                            </div>
                            <?php
                            break; 

                        // --- HALAMAN LIHAT PENGADUAN ---
                        case 'lihat_pengaduan':
                             ?>
                            <h2><i class="fas fa-list-check"></i> Daftar Pengaduan Masuk</h2>
                            <p>Menampilkan semua pengaduan yang telah dicatat.</p>
                            <?php if (!empty($pesan_error_global) && !$query_pengaduan) { echo '<p class="pesan error">' . htmlspecialchars($pesan_error_global) . '</p>'; } ?>
                            <?php if (isset($_SESSION['admin_message'])) { $msg_type = $_SESSION['admin_message_type'] ?? 'sukses'; echo '<p class="pesan ' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($_SESSION['admin_message']) . '</p>'; unset($_SESSION['admin_message'], $_SESSION['admin_message_type']); } ?>
                            <div class="table-responsive">
                                <table>
                                    <thead><tr><th>ID</th><th>Waktu Lapor</th><th>Judul</th><th>Pelapor</th><th>Kategori</th><th>Status</th><th>Dicatat Oleh</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        <?php if (isset($query_pengaduan) && mysqli_num_rows($query_pengaduan) > 0): ?>
                                            <?php while($pengaduan = mysqli_fetch_assoc($query_pengaduan)): ?>
                                            <tr>
                                                <td><?php echo $pengaduan['id_pengaduan']; ?></td>
                                                <td style="white-space: nowrap;"><?php echo date('d M Y, H:i', strtotime($pengaduan['waktu_lapor'])); ?></td>
                                                <td><?php echo htmlspecialchars($pengaduan['judul_pengaduan']); ?></td>
                                                <td><?php echo htmlspecialchars($pengaduan['nama_pelapor']); ?></td>
                                                <td><?php echo htmlspecialchars($pengaduan['kategori_pengaduan']); ?></td>
                                                <td><span class="status-<?php echo strtolower(htmlspecialchars($pengaduan['status_pengaduan'])); ?>"><?php echo htmlspecialchars($pengaduan['status_pengaduan']); ?></span></td>
                                                <td style="white-space: nowrap;"><?php echo htmlspecialchars($pengaduan['nama_pencatat'] ?? 'N/A'); ?></td>
                                                <td style="white-space: nowrap;">
                                                    <button type="button" class="btn btn-secondary btn-sm btn-detail-pengaduan" data-id="<?php echo $pengaduan['id_pengaduan']; ?>" title="Lihat Detail & Tindak Lanjut"><i class="fas fa-eye"></i> Detail</button>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="8" style="text-align: center;">Belum ada data pengaduan.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            break; 


                        // --- HALAMAN LIHAT TAMU (DEFAULT) ---
                        case 'lihat_tamu':
                        default:
                            ?>
                            <h2><i class="fas fa-list-alt"></i> Daftar Tamu Masuk</h2>
                            <p>Rata-rata Rating Kepuasan: <strong><?php echo $rata_rata_rating; ?> / 5</strong></p>
                            <?php if (isset($_SESSION['admin_message'])) { $msg_type = $_SESSION['admin_message_type'] ?? 'sukses'; echo '<p class="pesan ' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($_SESSION['admin_message']) . '</p>'; unset($_SESSION['admin_message'], $_SESSION['admin_message_type']); } ?>
                            <div class="table-responsive">
                                <table>
                                    <thead><tr><th>ID</th><th>Nama</th><th>Status</th><th>Alamat</th><th>Keperluan</th><th>No. Telp</th><th>Rating</th><th>Waktu Input</th><th>Waktu Scan</th><th>QR Code</th><th>Aksi</th></tr></thead>
                                    <tbody>
                                        <?php if ($query_tamu && mysqli_num_rows($query_tamu) > 0): ?>
                                            <?php while($tamu = mysqli_fetch_assoc($query_tamu)):
                                                $qrDataTamu = "ID Tamu: " . $tamu['id'] . "\nNama: " . $tamu['nama'] . "\nWaktu: " . date('d/m/Y H:i', strtotime($tamu['waktu_input'])) . "\n"; if (!empty($tamu['keperluan'])) $qrDataTamu .= "Keperluan: " . $tamu['keperluan'];
                                                $namaFileQr = 'QRCode_Tamu_' . $tamu['id'] . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $tamu['nama']) . '.png';
                                                $statusClass = (!empty($tamu['status_kehadiran']) && $tamu['status_kehadiran'] == 'Hadir') ? 'status-hadir' : 'status-belum';
                                                $statusText = ($statusClass == 'status-hadir') ? 'Hadir' : 'Belum Scan';
                                            ?>
                                            <tr>
                                                <td><?php echo $tamu['id']; ?></td><td><?php echo htmlspecialchars($tamu['nama']); ?></td>
                                                <td class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></td>
                                                <td><?php echo nl2br(htmlspecialchars($tamu['alamat'] ?? '-')); ?></td>
                                                <td><?php echo nl2br(htmlspecialchars($tamu['keperluan'] ?? '-')); ?></td>
                                                <td><?php echo htmlspecialchars($tamu['no_telp'] ?? '-'); ?></td>
                                                <td class="rating-stars"><?php if(!empty($tamu['rating']) && $tamu['rating'] > 0): ?><?php for($i = 1; $i <= 5; $i++): ?><i class="fa<?php echo ($i <= $tamu['rating']) ? 's' : 'r'; ?> fa-star"></i><?php endfor; ?>(<?php echo $tamu['rating']; ?>)<?php else: echo '-'; ?><?php endif; ?></td>
                                                <td><?php echo date('d M Y, H:i', strtotime($tamu['waktu_input'])); ?></td>
                                                <td><?php echo !empty($tamu['waktu_scan_masuk']) ? date('d M Y, H:i', strtotime($tamu['waktu_scan_masuk'])) : '-'; ?></td>
                                                <td style="text-align:center;"><button type="button" class="btn btn-secondary btn-sm btn-view-qr" data-qrdata="<?php echo htmlspecialchars($qrDataTamu); ?>" data-filename="<?php echo htmlspecialchars($namaFileQr); ?>" data-namatamu="<?php echo htmlspecialchars($tamu['nama']); ?>" title="Lihat QR"><i class="fas fa-qrcode"></i></button></td>
                                                <td style="white-space: nowrap;"> <button type="button" class="btn btn-warning btn-sm btn-edit-tamu" data-id="<?php echo $tamu['id']; ?>" data-nama="<?php echo htmlspecialchars($tamu['nama']); ?>" data-alamat="<?php echo htmlspecialchars($tamu['alamat'] ?? ''); ?>" data-keperluan="<?php echo htmlspecialchars($tamu['keperluan'] ?? ''); ?>" data-notelp="<?php echo htmlspecialchars($tamu['no_telp'] ?? ''); ?>" data-rating="<?php echo htmlspecialchars($tamu['rating'] ?? ''); ?>" title="Edit Data Tamu"><i class="fas fa-edit"></i></button><form action="hapus_tamu.php" method="post" onsubmit="return confirm('Yakin hapus tamu \'<?php echo htmlspecialchars(addslashes($tamu['nama'])); ?>\'?');" style="display:inline; margin-left: 5px;"><input type="hidden" name="id_tamu" value="<?php echo $tamu['id']; ?>"><button type="submit" name="hapus" class="btn btn-hapus btn-sm" title="Hapus"><i class="fas fa-trash-alt"></i></button></form></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="11" style="text-align: center;">Belum ada data tamu.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            break; 
                            
                    } // Akhir switch
                ?>
            </div> </main> </div> <div id="qrModal" class="modal"><div class="modal-content"><span class="modal-close-btn">&times;</span><h3 id="modalQrTitle">QR Code Tamu</h3><div class="modal-qr-display"><div id="modal-qrcode-container"></div></div><p><small>QR Code ini berisi detail tamu.</small></p><button id="modalDownloadQrBtn" class="btn btn-secondary"><i class="fas fa-download"></i> Download</button></div></div>
    <div id="editModal" class="modal"><div class="modal-content"><span class="modal-close-btn">&times;</span> <h3>Edit Data Tamu</h3><hr><div id="editPesan" class="pesan" style="display: none;"></div><form id="editTamuForm" class="form-container"><input type="hidden" id="edit_id_tamu" name="id_tamu"><div class="form-group"><label for="edit_nama">Nama:</label><input type="text" id="edit_nama" name="nama" required></div><div class="form-group"><label for="edit_alamat">Alamat:</label><textarea id="edit_alamat" name="alamat" rows="3"></textarea></div><div class="form-group"><label for="edit_keperluan">Keperluan:</label><textarea id="edit_keperluan" name="keperluan" rows="3"></textarea></div><div class="form-group"><label for="edit_no_telp">No. Telp:</label><input type="text" id="edit_no_telp" name="no_telp"></div><div class="form-group"><label>Rating:</label><div class="rating-input" id="edit_rating_input"><input type="radio" id="star5_edit" name="rating_edit" value="5"><label for="star5_edit"><i class="fas fa-star"></i></label><input type="radio" id="star4_edit" name="rating_edit" value="4"><label for="star4_edit"><i class="fas fa-star"></i></label><input type="radio" id="star3_edit" name="rating_edit" value="3"><label for="star3_edit"><i class="fas fa-star"></i></label><input type="radio" id="star2_edit" name="rating_edit" value="2"><label for="star2_edit"><i class="fas fa-star"></i></label><input type="radio" id="star1_edit" name="rating_edit" value="1"><label for="star1_edit"><i class="fas fa-star"></i></label><button type="button" id="reset_rating_edit" class="btn btn-sm btn-secondary" title="Hapus Rating">Reset</button></div></div><div style="text-align: right; margin-top:15px;"><button type="button" class="btn btn-secondary modal-close-btn-manual">Batal</button> <button type="submit" class="btn btn-success">Simpan</button></div></form></div></div>
    <div id="detailPengaduanModal" class="modal"><div class="modal-content" style="text-align: left;"><span class="modal-close-btn">&times;</span><h3>Detail Pengaduan</h3><hr><div id="detailPengaduanContent"><p><i>Memuat detail...</i></p></div><hr><h4>Tindak Lanjut</h4><form id="formTindakLanjut" style="margin-top: 10px;"><input type="hidden" id="detail_id_pengaduan" name="id_pengaduan"><div class="form-group"><label for="detail_status">Ubah Status:</label><select id="detail_status" name="status_pengaduan"><option value="Baru">Baru</option><option value="Diproses">Diproses</option><option value="Selesai">Selesai</option><option value="Ditolak">Ditolak</option></select></div><div class="form-group"><label for="detail_catatan">Catatan Tindak Lanjut:</label><textarea id="detail_catatan" name="catatan_tindaklanjut" rows="4"></textarea></div><div style="text-align:right;"><button type="submit" class="btn btn-success">Simpan Tindak Lanjut</button></div></form><div id="tindakLanjutPesan" class="pesan" style="display: none; margin-top: 10px;"></div></div></div>

    <?php
        if (isset($koneksi) && $koneksi instanceof mysqli) { mysqli_close($koneksi); }
    ?>

    <script>
        function htmlspecialchars(str) { const map = {'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'}; if (typeof str !== 'string') return ''; return str.replace(/[&<>"']/g, m => map[m]); }
        function processScanResult(decodedText) { const resultContainer=document.getElementById('qr-reader-results');if(!resultContainer)return;console.log(`Scan result: ${decodedText}`);resultContainer.innerHTML='<p><i>Memproses...</i></p>';let guestId=null;const lines=decodedText.split('\\n');for(const line of lines){const trimmedLine=line.trim();if(trimmedLine.toLowerCase().startsWith('id tamu:')){guestId=parseInt(trimmedLine.split(':')[1].trim(),10);break}}if(guestId&&!isNaN(guestId)){console.log("Sending ID:",guestId);resultContainer.innerHTML=`<p><i>Menyimpan ID: ${guestId}...</i></p>`;fetch('proses_scan.php',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({guestId:guestId})}).then(response=>{if(!response.ok){return response.text().then(text=>{throw new Error(`Server error (${response.status}): ${text||'Unknown error'}`)})}return response.json()}).then(data=>{console.log("Server response:",data);let messageClass=data.success?'sukses':'error';resultContainer.innerHTML=`<p class="pesan ${messageClass}">${htmlspecialchars(data.message||'Respon OK.')}</p>`}).catch(error=>{console.error('Scan error:',error);resultContainer.innerHTML=`<p class="pesan error">Error: ${htmlspecialchars(error.message)}.</p>`})}else{console.error("QR parse error:",decodedText);resultContainer.innerHTML=`<p class="pesan warning">Format QR tidak dikenali.</p><pre>${htmlspecialchars(decodedText)}</pre>`}}
        function nl2br(str) { if (typeof str === 'undefined' || str === null) return ''; return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2'); }

        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = '<?php echo $page; ?>'; 
            let html5QrcodeScannerInstance = null; 
            
            // --- Inisialisasi Chart.js ---
            if (currentPage === 'rating') { /* ... kode Chart.js sama ... */ const ratingCtx=document.getElementById('ratingChart');if(ratingCtx){const ratingContext=ratingCtx.getContext('2d');const chartLabels=<?php echo json_encode($chart_labels??[]);?>;const chartData=<?php echo json_encode($chart_data??[]);?>;const chartPercentages=<?php echo json_encode($chart_percentages??[]);?>;const chartBgColors=<?php echo json_encode($chart_bg_colors??[]);?>;const chartBorderColors=<?php echo json_encode($chart_border_colors??[]);?>;const totalRatings=<?php echo $total_ratings??0;?>;new Chart(ratingContext,{type:'bar',data:{labels:chartLabels,datasets:[{label:'Jumlah Rating',data:chartData,backgroundColor:chartBgColors,borderColor:chartBorderColors,borderWidth:1}]},options:{responsive:!0,maintainAspectRatio:!1,plugins:{title:{display:!0,text:'Distribusi Rating (Total: '+totalRatings+' Penilai)'},tooltip:{callbacks:{label:function(tooltipItem){let label=tooltipItem.dataset.label||'';if(label)label+=': ';const value=tooltipItem.parsed.y;const percentage=chartPercentages[tooltipItem.dataIndex];if(value!==null)label+=value+' ('+percentage+'%)';return label}}},legend:{display:!1}},scales:{y:{beginAtZero:!0,title:{display:!0,text:'Jumlah Tamu'},ticks:{stepSize:1}},x:{title:{display:!0,text:'Rating Bintang'}}}}})} }

            // --- Generate QR Code Manual ---
            if (currentPage === 'input_manual') { /* ... kode Generate & Download QR Manual sama ... */ const qrContainerManual=document.getElementById('qrcode-container');const qrDataInputManual=document.getElementById('qrDataInput');const downloadBtnManual=document.getElementById('downloadQrBtn');if(qrContainerManual&&qrDataInputManual&&qrDataInputManual.value){const qrDataValueManual=qrDataInputManual.value;qrContainerManual.innerHTML='';try{new QRCode(qrContainerManual,{text:qrDataValueManual,width:200,height:200,colorDark:"#000000",colorLight:"#ffffff",correctLevel:QRCode.CorrectLevel.L})}catch(e){console.error("Manual QR err:",e);qrContainerManual.innerHTML='<p>Gagal buat QR.</p>'}}if(downloadBtnManual&&qrContainerManual){downloadBtnManual.addEventListener('click',function(e){e.preventDefault();const filename=this.getAttribute('data-filename')||'qrcode.png';const qrImage=qrContainerManual.querySelector('img');const qrCanvas=qrContainerManual.querySelector('canvas');let dataUrl=null;if(qrImage&&qrImage.src){dataUrl=qrImage.src}else if(qrCanvas){try{dataUrl=qrCanvas.toDataURL('image/png')}catch(err){alert("Gagal unduh QR.");return}}if(dataUrl){const link=document.createElement('a');link.href=dataUrl;link.download=filename;document.body.appendChild(link);link.click();document.body.removeChild(link)}else{alert('Gagal data QR.')}})} }

            // --- Logika Modal QR dan Edit Tamu ---
            if (currentPage === 'lihat_tamu' || currentPage === '') { /* ... kode Modal QR & Edit Tamu sama ... */ const qrModal=document.getElementById('qrModal');const modalQrContainer=document.getElementById('modal-qrcode-container');const modalQrTitle=document.getElementById('modalQrTitle');const modalDownloadQrBtn=document.getElementById('modalDownloadQrBtn');const closeQrModalBtn=qrModal?qrModal.querySelector('.modal-close-btn'):null;const viewQrButtons=document.querySelectorAll('.btn-view-qr');let qrCodeInstanceModal=null;function openQrModal(qrData,filename,namaTamu){if(!qrModal||!modalQrContainer||!modalQrTitle||!modalDownloadQrBtn)return;modalQrTitle.textContent='QR Code: '+htmlspecialchars(namaTamu);modalQrContainer.innerHTML='';modalDownloadQrBtn.setAttribute('data-filename',filename);try{qrCodeInstanceModal=new QRCode(modalQrContainer,{text:qrData,width:250,height:250,colorDark:"#000000",colorLight:"#ffffff",correctLevel:QRCode.CorrectLevel.L})}catch(e){modalQrContainer.innerHTML='Gagal buat QR.'}qrModal.style.display='block'}function closeQrModal(){if(qrModal){qrModal.style.display='none';if(modalQrContainer)modalQrContainer.innerHTML='';qrCodeInstanceModal=null}}viewQrButtons.forEach(button=>{button.addEventListener('click',function(){const qrData=this.getAttribute('data-qrdata');const filename=this.getAttribute('data-filename');const namaTamu=this.getAttribute('data-namatamu');if(qrData&&filename&&namaTamu){openQrModal(qrData,filename,namaTamu)}})});if(closeQrModalBtn)closeQrModalBtn.addEventListener('click',closeQrModal);if(modalDownloadQrBtn){modalDownloadQrBtn.addEventListener('click',function(e){e.preventDefault();const filename=this.getAttribute('data-filename')||'qrcode_modal.png';const qrImage=modalQrContainer.querySelector('img');const qrCanvas=modalQrContainer.querySelector('canvas');let dataUrl=null;if(qrImage&&qrImage.src){dataUrl=qrImage.src}else if(qrCanvas){try{dataUrl=qrCanvas.toDataURL('image/png')}catch(err){alert("Gagal unduh QR.");return}}if(dataUrl){const link=document.createElement('a');link.href=dataUrl;link.download=filename;document.body.appendChild(link);link.click();document.body.removeChild(link)}else{alert('Gagal data QR modal.')}})}if(qrModal)window.addEventListener('click',function(event){if(event.target==qrModal){closeQrModal()}});const editModal=document.getElementById('editModal');const editForm=document.getElementById('editTamuForm');const editPesan=document.getElementById('editPesan');const editIdTamu=document.getElementById('edit_id_tamu');const editNama=document.getElementById('edit_nama');const editAlamat=document.getElementById('edit_alamat');const editKeperluan=document.getElementById('edit_keperluan');const editNoTelp=document.getElementById('edit_no_telp');const editRatingInputs=document.querySelectorAll('input[name="rating_edit"]');const editCloseBtns=editModal?editModal.querySelectorAll('.modal-close-btn,.modal-close-btn-manual'):[];const editButtons=document.querySelectorAll('.btn-edit-tamu');const resetRatingBtn=document.getElementById('reset_rating_edit');function openEditModal(data){if(!editModal||!editForm||!editPesan)return;editPesan.style.display='none';editPesan.textContent='';editPesan.className='pesan';editIdTamu.value=data.id||'';editNama.value=data.nama||'';editAlamat.value=data.alamat||'';editKeperluan.value=data.keperluan||'';editNoTelp.value=data.notelp||'';editRatingInputs.forEach(radio=>{radio.checked=(data.rating&&radio.value===data.rating)});editModal.style.display='block'}function closeEditModal(){if(editModal)editModal.style.display='none'}editButtons.forEach(button=>{button.addEventListener('click',function(){const data={id:this.getAttribute('data-id'),nama:this.getAttribute('data-nama'),alamat:this.getAttribute('data-alamat'),keperluan:this.getAttribute('data-keperluan'),notelp:this.getAttribute('data-notelp'),rating:this.getAttribute('data-rating')};openEditModal(data)})});editCloseBtns.forEach(btn=>btn.addEventListener('click',closeEditModal));if(resetRatingBtn){resetRatingBtn.addEventListener('click',function(){editRatingInputs.forEach(radio=>radio.checked=!1)})}if(editModal){window.addEventListener('click',function(event){if(event.target==editModal){closeEditModal()}})}if(editForm){editForm.addEventListener('submit',function(e){e.preventDefault();editPesan.textContent='Menyimpan...';editPesan.className='pesan warning';editPesan.style.display='block';const selectedRating=document.querySelector('input[name="rating_edit"]:checked');const ratingValue=selectedRating?selectedRating.value:null;const formData={id_tamu:editIdTamu.value,nama:editNama.value,alamat:editAlamat.value,keperluan:editKeperluan.value,no_telp:editNoTelp.value,rating:ratingValue};fetch('proses_update_tamu.php',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(formData)}).then(response=>{if(!response.ok){return response.text().then(text=>{throw new Error(`Server error (${response.status}): ${text||'Unknown error'}`)})}return response.json()}).then(data=>{editPesan.textContent=data.message||'Selesai.';editPesan.className='pesan '+(data.success?'sukses':'error');if(data.success){setTimeout(()=>{closeEditModal();alert("Data diperbarui. Refresh (F5).")},1500)}}).catch(error=>{editPesan.textContent='Gagal: '+error.message;editPesan.className='pesan error'})})}}

            // --- Logika Modal Detail Pengaduan ---
             if (currentPage === 'lihat_pengaduan') { /* ... kode JS Modal Detail Pengaduan sama seperti sebelumnya ... */ const modal=document.getElementById('detailPengaduanModal');const modalContent=document.getElementById('detailPengaduanContent');const closeModalBtn=modal?modal.querySelector('.modal-close-btn'):null;const detailButtons=document.querySelectorAll('.btn-detail-pengaduan');const formTindakLanjut=document.getElementById('formTindakLanjut');const detailIdPengaduan=document.getElementById('detail_id_pengaduan');const detailStatusSelect=document.getElementById('detail_status');const detailCatatanTextarea=document.getElementById('detail_catatan');const tindakLanjutPesan=document.getElementById('tindakLanjutPesan');function closeDetailModal(){if(modal)modal.style.display='none'}detailButtons.forEach(button=>{button.addEventListener('click',async function(){const id=this.getAttribute('data-id');if(!id||!modal||!modalContent)return;modalContent.innerHTML='<p><i>Memuat...</i></p>';if(tindakLanjutPesan)tindakLanjutPesan.style.display='none';modal.style.display='block';if(detailIdPengaduan)detailIdPengaduan.value=id;try{const response=await fetch(`ambil_detail_pengaduan.php?id=${id}`);if(!response.ok){let errorText=await response.text();throw new Error(`Server error (${response.status}): Gagal ambil data.`)}const data=await response.json();console.log("Detail Response:",data);if(data.success&&data.pengaduan){const p=data.pengaduan;const waktuLaporFormatted=p.waktu_lapor?new Date(p.waktu_lapor).toLocaleString('id-ID',{dateStyle:'medium',timeStyle:'short'}):'-';const waktuKejadianFormatted=p.waktu_kejadian?new Date(p.waktu_kejadian).toLocaleString('id-ID',{dateStyle:'medium',timeStyle:'short'}):'-';let lampiranHtml='-';if(p.lampiran){const namaFile=p.lampiran.split('/').pop();lampiranHtml=`<a href="${htmlspecialchars(p.lampiran)}" target="_blank">${htmlspecialchars(namaFile)}</a>`}modalContent.innerHTML=`<p><strong>ID:</strong> ${p.id_pengaduan}</p><p><strong>Waktu Lapor:</strong> ${waktuLaporFormatted}</p><p><strong>Judul:</strong> ${htmlspecialchars(p.judul_pengaduan)}</p><p><strong>Pelapor:</strong> ${htmlspecialchars(p.nama_pelapor)} (${htmlspecialchars(p.kontak_pelapor||'-')})</p><p><strong>Kategori:</strong> ${htmlspecialchars(p.kategori_pengaduan)}</p><p><strong>Detail:</strong><br>${nl2br(htmlspecialchars(p.detail_pengaduan))}</p><p><strong>Lokasi:</strong> ${htmlspecialchars(p.lokasi_kejadian||'-')}</p><p><strong>Waktu Kejadian:</strong> ${waktuKejadianFormatted}</p><p><strong>Lampiran:</strong> ${lampiranHtml}</p><p><strong>Status:</strong> ${htmlspecialchars(p.status_pengaduan)}</p><p><strong>Catatan:</strong><br>${nl2br(htmlspecialchars(p.catatan_tindaklanjut||'-'))}</p><p><strong>Dicatat Oleh:</strong> ${htmlspecialchars(p.nama_pencatat||'N/A')}</p>`;if(detailStatusSelect)detailStatusSelect.value=p.status_pengaduan||'Baru';if(detailCatatanTextarea)detailCatatanTextarea.value=p.catatan_tindaklanjut||''}else{modalContent.innerHTML=`<p class="pesan error">${htmlspecialchars(data.error||'Gagal.')}</p>`}}catch(error){console.error("Fetch detail error:",error);modalContent.innerHTML=`<p class="pesan error">Error ambil detail. (${htmlspecialchars(error.message)})</p>`}})});if(closeModalBtn)closeModalBtn.addEventListener('click',closeDetailModal);if(modal)window.addEventListener('click',function(event){if(event.target==modal)closeDetailModal()});if(formTindakLanjut&&tindakLanjutPesan){formTindakLanjut.addEventListener('submit',async function(e){e.preventDefault();tindakLanjutPesan.style.display='none';tindakLanjutPesan.textContent='Menyimpan...';tindakLanjutPesan.className='pesan warning';tindakLanjutPesan.style.display='block';const formData=new FormData(formTindakLanjut);try{const response=await fetch('proses_pengaduan_update.php',{method:'POST',body:formData});if(!response.ok){let errorText=await response.text();throw new Error(`Server error (${response.status}): ${errorText||response.statusText}`)}const data=await response.json();console.log("Update Response:",data);tindakLanjutPesan.textContent=data.message||'Selesai.';tindakLanjutPesan.className='pesan '+(data.success?'sukses':'error');if(data.success){setTimeout(()=>{alert("Tindak lanjut disimpan. Refresh (F5).")},1000)}}catch(error){console.error("Follow-up error:",error);tindakLanjutPesan.textContent='Gagal: '+error.message;tindakLanjutPesan.className='pesan error'}finally{tindakLanjutPesan.style.display='block'}})}}

            // --- Inisialisasi Scanner QR ---
            if (currentPage === 'scan_qr') { /* ... kode Scanner QR sama seperti sebelumnya ... */ const qrReaderElement=document.getElementById('qr-reader');const resultContainerScan=document.getElementById('qr-reader-results');const testScanBtn=document.getElementById('test-scan-btn');const manualQrDataInput=document.getElementById('manual-qr-data');if(qrReaderElement&&resultContainerScan){function onScanSuccess(decodedText,decodedResult){console.log(`Scan result: ${decodedText}`);processScanResult(decodedText);if(html5QrcodeScannerInstance){html5QrcodeScannerInstance.clear().catch(error=>console.error("Stop scanner err.",error));html5QrcodeScannerInstance=null;console.log("Scanner stopped.")}}function onScanFailure(error){}if(location.protocol!=='https:'){qrReaderElement.innerHTML="<p class='pesan error'>ERROR: Kamera butuh HTTPS.</p><p>Gunakan Tes Manual.</p>"}else{if(!html5QrcodeScannerInstance){try{html5QrcodeScannerInstance=new Html5QrcodeScanner("qr-reader",{fps:5,qrbox:{width:250,height:250},rememberLastUsedCamera:!0},!1);html5QrcodeScannerInstance.render(onScanSuccess,onScanFailure);console.log("Scanner rendered.")}catch(e){console.error("Scanner init err:",e);qrReaderElement.innerHTML="<p class='pesan error'>Gagal scanner.</p>"}}}if(testScanBtn&&manualQrDataInput){testScanBtn.addEventListener('click',function(){const manualData=manualQrDataInput.value;if(manualData.trim()===''){alert('Input data QR dulu.');return}processScanResult(manualData)})}}else{console.error("Scanner elements not found.")} }

            // === Kode Toggle Sidebar Responsif ===
            const sidebar = document.querySelector('.admin-sidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const sidebarOverlay = document.querySelector('.sidebar-overlay'); 
            if (sidebar && sidebarToggleBtn) { sidebarToggleBtn.addEventListener('click', function() { sidebar.classList.toggle('open'); }); } 
            else { console.error("Sidebar/Toggle button not found!"); }
            if (sidebar && sidebarOverlay) { sidebarOverlay.addEventListener('click', function() { sidebar.classList.remove('open'); }); }
            // === Akhir Kode Toggle Sidebar ===

        }); // Akhir DOMContentLoaded
    </script>

</body>
</html>