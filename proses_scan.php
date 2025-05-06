<?php 
 session_start(); 
 header('Content-Type: application/json'); 
 require_once 'koneksi.php'; 

 $response = ['success' => false, 'message' => 'Terjadi kesalahan.']; 

 if (!isset($_SESSION['id_petugas'])) { 
     $response['message'] = 'Akses ditolak.'; 
     echo json_encode($response); 
     exit(); 
 } 

 if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
      $response['message'] = 'Metode request tidak valid.'; 
      echo json_encode($response); 
      exit(); 
 } 

 $input = file_get_contents('php://input'); 
 $data = json_decode($input, true); 

 if (!isset($data['guestId']) || !filter_var($data['guestId'], FILTER_VALIDATE_INT)) { 
     $response['message'] = 'ID Tamu tidak valid dari data scan.'; 
     echo json_encode($response); 
     exit(); 
 } 

 if (!$koneksi) {
     $response['message'] = 'Koneksi database gagal.';
     echo json_encode($response);
     exit();
 }

 $guestId = (int)$data['guestId']; 
 $statusHadir = "Hadir"; 
 $waktuScan = date('Y-m-d H:i:s'); 

 // --- Cek dulu apakah tamu sudah hadir atau ID tidak ada ---
 $sql_check = "SELECT id, nama, alamat, no_telp, status_kehadiran FROM tamu WHERE id = ?";
 $stmt_check = mysqli_prepare($koneksi, $sql_check);
 $tamuData = null;
 $sudahHadir = false;
 $tamuDitemukan = false;

 if ($stmt_check) {
     mysqli_stmt_bind_param($stmt_check, "i", $guestId);
     if (mysqli_stmt_execute($stmt_check)) {
         $resultCheck = mysqli_stmt_get_result($stmt_check);
         if ($rowCheck = mysqli_fetch_assoc($resultCheck)) {
             $tamuDitemukan = true;
             $tamuData = $rowCheck; // Simpan data tamu
             if ($rowCheck['status_kehadiran'] == 'Hadir') {
                 $sudahHadir = true;
             }
         }
     } else {
         $response['message'] = 'Gagal memeriksa status tamu: ' . htmlspecialchars(mysqli_stmt_error($stmt_check));
         mysqli_stmt_close($stmt_check);
         mysqli_close($koneksi);
         echo json_encode($response); 
         exit(); 
     }
      mysqli_stmt_close($stmt_check);
 } else {
      $response['message'] = 'Gagal menyiapkan query check: ' . htmlspecialchars(mysqli_error($koneksi));
      mysqli_close($koneksi);
      echo json_encode($response); 
      exit(); 
 }

 // Jika tamu tidak ditemukan
 if (!$tamuDitemukan) {
      $response['message'] = 'ID Tamu (' . $guestId . ') tidak ditemukan di database.'; 
      mysqli_close($koneksi);
      echo json_encode($response); 
      exit(); 
 }

 // Jika sudah hadir, kirim respons spesifik
 if ($sudahHadir) {
      $response['success'] = false; 
      $response['message'] = 'Tamu "' . htmlspecialchars($tamuData['nama']) . '" (ID: ' . $guestId . ') sudah tercatat HADIR sebelumnya.';
      mysqli_close($koneksi);
      echo json_encode($response); 
      exit(); 
 }

 // --- Jika belum hadir dan ID ada, Lakukan UPDATE status hadir ---
 $sql_update = "UPDATE tamu SET status_kehadiran = ?, waktu_scan_masuk = ? WHERE id = ?"; 
 $stmt_update = mysqli_prepare($koneksi, $sql_update); 

 if ($stmt_update) { 
     mysqli_stmt_bind_param($stmt_update, "ssi", $statusHadir, $waktuScan, $guestId); 

     if (mysqli_stmt_execute($stmt_update)) { 
         if (mysqli_stmt_affected_rows($stmt_update) > 0) { 
             $response['success'] = true; 
             $response['message'] = 'Tamu ditemukan. Silakan konfirmasi keperluan.'; 
             $response['tamuData'] = [ 
                 'id' => $guestId,
                 'nama' => $tamuData['nama'],
                 'alamat' => $tamuData['alamat'],
                 'no_telp' => $tamuData['no_telp']
             ]; 
         } else { 
             $response['message'] = 'Gagal update, status tidak berubah (affected rows = 0).'; 
         } 
     } else { 
         $response['message'] = 'Gagal mengupdate database: ' . htmlspecialchars(mysqli_stmt_error($stmt_update)); 
     } 
     mysqli_stmt_close($stmt_update); 
 } else { 
     $response['message'] = 'Gagal menyiapkan query update: ' . htmlspecialchars(mysqli_error($koneksi)); 
 } 

 if (isset($koneksi) && $koneksi instanceof mysqli) { 
     mysqli_close($koneksi); 
 } 

 echo json_encode($response); 
 exit(); 
 ?>