<?php
require_once 'koneksi.php'; // Pastikan path benar

$username = 'admin';
$password_plain = 'password_admin_rahasia'; 
$nama_lengkap = 'Petugas';

$password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

$sql = "INSERT INTO petugas (username, password, nama_lengkap) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($koneksi, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $username, $password_hashed, $nama_lengkap);
    if (mysqli_stmt_execute($stmt)) {
        echo "Admin berhasil dibuat!";
    } else {
        echo "Gagal membuat admin: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
     echo "Gagal menyiapkan query: " . mysqli_error($koneksi);
}
mysqli_close($koneksi);
?>