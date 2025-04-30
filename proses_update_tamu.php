<?php
session_start();
header('Content-Type: application/json'); // Output sebagai JSON

require_once 'koneksi.php';

// Respon default
$response = ['success' => false, 'message' => 'Terjadi kesalahan.'];

// 1. Cek Login Admin
if (!isset($_SESSION['id_petugas'])) {
    $response['message'] = 'Akses ditolak. Silakan login.';
    echo json_encode($response);
    exit();
}

// 2. Cek Metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
     $response['message'] = 'Metode request tidak valid.';
     echo json_encode($response);
     exit();
}

// 3. Ambil dan Validasi Data Input
// Ambil dari body JSON atau form data (sesuaikan dengan fetch di JS)
// Contoh ini asumsi data dikirim sebagai JSON
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validasi ID
if (!isset($data['id_tamu']) || !filter_var($data['id_tamu'], FILTER_VALIDATE_INT)) {
    $response['message'] = 'ID Tamu tidak valid.';
    echo json_encode($response);
    exit();
}
$id_tamu = (int)$data['id_tamu'];

// Validasi Nama (Wajib)
if (!isset($data['nama']) || trim($data['nama']) === '') {
    $response['message'] = 'Nama Tamu wajib diisi.';
    echo json_encode($response);
    exit();
}
$nama = mysqli_real_escape_string($koneksi, trim($data['nama']));

// Ambil data lain (opsional)
$alamat = isset($data['alamat']) ? mysqli_real_escape_string($koneksi, trim($data['alamat'])) : NULL;
$keperluan = isset($data['keperluan']) ? mysqli_real_escape_string($koneksi, trim($data['keperluan'])) : NULL;
$no_telp = isset($data['no_telp']) ? mysqli_real_escape_string($koneksi, trim($data['no_telp'])) : NULL;

// Validasi Rating (jika ada, harus 1-5 atau NULL)
$rating = NULL;
if (isset($data['rating']) && $data['rating'] !== '' && $data['rating'] !== NULL) {
    $rating_input = intval($data['rating']);
    if ($rating_input >= 1 && $rating_input <= 5) {
        $rating = $rating_input;
    } else {
        $response['message'] = 'Rating tidak valid (harus 1-5 atau kosong).';
        echo json_encode($response);
        exit();
    }
}


// 4. Siapkan Query UPDATE
$sql_update = "UPDATE tamu SET
                    nama = ?,
                    alamat = ?,
                    keperluan = ?,
                    no_telp = ?,
                    rating = ?
                WHERE id = ?";

$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    // Bind parameter (s=string, i=integer). Perhatikan urutan dan tipe
    mysqli_stmt_bind_param($stmt_update, "ssssii",
        $nama,
        $alamat,
        $keperluan,
        $no_telp,
        $rating, // Kirim NULL jika tidak ada rating
        $id_tamu
    );

    if (mysqli_stmt_execute($stmt_update)) {
        // Cek apakah ada baris yang terpengaruh
        if (mysqli_stmt_affected_rows($stmt_update) > 0) {
            $response['success'] = true;
            $response['message'] = 'Data tamu berhasil diperbarui.';
        } else {
            // Query jalan tapi tidak ada yg berubah (mungkin data sama atau ID tidak ada)
            $response['success'] = true; // Anggap sukses jika tidak ada error SQL
            $response['message'] = 'Tidak ada perubahan data atau ID tidak ditemukan.';
        }
    } else {
        // Gagal eksekusi
        $response['message'] = 'Gagal mengupdate database: ' . htmlspecialchars(mysqli_stmt_error($stmt_update));
    }
    mysqli_stmt_close($stmt_update);
} else {
    // Gagal siapkan query
    $response['message'] = 'Gagal menyiapkan query update: ' . htmlspecialchars(mysqli_error($koneksi));
}

if (isset($koneksi) && $koneksi instanceof mysqli) {
    mysqli_close($koneksi);
}

// Kembalikan respon JSON
echo json_encode($response);
exit();
?>