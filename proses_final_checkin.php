<?php
session_start();
require_once 'koneksi.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Gagal memproses check-in akhir.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Metode request tidak valid.';
    echo json_encode($response);
    exit();
}
if (!$koneksi) {
    $response['message'] = 'Koneksi database gagal.';
    echo json_encode($response);
    exit();
}

$originalGuestId = $_POST['guestId'] ?? null;
$keperluan = trim($_POST['keperluan'] ?? '');
$waktu_sekarang = date('Y-m-d H:i:s');
$tanggal_sekarang = date('Y-m-d');

if (empty($originalGuestId) || !is_numeric($originalGuestId) || empty($keperluan)) {
    $response['message'] = 'ID Tamu atau Keperluan tidak valid.';
    echo json_encode($response);
    exit();
}

mysqli_begin_transaction($koneksi);
try {
    $sql_master = "SELECT nama, alamat, pekerjaan, no_telp FROM tamu WHERE id = ?";
    $stmt_master = mysqli_prepare($koneksi, $sql_master);
    if (!$stmt_master) throw new Exception("Gagal prepare data master: " . mysqli_error($koneksi));
    
    mysqli_stmt_bind_param($stmt_master, "i", $originalGuestId);
    if (!mysqli_stmt_execute($stmt_master)) throw new Exception("Gagal query data master: " . mysqli_stmt_error($stmt_master));
    
    $result_master = mysqli_stmt_get_result($stmt_master);
    $data_master = mysqli_fetch_assoc($result_master);
    mysqli_stmt_close($stmt_master);

    if (!$data_master) {
        throw new Exception("Data master tamu tidak ditemukan.");
    }

    $next_no_antrian = 1;
    $sql_max = "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?";
    $stmt_max = mysqli_prepare($koneksi, $sql_max);
    mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
    mysqli_stmt_execute($stmt_max);
    $res_max = mysqli_stmt_get_result($stmt_max);
    $max_d = mysqli_fetch_assoc($res_max);
    if ($max_d && $max_d['max_antrian'] !== null) {
        $next_no_antrian = (int)$max_d['max_antrian'] + 1;
    }
    mysqli_stmt_close($stmt_max);

    $sql_insert = "INSERT INTO tamu (nama, alamat, keperluan, pekerjaan, no_telp, status_kehadiran, waktu_input, waktu_scan_masuk, no_antrian_hari_ini, tanggal_antrian) 
                   VALUES (?, ?, ?, ?, ?, 'Hadir', ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
    if (!$stmt_insert) throw new Exception("Gagal prepare insert kunjungan baru: " . mysqli_error($koneksi));

    // =========================================================================
    // PERBAIKAN DI SINI: Tipe data disesuaikan menjadi 9 karakter "sssssssis"
    // =========================================================================
    mysqli_stmt_bind_param($stmt_insert, "sssssssis",
        $data_master['nama'],         // s
        $data_master['alamat'],       // s
        $keperluan,                  // s
        $data_master['pekerjaan'],    // s
        $data_master['no_telp'],      // s
        $waktu_sekarang,             // s (waktu_input)
        $waktu_sekarang,             // s (waktu_scan_masuk)
        $next_no_antrian,            // i (no_antrian_hari_ini)
        $tanggal_sekarang            // s (tanggal_antrian)
    );

    if (!mysqli_stmt_execute($stmt_insert)) {
        throw new Exception("Gagal execute insert kunjungan baru: " . mysqli_stmt_error($stmt_insert));
    }
    
    $new_guest_id = mysqli_insert_id($koneksi);
    mysqli_stmt_close($stmt_insert);

    mysqli_commit($koneksi);

    $response['success'] = true;
    $response['message'] = 'Check-in berhasil. Nomor antrian telah dibuat.';
    $response['tamuPopupData'] = [
        'id_tamu' => $new_guest_id,
        'nama' => $data_master['nama'],
        'alamat' => $data_master['alamat'],
        'no_telp' => $data_master['no_telp'],
        'no_antrian' => $next_no_antrian
    ];
    
} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $response['message'] = "Error Transaksi: " . $e->getMessage();
    error_log("Transaksi Gagal (Final Check-in): " . $e->getMessage());
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>