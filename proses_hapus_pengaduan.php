<?php
session_start();
require_once 'koneksi.php';

// Pastikan petugas sudah login
if (!isset($_SESSION['id_petugas'])) {
    header('Location: login.php');
    exit();
}

// Pastikan form hapus disubmit dan ID pengaduan ada
if (isset($_POST['hapus_pengaduan']) && isset($_POST['id_pengaduan'])) {
    $id_pengaduan = intval($_POST['id_pengaduan']);

    if ($id_pengaduan > 0) {
        // Hapus juga file lampiran jika ada sebelum menghapus record DB
        $sql_get_lampiran = "SELECT lampiran FROM pengaduan WHERE id_pengaduan = ?";
        $stmt_get = mysqli_prepare($koneksi, $sql_get_lampiran);
        mysqli_stmt_bind_param($stmt_get, "i", $id_pengaduan);
        mysqli_stmt_execute($stmt_get);
        $result = mysqli_stmt_get_result($stmt_get);
        if ($row = mysqli_fetch_assoc($result)) {
            if (!empty($row['lampiran']) && file_exists($row['lampiran'])) {
                unlink($row['lampiran']); // Hapus file dari server
            }
        }
        mysqli_stmt_close($stmt_get);

        // Lanjutkan menghapus record dari database
        $sql = "DELETE FROM pengaduan WHERE id_pengaduan = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id_pengaduan);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['admin_message'] = "Pengaduan (ID: $id_pengaduan) berhasil dihapus.";
            $_SESSION['admin_message_type'] = "sukses";
        } else {
            $_SESSION['admin_message'] = "Gagal menghapus pengaduan: " . mysqli_stmt_error($stmt);
            $_SESSION['admin_message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['admin_message'] = "ID Pengaduan tidak valid.";
        $_SESSION['admin_message_type'] = "error";
    }

    header('Location: admin_dashboard.php?page=lihat_pengaduan');
    exit();

} else {
    // Jika akses tidak sah
    header('Location: admin_dashboard.php');
    exit();
}
?>