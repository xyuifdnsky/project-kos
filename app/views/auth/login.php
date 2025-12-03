<?php
session_start();

include '../../../config/database.php';
$conn = databaseconfig::getConnection();

$error = '';
$title = "Login";

// Jika sudah login → langsung lempar ke dashboard sesuai role
if (isset($_SESSION['user'])) {
    switch ($_SESSION['user']['role']) {
        case 'admin': header("Location: dashboard/pemilik/admin/admin.php"); exit;
        case 'pemilik': header("Location: dashboard/pemilik/pemilik.php"); exit;
        default: header("Location: dashboard/penyewa/penyewa.php"); exit;
    }
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");

    if ($query && mysqli_num_rows($query) === 1) {

        $user = mysqli_fetch_assoc($query);

        // Verifikasi password
        if (password_verify($password, $user['password'])) {

            // 💥 FIX UTAMA → hapus session lama biar tidak tertukar
            session_unset();

            // Buat session baru secara aman
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            // Redirect sesuai role
           switch ($user['role']) {
   case 'admin':

    // Jika admin memiliki id_pemilik → masuk ke dashboard admin pemilik
    if (!empty($user['id_pemilik']) && $user['id_pemilik'] > 0) {
        header("Location: dashboard/pemilik/admin/admin.php");
        exit;
    }

    case 'pemilik':
        header("Location: dashboard/pemilik/pemilik.php");
        exit;
    default:
        header("Location: dashboard/penyewa/penyewa.php");
        exit;
}
        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $title ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background:#f8f9fa; }
.login-box { max-width:380px;margin:70px auto;padding:20px;background:#fff;border-radius:8px;box-shadow:0 0 8px rgba(0,0,0,0.1); }
.header-login { display:flex; align-items:center; margin-bottom:15px; }
.back-icon { font-size:18px; color:#333; margin-right:5px; text-decoration:none; }
.title { font-size:17px;font-weight:bold; }
button { width:100%; }
</style>
</head>
<body>

<div class="login-box">
    <div class="header-login">
        <a href="javascript:history.back()" class="back-icon"><i class="bi bi-arrow-left"></i></a>
        <span class="title"><?= $title ?></span>
    </div>

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">LOGIN</button>

        <div class="text-center mt-3">
            Belum punya akun? 
            <a href="register.php" style="color:#305FCA;font-weight:600;">Daftar</a>
        </div>
    </form>
</div>

</body>
</html>
