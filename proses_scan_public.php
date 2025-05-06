<?php
session_start(); 
require_once 'koneksi.php'; 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Kesalahan pemrosesan QR.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { /* ... Handle error metode ... */ exit(); }
if (!$koneksi) { /* ... Handle error koneksi ... */ exit(); }

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['guestId']) || !filter_var($data['guestId'], FILTER_VALIDATE_INT)) {
    $response['message'] = 'ID Tamu tidak valid dari data scan.';
    echo json_encode($response); exit();
}

$guestId = (int)$data['guestId'];
$statusHadir = "Hadir"; 
$waktuScan = date('Y-m-d H:i:s');
// $tanggal_sekarang = date('Y-m-d'); // Akan dihandle di proses_final_checkin

mysqli_begin_transaction($koneksi);
try {
    // 1. Ambil Data Tamu & Cek Status Kehadiran
    $sql_check = "SELECT id, nama, alamat, pekerjaan, no_telp, status_kehadiran FROM tamu WHERE id = ?";
    $stmt_check = mysqli_prepare($koneksi, $sql_check);
    if (!$stmt_check) throw new Exception("Gagal prepare check tamu: " . mysqli_error($koneksi));
    mysqli_stmt_bind_param($stmt_check, "i", $guestId);
    if (!mysqli_stmt_execute($stmt_check)) throw new Exception("Gagal execute check tamu: " . mysqli_stmt_error($stmt_check));
    $resultCheck = mysqli_stmt_get_result($stmt_check);
    $tamuData = mysqli_fetch_assoc($resultCheck);
    mysqli_stmt_close($stmt_check);

    if (!$tamuData) throw new Exception('ID Tamu (' . $guestId . ') tidak ditemukan.'); 
    if ($tamuData['status_kehadiran'] == 'Hadir') throw new Exception('Tamu "' . htmlspecialchars($tamuData['nama']) . '" sudah tercatat HADIR sebelumnya.'); 

    // 2. Update Status & Waktu Scan (JANGAN generate nomor antrian di sini)
    $sql_update_status = "UPDATE tamu SET status_kehadiran = ?, waktu_scan_masuk = ? WHERE id = ?"; 
    $stmt_update = mysqli_prepare($koneksi, $sql_update_status); 
    if (!$stmt_update) throw new Exception("Gagal prepare update status: " . mysqli_error($koneksi));
    mysqli_stmt_bind_param($stmt_update, "ssi", $statusHadir, $waktuScan, $guestId); 
    if (!mysqli_stmt_execute($stmt_update)) throw new Exception("Gagal update status hadir: " . mysqli_stmt_error($stmt_update));
    if (mysqli_stmt_affected_rows($stmt_update) <= 0) throw new Exception("Gagal update status (affected rows = 0).");
    mysqli_stmt_close($stmt_update);

    mysqli_commit($koneksi); 

    $response['success'] = true;
    $response['message'] = 'Data tamu ditemukan. Silakan lengkapi keperluan.';
    // Kirim data tamu yang relevan untuk ditampilkan di modal konfirmasi
    $response['tamuData'] = [
        'id' => $tamuData['id'],
        'nama' => $tamuData['nama'],
        'alamat' => $tamuData['alamat'],
        'pekerjaan' => $tamuData['pekerjaan'],
        'no_telp' => $tamuData['no_telp']
    ];

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $response['message'] = $e->getMessage();
    error_log("Error di proses_scan_public.php: " . $e->getMessage());
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>