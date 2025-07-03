<?php
require_once 'koneksi.php';

$id_kunjungan = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_kunjungan > 0) {
    $stmt = mysqli_prepare($koneksi, "SELECT nama, no_antrian_hari_ini, waktu_input FROM tamu WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id_kunjungan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {
        $nama_file = "antrian-" . $data['no_antrian_hari_ini'] . "-" . date('Ymd') . ".txt";
        
        // Header untuk memaksa download
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $nama_file . '"');

        // Isi file teks
        echo "========================================\r\n";
        echo "   BUKTI CHECK-IN - BPS KOTA BANDAR LAMPUNG\r\n";
        echo "========================================\r\n\r\n";
        echo "Nama Tamu       : " . $data['nama'] . "\r\n";
        echo "Waktu Check-in  : " . date('d F Y, H:i:s', strtotime($data['waktu_input'])) . " WIB\r\n";
        echo "\r\n";
        echo "NOMOR ANTRIAN ANDA HARI INI:\r\n";
        echo $data['no_antrian_hari_ini'] . "\r\n\r\n";
        echo "========================================\r\n";
        echo "Terima kasih atas kunjungan Anda.\r\n";
        
        exit();
    }
}
// Jika ID tidak valid atau tidak ditemukan
echo "Data kunjungan tidak valid.";
?>