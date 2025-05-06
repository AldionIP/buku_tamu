<?php
session_start(); 
require_once 'koneksi.php'; 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Gagal memproses check-in akhir.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { /* ... Handle error metode ... */ exit(); }
if (!$koneksi) { /* ... Handle error koneksi ... */ exit(); }

// Ambil data dari POST (dari form modal konfirmasi keperluan)
$guestId = $_POST['guestId'] ?? null;
$keperluan = trim($_POST['keperluan'] ?? '');

if (empty($guestId) || !is_numeric($guestId) || empty($keperluan)) {
    $response['message'] = 'ID Tamu atau Keperluan tidak valid.';
    echo json_encode($response); exit();
}
$guestId = (int)$guestId;
$tanggal_sekarang = date('Y-m-d');

mysqli_begin_transaction($koneksi);
try {
    // 1. Generate Nomor Antrian Harian
    $next_no_antrian = 1; 
    $sql_max = "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?";
    $stmt_max = mysqli_prepare($koneksi, $sql_max);
    if (!$stmt_max) throw new Exception("Gagal prepare max antrian: " . mysqli_error($koneksi));
    mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
    if (!mysqli_stmt_execute($stmt_max)) throw new Exception("Gagal query max antrian: " . mysqli_stmt_error($stmt_max));
    $res_max = mysqli_stmt_get_result($stmt_max);
    $max_d = mysqli_fetch_assoc($res_max);
    if ($max_d && $max_d['max_antrian'] !== null) { $next_no_antrian = $max_d['max_antrian'] + 1; }
    mysqli_stmt_close($stmt_max);

    // 2. Update Keperluan, Nomor Antrian, dan Tanggal Antrian untuk Tamu
    $sql_update = "UPDATE tamu 
                   SET keperluan = ?, no_antrian_hari_ini = ?, tanggal_antrian = ? 
                   WHERE id = ? AND status_kehadiran = 'Hadir'"; // Pastikan tamu sudah ditandai hadir
    $stmt_update = mysqli_prepare($koneksi, $sql_update);
    if (!$stmt_update) throw new Exception("Gagal prepare update final: " . mysqli_error($koneksi));
    
    mysqli_stmt_bind_param($stmt_update, "sisi", $keperluan, $next_no_antrian, $tanggal_sekarang, $guestId);
    if (!mysqli_stmt_execute($stmt_update)) throw new Exception("Gagal execute update final: " . mysqli_stmt_error($stmt_update));
    
    if (mysqli_stmt_affected_rows($stmt_update) > 0) {
        mysqli_stmt_close($stmt_update);
        
        // 3. Ambil data tamu lagi untuk popup antrian
        $sql_get_tamu = "SELECT nama, alamat, no_telp FROM tamu WHERE id = ?";
        $stmt_get = mysqli_prepare($koneksi, $sql_get_tamu);
        if (!$stmt_get) throw new Exception("Gagal prepare get tamu untuk popup: " . mysqli_error($koneksi));
        mysqli_stmt_bind_param($stmt_get, "i", $guestId);
        if (!mysqli_stmt_execute($stmt_get)) throw new Exception("Gagal execute get tamu: " . mysqli_stmt_error($stmt_get));
        $resultGet = mysqli_stmt_get_result($stmt_get);
        $tamuPopupData = mysqli_fetch_assoc($resultGet);
        mysqli_stmt_close($stmt_get);

        if (!$tamuPopupData) throw new Exception("Gagal mengambil data tamu untuk popup.");

        mysqli_commit($koneksi); 
        $response['success'] = true;
        $response['message'] = 'Check-in berhasil dikonfirmasi. Nomor antrian telah dibuat.';
        $response['tamuPopupData'] = [
            'nama' => $tamuPopupData['nama'],
            'alamat' => $tamuPopupData['alamat'],
            'no_telp' => $tamuPopupData['no_telp'],
            'no_antrian' => $next_no_antrian // Kirim nomor antrian yang baru digenerate
        ];
    } else {
        // Mungkin tamu tidak ditemukan atau statusnya bukan 'Hadir'
        throw new Exception('Gagal mengupdate data atau tamu tidak dalam status Hadir.');
    }

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    $response['message'] = "Error: " . $e->getMessage();
    error_log("Transaksi Gagal (Final Check-in): " . $e->getMessage());
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>