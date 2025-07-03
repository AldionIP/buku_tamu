<?php
session_start(); 
require_once 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_pengaduan'])) {

    if (!$koneksi) {
        $_SESSION['public_message'] = "Database Error: Koneksi gagal.";
        $_SESSION['public_message_type'] = "error";
        header('Location: pengaduan.php');
        exit();
    }
    
    // Ambil data (tanpa mysqli_real_escape_string)
    $nama_pelapor = trim($_POST['nama_pelapor'] ?? '');
    $kontak_pelapor = trim($_POST['kontak_pelapor'] ?? '');
    $kategori_pengaduan = trim($_POST['kategori_pengaduan'] ?? '');
    $judul_pengaduan = trim($_POST['judul_pengaduan'] ?? '');
    $detail_pengaduan = trim($_POST['detail_pengaduan'] ?? '');
    $lokasi_kejadian = trim($_POST['lokasi_kejadian'] ?? '');
    
    $path_lampiran_db = NULL; 

    if (empty($nama_pelapor) || empty($kategori_pengaduan) || empty($judul_pengaduan) || empty($detail_pengaduan)) {
        $_SESSION['public_message'] = "Input Error: Nama, Kategori, Judul, dan Detail wajib diisi!";
        $_SESSION['public_message_type'] = "error";
        header('Location: pengaduan.php');
        exit();
    }
    
    // --- Proses Upload Lampiran yang AMAN dan LENGKAP ---
    if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] == 0) {
        $file = $_FILES['lampiran'];
        $uploadDir = 'uploads/pengaduan/';
        $maxSize = 2 * 1024 * 1024; // 2MB
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0775, true)) {
                $_SESSION['public_message'] = "Gagal membuat direktori upload.";
                $_SESSION['public_message_type'] = "error";
                header('Location: pengaduan.php'); exit();
            }
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['public_message'] = "Ukuran file lampiran maksimal 2MB.";
            $_SESSION['public_message_type'] = "error";
            header('Location: pengaduan.php'); exit();
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $_SESSION['public_message'] = "Format file tidak diizinkan (hanya JPG, PNG, PDF).";
            $_SESSION['public_message_type'] = "error";
            header('Location: pengaduan.php'); exit();
        }

        $namaFileUnik = uniqid('lampiran_') . time() . '.' . $fileExtension;
        $pathTujuan = $uploadDir . $namaFileUnik;

        if (move_uploaded_file($file['tmp_name'], $pathTujuan)) {
            $path_lampiran_db = $pathTujuan;
        } else {
            $_SESSION['public_message'] = "Gagal memindahkan file yang di-upload.";
            $_SESSION['public_message_type'] = "error";
           header('Location: pengaduan.php'); exit();
        }
    } elseif (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] != UPLOAD_ERR_NO_FILE) {
        $_SESSION['public_message'] = "Terjadi error saat upload file.";
        $_SESSION['public_message_type'] = "error";
        header('Location: pengaduan.php'); exit();
    }

    $sql = "INSERT INTO pengaduan (nama_pelapor, kontak_pelapor, kategori_pengaduan, judul_pengaduan, detail_pengaduan, lokasi_kejadian, lampiran, status_pengaduan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Baru')"; 
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssss", 
            $nama_pelapor, $kontak_pelapor, $kategori_pengaduan, 
            $judul_pengaduan, $detail_pengaduan, $lokasi_kejadian, 
            $path_lampiran_db
        );
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['public_message'] = "Pengaduan Anda berhasil dikirim. Terima kasih.";
            $_SESSION['public_message_type'] = "sukses";
        } else {
            $_SESSION['public_message'] = "Gagal menyimpan pengaduan ke database.";
            $_SESSION['public_message_type'] = "error";
            if ($path_lampiran_db && file_exists($path_lampiran_db)) {
                unlink($path_lampiran_db);
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['public_message'] = "Gagal menyiapkan query database.";
        $_SESSION['public_message_type'] = "error";
        if ($path_lampiran_db && file_exists($path_lampiran_db)) {
            unlink($path_lampiran_db);
        }
    }
    
    mysqli_close($koneksi);
    header('Location: pengaduan.php'); 
    exit();

} else {
    header('Location: index.php'); 
    exit();
}
?>