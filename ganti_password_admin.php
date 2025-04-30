<?php
require_once 'koneksi.php'; // Sesuaikan path jika perlu

// --- KONFIGURASI ---
$username_target = 'admin'; // Masukkan username petugas yang ingin diubah passwordnya
$password_baru_plain = 'admin123'; // Masukkan password BARU yang Anda inginkan di sini
// --- AKHIR KONFIGURASI ---

// Validasi dasar
if (empty($username_target) || empty($password_baru_plain)) {
    die("Error: Username target dan password baru tidak boleh kosong dalam skrip ini.");
}

// Buat hash dari password baru
$password_baru_hashed = password_hash($password_baru_plain, PASSWORD_DEFAULT);

if ($password_baru_hashed === false) {
    die("Error: Gagal membuat hash password.");
}

echo "Password baru: " . htmlspecialchars($password_baru_plain) . "<br>";
echo "Hash yang dihasilkan: " . htmlspecialchars($password_baru_hashed) . "<br>";
echo "Mencoba mengubah password untuk user: " . htmlspecialchars($username_target) . "<br>";

// Update password di database menggunakan Prepared Statement
$sql = "UPDATE petugas SET password = ? WHERE username = ?";
$stmt = mysqli_prepare($koneksi, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $password_baru_hashed, $username_target); // ss = string, string

    if (mysqli_stmt_execute($stmt)) {
        // Cek apakah ada baris yang terpengaruh (berhasil diupdate)
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<strong>SUKSES: Password untuk username '" . htmlspecialchars($username_target) . "' berhasil diperbarui!</strong>";
        } else {
            echo "<strong>PERINGATAN: Query berhasil dijalankan, tetapi tidak ada baris yang diubah. Kemungkinan username '" . htmlspecialchars($username_target) . "' tidak ditemukan.</strong>";
        }
    } else {
        // Jika gagal eksekusi
        echo "<strong>ERROR: Gagal menjalankan update password: " . htmlspecialchars(mysqli_stmt_error($stmt)) . "</strong>";
    }
    mysqli_stmt_close($stmt);
} else {
    // Jika gagal menyiapkan statement
    echo "<strong>ERROR: Gagal menyiapkan query update: " . htmlspecialchars(mysqli_error($koneksi)) . "</strong>";
}

mysqli_close($koneksi);

echo "<hr><strong>PERHATIAN PENTING:</strong> Setelah memastikan password berhasil diubah dan Anda bisa login dengan password baru, segera <strong>HAPUS FILE INI (`ganti_password_admin.php`)</strong> dari server Anda demi keamanan!";
?>