<?php
session_start();
require_once 'koneksi.php'; // Pastikan path ini benar

// Atur header untuk respons JSON
header('Content-Type: application/json');

// Inisialisasi respons default
$response = ['success' => false, 'message' => 'Terjadi kesalahan.'];

// 1. Keamanan: Cek Login Petugas
if (!isset($_SESSION['id_petugas'])) {
    $response['message'] = 'Akses ditolak. Silakan login ulang.';
    echo json_encode($response);
    exit();
}

// 2. Cek Koneksi Database
if (!$koneksi) {
    $response['message'] = 'Koneksi database gagal.';
    echo json_encode($response);
    exit();
}

// 3. Cek Metode Request & Data POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari POST
    $id_pengaduan = $_POST['id_pengaduan'] ?? null;
    $status_pengaduan = $_POST['status_pengaduan'] ?? null;
    $catatan_tindaklanjut = trim($_POST['catatan_tindaklanjut'] ?? ''); // Trim catatan

    // 4. Validasi Input Sederhana
    if (empty($id_pengaduan) || !is_numeric($id_pengaduan)) {
        $response['message'] = 'ID Pengaduan tidak valid.';
        echo json_encode($response);
        exit();
    }
    
    // Daftar status yang valid
    $valid_statuses = ['Baru', 'Diproses', 'Selesai', 'Ditolak'];
    if (empty($status_pengaduan) || !in_array($status_pengaduan, $valid_statuses)) {
        $response['message'] = 'Status pengaduan tidak valid.';
        echo json_encode($response);
        exit();
    }

    // 5. Siapkan Query UPDATE dengan Prepared Statement
    $sql_update = "UPDATE pengaduan 
                   SET status_pengaduan = ?, catatan_tindaklanjut = ? 
                   WHERE id_pengaduan = ?";
                   
    $stmt = mysqli_prepare($koneksi, $sql_update);

    if ($stmt) {
        // Bind parameter: s = string, i = integer
        mysqli_stmt_bind_param($stmt, "ssi", 
            $status_pengaduan, 
            $catatan_tindaklanjut, 
            $id_pengaduan
        );

        // 6. Eksekusi Query
        if (mysqli_stmt_execute($stmt)) {
            // Cek apakah ada baris yang terpengaruh (berhasil diupdate)
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response['success'] = true;
                $response['message'] = 'Tindak lanjut berhasil disimpan.';
                 // Set session untuk pesan di halaman utama (opsional)
                 $_SESSION['admin_message'] = "Status/Catatan untuk Pengaduan ID: $id_pengaduan berhasil diperbarui.";
                 $_SESSION['admin_message_type'] = "sukses";
            } else {
                 // Query berhasil tapi tidak ada baris yang berubah (mungkin data sama)
                 $response['success'] = true; // Anggap sukses jika tidak ada error
                 $response['message'] = 'Tidak ada perubahan data yang disimpan.';
            }
        } else {
            // Gagal eksekusi query
            $response['message'] = 'Database Error: Gagal mengupdate data. ' . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt); // Tutup statement
    } else {
        // Gagal mempersiapkan statement
        $response['message'] = 'Database Error: Gagal mempersiapkan update. ' . mysqli_error($koneksi);
    }

} else {
    // Jika bukan metode POST
    $response['message'] = 'Metode request tidak valid.';
}

// 7. Tutup Koneksi & Kirim Respons JSON
mysqli_close($koneksi);
echo json_encode($response);
exit();
?>