<?php
session_start();
require_once 'koneksi.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Gagal memproses QR Master.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Metode request tidak valid.';
    echo json_encode($response);
    exit();
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$masterId = $data['masterId'] ?? null;

if (empty($masterId) || !is_numeric($masterId)) {
    $response['message'] = 'Format QR Master tidak valid.';
    echo json_encode($response);
    exit();
}

mysqli_begin_transaction($koneksi);
try {
    $stmt_master = mysqli_prepare($koneksi, "SELECT * FROM master_tamu WHERE id = ?");
    mysqli_stmt_bind_param($stmt_master, "i", $masterId);
    mysqli_stmt_execute($stmt_master);
    $result_master = mysqli_stmt_get_result($stmt_master);
    $data_master = mysqli_fetch_assoc($result_master);
    mysqli_stmt_close($stmt_master);

    if (!$data_master) {
        throw new Exception("Data master tamu tidak ditemukan.");
    }

    $nama = $data_master['nama'];
    $alamat = $data_master['alamat'];
    $no_hp = $data_master['no_hp'];
    $pekerjaan = $data_master['pekerjaan'];
    $rating = $data_master['rating'];
    $waktu_sekarang = date('Y-m-d H:i:s');
    $tanggal_sekarang = date('Y-m-d');
    
    $next_no_antrian = 1;
    $stmt_max = mysqli_prepare($koneksi, "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?");
    mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
    mysqli_stmt_execute($stmt_max);
    $res_max = mysqli_stmt_get_result($stmt_max);
    $max_d = mysqli_fetch_assoc($res_max);
    if ($max_d && $max_d['max_antrian'] !== null) {
        $next_no_antrian = (int)$max_d['max_antrian'] + 1;
    }
    mysqli_stmt_close($stmt_max);
    
    $sql_insert = "INSERT INTO tamu (nama, alamat, no_telp, pekerjaan, rating, status_kehadiran, waktu_input, waktu_scan_masuk, no_antrian_hari_ini, tanggal_antrian) 
                   VALUES (?, ?, ?, ?, ?, 'Hadir', ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ssssisissi", $nama, $alamat, $no_hp, $pekerjaan, $rating, $waktu_sekarang, $waktu_sekarang, $next_no_antrian, $tanggal_sekarang);
    mysqli_stmt_execute($stmt_insert);
    
    $new_visit_id = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt_insert);

    mysqli_commit($koneksi);

    $response['success'] = true;
    $response['message'] = 'Check-in dari data master berhasil.';
    $response['tamuPopupData'] = [
        'id_tamu' => $new_visit_id,
        'nama' => $nama,
        'alamat' => $alamat,
        'no_telp' => $no_hp,
        'no_antrian' => $next_no_antrian
    ];

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $response['message'] = "Error Transaksi: " . $e->getMessage();
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>