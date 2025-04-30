<?php
session_start();
require_once 'koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']); // Ambil password plain text

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = "Username dan Password wajib diisi!";
        header('Location: login.php');
        exit();
    }

    // Ambil data petugas berdasarkan username
    $sql = "SELECT id_petugas, username, password, nama_lengkap FROM petugas WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
            $petugas = mysqli_fetch_assoc($result);

            // Verifikasi password
            if (password_verify($password, $petugas['password'])) {
                // Password cocok, login berhasil!
                // Simpan data petugas ke session
                $_SESSION['id_petugas'] = $petugas['id_petugas'];
                $_SESSION['username'] = $petugas['username'];
                $_SESSION['nama_lengkap'] = $petugas['nama_lengkap'];

                // Regenerasi session ID untuk keamanan
                session_regenerate_id(true);

                // Redirect ke dashboard admin
                header('Location: admin_dashboard.php');
                exit();
            } else {
                // Password tidak cocok
                $_SESSION['login_error'] = "Username atau Password salah.";
                header('Location: login.php');
                exit();
            }
        } else {
            // Username tidak ditemukan
            $_SESSION['login_error'] = "Username atau Password salah.";
            header('Location: login.php');
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
         $_SESSION['login_error'] = "Terjadi kesalahan pada query.";
         header('Location: login.php');
         exit();
    }
    mysqli_close($koneksi);

} else {
    // Akses langsung tidak diizinkan
    header('Location: login.php');
    exit();
}
?>