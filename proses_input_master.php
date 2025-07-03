<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['id_petugas'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit_master'])) {
    // ID tidak lagi diambil dari form
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $pekerjaan = trim($_POST['pekerjaan'] ?? '');
    $rating = isset($_POST['rating']) && is_numeric($_POST['rating']) ? intval($_POST['rating']) : NULL;

    // Validasi hanya nama
    if (empty($nama)) {
        $_SESSION['admin_message'] = "Error: Nama tidak boleh kosong.";
        $_SESSION['admin_message_type'] = "error";
        header('Location: admin_dashboard.php?page=master_tamu');
        exit();
    }

    // Query INSERT sekarang tanpa kolom 'id'
    $sql = "INSERT INTO master_tamu (nama, email, no_hp, alamat, pekerjaan, rating) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql);
    
    if ($stmt_insert) {
        // bind_param sekarang tanpa 'i' untuk id
        mysqli_stmt_bind_param($stmt_insert, "sssssi", $nama, $email, $no_hp, $alamat, $pekerjaan, $rating);
        
        if (mysqli_stmt_execute($stmt_insert)) {
            $_SESSION['admin_message'] = "Data master tamu untuk '".htmlspecialchars($nama)."' berhasil ditambahkan.";
            $_SESSION['admin_message_type'] = "sukses";
        } else {
            $_SESSION['admin_message'] = "Gagal menambahkan data: " . mysqli_stmt_error($stmt_insert);
            $_SESSION['admin_message_type'] = "error";
        }
        mysqli_stmt_close($stmt_insert);
    } else {
         $_SESSION['admin_message'] = "Gagal menyiapkan query insert.";
         $_SESSION['admin_message_type'] = "error";
    }
    
    mysqli_close($koneksi);
    header('Location: admin_dashboard.php?page=master_tamu');
    exit();
}
?>