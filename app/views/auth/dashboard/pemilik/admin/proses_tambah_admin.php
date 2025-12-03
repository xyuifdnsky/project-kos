<?php
session_start();
require_once '../../../../models/database.php';

$conn = databaseconfig::getConnection();

// Pastikan pemilik
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pemilik') {
    header("Location: ../../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama_user = mysqli_real_escape_string($conn, $_POST['nama_user']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon   = mysqli_real_escape_string($conn, $_POST['telepon']);
    $password  = mysqli_real_escape_string($conn, $_POST['password']);
    $password2 = mysqli_real_escape_string($conn, $_POST['password2']);

    // Cek password match
    if ($password !== $password2) {
        echo "<script>alert('Password tidak sama');history.back();</script>";
        exit;
    }

    // Hash password
    $passHash = password_hash($password, PASSWORD_DEFAULT);

    // Ambil ID pemilik yang menambahkan admin
    $id_pemilik = $_SESSION['user']['id_user'];

    // Query insert admin
    $sql = "INSERT INTO users (username, nama_user, telepon, email, password, role, id_pemilik)
            VALUES ('$username', '$nama_user', '$telepon', '$email', '$passHash', 'admin', '$id_pemilik')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Admin berhasil ditambahkan');window.location='../../dashboard/pemilik.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah admin');history.back();</script>";
    }
}

?>
