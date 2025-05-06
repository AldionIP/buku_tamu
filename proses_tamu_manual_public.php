<?php
session_start(); // Perlu untuk set pesan/data popup
require_once 'koneksi.php'; 

// Hanya proses jika method POST & ada submit_manual
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_manual'])) {

    if (!$koneksi) {
         $_SESSION['public_message'] = "Database Error: Koneksi gagal.";
         $_SESSION['public_message_type'] = "error";
         header('Location: index.php#input-manual'); 
         exit();
    }

    // Ambil & Bersihkan Data 
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama_manual'] ?? ''));
    $alamat = mysqli_real_escape_string($koneksi, trim($_POST['alamat_manual'] ?? ''));
    $keperluan = trim($_POST['keperluan_manual'] ?? ''); // Dari dropdown
    $no_telp = mysqli_real_escape_string($koneksi, trim($_POST['no_telp_manual'] ?? ''));
    $waktu_sekarang = date('Y-m-d H:i:s');
    $tanggal_sekarang = date('Y-m-d');

    // Validasi Sederhana
    if (empty($nama) || empty($keperluan)) {
        $_SESSION['public_message'] = "Input Error: Nama dan Keperluan wajib diisi!";
        $_SESSION['public_message_type'] = "error";
        header('Location: index.php#input-manual'); 
        exit();
    }

    // Logika Transaksi dan Nomor Antrian
    mysqli_begin_transaction($koneksi);
    $popup_data_untuk_session = null;

    try {
        // 1. INSERT Tamu (tanpa no antrian dulu, tapi dengan tanggal antrian)
        $sql_insert = "INSERT INTO tamu (nama, alamat, keperluan, no_telp, waktu_input, tanggal_antrian) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($koneksi, $sql_insert);
        if (!$stmt_insert) throw new Exception("Gagal prepare insert tamu: " . mysqli_error($koneksi));
        
        mysqli_stmt_bind_param($stmt_insert, "ssssss", $nama, $alamat, $keperluan, $no_telp, $waktu_sekarang, $tanggal_sekarang);
        if (!mysqli_stmt_execute($stmt_insert)) throw new Exception("Gagal simpan data tamu: " . mysqli_stmt_error($stmt_insert));
        
        $last_id = mysqli_insert_id($koneksi);
        mysqli_stmt_close($stmt_insert);
        if (!$last_id > 0) throw new Exception("Gagal mendapatkan ID tamu yang baru disimpan.");

        // 2. Generate & Update No Antrian
        $next_no_antrian = 1;
        $sql_max = "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?";
        $stmt_max = mysqli_prepare($koneksi, $sql_max);
        if (!$stmt_max) throw new Exception("Gagal prepare max antrian: " . mysqli_error($koneksi));
        
        mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
        if (!mysqli_stmt_execute($stmt_max)) throw new Exception("Gagal query max antrian: " . mysqli_stmt_error($stmt_max));
        
        $res_max = mysqli_stmt_get_result($stmt_max);
        $max_d = mysqli_fetch_assoc($res_max);
        if ($max_d && $max_d['max_antrian'] !== null) { $next_no_antrian = $max_d['max_antrian'] + 1; }
        mysqli_stmt_close($stmt_max);

        $sql_upd_antrian = "UPDATE tamu SET no_antrian_hari_ini = ? WHERE id = ?";
        $stmt_upd_antrian = mysqli_prepare($koneksi, $sql_upd_antrian);
        if (!$stmt_upd_antrian) throw new Exception("Gagal prepare update antrian: " . mysqli_error($koneksi));
        
        mysqli_stmt_bind_param($stmt_upd_antrian, "ii", $next_no_antrian, $last_id);
        if (!mysqli_stmt_execute($stmt_upd_antrian)) throw new Exception("Gagal update antrian: " . mysqli_stmt_error($stmt_upd_antrian));
        mysqli_stmt_close($stmt_upd_antrian);

        mysqli_commit($koneksi); // Commit jika semua OK

        // Siapkan data untuk popup di halaman index via Session
        $_SESSION['popup_data_manual'] = [
             'nama' => $nama,
             'alamat' => $alamat,
             'no_telp' => $no_telp,
             'no_antrian' => $next_no_antrian
        ];
        // Pesan sukses umum (popup akan lebih detail)
        // $_SESSION['public_message'] = "Data Anda berhasil disimpan!"; 
        // $_SESSION['public_message_type'] = "sukses";

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $_SESSION['public_message'] = "Error: Gagal menyimpan data. " . $e->getMessage();
        $_SESSION['public_message_type'] = "error";
        error_log("Transaksi Gagal (Public Manual Input): " . $e->getMessage());
    }

    mysqli_close($koneksi);
    header('Location: index.php#input-manual'); // Redirect kembali ke section manual
    exit();

} else {
    // Jika akses tidak sesuai
    header('Location: index.php');
    exit();
}
?>