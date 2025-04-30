<?php
session_start();
require_once 'koneksi.php';

if (isset($_POST['submit_tamu'])) {
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    // Pesan boleh kosong, jadi tidak perlu validasi empty, tapi tetap escape
    $pesan = mysqli_real_escape_string($koneksi, trim($_POST['pesan']));
    // Ambil rating, pastikan itu angka antara 1-5
    $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 0;

    // Validasi
    if (empty($nama)) {
        $_SESSION['pesan_error'] = "Nama wajib diisi!";
        header('Location: isi_tamu.php');
        exit();
    }
    if ($rating < 1 || $rating > 5) {
         $_SESSION['pesan_error'] = "Silakan pilih rating kepuasan (1-5 bintang)!";
         header('Location: isi_tamu.php');
         exit();
    }

    // Simpan ke database menggunakan Prepared Statements
    $sql = "INSERT INTO tamu (nama, pesan, rating) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $pesan, $rating); // ssi = string, string, integer

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Terima kasih $nama, data dan rating Anda berhasil disimpan!";
        } else {
             $_SESSION['pesan_error'] = "Terjadi kesalahan saat menyimpan data: " . mysqli_stmt_error($stmt);
        }
         mysqli_stmt_close($stmt);
    } else {
         $_SESSION['pesan_error'] = "Gagal menyiapkan query: " . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
    header('Location: isi_tamu.php'); // Kembali ke form untuk lihat pesan sukses/error
    exit();

} else {
    // Akses langsung tidak diizinkan
    header('Location: index.php');
    exit();
}
?>