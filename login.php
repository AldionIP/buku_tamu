<?php
session_start();
// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_petugas'])) {
    header('Location: admin_dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Buku Tamu Digital XYZ</title>
    <link rel="stylesheet" href="style.css"> 
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* CSS KHUSUS UNTUK HALAMAN LOGIN */
        :root {
            --primary-color-login: #3498db; /* Biru yang sedikit lebih cerah */
            --secondary-color-login: #2c3e50; /* Biru gelap untuk teks */
            --background-gradient-start: #6dd5ed;
            --background-gradient-end: #2193b0;
            --form-bg-color: #ffffff;
            --input-border-color: #ddd;
            --input-focus-border-color: var(--primary-color-login);
            --text-color-light: #7f8c8d;
            --error-bg-color: #f8d7da;
            --error-text-color: #721c24;
            --error-border-color: #f5c6cb;
        }

        body.login-body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-image: linear-gradient(to right top, var(--background-gradient-start), #4DB6AC, var(--background-gradient-end)); /* Gradient background */
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            box-sizing: border-box;
        }

        .login-container {
            background-color: var(--form-bg-color);
            padding: 40px 35px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px; /* Lebar maksimum form */
            text-align: center;
        }

        .login-container h1 {
            color: var(--secondary-color-login);
            font-size: 2em; /* Lebih besar */
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .login-container .welcome-text {
            color: var(--text-color-light);
            font-size: 0.95em;
            margin-bottom: 30px;
        }

        .pesan.error { /* Style pesan error */
            background-color: var(--error-bg-color);
            color: var(--error-text-color);
            border: 1px solid var(--error-border-color);
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 0.9em;
        }

        .form-group {
            margin-bottom: 25px; /* Jarak antar form group */
            text-align: left; /* Label rata kiri */
            position: relative; /* Untuk ikon di dalam input */
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: var(--secondary-color-login);
            margin-bottom: 8px;
            font-size: 0.9em;
        }

        .form-group .input-icon-wrapper {
            position: relative;
        }

        .form-group i.input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-color-light);
            font-size: 0.9em;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 15px 12px 40px; /* Padding kiri untuk ikon */
            border: 1px solid var(--input-border-color);
            border-radius: 8px; /* Sudut lebih tumpul */
            box-sizing: border-box;
            font-size: 1em;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            border-color: var(--input-focus-border-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2); /* Bayangan saat focus */
        }

        .btn.btn-utama { /* Tombol Login */
            display: block;
            width: 100%;
            padding: 14px 20px;
            background-color: var(--primary-color-login);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px; /* Jarak dari form group terakhir */
        }

        .btn.btn-utama:hover {
            background-color: #2980b9; /* Warna hover sedikit lebih gelap */
            transform: translateY(-2px); /* Efek sedikit naik saat hover */
        }
        
        .login-footer {
            margin-top: 30px;
            font-size: 0.85em;
            color: var(--text-color-light);
        }
        .login-footer a {
            color: var(--primary-color-login);
            text-decoration: none;
            font-weight: 500;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive untuk layar lebih kecil */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
            }
            .login-container h1 {
                font-size: 1.8em;
            }
        }

    </style>
</head>
<body class="login-body">
    <div class="login-container">
        <h1>Selamat Datang Kembali!</h1>
        <p class="welcome-text">Silakan login untuk mengakses dasbor admin.</p>

        <?php
        // Tampilkan pesan error jika ada
        if (isset($_SESSION['login_error'])) {
            echo '<p class="pesan error"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($_SESSION['login_error']) . '</p>';
            unset($_SESSION['login_error']);
        }
        ?>

        <form action="proses_login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                 <div class="input-icon-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-utama">Login Sekarang</button>
        </form>
        
        <div class="login-footer">
            <p>&copy; <?php echo date("Y"); ?> Buku Tamu Digital BPS. <br> <a href="index.php">Kembali ke Halaman Utama</a></p>
        </div>
    </div>
</body>
</html>