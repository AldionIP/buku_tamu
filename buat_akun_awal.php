<?php
// Menonaktifkan batas waktu eksekusi jika diperlukan (opsional)
set_time_limit(60); 

// Tampilkan semua error untuk debugging (hapus atau beri komentar di produksi)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

echo "<h1>Membuat Akun Petugas Awal</h1>";

// 1. Sertakan file koneksi database Anda
require_once 'koneksi.php'; // Pastikan nama file ini benar

// Periksa koneksi
if (!$koneksi) {
    die("<p style='color:red;'><strong>Error:</strong> Koneksi database gagal.</p>");
} else {
    echo "<p style='color:green;'>Koneksi database berhasil.</p>";
}

// 2. Definisikan data akun yang ingin dibuat
//    Ganti username, password_asli, dan nama_lengkap sesuai kebutuhan Anda
$akun_petugas = [
    [
        'username' => 'dion',
        'password_asli' => '123', // Ganti dengan password kuat
        'nama_lengkap' => 'Al D yon'
    ],
    [
        'username' => 'arkan',
        'password_asli' => '123', // Ganti dengan password kuat
        'nama_lengkap' => 'arkan d piton'
    ],
    [
        'username' => 'Dian',
        'password_asli' => '123!', // Ganti dengan password kuat
        'nama_lengkap' => 'Dian d frediksen'
    ]
];

echo "<h2>Memproses Akun:</h2>";
echo "<ul>";

$semua_sukses = true;

// 3. Loop melalui setiap akun dan coba masukkan ke database
foreach ($akun_petugas as $akun) {
    $username = $akun['username'];
    $password_asli = $akun['password_asli'];
    $nama_lengkap = $akun['nama_lengkap'];

    echo "<li>Mencoba menambahkan <strong>" . htmlspecialchars($username) . "</strong>... ";

    // 4. Hash password sebelum disimpan
    $hash_password = password_hash($password_asli, PASSWORD_DEFAULT);
    if (!$hash_password) {
        echo "<span style='color:red;'>Gagal membuat hash password!</span></li>";
        $semua_sukses = false;
        continue; // Lanjut ke akun berikutnya
    }

    // 5. Gunakan Prepared Statement untuk keamanan
    $sql_check = "SELECT id_petugas FROM petugas WHERE username = ?";
    $stmt_check = mysqli_prepare($koneksi, $sql_check);
    
    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, "s", $username);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        // Cek apakah username sudah ada
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            echo "<span style='color:orange;'>Username sudah ada. Dilewati.</span></li>";
            mysqli_stmt_close($stmt_check);
            continue; // Lanjut ke akun berikutnya
        }
        mysqli_stmt_close($stmt_check);
    } else {
         echo "<span style='color:red;'>Gagal prepare check username: " . htmlspecialchars(mysqli_error($koneksi)) . "</span></li>";
         $semua_sukses = false;
         continue; // Lanjut ke akun berikutnya
    }


    // Jika username belum ada, lakukan INSERT
    $sql_insert = "INSERT INTO petugas (username, password, nama_lengkap) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($koneksi, $sql_insert);

    if ($stmt_insert) {
        mysqli_stmt_bind_param($stmt_insert, "sss", $username, $hash_password, $nama_lengkap);
        
        // Eksekusi query INSERT
        if (mysqli_stmt_execute($stmt_insert)) {
            echo "<span style='color:green;'>Berhasil ditambahkan!</span></li>";
        } else {
            echo "<span style='color:red;'>Gagal menambahkan: " . htmlspecialchars(mysqli_stmt_error($stmt_insert)) . "</span></li>";
            $semua_sukses = false;
        }
        mysqli_stmt_close($stmt_insert); // Tutup statement insert
    } else {
        // Gagal mempersiapkan statement INSERT
        echo "<span style='color:red;'>Gagal prepare insert: " . htmlspecialchars(mysqli_error($koneksi)) . "</span></li>";
        $semua_sukses = false;
    }
}

echo "</ul>";

// 6. Tutup koneksi database
mysqli_close($koneksi);

echo "<h2>Selesai</h2>";
if ($semua_sukses) {
     echo "<p style='color:blue; font-weight:bold;'>Semua proses akun selesai. Beberapa akun mungkin dilewati jika username sudah ada.</p>";
} else {
     echo "<p style='color:red; font-weight:bold;'>Terjadi error pada beberapa proses. Silakan cek pesan di atas.</p>";
}
echo "<p style='background:yellow; padding:10px; border:1px solid orange;'><strong>PENTING:</strong> Jangan lupa untuk menghapus atau memindahkan file <code>buat_akun_awal.php</code> ini dari server Anda sekarang!</p>";

?>