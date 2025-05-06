<?php
session_start(); 
require_once 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_pengaduan'])) {

     if (!$koneksi) {
         $_SESSION['public_message'] = "Database Error: Koneksi gagal.";
         $_SESSION['public_message_type'] = "error";
         header('Location: index.php#input-pengaduan'); 
         exit();
    }
    
    $nama_pelapor = mysqli_real_escape_string($koneksi, trim($_POST['nama_pelapor'] ?? ''));
    $kontak_pelapor = mysqli_real_escape_string($koneksi, trim($_POST['kontak_pelapor'] ?? ''));
    $kategori_pengaduan = trim($_POST['kategori_pengaduan'] ?? '');
    $judul_pengaduan = mysqli_real_escape_string($koneksi, trim($_POST['judul_pengaduan'] ?? ''));
    $detail_pengaduan = mysqli_real_escape_string($koneksi, trim($_POST['detail_pengaduan'] ?? ''));
    $lokasi_kejadian = mysqli_real_escape_string($koneksi, trim($_POST['lokasi_kejadian'] ?? ''));
    $waktu_kejadian_raw = trim($_POST['waktu_kejadian'] ?? '');
    $waktu_kejadian = !empty($waktu_kejadian_raw) ? date('Y-m-d H:i:s', strtotime($waktu_kejadian_raw)) : NULL;

    $path_lampiran_db = NULL; 

    if (empty($nama_pelapor) || empty($kategori_pengaduan) || empty($judul_pengaduan) || empty($detail_pengaduan)) {
        $_SESSION['public_message'] = "Input Error: Nama, Kategori, Judul, dan Detail wajib diisi!";
        $_SESSION['public_message_type'] = "error";
        header('Location: index.php#input-pengaduan'); 
        exit();
    }
    
    // --- Proses Upload Lampiran (Sama seperti sebelumnya) ---
    if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] == 0) {
        $file = $_FILES['lampiran'];
        $uploadDir = 'uploads/pengaduan/'; 
        if (!file_exists($uploadDir) && !is_dir($uploadDir)) { if (!mkdir($uploadDir, 0775, true)) { /* error handling */ }}
        if (!is_writable($uploadDir)) { /* error handling */ }
        // ... (Validasi ukuran, tipe, move_uploaded_file SAMA seperti di jawaban sebelumnya) ...
        // Jika sukses upload, set $path_lampiran_db = $pathTujuan;
        // Jika gagal, set pesan error di session dan redirect.
    } elseif (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] != 4) { /* Handle error upload */ }

    // Simpan ke DB
    $sql = "INSERT INTO pengaduan (id_petugas_pencatat, nama_pelapor, kontak_pelapor, kategori_pengaduan, judul_pengaduan, detail_pengaduan, lokasi_kejadian, waktu_kejadian, lampiran, status_pengaduan) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, 'Baru')"; 
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssss", 
            $nama_pelapor, $kontak_pelapor, $kategori_pengaduan, 
            $judul_pengaduan, $detail_pengaduan, $lokasi_kejadian, 
            $waktu_kejadian, // tambahkan ini
            $path_lampiran_db
        );
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['public_message'] = "Pengaduan Anda berhasil dikirim. Terima kasih.";
            $_SESSION['public_message_type'] = "sukses";
        } else { /* error handling */ }
        mysqli_stmt_close($stmt);
    } else { /* error handling */ }
    
    mysqli_close($koneksi);
    header('Location: index.php#input-pengaduan'); 
    exit();

} else {
    header('Location: index.php'); 
    exit();
}
?>