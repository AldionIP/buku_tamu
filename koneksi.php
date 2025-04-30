<?php
// Informasi koneksi database
$db_host = "localhost"; // Biasanya localhost
$db_user = "root";      // User default XAMPP/MAMP
$db_pass = "";          // Password default XAMPP/MAMP kosong
$db_name = "db_bukutamu"; // Nama database yang tadi dibuat

// Membuat koneksi
$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Cek koneksi
if (!$koneksi) {
    // die() akan menghentikan eksekusi skrip dan menampilkan pesan error
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Jika ingin menampilkan pesan sukses (opsional, bisa dihapus nanti)
// echo "Koneksi Berhasil!";

// Set charset (opsional tapi bagus untuk karakter non-latin)
mysqli_set_charset($koneksi, "utf8mb4");
?>