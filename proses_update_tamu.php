<?php
session_start();
header('Content-Type: application/json');
require_once 'koneksi.php';

$response = ['success' => false, 'message' => 'Gagal update data.'];

if (!isset($_SESSION['id_petugas'])) {
    $response['message'] = 'Akses ditolak. Silakan login ulang.';
    echo json_encode($response); exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    $id_tamu = $data['id_tamu'] ?? null;
    $nama = trim($data['nama'] ?? '');
    $alamat = trim($data['alamat'] ?? '');
    $keperluan = trim($data['keperluan'] ?? ''); // Sekarang dari select
    $pekerjaan = trim($data['pekerjaan'] ?? NULL); // Field BARU
    $no_telp = trim($data['no_telp'] ?? '');
    $rating = isset($data['rating']) && is_numeric($data['rating']) ? intval($data['rating']) : NULL; // Field BARU

    if (empty($id_tamu) || !is_numeric($id_tamu) || empty($nama) || empty($keperluan)) {
        $response['message'] = 'ID Tamu, Nama, dan Keperluan tidak boleh kosong.';
        echo json_encode($response); exit();
    }
    if ($rating !== NULL && ($rating < 1 || $rating > 5)) {
        $response['message'] = 'Rating tidak valid.';
        echo json_encode($response); exit();
    }
    if (!$koneksi) { 
        $response['message'] = 'Koneksi database gagal.';
        echo json_encode($response); exit();
    }

    $sql = "UPDATE tamu SET nama = ?, alamat = ?, keperluan = ?, pekerjaan = ?, no_telp = ?, rating = ? 
            WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Perhatikan urutan dan tipe data: s (string), s, s, s (pekerjaan), s, i (rating), i (id)
        mysqli_stmt_bind_param($stmt, "sssssii", 
            $nama, $alamat, $keperluan, $pekerjaan, $no_telp, $rating, $id_tamu
        );

        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response['success'] = true;
                $response['message'] = 'Data tamu berhasil diperbarui.';
                $_SESSION['admin_message'] = 'Data tamu ID: ' . $id_tamu . ' berhasil diperbarui.';
                $_SESSION['admin_message_type'] = 'sukses';
            } else {
                $response['message'] = 'Tidak ada perubahan data atau ID tamu tidak ditemukan.';
                $response['success'] = true; // Anggap sukses jika tidak ada error, mungkin data sama
            }
        } else {
            $response['message'] = 'Gagal eksekusi update: ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $response['message'] = 'Gagal prepare statement: ' . mysqli_error($koneksi);
    }
    mysqli_close($koneksi);
} else {
    $response['message'] = 'Metode request tidak valid.';
}
echo json_encode($response);
exit();
?>