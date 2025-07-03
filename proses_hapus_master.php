<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['id_petugas'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['hapus_master']) && isset($_POST['id_master'])) {
    $id = intval($_POST['id_master']);
    if ($id > 0) {
        $sql = "DELETE FROM master_tamu WHERE id = ?";
        $stmt = mysqli_prepare($koneksi, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['admin_message'] = "Data master tamu (ID: $id) berhasil dihapus.";
            $_SESSION['admin_message_type'] = "sukses";
        } else {
            $_SESSION['admin_message'] = "Gagal menghapus data: " . mysqli_stmt_error($stmt);
            $_SESSION['admin_message_type'] = "error";
        }
        mysqli_stmt_close($stmt);
    }
    header('Location: admin_dashboard.php?page=master_tamu');
    exit();
}
?>