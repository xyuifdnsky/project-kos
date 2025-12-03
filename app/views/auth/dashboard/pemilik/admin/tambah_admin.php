<?php
session_start();
require_once '../../../../models/database.php';

// Pastikan hanya pemilik yang bisa buka
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'pemilik') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<title>Tambah Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>Tambah Admin Baru</h3>
<form action="proses_tambah_admin.php" method="POST">

    <div class="mb-3">
        <label>Nama Admin</label>
        <input type="text" class="form-control" name="nama_user" required>
    </div>

    <div class="mb-3">
        <label>Username</label>
        <input type="text" class="form-control" name="username" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" class="form-control" name="telepon" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>

    <div class="mb-3">
        <label>Konfirmasi Password</label>
        <input type="password" class="form-control" name="password2" required>
    </div>

    <!-- konfirmasi pemilik -->
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" required>
        <label class="form-check-label">
            Saya yakin menambahkan admin baru untuk membantu mengelola kos.
        </label>
    </div>

    <button class="btn btn-primary w-100">Tambah Admin</button>

</form>
</body>
</html>
