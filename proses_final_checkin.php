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

// Ambil data dari POST (dari form modal konfirmasi)
$originalGuestId = $_POST['guestId'] ?? null; // Ini ID dari QR (data master)
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
    // 1. Ambil data master dari kunjungan sebelumnya menggunakan ID dari QR
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

    // 2. Generate Nomor Antrian Harian (Logic sama seperti sebelumnya)
    $next_no_antrian = 1;
    $sql_max = "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?";
    $stmt_max = mysqli_prepare($koneksi, $sql_max);
    mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
    mysqli_stmt_execute($stmt_max);
    $res_max = mysqli_stmt_get_result($stmt_max);
    $max_d = mysqli_fetch_assoc($res_max);
    if ($max_d && $max_d['max_antrian'] !== null) {
        $next_no_antrian = $max_d['max_antrian'] + 1;
    }
    mysqli_stmt_close($stmt_max);

    // 3. INSERT data baru, bukan UPDATE!
    $sql_insert = "INSERT INTO tamu (nama, alamat, keperluan, pekerjaan, no_telp, status_kehadiran, waktu_input, waktu_scan_masuk, no_antrian_hari_ini, tanggal_antrian) 
                   VALUES (?, ?, ?, ?, ?, 'Hadir', ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
    if (!$stmt_insert) throw new Exception("Gagal prepare insert kunjungan baru: " . mysqli_error($koneksi));

    mysqli_stmt_bind_param($stmt_insert, "ssssssis",
        $data_master['nama'],
        $data_master['alamat'],
        $keperluan,
        $data_master['pekerjaan'],
        $data_master['no_telp'],
        $waktu_sekarang,
        $waktu_sekarang,
        $next_no_antrian,
        $tanggal_sekarang
    );

    if (!mysqli_stmt_execute($stmt_insert)) {
        throw new Exception("Gagal execute insert kunjungan baru: " . mysqli_stmt_error($stmt_insert));
    }
    
    $new_guest_id = mysqli_insert_id($koneksi); // ID kunjungan BARU
    mysqli_stmt_close($stmt_insert);

    // Commit transaksi
    mysqli_commit($koneksi);

    // Kirim respons sukses dengan data yang baru di-insert
    $response['success'] = true;
    $response['message'] = 'Check-in berhasil. Nomor antrian telah dibuat.';
    $response['tamuPopupData'] = [
        'id_tamu' => $new_guest_id, // Kirim ID baru
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