<?php
session_start();
require_once 'koneksi.php'; // Pastikan path ini benar

// Atur header untuk respons JSON
header('Content-Type: application/json');

// Inisialisasi respons default
$response = ['success' => false, 'error' => 'Terjadi kesalahan tidak diketahui.'];

// 1. Keamanan: Cek Login Petugas (opsional, tergantung apakah detail boleh dilihat tanpa login)
/* // Jika hanya petugas login yg boleh lihat detail, aktifkan blok ini:
if (!isset($_SESSION['id_petugas'])) {
    $response['error'] = 'Akses ditolak. Silakan login ulang.';
    echo json_encode($response);
    exit();
}
*/

// 2. Cek Koneksi Database
if (!$koneksi) {
    $response['error'] = 'Koneksi database gagal.';
    echo json_encode($response);
    exit();
}

// 3. Ambil dan Validasi ID dari GET request
$id_pengaduan = $_GET['id'] ?? null;

if (empty($id_pengaduan) || !is_numeric($id_pengaduan)) {
    $response['error'] = 'ID Pengaduan tidak valid atau tidak diberikan.';
    echo json_encode($response);
    exit();
}

// 4. Siapkan Query SELECT dengan Prepared Statement
//    Ambil semua detail yang relevan, termasuk catatan tindak lanjut dan nama pencatat
$sql_detail = "SELECT 
                    p.*, -- Semua kolom dari tabel pengaduan
                    pt.nama_lengkap as nama_pencatat 
                FROM pengaduan p 
                LEFT JOIN petugas pt ON p.id_petugas_pencatat = pt.id_petugas 
                WHERE p.id_pengaduan = ?"; // Filter berdasarkan ID

$stmt = mysqli_prepare($koneksi, $sql_detail);

if ($stmt) {
    // Bind parameter ID (integer)
    mysqli_stmt_bind_param($stmt, "i", $id_pengaduan);

    // 5. Eksekusi Query
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        $pengaduan_detail = mysqli_fetch_assoc($result);

        // 6. Cek Hasil Query
        if ($pengaduan_detail) {
            // Data ditemukan, siapkan respons sukses
            $response['success'] = true;
            $response['pengaduan'] = $pengaduan_detail;
            unset($response['error']); // Hapus kunci error jika sukses
        } else {
            // Data tidak ditemukan untuk ID tersebut
            $response['error'] = 'Data pengaduan tidak ditemukan untuk ID: ' . $id_pengaduan;
        }
    } else {
        // Gagal eksekusi query
        $response['error'] = 'Database Error: Gagal mengambil detail. ' . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt); // Tutup statement
} else {
    // Gagal mempersiapkan statement
    $response['error'] = 'Database Error: Gagal mempersiapkan query detail. ' . mysqli_error($koneksi);
}

// 7. Tutup Koneksi & Kirim Respons JSON
mysqli_close($koneksi);
echo json_encode($response);
exit();
?>