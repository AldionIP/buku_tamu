<?php
session_start();
require_once 'koneksi.php';
header('Content-Type: application/json');
$response = ['success' => false];

if (isset($_SESSION['id_petugas']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_tamu = $_POST['id_tamu'] ?? null;
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? null); // Ambil email
    $alamat = trim($_POST['alamat'] ?? null);
    $no_telp = trim($_POST['no_telp'] ?? null);
    $keperluan = trim($_POST['keperluan'] ?? '');
    $pekerjaan = trim($_POST['pekerjaan'] ?? null);

    if (!empty($id_tamu) && !empty($nama)) {
        $sql = "UPDATE tamu SET nama = ?, email = ?, alamat = ?, keperluan = ?, pekerjaan = ?, no_telp = ? WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi", $nama, $email, $alamat, $keperluan, $pekerjaan, $no_telp, $id_tamu);
        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Data tamu berhasil diperbarui.';
        } else {
            $response['message'] = 'Gagal update data.';
        }
    } else {
        $response['message'] = 'ID dan Nama tidak boleh kosong.';
    }
}
echo json_encode($response);
?>