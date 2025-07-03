<?php
session_start();
header('Content-Type: application/json');
require_once 'koneksi.php';

$response = ['success' => false, 'message' => 'Gagal update data.'];

if (isset($_SESSION['id_petugas'])) {
    // Membaca data dari $_POST karena dikirim sebagai FormData
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'] ?? null;
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        $pekerjaan = trim($_POST['pekerjaan'] ?? '');
        $rating = isset($_POST['rating']) && is_numeric($_POST['rating']) ? intval($_POST['rating']) : NULL;

        if (!empty($id) && !empty($nama)) {
            $sql = "UPDATE master_tamu SET nama = ?, email = ?, no_hp = ?, alamat = ?, pekerjaan = ? WHERE id = ?";
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, "sssssi", $nama, $email, $no_hp, $alamat, $pekerjaan, $id);

            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = 'Data master tamu berhasil diperbarui.';
            } else {
                $response['message'] = 'Gagal eksekusi update: ' . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            $response['message'] = 'ID dan Nama tidak boleh kosong.';
        }
    }
} else {
    $response['message'] = 'Akses ditolak.';
}
echo json_encode($response);
exit();
?>