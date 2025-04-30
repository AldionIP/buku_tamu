<?php
session_start();
header('Content-Type: application/json'); // Set header untuk output JSON

require_once 'koneksi.php'; // Include koneksi database

// Respon default jika terjadi error
$response = ['success' => false, 'message' => 'Terjadi kesalahan.'];

// 1. Cek apakah admin sudah login
if (!isset($_SESSION['id_petugas'])) {
    $response['message'] = 'Akses ditolak. Silakan login sebagai admin.';
    echo json_encode($response);
    exit();
}

// 2. Cek metode request (harus POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
     $response['message'] = 'Metode request tidak valid.';
     echo json_encode($response);
     exit();
}

// 3. Ambil data JSON yang dikirim dari JavaScript
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// 4. Validasi data input (harus ada guestId dan berupa angka)
if (!isset($data['guestId']) || !filter_var($data['guestId'], FILTER_VALIDATE_INT)) {
    $response['message'] = 'ID Tamu tidak valid atau tidak ditemukan dalam data scan.';
    echo json_encode($response);
    exit();
}

$guestId = (int)$data['guestId'];
$statusHadir = "Hadir"; // Status yang ingin kita set
$waktuScan = date('Y-m-d H:i:s'); // Waktu saat ini

// 5. Siapkan query UPDATE menggunakan prepared statement
$sql_update = "UPDATE tamu SET status_kehadiran = ?, waktu_scan_masuk = ? WHERE id = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    mysqli_stmt_bind_param($stmt_update, "ssi", $statusHadir, $waktuScan, $guestId);

    if (mysqli_stmt_execute($stmt_update)) {
        if (mysqli_stmt_affected_rows($stmt_update) > 0) {
            // Ambil nama tamu untuk pesan respon
            $sql_get_nama = "SELECT nama FROM tamu WHERE id = ?";
            $stmt_get_nama = mysqli_prepare($koneksi, $sql_get_nama);
            $namaTamu = "Tamu"; // Default
            if($stmt_get_nama){
                mysqli_stmt_bind_param($stmt_get_nama, "i", $guestId);
                if(mysqli_stmt_execute($stmt_get_nama)){
                    $resultNama = mysqli_stmt_get_result($stmt_get_nama);
                    if($rowNama = mysqli_fetch_assoc($resultNama)){
                        $namaTamu = $rowNama['nama'];
                    }
                }
                mysqli_stmt_close($stmt_get_nama);
            }
            $response['success'] = true;
            $response['message'] = 'Tamu "' . htmlspecialchars($namaTamu) . '" (ID: ' . $guestId . ') berhasil ditandai HADIR pada ' . date('d/m/Y H:i', strtotime($waktuScan)) . '.';
        } else {
            $response['message'] = 'ID Tamu (' . $guestId . ') tidak ditemukan atau status tidak berubah.';
        }
    } else {
        $response['message'] = 'Gagal mengupdate database: ' . htmlspecialchars(mysqli_stmt_error($stmt_update));
    }
    mysqli_stmt_close($stmt_update);
} else {
    $response['message'] = 'Gagal menyiapkan query update: ' . htmlspecialchars(mysqli_error($koneksi));
}

if (isset($koneksi) && $koneksi instanceof mysqli) {
     mysqli_close($koneksi);
}

echo json_encode($response);
exit();
?>