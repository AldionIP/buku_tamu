<?php
session_start(); // Untuk menampilkan pesan
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Buku Tamu & Rating</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> </head>
<body class="guest-body">
     <div class="container">
        <h1>Buku Tamu & Rating Kepuasan</h1>
        <p>Terima kasih atas kunjungan Anda! Mohon isi data dan berikan rating kepuasan Anda terhadap pelayanan kami.</p>

        <div class="form-container guest-form">
             <?php
                // Tampilkan notifikasi jika ada
                if (isset($_SESSION['pesan_sukses'])) {
                    echo '<p class="pesan sukses">' . $_SESSION['pesan_sukses'] . '</p>';
                    unset($_SESSION['pesan_sukses']);
                }
                if (isset($_SESSION['pesan_error'])) {
                    echo '<p class="pesan error">' . $_SESSION['pesan_error'] . '</p>';
                    unset($_SESSION['pesan_error']);
                }
            ?>
            <form action="proses_simpan_tamu.php" method="post">
                <div class="form-group">
                    <label for="nama">Nama Anda:</label>
                    <input type="text" id="nama" name="nama" required>
                </div>
                <div class="form-group">
                    <label for="pesan">Pesan/Kesan (Opsional):</label>
                    <textarea id="pesan" name="pesan" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label>Rating Kepuasan Pelayanan:</label>
                    <div class="rating-input">
                        <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="Sangat Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="Cukup Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="Kurang Puas"><i class="fas fa-star"></i></label>
                        <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="Tidak Puas"><i class="fas fa-star"></i></label>
                    </div>
                </div>
                <button type="submit" name="submit_tamu">Kirim Data</button>
            </form>
             <p style="margin-top: 20px; text-align:center;"><a href="index.php">Kembali ke Halaman Utama</a></p>
        </div>
    </div>
</body>
</html>