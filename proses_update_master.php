<?php
session_start();
require_once 'koneksi.php';

// Atur header sebagai JSON di awal untuk memastikan respons selalu JSON
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Akses ditolak atau metode tidak valid.'];

if (isset($_SESSION['id_petugas']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $id = $_POST['id'] ?? null;
    $bpd_id = trim($_POST['Bpd_id'] ?? ''); // Menggunakan Bpd_id
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $pekerjaan = trim($_POST['pekerjaan'] ?? '');

    if (empty($id) || empty($nama)) {
        $response['message'] = 'ID dan Nama tidak boleh kosong.';
        echo json_encode($response);
        exit();
    }

    $sql = "UPDATE master_tamu SET Bpd_id = ?, nama = ?, email = ?, no_hp = ?, alamat = ?, pekerjaan = ? WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    
    if ($stmt) {
        // Sesuaikan tipe dan jumlah variabel di bind_param
        mysqli_stmt_bind_param($stmt, "ssssssi", $bpd_id, $nama, $email, $no_hp, $alamat, $pekerjaan, $id);
        if (mysqli_stmt_execute($stmt)) {
            $response['success'] = true;
            $response['message'] = 'Data master tamu berhasil diperbarui.';
        } else {
            $response['message'] = 'Gagal memperbarui data di database.';
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Gagal menyiapkan query database.';
    }
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>