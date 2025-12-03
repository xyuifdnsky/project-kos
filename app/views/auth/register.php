<?php  
include '../../../config/database.php';
$conn = databaseconfig::getConnection();
$_SESSION['from_login'] = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_user = mysqli_real_escape_string($conn, $_POST['nama_user']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon   = mysqli_real_escape_string($conn, $_POST['telepon']);
    $password  = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);
    $role      = mysqli_real_escape_string($conn, $_POST['role']);

    // Validasi role
    if ($role !== 'pemilik' && $role !== 'penyewa') {
        die("Role tidak valid!");
    }

    // Validasi ulang password
    if ($password !== $password2) {
        echo "<script>alert('Konfirmasi password tidak cocok!');history.back();</script>";
        exit;
    }

    $passHash = password_hash($password, PASSWORD_DEFAULT);

    // Cek username/email
    $cek = mysqli_query($conn, "SELECT id_user FROM users WHERE username='$username' OR email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username atau Email sudah digunakan!');history.back();</script>";
        exit;
    }

    // Insert user
    $sql = "INSERT INTO users (username, nama_user, telepon, email, password, role)
            VALUES ('$username', '$nama_user', '$telepon', '$email', '$passHash', '$role')";

    if (mysqli_query($conn, $sql)) {
        // Redirect ke login, tidak langsung login
        header("Location: login.php");
        exit;
    }
}

// Ambil role dari URL
$login_role = isset($_GET['role']) ? htmlspecialchars($_GET['role']) : 'penyewa';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrasi - Kost App</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
body { font-family: 'Plus Jakarta Sans', sans-serif; background: #F7F7F7; padding: 2rem; }
.title { font-weight: 700; font-size: 26px; }
.input-group-text { background: #E6EEFF; border: 1px solid #CED4DA; }
.form-control { background: #F5F8FF; border: 1px solid #CED4DA; padding: 12px; }
.form-control:focus { border-color: #305FCA; box-shadow: none; }
.btn-register { background-color: #305FCA; color: white; padding: 13px; font-weight: 600; }
.btn-register:hover { background-color: #21469A; }
.checkbox-text { font-size: 14px; color: #555; }
.back-icon { font-size: 22px; color: black; text-decoration:none; margin-right:5px; }
</style>
</head>
<body>

<div class="container" style="max-width:600px;">
    <a href="javascript:history.back()" class="back-icon"><i class="bi bi-arrow-left"></i></a>
    <span class="title">Daftar Akun <?= ($login_role=='pemilik')?'Pemilik Kos':'Pencari Kos' ?></span>

    <form class="mt-4" method="POST">

        <div class="mb-3">
            <label class="fw-semibold">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_user" placeholder="Masukkan nama lengkap sesuai identitas" required>
        </div>

        <div class="mb-3">
            <label class="fw-semibold">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
        </div>

        <div class="mb-3">
            <label class="fw-semibold">Nomor Handphone</label>
            <input type="tel" class="form-control" name="telepon" placeholder="Isi dengan nomor handphone yang aktif" required>
        </div>

        <div class="mb-3">
            <label class="fw-semibold">Email</label>
            <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
        </div>

        <div class="mb-3">
            <label class="fw-semibold">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password" placeholder="Masukkan password" required>
                <span class="input-group-text"><i class="bi bi-eye-slash" style="cursor:pointer;" onclick="togglePass(this)"></i></span>
            </div>
        </div>

        <div class="mb-3">
            <label class="fw-semibold">Ulangi Password</label>
            <div class="input-group">
                <input type="password" class="form-control" name="password2" placeholder="Masukkan kembali password" required>
                <span class="input-group-text"><i class="bi bi-eye-slash" style="cursor:pointer;" onclick="togglePass(this)"></i></span>
            </div>
        </div>

        <input type="hidden" name="role" value="<?= $login_role ?>">

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" required>
            <label class="form-check-label checkbox-text">
                Dengan klik Saya Setuju, saya menyatakan telah membaca dan menyetujui <a href="syarat.php">Syarat dan Ketentuan</a>
            </label>
        </div>

        <button type="submit" class="btn btn-register w-100">Daftar Sekarang</button>

        <p class="text-center mt-3">Sudah punya akun?
            <a href="login.php" style="color:#305FCA;font-weight:600;">Masuk</a>
        </p>
    </form>
</div>

<script>
function togglePass(el){
    let input = el.parentElement.previousElementSibling;
    input.type = (input.type === "password") ? "text" : "password";
}
</script>

</body>
</html>
