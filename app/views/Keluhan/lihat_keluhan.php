<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once __DIR__ . '/../../../config/database.php';

$conn = DatabaseConfig::getConnection();
if (!$conn) die("Koneksi database gagal!");

session_start();

// CEK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keluhan</title>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f4f4f4;
    }

    /* === SIDEBAR === */
    .sidebar {
        width: 230px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: white;
        border-right: 2px solid #ddd;
        padding-top: 15px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        font-size: 17px;
        font-weight: 500;
        color: black;
        text-decoration: none;
        border-radius: 4px;
        margin: 4px 10px;
    }

    .sidebar a i {
        font-size: 20px;
    }

    .sidebar a:hover {
        background: #e6f0ff;
        color: #0056d6;
    }

    .sidebar a.active {
        background: #0d6efd;
        color: white !important;
    }

    /* Logout item */
    .sidebar a.logout {
        color: red;
        margin-top: 10px;
    }

    .sidebar a.logout:hover {
        background: #ffe5e5;
        color: red;
    }

    /* === CONTENT === */
    .content {
        margin-left: 250px;
        padding: 20px;
    }

    .topbar {
        display: flex;
        justify-content: flex-end;
        padding: 10px;
        font-weight: bold;
    }

    /* Search box */
    .search-box input {
        padding: 10px;
        width: 300px;
        font-size: 16px;
        border: 2px solid black;
        border-radius: 4px;
        margin-bottom: 20px;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid black;
        background: white;
    }

    th, td {
        padding: 10px;
        border-bottom: 1px solid black;
        text-align: left;
    }

    th {
        font-weight: bold;
        border-bottom: 2px solid black;
    }

    .checkbox {
        width: 20px;
        height: 20px;
        border: 2px solid black;
        display: inline-block;
    }

    /* PAGINATION */
    .pagination {
        margin-top: 15px;
        display: flex;
        justify-content: center;
        gap: 10px;
        align-items: center;
    }

    .page-btn {
        padding: 5px 10px;
        border: 2px solid black;
        cursor: pointer;
        background: white;
    }
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="../dashboard/pemilik/pemilik.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="../penyewa/penyewa.php">
        <i class="bi bi-person"></i> Data Penghuni
    </a>

    <a href="../kamar/create.php">
        <i class="bi bi-door-open"></i> Tambah Kamar
    </a>

    <a href="lihat_keluhan.php" class="active">
        <i class="bi bi-chat-dots"></i> Keluhan
    </a>

    <a href="../Pembayaran/transaksi.php">
        <i class="bi bi-credit-card"></i> Transaksi
    </a>

    <a href="../logout.php" class="logout">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="topbar">
        <i class="bi bi-person-circle"></i>&nbsp; <?= $_SESSION['user']['username'] ?> | <?= ucfirst($role) ?>
    </div>

    <h1>Keluhan</h1>

    <div class="search-box">
        <input type="text" placeholder="Cari keluhan...">
    </div>

    <!-- TABLE -->
    <table>
        <tr>
            <th></th>
            <th>NO</th>
            <th>Nama</th>
            <th>Deskripsi</th>
            <th>Jenis keluhan</th>
            <th>Status Keluhan</th>
            <th>Aksi</th>
        </tr>

        <tr>
            <td><div class="checkbox"></div></td>
            <td>1</td>
            <td>Jhon</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td><button>Aksi</button></td>
        </tr>

        <tr>
            <td><div class="checkbox"></div></td>
            <td>2</td>
            <td>Jhon</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td><button>Aksi</button></td>
        </tr>
    </table>

    <!-- PAGINATION -->
    <div class="pagination">
        <div class="page-btn">&lt;</div>
        <div class="page-btn">1</div>
        <span>-</span>
        <div class="page-btn">2</div>
        <div class="page-btn">&gt;</div>
    </div>

</div>

</body>
</html>
