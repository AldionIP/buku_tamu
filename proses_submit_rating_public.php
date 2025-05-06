<?php
session_start(); 
require_once 'koneksi.php'; 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Gagal menyimpan rating.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { /* ... Handle error metode ... */ exit(); }
if (!$koneksi) { /* ... Handle error koneksi ... */ exit(); }

$guestId = $_POST['guestId'] ?? null;
$rating = isset($_POST['rating_pelayanan']) ? intval($_POST['rating_pelayanan']) : null;

if (empty($guestId) || !is_numeric($guestId)) {
    $response['message'] = 'ID Tamu tidak valid untuk rating.';
    echo json_encode($response); exit();
}
if ($rating === null || $rating < 1 || $rating > 5) {
    $response['message'] = 'Nilai rating tidak valid (harus antara 1-5).';
    echo json_encode($response); exit();
}
$guestId = (int)$guestId;

$sql_update = "UPDATE tamu SET rating = ? WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql_update);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ii", $rating, $guestId);
    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            $response['success'] = true;
            $response['message'] = 'Terima kasih atas rating Anda!';
        } else {
            // Mungkin ID tidak ditemukan, atau ratingnya sama dengan yg sudah ada
            $response['message'] = 'Rating tidak berubah atau ID tamu tidak ditemukan.';
            // Jika ingin tetap dianggap sukses jika tidak ada error SQL:
            // $response['success'] = true; 
        }
    } else {
        $response['message'] = 'Gagal menyimpan rating ke database: ' . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    $response['message'] = 'Gagal menyiapkan query rating: ' . mysqli_error($koneksi);
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>