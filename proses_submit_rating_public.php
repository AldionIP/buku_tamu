<?php
session_start(); 
require_once 'koneksi.php'; 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Gagal menyimpan rating.'];

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

try {
    $guestId = $_POST['guestId'] ?? null;
    $rating = isset($_POST['rating_pelayanan']) ? intval($_POST['rating_pelayanan']) : null;

    if (empty($guestId) || !is_numeric($guestId)) {
        throw new Exception('ID Tamu tidak valid untuk memberi rating.');
    }
    if ($rating === null || $rating < 1 || $rating > 5) {
        throw new Exception('Nilai rating tidak valid (harus antara 1-5).');
    }

    $sql_update = "UPDATE tamu SET rating = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql_update);
    if (!$stmt) throw new Exception("Gagal prepare query rating: ". mysqli_error($koneksi));
    
    mysqli_stmt_bind_param($stmt, "ii", $rating, $guestId);
    
    if (!mysqli_stmt_execute($stmt)) throw new Exception("Gagal eksekusi update rating: ". mysqli_stmt_error($stmt));
    
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $response['success'] = true;
        $response['message'] = 'Terima kasih! Rating Anda berhasil disimpan.';
    } else {
        $response['success'] = true;
        $response['message'] = 'Rating tidak berubah atau ID tamu tidak ditemukan.';
    }
    mysqli_stmt_close($stmt);

} catch(Throwable $e) {
    $response['message'] = "Error: " . $e->getMessage();
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>