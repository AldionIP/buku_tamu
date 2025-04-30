<?php
session_start();
require_once 'koneksi.php';

// Pastikan petugas sudah login
if (!isset($_SESSION['id_petugas'])) {
    $_SESSION['login_error'] = "Aksi tidak diizinkan. Silakan login.";
    header('Location: login.php');
    exit();
}

// Pastikan form hapus disubmit dan ID tamu ada
if (isset($_POST['hapus']) && isset($_POST['id_tamu'])) {
    $id_tamu = intval($_POST['id_tamu']); // Ambil ID dan pastikan integer

    if ($id_tamu > 0) {
        $sql = "DELETE FROM tamu WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $id_tamu);
            if (mysqli_stmt_execute($stmt)) {
                // Jika berhasil hapus
                 $_SESSION['admin_message'] = "Data tamu (ID: $id_tamu) berhasil dihapus.";
                 $_SESSION['admin_message_type'] = "sukses";
            } else {
                // Jika gagal eksekusi
                 $_SESSION['admin_message'] = "Gagal menghapus data tamu: " . mysqli_stmt_error($stmt);
                 $_SESSION['admin_message_type'] = "error";
            }
            mysqli_stmt_close($stmt);
        } else {
            // Jika gagal siapkan statement
             $_SESSION['admin_message'] = "Gagal menyiapkan query hapus: " . mysqli_error($koneksi);
             $_SESSION['admin_message_type'] = "error";
        }
    } else {
         $_SESSION['admin_message'] = "ID Tamu tidak valid.";
         $_SESSION['admin_message_type'] = "error";
    }

     mysqli_close($koneksi);
     header('Location: admin_dashboard.php'); // Kembali ke dashboard
     exit();

} else {
     // Jika akses tidak sah
     header('Location: admin_dashboard.php');
     exit();
}
?>