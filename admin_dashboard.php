<?php
session_start();
if (!isset($_SESSION['id_petugas'])) {
    header('Location: login.php');
    exit();
}
require_once 'koneksi.php';
$page = $_GET['page'] ?? 'lihat_tamu';
$opsi_keperluan = ['Koordinasi AntarInstansi' => 'Koordinasi AntarInstansi', 'Penawaran Kerja Sama' => 'Penawaran Kerja Sama', 'Pelayanan Statistik Terpadu' => 'Pelayanan Statistik Terpadu', 'Rapat/Pertemuan' => 'Rapat/Pertemuan', 'Diskusi/Koordinasi Kegiatan Statistik' => 'Diskusi/Koordinasi Kegiatan Statistik', 'Lainnya' => 'Lainnya'];
$opsi_pekerjaan = ['Aparat Desa/Kelurahan' => 'Aparat Desa/Kelurahan', 'Pegawai/Guru' => 'Pegawai/Guru', 'Mengurus Rumah Tangga' => 'Mengurus Rumah Tangga', 'Mitra BPS' => 'Mitra BPS', 'Wirausaha' => 'Wirausaha', 'Pelajar/Mahasiswa' => 'Pelajar/Mahasiswa', 'Honorer' => 'Honorer',  'Wiraswasta' => 'Wiraswasta',  'Freelance' => 'Freelance',  'Buruh' => 'Buruh',  'Lainnya' => 'Lainnya'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin</title>
    <link rel="stylesheet" href="desain_utama.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <style>
        :root {
            --sidebar-bg: #2c3e50;
            --sidebar-text-color: #ecf0f1;
            --sidebar-active-bg: #3498db;
            --sidebar-hover-bg: #34495e;
            --header-bg: #ffffff;
            --content-bg: #ecf0f5;
            --border-color: #d2d6de;
            --star-color: #f39c12;
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        body.admin-body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--content-bg);
            font-size: 14px;
        }

        * {
            box-sizing: border-box;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 240px;
            flex-shrink: 0;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text-color);
            height: 100vh;
            position: sticky;
            top: 0;
            transition: left 0.3s;
            overflow-y: auto;
            z-index: 1010;
        }

        .sidebar-header {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #3a536c;
        }

        .sidebar-header h3 {
            margin: 0;
            color: #fff;
            font-size: 1.2rem;
        }

        .sidebar-nav ul {
            list-style: none;
            padding: 10px 0;
            margin: 0;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--sidebar-text-color);
            font-size: 0.95rem;
            transition: background-color 0.2s;
        }

        .sidebar-nav li a:hover {
            background-color: var(--sidebar-hover-bg);
        }

        .sidebar-nav li.active>a {
            background-color: var(--sidebar-active-bg);
            font-weight: 600;
        }

        .sidebar-nav i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .admin-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .admin-content-header {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .admin-content-header h1 {
            margin: 0;
            flex-grow: 1;
            font-size: 1.4rem;
        }

        .admin-info {
            margin-left: auto;
        }

        .content-area {
            padding: 25px;
        }

        .page-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .sidebar-toggle-button {
            display: none;
            background: none;
            border: none;
            font-size: 1.6rem;
            cursor: pointer;
            margin-right: 15px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #0009;
            z-index: 1040;
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                position: fixed;
                left: -250px;
            }

            .admin-sidebar.open {
                left: 0;
            }

            .sidebar-toggle-button {
                display: block;
            }

            .admin-sidebar.open+.sidebar-overlay {
                display: block;
            }
        }

        .pesan {
            padding: 12px 18px;
            margin: 20px 0;
            border-radius: 6px;
            border: 1px solid;
        }

        .pesan.sukses {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }

        .pesan.error {
            background-color: #f8d7da;
            border-color: #f5c2c7;
            color: #842029;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }

        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-utama {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: #fff;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            color: #fff;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 0.8rem;
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 15px;
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid var(--border-color);
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f8f9fa;
        }

        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-start;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            color: #ddd;
            font-size: 1.5em;
            cursor: pointer;
            padding: 0 0.1em;
        }

        .rating-input input:checked~label,
        .rating-input label:hover,
        .rating-input label:hover~label {
            color: var(--star-color);
        }

        .rating-stars-small i {
            color: var(--star-color);
            font-size: 0.9em;
        }

        .status-hadir {
            color: green;
            font-weight: bold;
        }

        .status-belum {
            color: grey;
        }

        td:last-child {
            white-space: nowrap;
            text-align: center;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1051;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: #0009;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 25px;
            border: 1px solid #aaa;
            width: 90%;
            max-width: 550px;
            border-radius: 8px;
            position: relative;
        }

        .modal-close-btn {
            color: #aaa;
            position: absolute;
            top: 15px;
            right: 25px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-qr-display {
            text-align: center;
        }

        #modal-qrcode-container {
            margin: 15px auto;
            padding: 5px;
            border: 1px solid #ccc;
            display: inline-block;
        }

        .monitoring-top-row {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            margin-bottom: 25px;
            align-items: stretch;
        }

        .summary-card {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-color);
            flex-basis: 250px;
            flex-grow: 0;
        }

        .summary-card h4 {
            margin: 0 0 10px 0;
            color: var(--secondary-color);
            font-weight: 500;
            font-size: 1rem;
        }

        .summary-card .count {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .main-chart-container {
            flex-grow: 1;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: relative;
            border: 1px solid var(--border-color);
            min-height: 350px;
        }

        .chart-grid {
            display: grid;
            gap: 25px;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        }

        .chart-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: relative;
            border: 1px solid var(--border-color);
            height: 400px;
        }
    </style>
</head>

<body class="admin-body">
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h3>SIVITAS</h3>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li class="<?php echo ($page == 'lihat_tamu' || $page == '') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=lihat_tamu"><i class="fas fa-list-alt"></i> <span>Daftar Tamu</span></a></li>
                    <li class="<?php echo ($page == 'master_tamu') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=master_tamu"><i class="fas fa-users"></i> <span>Master</span></a></li>
                    <li class="<?php echo ($page == 'monitoring') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=monitoring"><i class="fas fa-chart-line"></i> <span>Monitoring</span></a></li>
                    <li class="<?php echo ($page == 'rating') ? 'active' : ''; ?>"><a href="admin_dashboard.php?page=rating"><i class="fas fa-star-half-alt"></i> <span>Rating Pelayanan</span></a></li>
                    <li class="<?php echo ($page == 'lihat_pengaduan') ? 'active' : ''; ?>"> <a href="admin_dashboard.php?page=lihat_pengaduan"><i class="fas fa-list-check"></i> <span>Lihat Pengaduan</span></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
                </ul>
            </nav>
        </aside>
        <div class="sidebar-overlay"></div>
        <main class="admin-content">
            <header class="admin-content-header">
                <button id="sidebarToggleBtn" class="sidebar-toggle-button"><i class="fas fa-bars"></i></button>
                <h1>DASHBOARD</h1>
                <div class="admin-info"><span><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['nama_lengkap'] ?? ''); ?></span></div>
            </header>
            <div class="content-area">
                <?php if (isset($_SESSION['admin_message'])): ?>
                    <p class="pesan <?php echo $_SESSION['admin_message_type'] ?? 'sukses'; ?>"><?php echo htmlspecialchars($_SESSION['admin_message']); ?></p>
                    <?php unset($_SESSION['admin_message'], $_SESSION['admin_message_type']); ?>
                <?php endif; ?>

                <?php
                // ===================================================================
                // SEMUA KONTEN HALAMAN LENGKAP ADA DI SINI
                // ===================================================================
                switch ($page) {
                    case 'monitoring':
                        $selected_year = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
                        $total_tamu = 0;
                        if ($stmt_total = mysqli_prepare($koneksi, "SELECT COUNT(id) as total FROM tamu WHERE YEAR(waktu_input) = ?")) {
                            mysqli_stmt_bind_param($stmt_total, "i", $selected_year);
                            mysqli_stmt_execute($stmt_total);
                            $result_total = mysqli_stmt_get_result($stmt_total);
                            if ($row_total = mysqli_fetch_assoc($result_total)) {
                                $total_tamu = $row_total['total'];
                            }
                            mysqli_stmt_close($stmt_total);
                        }
                        $monthly_counts = array_fill(1, 12, 0);
                        if ($stmt_monthly = mysqli_prepare($koneksi, "SELECT MONTH(waktu_input) as bulan, COUNT(id) as jumlah FROM tamu WHERE YEAR(waktu_input) = ? GROUP BY MONTH(waktu_input)")) {
                            mysqli_stmt_bind_param($stmt_monthly, "i", $selected_year);
                            mysqli_stmt_execute($stmt_monthly);
                            $result_monthly = mysqli_stmt_get_result($stmt_monthly);
                            while ($row_monthly = mysqli_fetch_assoc($result_monthly)) {
                                $monthly_counts[$row_monthly['bulan']] = $row_monthly['jumlah'];
                            }
                            mysqli_stmt_close($stmt_monthly);
                        }
                        $keperluan_labels = [];
                        $keperluan_data = [];
                        if ($stmt_keperluan = mysqli_prepare($koneksi, "SELECT keperluan, COUNT(id) as jumlah FROM tamu WHERE YEAR(waktu_input) = ? AND keperluan IS NOT NULL AND keperluan != '' GROUP BY keperluan ORDER BY jumlah DESC")) {
                            mysqli_stmt_bind_param($stmt_keperluan, "i", $selected_year);
                            mysqli_stmt_execute($stmt_keperluan);
                            $result_keperluan = mysqli_stmt_get_result($stmt_keperluan);
                            while ($row = mysqli_fetch_assoc($result_keperluan)) {
                                $keperluan_labels[] = $row['keperluan'];
                                $keperluan_data[] = $row['jumlah'];
                            }
                            mysqli_stmt_close($stmt_keperluan);
                        }
                        $pekerjaan_labels = [];
                        $pekerjaan_data = [];
                        if ($stmt_pekerjaan = mysqli_prepare($koneksi, "SELECT pekerjaan, COUNT(id) as jumlah FROM tamu WHERE YEAR(waktu_input) = ? AND pekerjaan IS NOT NULL AND pekerjaan != '' GROUP BY pekerjaan ORDER BY jumlah DESC")) {
                            mysqli_stmt_bind_param($stmt_pekerjaan, "i", $selected_year);
                            mysqli_stmt_execute($stmt_pekerjaan);
                            $result_pekerjaan = mysqli_stmt_get_result($stmt_pekerjaan);
                            while ($row = mysqli_fetch_assoc($result_pekerjaan)) {
                                $pekerjaan_labels[] = $row['pekerjaan'];
                                $pekerjaan_data[] = $row['jumlah'];
                            }
                            mysqli_stmt_close($stmt_pekerjaan);
                        }
                ?>
                        <h2><i class="fas fa-chart-line"></i> Monitoring Tahun <?php echo $selected_year; ?></h2>
                        <div class="page-controls">
                            <form method="GET"><input type="hidden" name="page" value="monitoring"><label for="tahun_filter">Pilih Tahun:</label><select name="tahun" id="tahun_filter" onchange="this.form.submit()"><?php for ($y = date('Y'); $y >= 2023; $y--): ?><option value="<?php echo $y; ?>" <?php if ($y == $selected_year) echo 'selected'; ?>><?php echo $y; ?></option><?php endfor; ?></select></form>
                        </div>
                        <div class="monitoring-top-row">
                            <div class="summary-card">
                                <h4>Total Kunjungan Tamu</h4>
                                <div class="count"><?php echo $total_tamu; ?></div>
                            </div>
                            <div class="main-chart-container">
                                <canvas id="monthlyGuestChart"></canvas>
                            </div>
                        </div>
                        <div class="chart-grid">
                            <div class="chart-container"><canvas id="keperluanPieChart"></canvas></div>
                            <div class="chart-container"><canvas id="pekerjaanPieChart"></canvas></div>
                        </div>
                    <?php
                        break;
                    case 'rating':
                        $selected_year = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');
                        $chart_labels = [];
                        $chart_data = [];
                        $total_ratings = 0;
                        $sql_rating = "SELECT rating, COUNT(*) as jumlah FROM tamu WHERE rating > 0 AND YEAR(waktu_input) = ? GROUP BY rating";
                        $stmt_rating = mysqli_prepare($koneksi, $sql_rating);
                        if ($stmt_rating) {
                            mysqli_stmt_bind_param($stmt_rating, "i", $selected_year);
                            mysqli_stmt_execute($stmt_rating);
                            $q_rating_data = mysqli_stmt_get_result($stmt_rating);
                            if ($q_rating_data) {
                                $rating_counts = array_fill(1, 5, 0);
                                while ($row = mysqli_fetch_assoc($q_rating_data)) {
                                    $rating_counts[$row['rating']] = $row['jumlah'];
                                    $total_ratings += $row['jumlah'];
                                }
                                for ($i = 1; $i <= 5; $i++) {
                                    $chart_labels[] = "$i Bintang";
                                    $chart_data[] = $rating_counts[$i];
                                }
                            }
                        }
                        $sql_list = "SELECT nama, rating FROM tamu WHERE rating > 0 AND YEAR(waktu_input) = ? ORDER BY waktu_input DESC";
                        $stmt_list = mysqli_prepare($koneksi, $sql_list);
                        if ($stmt_list) {
                            mysqli_stmt_bind_param($stmt_list, "i", $selected_year);
                            mysqli_stmt_execute($stmt_list);
                            $query_ratings_list = mysqli_stmt_get_result($stmt_list);
                        }
                    ?>
                        <h2><i class="fas fa-star-half-alt"></i> Analisis Rating Kepuasan</h2>
                        <div class="page-controls">
                            <form method="GET"><input type="hidden" name="page" value="rating"><label for="tahun_filter">Pilih Tahun:</label><select name="tahun" id="tahun_filter" onchange="this.form.submit()"><?php for ($y = date('Y'); $y >= 2023; $y--): ?><option value="<?php echo $y; ?>" <?php if ($y == $selected_year) echo 'selected'; ?>><?php echo $y; ?></option><?php endfor; ?></select></form>
                        </div>
                        <div class="chart-grid">
                            <div class="chart-container"><canvas id="ratingBarChart"></canvas></div>
                            <div class="chart-container"><canvas id="ratingPieChart"></canvas></div>
                        </div>
                        <div class="table-responsive" style="margin-top: 30px;">
                            <h3>Detail Penilaian Tahun <?php echo $selected_year; ?> (Total: <?php echo $total_ratings; ?> Penilai)</h3>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nama Tamu</th>
                                        <th>Rating Diberikan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($query_ratings_list) && mysqli_num_rows($query_ratings_list) > 0): while ($item = mysqli_fetch_assoc($query_ratings_list)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                                <td class="rating-stars-small"><?php for ($i = 1; $i <= 5; $i++) {
                                                                                    echo '<i class="fa' . ($i <= $item['rating'] ? 's' : 'r') . ' fa-star"></i>';
                                                                                } ?></td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="2" align="center">Belum ada data rating untuk tahun <?php echo $selected_year; ?>.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                        break;
                    case 'lihat_pengaduan':
                        $query_pengaduan = mysqli_query($koneksi, "SELECT * FROM pengaduan ORDER BY waktu_lapor DESC");
                    ?>
                        <h2><i class="fas fa-list-check"></i> Daftar Pengaduan</h2>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Waktu</th>
                                        <th>Judul</th>
                                        <th>Pelapor</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($query_pengaduan && mysqli_num_rows($query_pengaduan) > 0): while ($p = mysqli_fetch_assoc($query_pengaduan)): ?>
                                            <tr>
                                                <td><?php echo $p['id_pengaduan']; ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($p['waktu_lapor'])); ?></td>
                                                <td><?php echo htmlspecialchars($p['judul_pengaduan']); ?></td>
                                                <td><?php echo htmlspecialchars($p['nama_pelapor']); ?></td>
                                                <td><?php echo htmlspecialchars($p['status_pengaduan']); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning btn-detail-pengaduan" data-id="<?php echo $p['id_pengaduan']; ?>" title="Detail/Edit Pengaduan"><i class="fas fa-edit"></i></button>
                                                    <form action="proses_hapus_pengaduan.php" method="post" style="display:inline;" onsubmit="return confirm('Hapus pengaduan ini?');"><input type="hidden" name="id_pengaduan" value="<?php echo $p['id_pengaduan']; ?>"><button type="submit" name="hapus_pengaduan" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="6" align="center">Belum ada pengaduan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                    break;
                    case 'master_tamu':
                        // --- LOGIKA PENCARIAN BARU ---
                        $search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
                        $sql_master = "SELECT * FROM master_tamu";
                        if (!empty($search_keyword)) {
                            // Mencari berdasarkan nama saja
                            $sql_master .= " WHERE nama LIKE ?";
                        }
                        $sql_master .= " ORDER BY nama ASC";

                        $stmt_master = mysqli_prepare($koneksi, $sql_master);
                        if (!empty($search_keyword)) {
                            $search_param = "%{$search_keyword}%";
                            mysqli_stmt_bind_param($stmt_master, "s", $search_param);
                        }
                        mysqli_stmt_execute($stmt_master);
                        $query_master = mysqli_stmt_get_result($stmt_master);
                    ?>
                        <h2><i class="fas fa-users"></i> Daftar Master Tamu</h2>
                        <div class="page-controls"><button type="button" class="btn btn-utama" id="btnTambahMaster"><i class="fas fa-plus"></i> Tambah Data Master</button></div>

                        <form method="GET" class="search-form" style="margin-bottom: 20px; display: flex; gap: 10px;">
                            <input type="hidden" name="page" value="master_tamu">
                            <input type="text" name="search" placeholder="Cari berdasarkan nama..." value="<?php echo htmlspecialchars($search_keyword); ?>" style="flex-grow: 1;">
                            <button type="submit" class="btn btn-secondary">Cari</button>
                        </form>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No. HP</th>
                                        <th>Pekerjaan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($query_master && mysqli_num_rows($query_master) > 0): while ($m = mysqli_fetch_assoc($query_master)): ?>
                                            <tr>
                                                <td><?php echo $m['id']; ?></td>
                                                <td><?php echo htmlspecialchars($m['nama']); ?></td>
                                                <td><?php echo htmlspecialchars($m['email'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($m['no_hp'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($m['pekerjaan'] ?? '-'); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-secondary btn-view-qr" data-qrdata="ID Master Tamu: <?php echo $m['id']; ?>" data-namatamu="<?php echo htmlspecialchars($m['nama']); ?>"><i class="fas fa-qrcode"></i></button>
                                                    <button type="button" class="btn btn-sm btn-warning btn-edit-master" data-id="<?php echo $m['id']; ?>" data-nama="<?php echo htmlspecialchars($m['nama']); ?>" data-email="<?php echo htmlspecialchars($m['email'] ?? ''); ?>" data-no_hp="<?php echo htmlspecialchars($m['no_hp'] ?? ''); ?>" data-alamat="<?php echo htmlspecialchars($m['alamat'] ?? ''); ?>" data-pekerjaan="<?php echo htmlspecialchars($m['pekerjaan'] ?? ''); ?>"><i class="fas fa-edit"></i></button>
                                                    <form action="proses_hapus_master.php" method="post" style="display:inline;" onsubmit="return confirm('Hapus master tamu ini?');"><input type="hidden" name="id_master" value="<?php echo $m['id']; ?>"><button type="submit" name="hapus_master" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="6" align="center">Data tidak ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php
                        break;

                    case 'lihat_tamu':
                    default:
                        // --- LOGIKA PENCARIAN BERDASARKAN NAMA ---
                        $search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
                        $sql_tamu = "SELECT * FROM tamu";
                        if (!empty($search_keyword)) {
                            $sql_tamu .= " WHERE nama LIKE ?";
                        }
                        $sql_tamu .= " ORDER BY waktu_input DESC";

                        $stmt_tamu = mysqli_prepare($koneksi, $sql_tamu);
                        if (!empty($search_keyword)) {
                            $search_param = "%{$search_keyword}%";
                            mysqli_stmt_bind_param($stmt_tamu, "s", $search_param);
                        }
                        mysqli_stmt_execute($stmt_tamu);
                        $query_tamu = mysqli_stmt_get_result($stmt_tamu);
                    ?>
                        <h2><i class="fas fa-list-alt"></i> Daftar Kunjungan Tamu</h2>

                        <form method="GET" class="search-form" style="margin-bottom: 20px; display: flex; gap: 10px;">
                            <input type="hidden" name="page" value="lihat_tamu">
                            <input type="text" name="search" placeholder="Cari berdasarkan nama..." value="<?php echo htmlspecialchars($search_keyword); ?>" style="flex-grow: 1;">
                            <button type="submit" class="btn btn-secondary">Cari</button>
                        </form>

                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>No Antrian</th>
                                        <th>Email</th>
                                        <th>Keperluan</th>
                                        <th>Pekerjaan</th>
                                        <th>Waktu</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($query_tamu && mysqli_num_rows($query_tamu) > 0): while ($t = mysqli_fetch_assoc($query_tamu)): ?>
                                            <tr>
                                                <td><?php echo $t['id']; ?></td>
                                                <td><?php echo htmlspecialchars($t['nama']); ?></td>
                                                <td align="center" style="font-weight:bold;"><?php echo $t['no_antrian_hari_ini'] ?? '-'; ?></td>
                                                <td><?php echo htmlspecialchars($t['email'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($t['keperluan'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars($t['pekerjaan'] ?? '-'); ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($t['waktu_input'])); ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-secondary btn-view-qr" data-qrdata="ID Tamu: <?php echo $t['id']; ?>" data-namatamu="<?php echo htmlspecialchars($t['nama']); ?>"><i class="fas fa-qrcode"></i></button>
                                                    <button type="button" class="btn btn-sm btn-warning btn-edit-tamu" data-id="<?php echo $t['id']; ?>" data-master_id_sumber="<?php echo htmlspecialchars($t['master_id_sumber'] ?? ''); ?>" data-nama="<?php echo htmlspecialchars($t['nama']); ?>" data-email="<?php echo htmlspecialchars($t['email'] ?? ''); ?>" data-alamat="<?php echo htmlspecialchars($t['alamat'] ?? ''); ?>" data-keperluan="<?php echo htmlspecialchars($t['keperluan'] ?? ''); ?>" data-pekerjaan="<?php echo htmlspecialchars($t['pekerjaan'] ?? ''); ?>" data-no_telp="<?php echo htmlspecialchars($t['no_telp'] ?? ''); ?>"><i class="fas fa-edit"></i></button>
                                                    <form action="hapus_tamu.php" method="post" style="display:inline;" onsubmit="return confirm('Hapus data kunjungan ini?');"><input type="hidden" name="id_tamu" value="<?php echo $t['id']; ?>"><button type="submit" name="hapus" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></form>
                                                </td>
                                            </tr>
                                        <?php endwhile;
                                    else: ?>
                                        <tr>
                                            <td colspan="8" align="center">Data tidak ditemukan.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                <?php
                        break;
                }
                ?>
            </div>
        </main>
    </div>

    <div id="tambahMasterModal" class="modal">
        <div class="modal-content"><span class="modal-close-btn">&times;</span>
            <h3>Tambah Data Master</h3>
            <form action="proses_input_master.php" method="post" style="padding-top:15px;">
                <div class="form-group"><label>ID Unik:</label><input type="number" name="id" required></div>
                <div class="form-group"><label>Nama:</label><input type="text" name="nama" required></div>
                <div class="form-group"><label>Email:</label><input type="email" name="email"></div>
                <div class="form-group"><label>No. HP:</label><input type="text" name="no_hp"></div>
                <div class="form-group"><label>Alamat:</label><textarea name="alamat" rows="2"></textarea></div>
                <div class="form-group"><label>Pekerjaan:</label><select name="pekerjaan">
                        <option value="">-</option><?php foreach ($opsi_pekerjaan as $v => $t) echo "<option value=\"" . htmlspecialchars($v) . "\">" . htmlspecialchars($t) . "</option>"; ?>
                    </select></div>
                <div class="form-group"><label>Rating Default (Opsional):</label>
                    <div class="rating-input" style="justify-content: flex-start;"><input type="radio" id="add_star5" name="rating" value="5"><label for="add_star5">★</label><input type="radio" id="add_star4" name="rating" value="4"><label for="add_star4">★</label><input type="radio" id="add_star3" name="rating" value="3"><label for="add_star3">★</label><input type="radio" id="add_star2" name="rating" value="2"><label for="add_star2">★</label><input type="radio" id="add_star1" name="rating" value="1"><label for="add_star1">★</label></div>
                </div>
                <div style="text-align:right;"><button type="submit" name="submit_master" class="btn btn-utama">Simpan</button></div>
            </form>
        </div>
    </div>
    <div id="editMasterModal" class="modal">
        <div class="modal-content"><span class="modal-close-btn">&times;</span>
            <h3>Edit Data Master</h3>
            <form id="editMasterForm" style="padding-top:15px;"><input type="hidden" name="id">
                <div class="form-group"><label>Nama:</label><input type="text" name="nama" required></div>
                <div class="form-group"><label>Email:</label><input type="email" name="email"></div>
                <div class="form-group"><label>No. HP:</label><input type="text" name="no_hp"></div>
                <div class="form-group"><label>Alamat:</label><textarea name="alamat" rows="2"></textarea></div>
                <div class="form-group"><label>Pekerjaan:</label><select name="pekerjaan">
                        <option value="">-</option><?php foreach ($opsi_pekerjaan as $v => $t) echo "<option value=\"" . htmlspecialchars($v) . "\">" . htmlspecialchars($t) . "</option>"; ?>
                    </select></div>
                <div class="pesan" id="editMasterPesan" style="display:none;"></div>
                <div style="text-align:right;"><button type="submit" class="btn btn-utama">Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
    <div id="editModal" class="modal">
        <div class="modal-content"><span class="modal-close-btn">&times;</span>
            <h3>Edit Kunjungan Tamu</h3>
            <form id="editTamuForm" style="padding-top:15px;"><input type="hidden" name="id_tamu">
                <div class="form-group"><label>ID</label><input type="text" name="master_id_sumber" readonly style="background:#e9ecef;"></div>
                <div class="form-group"><label>Nama:</label><input type="text" name="nama" required></div>
                <div class="form-group"><label>Email:</label><input type="email" name="email"></div>
                <div class="form-group"><label>Alamat:</label><textarea name="alamat" rows="2"></textarea></div>
                <div class="form-group"><label>No. Telp:</label><input type="text" name="no_telp"></div>
                <div class="form-group"><label>Keperluan:</label><select name="keperluan"><?php foreach ($opsi_keperluan as $v => $t) echo "<option value=\"" . htmlspecialchars($v) . "\">" . htmlspecialchars($t) . "</option>"; ?></select></div>
                <div class="form-group"><label>Pekerjaan:</label><select name="pekerjaan"><?php foreach ($opsi_pekerjaan as $v => $t) echo "<option value=\"" . htmlspecialchars($v) . "\">" . htmlspecialchars($t) . "</option>"; ?></select></div>
                <div class="pesan" id="editPesan" style="display:none;"></div>
                <div style="text-align:right;"><button type="submit" class="btn btn-utama">Simpan Perubahan</button></div>
            </form>
        </div>
    </div>
    <div id="qrModal" class="modal">
        <div class="modal-content"><span class="modal-close-btn">&times;</span>
            <h3 id="modalQrTitle"></h3>
            <div class="modal-qr-display">
                <div id="modal-qrcode-container"></div>
            </div>
        </div>
    </div>
    <div id="detailPengaduanModal" class="modal">
        <div class="modal-content"><span class="modal-close-btn">&times;</span>
            <h3>Detail Pengaduan</h3>
            <div id="detailPengaduanContent" style="padding-top:15px; line-height:1.6;"></div>
            <hr>
            <h4>Tindak Lanjut</h4>
            <form id="formTindakLanjut"><input type="hidden" id="detail_id_pengaduan" name="id_pengaduan">
                <div class="form-group"><label>Ubah Status:</label><select id="detail_status" name="status_pengaduan">
                        <option>Baru</option>
                        <option>Diproses</option>
                        <option>Selesai</option>
                        <option>Ditolak</option>
                    </select></div>
                <div class="form-group"><label>Catatan:</label><textarea id="detail_catatan" name="catatan_tindaklanjut" rows="3"></textarea></div>
                <div id="tindakLanjutPesan" class="pesan" style="display:none;"></div>
                <div style="text-align:right;"><button type="submit" class="btn btn-utama">Simpan</button></div>
            </form>
        </div>
    </div>

    <?php if ($koneksi) mysqli_close($koneksi); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = '<?php echo $page; ?>';
            const sidebar = document.querySelector('.admin-sidebar');
            document.getElementById('sidebarToggleBtn')?.addEventListener('click', () => sidebar.classList.toggle('open'));
            document.querySelector('.sidebar-overlay')?.addEventListener('click', () => sidebar.classList.remove('open'));

            function setupModal(options) {
                const {
                    modalId,
                    openSelector,
                    onOpen
                } = options;
                const modal = document.getElementById(modalId);
                if (!modal) return;
                document.querySelectorAll(openSelector).forEach(button => {
                    button.addEventListener('click', function() {
                        const form = modal.querySelector('form');
                        if (form) form.reset();
                        const pesanEl = modal.querySelector('.pesan');
                        if (pesanEl) pesanEl.style.display = 'none';
                        if (onOpen) onOpen(modal, this);
                        modal.style.display = 'block';
                    });
                });
                modal.querySelector('.modal-close-btn')?.addEventListener('click', () => modal.style.display = 'none');
                window.addEventListener('click', e => {
                    if (e.target == modal) modal.style.display = 'none';
                });
            }

            function setupFormSubmission(formId, endpoint) {
                const form = document.getElementById(formId);
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const pesanEl = this.closest('.modal-content').querySelector('.pesan');
                        if (pesanEl) {
                            pesanEl.textContent = 'Menyimpan...';
                            pesanEl.className = 'pesan';
                            pesanEl.style.display = 'block';
                        }
                        fetch(endpoint, {
                            method: 'POST',
                            body: new FormData(this)
                        }).then(res => res.json()).then(result => {
                            if (pesanEl) {
                                pesanEl.textContent = result.message;
                                pesanEl.className = 'pesan ' + (result.success ? 'sukses' : 'error');
                            }
                            if (result.success) setTimeout(() => window.location.reload(), 1500);
                        }).catch(err => {
                            if (pesanEl) {
                                pesanEl.textContent = 'Error: ' + err;
                                pesanEl.className = 'pesan error';
                            }
                        });
                    });
                }
            }
            setupModal({
                modalId: 'qrModal',
                openSelector: '.btn-view-qr',
                onOpen: (modal, button) => {
                    modal.querySelector('#modalQrTitle').textContent = 'QR Code: ' + button.dataset.namatamu;
                    const container = modal.querySelector('#modal-qrcode-container');
                    container.innerHTML = '';
                    new QRCode(container, {
                        text: button.dataset.qrdata,
                        width: 220,
                        height: 220
                    });
                }
            });
            setupModal({
                modalId: 'tambahMasterModal',
                openSelector: '#btnTambahMaster'
            });
            setupFormSubmission('editMasterForm', 'proses_update_master.php');
            setupModal({
                modalId: 'editMasterModal',
                openSelector: '.btn-edit-master',
                onOpen: (modal, button) => {
                    const form = modal.querySelector('form');
                    for (const key in button.dataset) {
                        if (form.elements[key]) form.elements[key].value = button.dataset[key];
                    }
                }
            });
            setupFormSubmission('editTamuForm', 'proses_update_tamu.php');
            setupModal({
                modalId: 'editModal',
                openSelector: '.btn-edit-tamu',
                onOpen: (modal, button) => {
                    const form = modal.querySelector('form');
                    const ds = button.dataset;
                    form.elements['id_tamu'].value = ds.id || '';
                    form.elements['master_id_sumber'].value = ds.master_id_sumber || 'Bukan dari master';
                    form.elements['nama'].value = ds.nama || '';
                    form.elements['email'].value = ds.email || '';
                    form.elements['alamat'].value = ds.alamat || '';
                    form.elements['no_telp'].value = ds.notelp || '';
                    form.elements['keperluan'].value = ds.keperluan || '';
                    form.elements['pekerjaan'].value = ds.pekerjaan || '';
                }
            });
            setupFormSubmission('formTindakLanjut', 'proses_pengaduan_update.php');
            setupModal({
                modalId: 'detailPengaduanModal',
                openSelector: '.btn-detail-pengaduan',
                onOpen: async (modal, button) => {
                    const contentEl = modal.querySelector('#detailPengaduanContent');
                    contentEl.innerHTML = '<i>Memuat...</i>';
                    try {
                        const response = await fetch(`ambil_detail_pengaduan.php?id=${button.dataset.id}`);
                        const data = await response.json();
                        if (data.success && data.pengaduan) {
                            const p = data.pengaduan;
                            let lampiranHtml = p.lampiran ? `<a href="${p.lampiran}" target="_blank">Lihat</a>` : '-';
                            contentEl.innerHTML = `<p><b>Pelapor:</b> ${p.nama_pelapor}<br><b>Detail:</b> ${p.detail_pengaduan || '-'}<br><b>Lampiran:</b> ${lampiranHtml}</p>`;
                            modal.querySelector('#detail_id_pengaduan').value = p.id_pengaduan;
                            modal.querySelector('#detail_status').value = p.status_pengaduan;
                            modal.querySelector('#detail_catatan').value = p.catatan_tindaklanjut || '';
                        }
                    } catch (e) {
                        contentEl.innerHTML = 'Gagal memuat data.';
                    }
                }
            });

            if (currentPage === 'monitoring' || currentPage === 'rating') {
                Chart.register(ChartDataLabels);
                const pieOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true
                        },
                        legend: {
                            position: 'bottom'
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = sum > 0 ? (value * 100 / sum).toFixed(1) + "%" : '0%';
                                return percentage;
                            },
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                };

                if (currentPage === 'monitoring') {
                    const monthlyData = <?php echo json_encode(array_values($monthly_counts ?? [])); ?>;
                    const keperluanLabels = <?php echo json_encode($keperluan_labels ?? []); ?>;
                    const keperluanData = <?php echo json_encode($keperluan_data ?? []); ?>;
                    const pekerjaanLabels = <?php echo json_encode($pekerjaan_labels ?? []); ?>;
                    const pekerjaanData = <?php echo json_encode($pekerjaan_data ?? []); ?>;
                    const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

                    const monthlyCtx = document.getElementById('monthlyGuestChart')?.getContext('2d');
                    if (monthlyCtx) new Chart(monthlyCtx, {
                        type: 'bar',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Jumlah Tamu',
                                data: monthlyData,
                                backgroundColor: 'rgba(0, 123, 255, 0.7)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Kunjungan per Bulan'
                                }
                            }
                        }
                    });

                    const keperluanPieOptions = {
                        ...pieOptions,
                        plugins: {
                            ...pieOptions.plugins,
                            title: {
                                display: true,
                                text: 'Distribusi Keperluan'
                            }
                        }
                    };
                    const keperluanCtx = document.getElementById('keperluanPieChart')?.getContext('2d');
                    if (keperluanCtx) new Chart(keperluanCtx, {
                        type: 'pie',
                        data: {
                            labels: keperluanLabels,
                            datasets: [{
                                data: keperluanData
                            }]
                        },
                        options: keperluanPieOptions
                    });

                    const pekerjaanPieOptions = {
                        ...pieOptions,
                        plugins: {
                            ...pieOptions.plugins,
                            title: {
                                display: true,
                                text: 'Distribusi Pekerjaan'
                            }
                        }
                    };
                    const pekerjaanCtx = document.getElementById('pekerjaanPieChart')?.getContext('2d');
                    if (pekerjaanCtx) new Chart(pekerjaanCtx, {
                        type: 'pie',
                        data: {
                            labels: pekerjaanLabels,
                            datasets: [{
                                data: pekerjaanData
                            }]
                        },
                        options: pekerjaanPieOptions
                    });
                }

                if (currentPage === 'rating') {
                    const chartData = <?php echo json_encode($chart_data ?? []); ?>;
                    const chartLabels = <?php echo json_encode($chart_labels ?? []); ?>;
                    const barChartOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Distribusi Rating'
                            }
                        }
                    };
                    const ratingPieOptions = {
                        ...pieOptions,
                        plugins: {
                            ...pieOptions.plugins,
                            title: {
                                display: true,
                                text: 'Persentase Rating'
                            }
                        }
                    };
                    const barCtx = document.getElementById('ratingBarChart')?.getContext('2d');
                    if (barCtx) new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: chartLabels,
                            datasets: [{
                                label: 'Jumlah',
                                data: chartData,
                                backgroundColor: ['#dc3545', '#ffc107', '#6f42c1', '#007bff', '#28a745']
                            }]
                        },
                        options: barChartOptions
                    });
                    const pieCtx = document.getElementById('ratingPieChart')?.getContext('2d');
                    if (pieCtx) new Chart(pieCtx, {
                        type: 'pie',
                        data: {
                            labels: chartLabels,
                            datasets: [{
                                data: chartData,
                                backgroundColor: ['#dc3545', '#ffc107', '#6f42c1', '#007bff', '#28a745']
                            }]
                        },
                        options: ratingPieOptions
                    });
                }
            }
        });
    </script>
</body>

</html>