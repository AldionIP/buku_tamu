<?php
session_start();
require_once 'koneksi.php';

// Jika sudah login, redirect
if (isset($_SESSION['id_petugas'])) {
    header('Location: admin_dashboard.php');
    exit();
}

// Pastikan form disubmit
if (isset($_POST['login'])) {
    if (!$koneksi) {
        $_SESSION['login_error'] = "Koneksi database gagal.";
        header('Location: login.php');
        exit();
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Username dan password wajib diisi.";
        header('Location: login.php');
        exit();
    }

    $sql = "SELECT id_petugas, username, password, nama_lengkap FROM petugas WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $petugas = mysqli_fetch_assoc($result);

        // Verifikasi password
        if ($petugas && password_verify($password, $petugas['password'])) {
            // Login sukses, set session
            session_regenerate_id(true); // Keamanan
            $_SESSION['id_petugas'] = $petugas['id_petugas'];
            $_SESSION['username'] = $petugas['username'];
            $_SESSION['nama_lengkap'] = $petugas['nama_lengkap'];
            
            mysqli_stmt_close($stmt);
            mysqli_close($koneksi);
            header('Location: admin_dashboard.php');
            exit();
        } else {
            // Login gagal
            $_SESSION['login_error'] = "Username atau password salah.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['login_error'] = "Terjadi kesalahan pada query database.";
    }
    
    mysqli_close($koneksi);
    header('Location: login.php');
    exit();

} else {
    // Jika akses langsung ke file
    header('Location: login.php');
    exit();
}
?>