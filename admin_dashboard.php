<?php
session_start();

// 1. Cek Login Admin
if (!isset($_SESSION['id_petugas'])) {
    $_SESSION['login_error'] = "Anda harus login terlebih dahulu.";
    header('Location: login.php');
    exit();
}

// 2. Koneksi DB
require_once 'koneksi.php'; 

// 3. Tentukan Halaman Aktif
$page = isset($_GET['page']) ? $_GET['page'] : 'lihat_tamu';

// 4. Inisialisasi Variabel
$pesan_error_global = '';
$query_tamu = null; 
$rata_rata_rating = 'N/A'; 
$query_ratings_list = null; 
$rating_counts = array_fill_keys(range(1, 5), 0);
$total_ratings = 0; 
$chart_labels = []; 
$chart_data = []; 
$chart_percentages = [];
$chart_bg_colors = ['rgba(220, 53, 69, 0.7)', 'rgba(255, 193, 7, 0.7)', 'rgba(111, 66, 193, 0.7)', 'rgba(0, 123, 255, 0.7)', 'rgba(40, 167, 69, 0.7)'];
$chart_border_colors = ['rgba(220, 53, 69, 1)', 'rgba(255, 193, 7, 1)', 'rgba(111, 66, 193, 1)', 'rgba(0, 123, 255, 1)', 'rgba(40, 167, 69, 1)'];
$query_pengaduan = null;

// Opsi Dropdown (dipakai di modal edit tamu)
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

// 5. Logika Pengambilan Data Halaman Admin
if (!$koneksi) {
    $pesan_error_global = "Koneksi database gagal.";
} else {
    if ($page == 'lihat_tamu' || $page == '') {
        $sql_tamu = "SELECT id, nama, alamat, keperluan, pekerjaan, no_telp, pesan, rating, waktu_input, status_kehadiran, waktu_scan_masuk, no_antrian_hari_ini, tanggal_antrian 
                     FROM tamu ORDER BY waktu_input DESC"; 
        $query_tamu = mysqli_query($koneksi, $sql_tamu); 
        if (!$query_tamu) { $pesan_error_global = "Gagal mengambil data tamu: " . mysqli_error($koneksi); }
        
        $sql_avg_rating = "SELECT AVG(rating) as rata_rata FROM tamu WHERE rating IS NOT NULL AND rating > 0 AND rating <= 5"; 
        $query_avg = mysqli_query($koneksi, $sql_avg_rating); 
        if ($query_avg) { 
            $avg_rating_data = mysqli_fetch_assoc($query_avg); 
            $rata_rata_rating = ($avg_rating_data && $avg_rating_data['rata_rata'] !== null) ? round($avg_rating_data['rata_rata'], 1) : 'N/A'; 
        } else { $pesan_error_global .= " Gagal mengambil rata-rata rating."; } 
    } 
    elseif ($page == 'rating') {
        $sql_ratings_list_data = "SELECT nama, rating FROM tamu WHERE rating IS NOT NULL AND rating > 0 AND rating <= 5 ORDER BY waktu_input DESC";
        $query_ratings_list = mysqli_query($koneksi, $sql_ratings_list_data);
        if (!$query_ratings_list) { $pesan_error_global = "Gagal mengambil daftar rating: " . mysqli_error($koneksi); }

        $sql_chart_source = "SELECT rating, COUNT(*) as jumlah FROM tamu WHERE rating IS NOT NULL AND rating > 0 AND rating <= 5 GROUP BY rating ORDER BY rating ASC";
        $query_chart_data = mysqli_query($koneksi, $sql_chart_source);
        if ($query_chart_data) {
            while ($row = mysqli_fetch_assoc($query_chart_data)) {
                if (isset($rating_counts[$row['rating']])) { $rating_counts[$row['rating']] = (int)$row['jumlah']; $total_ratings += (int)$row['jumlah']; }
            }
        } else { $pesan_error_global = "Gagal mengambil data chart: " . mysqli_error($koneksi); }
        
        for ($i = 1; $i <= 5; $i++) {
            $chart_labels[] = $i . " Bintang"; $count = $rating_counts[$i]; $chart_data[] = $count;
            $percentage = ($total_ratings > 0) ? round(($count / $total_ratings) * 100, 1) : 0; $chart_percentages[] = $percentage;
        }
    }
    elseif ($page == 'lihat_pengaduan') {
        $sql_pengaduan = "SELECT p.id_pengaduan, p.waktu_lapor, p.judul_pengaduan, p.nama_pelapor, p.kategori_pengaduan, p.status_pengaduan, pt.nama_lengkap as nama_pencatat 
                         FROM pengaduan p LEFT JOIN petugas pt ON p.id_petugas_pencatat = pt.id_petugas ORDER BY p.waktu_lapor DESC"; 
        $query_pengaduan = mysqli_query($koneksi, $sql_pengaduan);
        if (!$query_pengaduan) { $pesan_error_global = "Gagal mengambil data pengaduan: " . mysqli_error($koneksi); }
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
    
    <style>
        /* === CSS LENGKAP UNTUK ADMIN DASHBOARD === */
        /* (Salin semua CSS dari jawaban sebelumnya di mana layout sudah bagus dan modal tersembunyi) */
        /* Pastikan .modal { display: none; } ada dan benar */
         :root { --sidebar-bg: #2c3e50; --sidebar-text-color: #ecf0f1; --sidebar-active-bg: #3498db; --sidebar-hover-bg: #34495e; --header-bg: #ffffff; --header-text-color: #333; --content-bg: #ecf0f5; --border-color: #d2d6de; --star-color: #f39c12; --link-color: #3498db; --primary-color: #007bff; --secondary-color: #6c757d; --success-color: #28a745; --danger-color: #dc3545; --light-bg: #f8f9fa; --dark-text: #343a40; --light-text: #ffffff; --border-color-light: #dee2e6; } body.admin-body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--content-bg); font-size: 14px; color: #333; } * { box-sizing: border-box; } a { color: var(--link-color); text-decoration: none; } a:hover { text-decoration: underline; } .admin-wrapper { display: flex; min-height: 100vh; } .admin-sidebar { width: 240px; flex-shrink: 0; background-color: var(--sidebar-bg); color: var(--sidebar-text-color); height: 100vh; position: sticky; top: 0; transition: left 0.3s ease-in-out; overflow-y: auto; z-index: 1010; } .sidebar-header { padding: 15px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.1); } .sidebar-header h3 { margin: 0; color: #fff; font-size: 1.2rem; } .sidebar-nav ul { list-style: none; padding: 10px 0; margin: 0; } .sidebar-nav li a { display: flex; align-items: center; padding: 10px 15px; color: var(--sidebar-text-color); transition: background-color 0.2s ease, color 0.2s ease; font-size: 0.9rem; text-decoration: none; } .sidebar-nav li a i { margin-right: 12px; width: 20px; text-align: center; font-size: 1.1em; } .sidebar-nav li a:hover { background-color: var(--sidebar-hover-bg); color: #fff; } .sidebar-nav li.active > a { background-color: var(--sidebar-active-bg); color: #fff; font-weight: 600; } .admin-content { flex-grow: 1; /* height: 100vh; */ /* overflow-y: auto; */ } .admin-content-header { display: flex; align-items: center; padding: 12px 20px; background-color: var(--header-bg); border-bottom: 1px solid var(--border-color); position: sticky; top: 0; z-index: 1000; min-height: 50px; } .admin-content-header h1 { margin: 0; flex-grow: 1; font-size: 1.4rem; color: var(--header-text-color); } .admin-info { margin-left: auto; font-size: 0.9rem; color: #555; } .admin-info i { margin-right: 5px; } .content-area { padding: 25px; } .sidebar-toggle-button { display: none; background: none; border: none; font-size: 1.6rem; color: var(--header-text-color); cursor: pointer; padding: 0 10px; margin-right: 15px; line-height: 1; } .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.6); z-index: 1040; } @media (max-width: 768px) { .admin-sidebar { position: fixed; left: -250px; top: 0; bottom: 0; z-index: 1050; } .admin-sidebar.open { left: 0; box-shadow: 3px 0 15px rgba(0,0,0,0.2); } .sidebar-toggle-button { display: block; } .admin-sidebar.open + .sidebar-overlay { display: block; } .admin-content-header { padding: 10px 15px; } .admin-content-header h1 { font-size: 1.2rem; } .content-area { padding: 15px; } } .pesan { padding: 12px 18px; margin: 20px 0; border-radius: 6px; border: 1px solid transparent; font-size: 0.95em;} .pesan.sukses { background-color: #d1e7dd; border-color: #badbcc; color: #0f5132; } .pesan.error { background-color: #f8d7da; border-color: #f5c2c7; color: #842029; } .pesan.warning { background-color: #fff3cd; border-color: #ffe69c; color: #664d03; } .form-container { background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-top: 20px; } .form-group { margin-bottom: 18px; } .form-group label { display: block; margin-bottom: 6px; font-weight: 600; color: #495057; font-size: 0.95em; } .form-group input[type="text"], .form-group input[type="password"], .form-group input[type="email"], .form-group input[type="datetime-local"], .form-group textarea, .form-group select, .form-group input[type="file"] { width: 100%; padding: 10px 12px; border: 1px solid var(--border-color-light); border-radius: 5px; box-sizing: border-box; font-size: 1em; transition: border-color 0.2s ease, box-shadow 0.2s ease; } .form-group input:focus, .form-group textarea:focus, .form-group select:focus { border-color: var(--primary-color); outline: none; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2); } .form-group textarea { resize: vertical; min-height: 80px;} .form-group small { font-size: 0.85em; color: #6c757d; margin-top: 6px; display: block; } .btn { padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.9rem; text-decoration: none; display: inline-block; text-align: center; transition: background-color 0.2s ease;} .btn-utama { background-color: #007bff; color: white; } .btn-utama:hover { background-color: #0056b3; } .btn-secondary { background-color: #6c757d; color: white; } .btn-secondary:hover { background-color: #5a6268; } .btn-success { background-color: #28a745; color: white; } .btn-success:hover { background-color: #218838; } .btn-danger, .btn-hapus { background-color: #dc3545; color: white; } .btn-danger:hover, .btn-hapus:hover { background-color: #c82333; } .btn-warning { background-color: #ffc107; color: #212529; } .btn-warning:hover { background-color: #e0a800; } .btn-sm { padding: 4px 8px; font-size: 0.8rem; } .table-responsive { overflow-x: auto; margin-top: 15px; background-color: #fff; border: 1px solid var(--border-color); border-radius: 4px; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid var(--border-color); padding: 10px 12px; text-align: left; vertical-align: middle; font-size: 0.9rem; } th { background-color: #f8f9fa; font-weight: 600; } tbody tr:nth-child(odd) { background-color: #f9f9f9; } .rating-input { display: flex; flex-direction: row-reverse; justify-content: flex-start; margin-bottom: 10px;} .rating-input input[type="radio"] { display: none; } .rating-input label { color: #ddd; font-size: 1.8em; cursor: pointer; padding: 0 0.1em; transition: color 0.2s ease-in-out;} .rating-input input[type="radio"]:checked ~ label, .rating-input label:hover, .rating-input label:hover ~ label { color: var(--star-color); } .rating-stars i, .rating-stars-small i { color: var(--star-color); } .rating-stars-small i { font-size: 0.9em; } .status-hadir { color: green; font-weight: bold; } .status-belum { color: grey; } .table-responsive th:last-child, .table-responsive td:last-child { width: auto; white-space: nowrap; text-align: center;} .modal { display: none; position: fixed; z-index: 1001; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); padding-top: 50px; } .modal-content { background-color: #fefefe; margin: 5% auto; padding: 25px; border: 1px solid #aaa; width: 90%; max-width: 550px; border-radius: 8px; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.2); text-align: left; } .modal-content h3 { margin-top: 0; margin-bottom: 20px; text-align: center; } .modal-close-btn { color: #aaa; position: absolute; top: 15px; right: 25px; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1;} .modal-close-btn:hover, .modal-close-btn:focus { color: black; text-decoration: none; } .modal-qr-display {text-align: center;} .modal-qr-display #modal-qrcode-container { margin: 15px auto; background: white; display: inline-block; padding: 5px; border: 1px solid #ccc; } #editPesan, #tindakLanjutPesan { margin-bottom: 15px; margin-top: 10px;} #detailPengaduanContent p { margin-bottom: 8px; line-height: 1.5;} #detailPengaduanContent p strong { display: inline-block; min-width: 120px; font-weight: bold;} .status-baru { padding: 3px 6px; background-color: #ffc107; color: #333; border-radius: 4px; font-size: 0.85em; display: inline-block;} .status-diproses { padding: 3px 6px; background-color: #0dcaf0; color: #fff; border-radius: 4px; font-size: 0.85em; display: inline-block;} .status-selesai { padding: 3px 6px; background-color: #198754; color: #fff; border-radius: 4px; font-size: 0.85em; display: inline-block;} .status-ditolak { padding: 3px 6px; background-color: #dc3545; color: #fff; border-radius: 4px; font-size: 0.85em; display: inline-block;} .rating-summary { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; align-items: flex-start;} .rating-chart-container { flex: 1 1 45%; min-width: 300px; position: relative; height:300px; margin-bottom: 20px; } .rating-list-container { flex: 1 1 100%; max-height: 40vh; overflow-y: auto;} .rating-table-scroll table { width: 100%;}
    </style>
</head>
<body class="admin-body">

    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header"><h3>Menu Admin</h3></div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?php echo ($page == 'lihat_tamu' || $page == '') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=lihat_tamu"><i class="fas fa-list-alt"></i> <span class="menu-text">Lihat Tamu</span></a></li>
                    <li class="<?php echo ($page == 'rating') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=rating"><i class="fas fa-star-half-alt"></i> <span class="menu-text">Rating Pelayanan</span></a></li>
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
                // Bagian untuk menampilkan pesan error global dari PHP
                if (!empty($pesan_error_global)) {
                    echo '<p class="pesan error">' . htmlspecialchars($pesan_error_global) . '</p>';
                }

                // Switch untuk konten halaman
                switch ($page) {
                    case 'rating':
                        ?>
                        <h2><i class="fas fa-star-half-alt"></i> Analisis Rating Kepuasan Pelayanan</h2>
                        <div class="rating-summary">
                            <div class="rating-chart-container">
                                <h3 style="text-align:center;">Distribusi Jumlah Rating</h3>
                                <canvas id="ratingBarChart"></canvas>
                            </div>
                            <div class="rating-chart-container">
                                <h3 style="text-align:center;">Persentase Rating</h3>
                                <canvas id="ratingPieChart"></canvas>
                            </div>
                        </div>
                        <div class="rating-list-container" style="margin-top: 30px;">
                            <h3>Detail Penilaian Tamu (Total: <?php echo $total_ratings; ?> Penilai)</h3>
                            <div class="table-responsive rating-table-scroll">
                                <?php if ($query_ratings_list && mysqli_num_rows($query_ratings_list) > 0): ?>
                                    <table>
                                        <thead><tr><th>Nama Tamu</th><th>Rating Diberikan</th></tr></thead>
                                        <tbody>
                                            <?php while($item = mysqli_fetch_assoc($query_ratings_list)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                                <td class="rating-stars-small">
                                                    <?php if (!empty($item['rating'])): ?>
                                                        <?php for($i_star = 1; $i_star <= 5; $i_star++): ?>
                                                            <i class="fa<?php echo ($i_star <= $item['rating']) ? 's' : 'r'; ?> fa-star"></i>
                                                        <?php endfor; ?>
                                                        (<?php echo $item['rating']; ?>)
                                                    <?php else: echo '-'; endif; ?>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p style="text-align: center; padding-top: 20px;">Belum ada data rating tamu.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        break; 

                    case 'lihat_pengaduan':
                        ?>
                        <h2><i class="fas fa-list-check"></i> Daftar Pengaduan Masuk</h2>
                        <p>Menampilkan semua pengaduan yang telah dicatat.</p>
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

                    case 'lihat_tamu':
                    default:
                        ?>
                        <h2><i class="fas fa-list-alt"></i> Daftar Tamu Masuk</h2>
                        <p>Rata-rata Rating Kepuasan: <strong><?php echo $rata_rata_rating; ?> / 5</strong></p>
                        <?php if (isset($_SESSION['admin_message'])) { $msg_type = $_SESSION['admin_message_type'] ?? 'sukses'; echo '<p class="pesan ' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($_SESSION['admin_message']) . '</p>'; unset($_SESSION['admin_message'], $_SESSION['admin_message_type']); } ?> 
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th><th>Nama</th><th>Status</th><th>No Antrian</th>
                                        <th>Keperluan</th><th>Pekerjaan</th><th>Rating</th>
                                        <th>Waktu Input</th><th>Aksi</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($query_tamu && mysqli_num_rows($query_tamu) > 0): ?>
                                        <?php while($tamu = mysqli_fetch_assoc($query_tamu)):
                                            $statusClass = (!empty($tamu['status_kehadiran']) && $tamu['status_kehadiran'] == 'Hadir') ? 'status-hadir' : 'status-belum';
                                            $statusText = ($statusClass == 'status-hadir') ? 'Hadir' : 'Belum Scan';
                                            $no_antrian_display = '-';
                                            if (!empty($tamu['tanggal_antrian']) && $tamu['tanggal_antrian'] == date('Y-m-d') && !empty($tamu['no_antrian_hari_ini'])) {
                                                $no_antrian_display = $tamu['no_antrian_hari_ini'];
                                            }
                                            $qrDataTamu = "ID Tamu: " . $tamu['id'] . "\nNama: " . htmlspecialchars($tamu['nama']) . "\nPekerjaan: " . htmlspecialchars($tamu['pekerjaan'] ?? '-') ."\nWaktu: " . date('d/m/Y H:i', strtotime($tamu['waktu_input'])) . "\n"; 
                                            if (!empty($tamu['keperluan'])) $qrDataTamu .= "Keperluan: " . htmlspecialchars($tamu['keperluan']);
                                            $nama_tamu_bersih_untuk_file = preg_replace('/[^A-Za-z0-9_\-]/', '_', $tamu['nama']);
                                            $namaFileQr = 'QRCode_Tamu_' . $tamu['id'] . '_' . $nama_tamu_bersih_untuk_file . '.png';
                                        ?>
                                        <tr>
                                            <td><?php echo $tamu['id']; ?></td>
                                            <td><?php echo htmlspecialchars($tamu['nama']); ?></td>
                                            <td class="<?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                                <?php if($statusClass == 'status-hadir' && !empty($tamu['waktu_scan_masuk'])): ?>
                                                    <br><small>(<?php echo date('H:i', strtotime($tamu['waktu_scan_masuk'])); ?>)</small>
                                                <?php endif; ?>
                                            </td>
                                            <td style="text-align: center; font-weight: bold;"><?php echo $no_antrian_display; ?></td>
                                            <td><?php echo htmlspecialchars($tamu['keperluan'] ?? '-'); ?></td> 
                                            <td><?php echo htmlspecialchars($tamu['pekerjaan'] ?? '-'); ?></td> 
                                            <td class="rating-stars-small" style="text-align:center;"> 
                                                <?php if (!empty($tamu['rating'])): ?>
                                                    <?php for($i_star = 1; $i_star <= 5; $i_star++): ?>
                                                        <i class="fa<?php echo ($i_star <= $tamu['rating']) ? 's' : 'r'; ?> fa-star"></i>
                                                    <?php endfor; ?>
                                                    (<?php echo $tamu['rating']; ?>)
                                                <?php else: echo '-'; endif; ?>
                                            </td>
                                            <td><?php echo date('d M Y, H:i', strtotime($tamu['waktu_input'])); ?></td>
                                            <td style="white-space: nowrap;"> 
                                                 <button type="button" class="btn btn-secondary btn-sm btn-view-qr" 
                                                         data-qrdata="<?php echo htmlspecialchars($qrDataTamu); ?>" 
                                                         data-filename="<?php echo htmlspecialchars($namaFileQr); ?>" 
                                                         data-namatamu="<?php echo htmlspecialchars($tamu['nama']); ?>" 
                                                         title="Lihat QR">
                                                     <i class="fas fa-qrcode"></i>
                                                 </button>
                                                 <button type="button" class="btn btn-warning btn-sm btn-edit-tamu" 
                                                    data-id="<?php echo $tamu['id'];?>" 
                                                    data-nama="<?php echo htmlspecialchars($tamu['nama']);?>" 
                                                    data-alamat="<?php echo htmlspecialchars($tamu['alamat']??'');?>" 
                                                    data-keperluan="<?php echo htmlspecialchars($tamu['keperluan']??'');?>" 
                                                    data-pekerjaan="<?php echo htmlspecialchars($tamu['pekerjaan']??'');?>" 
                                                    data-notelp="<?php echo htmlspecialchars($tamu['no_telp']??'');?>" 
                                                    data-rating="<?php echo htmlspecialchars($tamu['rating']??'');?>" 
                                                    title="Edit Data Tamu">
                                                     <i class="fas fa-edit"></i>
                                                 </button>
                                                 <form action="hapus_tamu.php" method="post" 
                                                       onsubmit="return confirm('Yakin hapus tamu \'<?php echo htmlspecialchars(addslashes($tamu['nama'])); ?>\' (ID: <?php echo $tamu['id']; ?>)?');" 
                                                       style="display:inline; margin-left: 5px;">
                                                     <input type="hidden" name="id_tamu" value="<?php echo $tamu['id']; ?>">
                                                     <button type="submit" name="hapus" class="btn btn-hapus btn-sm" title="Hapus">
                                                         <i class="fas fa-trash-alt"></i>
                                                     </button>
                                                 </form>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="9" style="text-align: center;">Belum ada data tamu.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                        break; 
                } // Akhir switch
                ?>
            </div> 
        </main> 
    </div> 

    <div id="qrModal" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn">&times;</span><h3 id="modalQrTitle">QR Code Tamu</h3>
            <div class="modal-qr-display"><div id="modal-qrcode-container"></div></div>
            <p><small>QR Code ini berisi detail tamu.</small></p>
            <button id="modalDownloadQrBtn" class="btn btn-secondary"><i class="fas fa-download"></i> Download</button>
        </div>
    </div> 
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn">&times;</span> 
            <h3>Edit Data Tamu</h3><hr>
            <div id="editPesan" class="pesan" style="display: none;"></div>
            <form id="editTamuForm" class="form-container">
                <input type="hidden" id="edit_id_tamu" name="id_tamu">
                <div class="form-group"><label for="edit_nama">Nama:</label><input type="text" id="edit_nama" name="nama" required></div>
                <div class="form-group"><label for="edit_alamat">Alamat:</label><textarea id="edit_alamat" name="alamat" rows="3"></textarea></div>
                <div class="form-group">
                    <label for="edit_keperluan">Keperluan:</label>
                    <select id="edit_keperluan" name="keperluan" required>
                        <option value="">-- Pilih Keperluan --</option>
                        <?php foreach ($opsi_keperluan as $value_opt => $text_opt): ?>
                            <option value="<?php echo htmlspecialchars($value_opt); ?>"><?php echo htmlspecialchars($text_opt); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_pekerjaan">Pekerjaan/Role:</label>
                    <select id="edit_pekerjaan" name="pekerjaan">
                        <option value="">-- Pilih Pekerjaan/Role --</option>
                        <?php foreach ($opsi_pekerjaan as $value_opt => $text_opt): ?>
                            <option value="<?php echo htmlspecialchars($value_opt); ?>"><?php echo htmlspecialchars($text_opt); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group"><label for="edit_no_telp">No. Telp:</label><input type="text" id="edit_no_telp" name="no_telp"></div>
                <div class="form-group">
                    <label>Rating:</label>
                    <div class="rating-input" id="edit_rating_input">
                        <input type="radio" id="star5_edit" name="rating_edit" value="5"><label for="star5_edit" title="Sangat Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4_edit" name="rating_edit" value="4"><label for="star4_edit" title="Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3_edit" name="rating_edit" value="3"><label for="star3_edit" title="Cukup Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2_edit" name="rating_edit" value="2"><label for="star2_edit" title="Kurang Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1_edit" name="rating_edit" value="1"><label for="star1_edit" title="Tidak Puas"><i class="fas fa-star"></i></label>
                        <button type="button" id="reset_rating_edit" class="btn btn-sm btn-secondary" style="margin-left: 10px; padding: 2px 5px; font-size:0.7em;" title="Hapus Rating">Reset</button>
                    </div>
                </div>
                <div style="text-align: right; margin-top:15px;">
                    <button type="button" class="btn btn-secondary modal-close-btn-manual">Batal</button> 
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div> 
    
    <div id="detailPengaduanModal" class="modal">
         <div class="modal-content" style="text-align: left;">
            <span class="modal-close-btn">&times;</span><h3>Detail Pengaduan</h3><hr>
            <div id="detailPengaduanContent"><p><i>Memuat detail...</i></p></div><hr>
            <h4>Tindak Lanjut</h4>
            <form id="formTindakLanjut" style="margin-top: 10px;">
                <input type="hidden" id="detail_id_pengaduan" name="id_pengaduan">
                <div class="form-group"><label for="detail_status">Ubah Status:</label><select id="detail_status" name="status_pengaduan"><option value="Baru">Baru</option><option value="Diproses">Diproses</option><option value="Selesai">Selesai</option><option value="Ditolak">Ditolak</option></select></div>
                <div class="form-group"><label for="detail_catatan">Catatan Tindak Lanjut:</label><textarea id="detail_catatan" name="catatan_tindaklanjut" rows="4"></textarea></div>
                <div style="text-align:right;"><button type="submit" class="btn btn-success">Simpan Tindak Lanjut</button></div>
            </form>
            <div id="tindakLanjutPesan" class="pesan" style="display: none; margin-top: 10px;"></div>
        </div>
    </div> 

    <?php if (isset($koneksi) && $koneksi instanceof mysqli) { mysqli_close($koneksi); } ?>

    <script>
        function htmlspecialchars(str) { 
            const map = {'&': '&amp;','<': '&lt;','>': '&gt;','"': '&quot;',"'": '&#039;'}; 
            if (typeof str !== 'string') return ''; 
            return str.replace(/[&<>"']/g, m => map[m]); 
        }
        function nl2br(str) { 
            if (typeof str === 'undefined' || str === null) return ''; 
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1<br>$2'); 
        }

        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = '<?php echo $page; ?>'; 
            
            // --- Inisialisasi Chart.js ---
            if (currentPage === 'rating') { 
                const ratingBarCtx = document.getElementById('ratingBarChart');
                if (ratingBarCtx) { 
                    const chartLabels = <?php echo json_encode($chart_labels ?? []); ?>; 
                    const chartData = <?php echo json_encode($chart_data ?? []); ?>; 
                    const chartPercentages = <?php echo json_encode($chart_percentages ?? []); ?>;
                    const chartBgColors = <?php echo json_encode($chart_bg_colors ?? []); ?>; 
                    const chartBorderColors = <?php echo json_encode($chart_border_colors ?? []); ?>; 
                    const totalRatings = <?php echo $total_ratings ?? 0; ?>;
                    new Chart(ratingBarCtx.getContext('2d'), { 
                        type: 'bar', 
                        data: { 
                            labels: chartLabels, 
                            datasets: [{ 
                                label: 'Jumlah Rating', 
                                data: chartData, 
                                backgroundColor: chartBgColors, 
                                borderColor: chartBorderColors, 
                                borderWidth: 1 
                            }] 
                        }, 
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false, 
                            plugins: { 
                                title: { display: true, text: 'Distribusi Jumlah Rating (Total: ' + totalRatings + ' Penilai)'}, 
                                tooltip: { callbacks: { label: function(tooltipItem) { return tooltipItem.dataset.label + ': ' + tooltipItem.parsed.y + ' (' + chartPercentages[tooltipItem.dataIndex] + '%)';} } }, 
                                legend: { display: false } 
                            }, 
                            scales: { 
                                y: { beginAtZero: true, title: { display: true, text: 'Jumlah Tamu' }, ticks: { stepSize: 1 } }, 
                                x: { title: { display: true, text: 'Rating Bintang' } } 
                            } 
                        } 
                    }); 
                }
                const ratingPieCtx = document.getElementById('ratingPieChart'); 
                if (ratingPieCtx) {
                    const chartLabelsPie = <?php echo json_encode($chart_labels ?? []); ?>; 
                    const chartDataPie = <?php echo json_encode($chart_data ?? []); ?>; 
                    const chartBgColorsPie = <?php echo json_encode($chart_bg_colors ?? []); ?>; 
                    const chartBorderColorsPie = <?php echo json_encode($chart_border_colors ?? []); ?>;
                    new Chart(ratingPieCtx.getContext('2d'), {
                        type: 'pie', 
                        data: { 
                            labels: chartLabelsPie, 
                            datasets: [{ 
                                label: 'Distribusi Rating', 
                                data: chartDataPie, 
                                backgroundColor: chartBgColorsPie, 
                                borderColor: chartBorderColorsPie, 
                                borderWidth: 1 
                            }] 
                        },
                        options: { 
                            responsive: true, 
                            maintainAspectRatio: false, 
                            plugins: { 
                                title: { display: true, text: 'Persentase Distribusi Rating'}, 
                                tooltip: { callbacks: { label: function(tooltipItem) { let sum = tooltipItem.dataset.data.reduce((a, b) => a + b, 0); let percentage = sum > 0 ? (tooltipItem.raw / sum * 100).toFixed(1) : 0; return tooltipItem.label + ': ' + tooltipItem.raw + ' (' + percentage + '%)'; } } }, 
                                legend: { position: 'top' } 
                            } 
                        }
                    });
                }
            } // Akhir if currentPage === 'rating'

            // --- Logika Modal QR dan Edit Tamu (di halaman lihat_tamu) ---
            if (currentPage === 'lihat_tamu' || currentPage === '') { 
                // ** Modal QR View **
                const qrModal=document.getElementById('qrModal');
                const modalQrContainer=document.getElementById('modal-qrcode-container');
                const modalQrTitle=document.getElementById('modalQrTitle');
                const modalDownloadQrBtn=document.getElementById('modalDownloadQrBtn');
                const closeQrModalBtn=qrModal?qrModal.querySelector('.modal-close-btn'):null;
                const viewQrButtons=document.querySelectorAll('.btn-view-qr');
                let qrCodeInstanceModal=null;
                
                function openQrModal(qrData,filename,namaTamu){
                    if(!qrModal||!modalQrContainer||!modalQrTitle||!modalDownloadQrBtn)return;
                    modalQrTitle.textContent='QR Code: '+htmlspecialchars(namaTamu);
                    modalQrContainer.innerHTML='';
                    modalDownloadQrBtn.setAttribute('data-filename',filename);
                    try{
                        qrCodeInstanceModal=new QRCode(modalQrContainer,{text:qrData,width:250,height:250,correctLevel:QRCode.CorrectLevel.L});
                    }catch(e){
                        modalQrContainer.innerHTML='Gagal buat QR.';
                    }
                    qrModal.style.display='block';
                } 
                function closeQrModal(){
                    if(qrModal){
                        qrModal.style.display='none';
                        if(modalQrContainer)modalQrContainer.innerHTML='';
                        qrCodeInstanceModal=null;
                    }
                } 
                viewQrButtons.forEach(button=>{
                    button.addEventListener('click',function(){
                        const qrData=this.getAttribute('data-qrdata');
                        const filename=this.getAttribute('data-filename');
                        const namaTamu=this.getAttribute('data-namatamu');
                        if(qrData&&filename&&namaTamu){openQrModal(qrData,filename,namaTamu);}
                    });
                }); 
                if(closeQrModalBtn)closeQrModalBtn.addEventListener('click',closeQrModal); 
                if(modalDownloadQrBtn){
                    modalDownloadQrBtn.addEventListener('click',function(e){
                        e.preventDefault();
                        const filename=this.getAttribute('data-filename')||'qrcode.png';
                        const qrCanvas=modalQrContainer.querySelector('canvas');
                        let dataUrl=null;
                        if(qrCanvas){try{dataUrl=qrCanvas.toDataURL('image/png');}catch(err){alert("Gagal unduh QR.");return;}}
                        if(dataUrl){const link=document.createElement('a');link.href=dataUrl;link.download=filename;document.body.appendChild(link);link.click();document.body.removeChild(link);}
                        else{alert('Gagal data QR modal.');}
                    });
                } 
                if(qrModal)window.addEventListener('click',function(event){if(event.target==qrModal){closeQrModal();}});
                
                // ** Modal Edit Tamu **
                const editModal=document.getElementById('editModal'); 
                const editForm=document.getElementById('editTamuForm'); 
                const editPesan=document.getElementById('editPesan'); 
                const editIdTamu=document.getElementById('edit_id_tamu'); 
                const editNama=document.getElementById('edit_nama'); 
                const editAlamat=document.getElementById('edit_alamat'); 
                const editKeperluanSelect=document.getElementById('edit_keperluan'); 
                const editPekerjaan=document.getElementById('edit_pekerjaan'); 
                const editNoTelp=document.getElementById('edit_no_telp'); 
                const editRatingInputs=document.querySelectorAll('input[name="rating_edit"]'); 
                const editCloseBtns=editModal?editModal.querySelectorAll('.modal-close-btn,.modal-close-btn-manual'):[]; 
                const editButtons=document.querySelectorAll('.btn-edit-tamu'); 
                const resetRatingBtn=document.getElementById('reset_rating_edit');
                
                function openEditModal(data){
                    if(!editModal||!editForm||!editPesan)return;
                    editPesan.style.display='none';editPesan.textContent='';editPesan.className='pesan';
                    editIdTamu.value=data.id||'';editNama.value=data.nama||'';editAlamat.value=data.alamat||'';
                    if(editKeperluanSelect)editKeperluanSelect.value=data.keperluan||'';
                    if(editPekerjaan)editPekerjaan.value=data.pekerjaan||'';
                    editNoTelp.value=data.notelp||'';
                    editRatingInputs.forEach(radio=>{radio.checked=(data.rating&&radio.value===data.rating);});
                    editModal.style.display='block';
                } 
                function closeEditModal(){if(editModal)editModal.style.display='none';}
                
                editButtons.forEach(button=>{
                    button.addEventListener('click',function(){
                        const data={id:this.getAttribute('data-id'),nama:this.getAttribute('data-nama'),alamat:this.getAttribute('data-alamat'),keperluan:this.getAttribute('data-keperluan'),pekerjaan:this.getAttribute('data-pekerjaan'),notelp:this.getAttribute('data-notelp'),rating:this.getAttribute('data-rating')};
                        openEditModal(data);
                    });
                }); 
                editCloseBtns.forEach(btn=>btn.addEventListener('click',closeEditModal)); 
                if(resetRatingBtn){resetRatingBtn.addEventListener('click',function(){editRatingInputs.forEach(radio=>radio.checked=false);});} 
                if(editModal){window.addEventListener('click',function(event){if(event.target==editModal){closeEditModal();}});}
                
                if(editForm){
                    editForm.addEventListener('submit',function(e){
                        e.preventDefault();
                        editPesan.textContent='Menyimpan...';editPesan.className='pesan warning';editPesan.style.display='block';
                        const submitButton=editForm.querySelector('button[type="submit"]');
                        if(submitButton)submitButton.disabled=true;
                        const selectedRating=document.querySelector('input[name="rating_edit"]:checked');
                        const ratingValue=selectedRating?selectedRating.value:null;
                        const formData={id_tamu:editIdTamu.value,nama:editNama.value,alamat:editAlamat.value,keperluan:editKeperluanSelect.value,pekerjaan:editPekerjaan.value,no_telp:editNoTelp.value,rating:ratingValue};
                        
                        fetch('proses_update_tamu.php',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(formData)})
                        .then(response=>{
                            if(!response.ok){return response.text().then(text=>{throw new Error(`Server error (${response.status}): ${text||'Gagal.'}`);});}
                            return response.json();
                        })
                        .then(data=>{
                            editPesan.textContent=data.message||'Selesai.';
                            editPesan.className='pesan '+(data.success?'sukses':'error');
                            if(data.success){
                                setTimeout(()=>{
                                    closeEditModal();
                                    alert("Data diperbarui. Silakan refresh halaman ini (F5).");
                                    window.location.reload();
                                },1500);
                            }
                        })
                        .catch(error=>{
                            editPesan.textContent='Gagal: '+error.message;
                            editPesan.className='pesan error';
                        })
                        .finally(()=>{
                            if(submitButton)submitButton.disabled=false;
                        });
                    });
                } // Akhir if(editForm)
            } // Akhir if currentPage === 'lihat_tamu'

            // --- Logika Modal Detail Pengaduan ---
            if (currentPage === 'lihat_pengaduan') { 
                const modal=document.getElementById('detailPengaduanModal');
                const modalContent=document.getElementById('detailPengaduanContent');
                const closeModalBtn=modal?modal.querySelector('.modal-close-btn'):null;
                const detailButtons=document.querySelectorAll('.btn-detail-pengaduan');
                const formTindakLanjut=document.getElementById('formTindakLanjut');
                const detailIdPengaduan=document.getElementById('detail_id_pengaduan');
                const detailStatusSelect=document.getElementById('detail_status');
                const detailCatatanTextarea=document.getElementById('detail_catatan');
                const tindakLanjutPesan=document.getElementById('tindakLanjutPesan');
                
                function closeDetailModal(){if(modal)modal.style.display='none';}
                
                detailButtons.forEach(button=>{
                    button.addEventListener('click',async function(){
                        const id=this.getAttribute('data-id');
                        if(!id||!modal||!modalContent)return;
                        modalContent.innerHTML='<p><i>Memuat...</i></p>';
                        if(tindakLanjutPesan)tindakLanjutPesan.style.display='none';
                        modal.style.display='block';
                        if(detailIdPengaduan)detailIdPengaduan.value=id;
                        try{
                            const response=await fetch(`ambil_detail_pengaduan.php?id=${id}`);
                            if(!response.ok){let errorText=await response.text();throw new Error(`Server error (${response.status}): Gagal.` );}
                            const data=await response.json();
                            if(data.success&&data.pengaduan){
                                const p=data.pengaduan;
                                const waktuLaporFormatted=p.waktu_lapor?new Date(p.waktu_lapor).toLocaleString('id-ID',{dateStyle:'medium',timeStyle:'short'}):'-';
                                const waktuKejadianFormatted=p.waktu_kejadian?new Date(p.waktu_kejadian).toLocaleString('id-ID',{dateStyle:'medium',timeStyle:'short'}):'-';
                                let lampiranHtml='-';
                                if(p.lampiran){const namaFile=p.lampiran.split('/').pop();lampiranHtml=`<a href="${htmlspecialchars(p.lampiran)}" target="_blank">${htmlspecialchars(namaFile)}</a>`;}
                                modalContent.innerHTML=`<p><strong>ID:</strong> ${p.id_pengaduan}</p><p><strong>Waktu Lapor:</strong> ${waktuLaporFormatted}</p><p><strong>Judul:</strong> ${htmlspecialchars(p.judul_pengaduan)}</p><p><strong>Pelapor:</strong> ${htmlspecialchars(p.nama_pelapor)} (${htmlspecialchars(p.kontak_pelapor||'-')})</p><p><strong>Kategori:</strong> ${htmlspecialchars(p.kategori_pengaduan)}</p><p><strong>Detail:</strong><br>${nl2br(htmlspecialchars(p.detail_pengaduan))}</p><p><strong>Lokasi:</strong> ${htmlspecialchars(p.lokasi_kejadian||'-')}</p><p><strong>Waktu Kejadian:</strong> ${waktuKejadianFormatted}</p><p><strong>Lampiran:</strong> ${lampiranHtml}</p><p><strong>Status:</strong> ${htmlspecialchars(p.status_pengaduan)}</p><p><strong>Catatan:</strong><br>${nl2br(htmlspecialchars(p.catatan_tindaklanjut||'-'))}</p><p><strong>Dicatat Oleh:</strong> ${htmlspecialchars(p.nama_pencatat||'N/A')}</p>`;
                                if(detailStatusSelect)detailStatusSelect.value=p.status_pengaduan||'Baru';
                                if(detailCatatanTextarea)detailCatatanTextarea.value=p.catatan_tindaklanjut||'';
                            }else{
                                modalContent.innerHTML=`<p class="pesan error">${htmlspecialchars(data.error||'Gagal.')}</p>`;
                            }
                        }catch(error){
                            console.error("Fetch detail err:",error);
                            modalContent.innerHTML=`<p class="pesan error">Error ambil detail. (${htmlspecialchars(error.message)})</p>`;
                        }
                    });
                });
                if(closeModalBtn)closeModalBtn.addEventListener('click',closeDetailModal);
                if(modal)window.addEventListener('click',function(event){if(event.target==modal)closeDetailModal();});
                
                if(formTindakLanjut&&tindakLanjutPesan){
                    formTindakLanjut.addEventListener('submit',async function(e){
                        e.preventDefault();
                        tindakLanjutPesan.style.display='none';tindakLanjutPesan.textContent='Menyimpan...';tindakLanjutPesan.className='pesan warning';tindakLanjutPesan.style.display='block';
                        const submitButton=formTindakLanjut.querySelector('button[type="submit"]');
                        if(submitButton)submitButton.disabled=true;
                        const formData=new FormData(formTindakLanjut);
                        try{
                            const response=await fetch('proses_pengaduan_update.php',{method:'POST',body:formData});
                            if(!response.ok){let errorText=await response.text();throw new Error(`Server error (${response.status}): ${errorText||response.statusText}`);}
                            const data=await response.json();
                            tindakLanjutPesan.textContent=data.message||'Selesai.';
                            tindakLanjutPesan.className='pesan '+(data.success?'sukses':'error');
                            if(data.success){
                                setTimeout(()=>{
                                    alert("Tindak lanjut disimpan. Refresh (F5).");
                                    window.location.reload();
                                },1000);
                            }
                        }catch(error){
                            console.error("Follow-up err:",error);
                            tindakLanjutPesan.textContent='Gagal: '+error.message;
                            tindakLanjutPesan.className='pesan error';
                        }finally{
                            if(submitButton)submitButton.disabled=false;
                            tindakLanjutPesan.style.display='block';
                        }
                    });
                } // Akhir if(formTindakLanjut && tindakLanjutPesan)
            } // Akhir if currentPage === 'lihat_pengaduan'
            
            // === Kode Toggle Sidebar Responsif === 
            const sidebar = document.querySelector('.admin-sidebar');
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const sidebarOverlay = document.querySelector('.sidebar-overlay'); 
            if (sidebar && sidebarToggleBtn) { 
                sidebarToggleBtn.addEventListener('click', function() { 
                    sidebar.classList.toggle('open'); 
                }); 
            } else { 
                // console.error("Sidebar/Toggle button tidak ditemukan!"); // Komentari jika tidak perlu
            }
            if (sidebar && sidebarOverlay) { 
                sidebarOverlay.addEventListener('click', function() { 
                    sidebar.classList.remove('open'); 
                }); 
            }
            // === Akhir Kode Toggle Sidebar ===

        }); // Akhir DOMContentLoaded
    </script>

</body> 
</html>