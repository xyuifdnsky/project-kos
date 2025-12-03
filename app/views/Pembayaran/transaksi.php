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
<title>Transaksi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f4f4f4;
    }

    /* SIDEBAR */
    .sidebar {
        width: 230px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: white;
        border-right: 2px solid black;
        padding-top: 15px;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        font-size: 18px;
        color: black;
        text-decoration: none;
        border-left: 5px solid transparent;
    }

    .sidebar a:hover {
        background: #e7f1ff;
        color: #0057d9;
    }

    .sidebar a.active {
        background: #007bff;
        color: white !important;
        border-left: 5px solid navy;
    }

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

    .search-box input {
        padding: 10px;
        width: 300px;
        border: 2px solid black;
        font-size: 16px;
        margin-bottom: 20px;
        border-radius: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid black;
        background: white;
    }

    th, td {
        padding: 12px;
        border-bottom: 1px solid black;
        text-align: left;
    }

    th {
        border-bottom: 2px solid black;
        font-size: 16px;
    }

    .checkbox {
        width: 22px;
        height: 22px;
        border: 2px solid black;
        display: inline-block;
    }

    .view-btn, .verify-btn {
        border: 2px solid black;
        padding: 6px 12px;
        cursor: pointer;
        background: white;
        font-size: 14px;
    }

    .verify-btn {
        width: 150px;
    }

    /* PAGINATION */
    .pagination {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding-right: 20px;
    }

    .page-btn {
        padding: 5px 10px;
        border: 2px solid black;
        cursor: pointer;
        background: white;
        font-size: 16px;
    }
</style>

</head>
<body>

<div class="sidebar">
    <a href="../auth/dashboard/pemilik/pemilik.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="../penyewa/penyewa.php">
        <i class="bi bi-people"></i> Penghuni
    </a>

    <a href="../kamar/create.php">
        <i class="bi bi-door-open"></i> Tambah Kamar
    </a>

    <a href="../Keluhan/lihat_keluhan.php">
        <i class="bi bi-chat-dots"></i> Keluhan
    </a>

    <a class="active" href="#">
        <i class="bi bi-credit-card"></i> Transaksi
    </a>

    <a href="../logout.php" style="color:red;">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<div class="content">

     <div class="topbar">
        <i class="bi bi-person-circle"></i>&nbsp; <?= $_SESSION['user']['username'] ?> | <?= ucfirst($role) ?>
    </div>

    <h1>Transaksi</h1>

    <div class="search-box">
        <input type="text" placeholder="Cari Transaksi...">
    </div>

    <table>
        <tr>
            <th></th>
            <th>NO</th>
            <th>Nama</th>
            <th>Lihat bukti bayar</th>
            <th>Verifikasi Pembayaran</th>
            <th>Aksi</th>
        </tr>

        <tr>
            <td><div class="checkbox"></div></td>
            <td>1</td>
            <td>Jhon</td>
            <td>
                <button class="view-btn">Lihat Bukti</button>
            </td>
            <td>
                <button class="verify-btn">Terverifikasi ✔</button>
            </td>
            <td>
                <button class="view-btn">Edit</button>
                <button class="view-btn">Hapus</button>
            </td>
        </tr>

        <tr>
            <td><div class="checkbox"></div></td>
            <td>2</td>
            <td>Jhon</td>
            <td>
                <button class="view-btn">Lihat Bukti</button>
            </td>
            <td>
                <button class="verify-btn">Tidak Terverifikasi ✖</button>
            </td>
            <td>
                <button class="view-btn">Edit</button>
                <button class="view-btn">Hapus</button>
            </td>
        </tr>

    </table>

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
