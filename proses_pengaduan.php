<?php
session_start();
require_once 'koneksi.php'; // Sesuaikan path jika perlu

// 1. Keamanan Dasar: Pastikan hanya petugas yang login yang bisa submit
if (!isset($_SESSION['id_petugas'])) {
    // Jika belum login, kembalikan ke halaman login
    $_SESSION['login_error'] = "Akses ditolak. Silakan login ulang.";
    header('Location: login.php');
    exit();
}

// 2. Cek apakah form disubmit dengan benar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_pengaduan'])) {

    // 3. Ambil data dari form dan bersihkan (basic cleaning)
    $id_petugas_pencatat = $_SESSION['id_petugas']; // Ambil dari session
    $nama_pelapor = trim($_POST['nama_pelapor'] ?? '');
    $kontak_pelapor = trim($_POST['kontak_pelapor'] ?? '');
    $kategori_pengaduan = trim($_POST['kategori_pengaduan'] ?? '');
    $judul_pengaduan = trim($_POST['judul_pengaduan'] ?? '');
    $detail_pengaduan = trim($_POST['detail_pengaduan'] ?? '');
    $lokasi_kejadian = trim($_POST['lokasi_kejadian'] ?? '');
    // Format waktu kejadian jika ada, jika tidak set NULL
    $waktu_kejadian = !empty($_POST['waktu_kejadian']) ? date('Y-m-d H:i:s', strtotime($_POST['waktu_kejadian'])) : NULL;

    $path_lampiran_db = NULL; // Default path lampiran kosong

    // 4. Validasi Input Dasar (Tambahkan validasi lain sesuai kebutuhan)
    if (empty($nama_pelapor) || empty($kategori_pengaduan) || empty($judul_pengaduan) || empty($detail_pengaduan)) {
        $_SESSION['pengaduan_message'] = "Input Error: Nama Pelapor, Kategori, Judul, dan Detail wajib diisi!";
        $_SESSION['pengaduan_message_type'] = "error";
        header('Location: admin_dashboard.php?page=pengaduan');
        exit();
    }

    // 5. Proses Upload Lampiran (jika ada)
    if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] == 0) {
        $file = $_FILES['lampiran'];
        $uploadDir = 'uploads/pengaduan/'; // Pastikan folder ini ada dan writable!
        $maxSize = 2 * 1024 * 1024; // 2MB
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];

        // Buat folder jika belum ada
        if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
             if (!mkdir($uploadDir, 0775, true)) { // Coba buat rekursif
                $_SESSION['pengaduan_message'] = "Upload Error: Gagal membuat folder upload.";
                $_SESSION['pengaduan_message_type'] = "error";
                header('Location: admin_dashboard.php?page=pengaduan');
                exit();
             }
        }

        if (!is_writable($uploadDir)) {
             $_SESSION['pengaduan_message'] = "Upload Error: Folder upload tidak writable.";
             $_SESSION['pengaduan_message_type'] = "error";
             header('Location: admin_dashboard.php?page=pengaduan');
             exit();
        }

        // Validasi Ukuran
        if ($file['size'] > $maxSize) {
            $_SESSION['pengaduan_message'] = "Upload Error: Ukuran file lampiran maksimal 2MB.";
            $_SESSION['pengaduan_message_type'] = "error";
            header('Location: admin_dashboard.php?page=pengaduan');
            exit();
        }

        // Validasi Tipe File (lebih aman cek mime type)
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $file['tmp_name']);
        finfo_close($fileInfo);

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($mimeType, $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
             $_SESSION['pengaduan_message'] = "Upload Error: Format file tidak diizinkan (Hanya JPG, PNG, PDF).";
             $_SESSION['pengaduan_message_type'] = "error";
             header('Location: admin_dashboard.php?page=pengaduan');
             exit();
        }

        // Buat nama file unik untuk menghindari tumpang tindih
        $namaFileUnik = uniqid('lampiran_') . '_' . time() . '.' . $fileExtension;
        $pathTujuan = $uploadDir . $namaFileUnik;

        // Pindahkan file upload
        if (move_uploaded_file($file['tmp_name'], $pathTujuan)) {
            $path_lampiran_db = $pathTujuan; // Simpan path relatif ke database
        } else {
            $_SESSION['pengaduan_message'] = "Upload Error: Gagal memindahkan file.";
            $_SESSION['pengaduan_message_type'] = "error";
            header('Location: admin_dashboard.php?page=pengaduan');
            exit();
        }
    } elseif (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] != 4) { // Error selain 'No file was uploaded'
         $_SESSION['pengaduan_message'] = "Upload Error: Terjadi masalah saat upload file (Error code: " . $_FILES['lampiran']['error'] . ").";
         $_SESSION['pengaduan_message_type'] = "error";
         header('Location: admin_dashboard.php?page=pengaduan');
         exit();
    }

    // 6. Simpan data ke database menggunakan Prepared Statement
    $sql = "INSERT INTO pengaduan (id_petugas_pencatat, nama_pelapor, kontak_pelapor, kategori_pengaduan, judul_pengaduan, detail_pengaduan, lokasi_kejadian, waktu_kejadian, lampiran, status_pengaduan) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Baru')"; // Status awal 'Baru'

    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter: i=integer, s=string
        mysqli_stmt_bind_param($stmt, "issssssss", 
            $id_petugas_pencatat, 
            $nama_pelapor, 
            $kontak_pelapor, 
            $kategori_pengaduan, 
            $judul_pengaduan, 
            $detail_pengaduan, 
            $lokasi_kejadian, 
            $waktu_kejadian, 
            $path_lampiran_db
        );

        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil
            $_SESSION['pengaduan_message'] = "Pengaduan berhasil disimpan.";
            $_SESSION['pengaduan_message_type'] = "sukses";
        } else {
            // Jika gagal eksekusi
            $_SESSION['pengaduan_message'] = "Database Error: Gagal menyimpan pengaduan. " . mysqli_stmt_error($stmt);
            $_SESSION['pengaduan_message_type'] = "error";
             // Jika gagal simpan DB, hapus file yg mungkin sudah terupload
            if ($path_lampiran_db && file_exists($path_lampiran_db)) {
                unlink($path_lampiran_db);
            }
        }
        mysqli_stmt_close($stmt); // Tutup statement
    } else {
        // Jika gagal prepare statement
        $_SESSION['pengaduan_message'] = "Database Error: Gagal mempersiapkan penyimpanan. " . mysqli_error($koneksi);
        $_SESSION['pengaduan_message_type'] = "error";
         if ($path_lampiran_db && file_exists($path_lampiran_db)) {
             unlink($path_lampiran_db);
         }
    }

    mysqli_close($koneksi); // Tutup koneksi

    // 7. Redirect kembali ke halaman form pengaduan
    header('Location: admin_dashboard.php?page=pengaduan');
    exit();

} else {
    // Jika akses langsung ke file ini atau form tidak disubmit
    $_SESSION['pengaduan_message'] = "Akses tidak sah.";
    $_SESSION['pengaduan_message_type'] = "error";
    header('Location: admin_dashboard.php?page=pengaduan'); // Atau ke halaman lain
    exit();
}
?>