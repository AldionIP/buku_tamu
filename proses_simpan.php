<?php
// Memulai session untuk menyimpan pesan notifikasi
session_start();

// Memanggil file koneksi
require_once 'koneksi.php';

// Cek apakah form sudah disubmit
if (isset($_POST['submit'])) {

    // Ambil data dari form dan bersihkan (dasar)
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $pesan = mysqli_real_escape_string($koneksi, trim($_POST['pesan']));

    // Validasi dasar (tidak boleh kosong)
    if (empty($nama) || empty($pesan)) {
        $_SESSION['pesan_error'] = "Nama dan Pesan tidak boleh kosong!";
        header('Location: index.php'); // Kembali ke form
        exit(); // Hentikan skrip
    }

    // ---- Menggunakan Prepared Statements (LEBIH AMAN!) ----
    $sql_insert = "INSERT INTO tamu (nama, pesan) VALUES (?, ?)";

    // Siapkan statement
    $stmt = mysqli_prepare($koneksi, $sql_insert);

    if ($stmt) {
        // Ikat parameter ke statement (s = string)
        mysqli_stmt_bind_param($stmt, "ss", $nama, $pesan);

        // Eksekusi statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil
            $_SESSION['pesan_sukses'] = "Terima kasih! Data Anda berhasil disimpan.";
        } else {
            // Jika gagal eksekusi
            $_SESSION['pesan_error'] = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
        }
        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        // Jika gagal menyiapkan statement
        $_SESSION['pesan_error'] = "Gagal menyiapkan query: " . mysqli_error($koneksi);
    }
    // ---- Akhir Prepared Statements ----

    // Tutup koneksi
    mysqli_close($koneksi);

    // Redirect kembali ke halaman utama setelah proses selesai
    header('Location: index.php');
    exit();

} else {
    // Jika file diakses langsung tanpa submit form, redirect ke halaman utama
    header('Location: index.php');
    exit();
}
?>