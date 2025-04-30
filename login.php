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
    <title>Login Petugas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <h1>Login Petugas</h1>
        <?php
        // Tampilkan pesan error jika ada
        if (isset($_SESSION['login_error'])) {
            echo '<p class="pesan error">' . $_SESSION['login_error'] . '</p>';
            unset($_SESSION['login_error']);
        }
        ?>
        <form action="proses_login.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-utama">Login</button>
        </form>
    </div>
</body>
</html>