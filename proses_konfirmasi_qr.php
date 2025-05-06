<?php
session_start();
require_once 'koneksi.php'; 

header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Gagal memproses konfirmasi.'];

// 1. Cek Login & Request Method
if (!isset($_SESSION['id_petugas'])) {
    $response['message'] = 'Akses ditolak.';
    echo json_encode($response); exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Metode request tidak valid.';
    echo json_encode($response); exit();
}
if (!$koneksi) {
    $response['message'] = 'Koneksi database gagal.';
    echo json_encode($response); exit();
}

// 2. Ambil dan Validasi Data POST
$guestId = $_POST['guestId'] ?? null;
$keperluan = trim($_POST['keperluan'] ?? '');

if (empty($guestId) || !is_numeric($guestId)) {
     $response['message'] = 'ID Tamu tidak valid.';
     echo json_encode($response); exit();
}
if (empty($keperluan)) {
     $response['message'] = 'Keperluan wajib dipilih.';
     echo json_encode($response); exit();
}

$guestId = (int)$guestId;
$tanggal_sekarang = date('Y-m-d'); // Tanggal hari ini

// 3. Generate Nomor Antrian Harian
$next_no_antrian = 1; 
$sql_max_antrian = "SELECT MAX(no_antrian_hari_ini) as max_antrian FROM tamu WHERE tanggal_antrian = ?";
$stmt_max = mysqli_prepare($koneksi, $sql_max_antrian);
if ($stmt_max) {
    mysqli_stmt_bind_param($stmt_max, "s", $tanggal_sekarang);
    if (mysqli_stmt_execute($stmt_max)) {
        $result_max = mysqli_stmt_get_result($stmt_max);
        $max_data = mysqli_fetch_assoc($result_max);
        if ($max_data && $max_data['max_antrian'] !== null) {
            $next_no_antrian = $max_data['max_antrian'] + 1;
        }
    } else { error_log("Gagal query MAX antrian (konfirmasi): " . mysqli_stmt_error($stmt_max)); }
    mysqli_stmt_close($stmt_max);
} else { error_log("Gagal prepare MAX antrian (konfirmasi): " . mysqli_error($koneksi)); }

// 4. Update Data Tamu (Keperluan, No Antrian, Tanggal Antrian)
$sql_update = "UPDATE tamu 
               SET keperluan = ?, no_antrian_hari_ini = ?, tanggal_antrian = ? 
               WHERE id = ?";
$stmt_update = mysqli_prepare($koneksi, $sql_update);

if ($stmt_update) {
    mysqli_stmt_bind_param($stmt_update, "sisi", 
        $keperluan, 
        $next_no_antrian, 
        $tanggal_sekarang, 
        $guestId
    );

    if (mysqli_stmt_execute($stmt_update)) {
        if (mysqli_stmt_affected_rows($stmt_update) > 0) {
            
            // 5. Ambil data tamu lagi untuk popup
            $sql_get_tamu = "SELECT nama, alamat, no_telp FROM tamu WHERE id = ?";
            $stmt_get = mysqli_prepare($koneksi, $sql_get_tamu);
            $tamuPopupData = null;
            if($stmt_get){
                mysqli_stmt_bind_param($stmt_get, "i", $guestId);
                 if(mysqli_stmt_execute($stmt_get)){
                      $resultGet = mysqli_stmt_get_result($stmt_get);
                      if($rowGet = mysqli_fetch_assoc($resultGet)){
                           $tamuPopupData = $rowGet; // Ambil data nama, alamat, no_telp
                           $tamuPopupData['no_antrian'] = $next_no_antrian; // Tambahkan no antrian ke data popup
                      }
                 }
                 mysqli_stmt_close($stmt_get);
            }

            if ($tamuPopupData) {
                 $response['success'] = true;
                 $response['message'] = 'Konfirmasi berhasil. Nomor antrian telah dibuat.';
                 $response['no_antrian'] = $next_no_antrian;
                 $response['tamuPopupData'] = $tamuPopupData;
            } else {
                 $response['message'] = 'Konfirmasi berhasil, tapi gagal mengambil data untuk popup.';
                 // Mungkin tetap dianggap sukses sebagian?
                 // $response['success'] = true; 
                 // $response['no_antrian'] = $next_no_antrian;
            }

        } else {
            $response['message'] = 'Gagal update data konfirmasi (affected rows = 0).';
        }
    } else {
        $response['message'] = 'Gagal execute update konfirmasi: ' . mysqli_stmt_error($stmt_update);
    }
    mysqli_stmt_close($stmt_update);
} else {
    $response['message'] = 'Gagal prepare update konfirmasi: ' . mysqli_error($koneksi);
}

mysqli_close($koneksi);
echo json_encode($response);
exit();
?>