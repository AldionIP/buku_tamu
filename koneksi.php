<?php
// SET ZONA WAKTU KE ASIA/JAKARTA (WIB)
date_default_timezone_set('Asia/Jakarta');

// Informasi koneksi database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "db_bukutamu";

// Membuat koneksi
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8mb4");
?>