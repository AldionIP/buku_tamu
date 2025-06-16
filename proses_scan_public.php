<?php
// TANPA session_start() karena ini diakses publik
require_once 'koneksi.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Gagal memproses QR.'];

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

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$guestId = $data['guestId'] ?? null;

if (empty($guestId) || !is_numeric($guestId)) {
    $response['message'] = 'Format QR Code tidak valid (ID tidak ditemukan).';
    echo json_encode($response);
    exit();
}

// Ambil data master tamu dari kunjungan sebelumnya
$sql = "SELECT id, nama, alamat, pekerjaan, no_telp FROM tamu WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $guestId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tamuData = mysqli_fetch_assoc($result);

    if ($tamuData) {
        // Tandai kehadiran tamu dengan status 'Hadir' untuk kunjungan BARU ini
        // Ini adalah langkah sementara sebelum data lengkapnya di-insert oleh proses_final_checkin
        // Kita langsung kirimkan datanya ke modal
        $response['success'] = true;
        $response['message'] = 'Data tamu ditemukan. Silakan lengkapi keperluan.';
        $response['tamuData'] = $tamuData;
    } else {
        $response['message'] = 'Data tamu dengan ID dari QR Code ini tidak ditemukan.';
    }
    mysqli_stmt_close($stmt);
} else {
    $response['message'] = 'Gagal menyiapkan query database.';
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>