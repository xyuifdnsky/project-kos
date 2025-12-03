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
<title>Data Penghuni</title>



<!-- Bootstrap Icons -->
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
        top: 0;
        left: 0;
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
        text-decoration: none;
        color: black;
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

    .sidebar a.logout {
        color: red;
        margin-top: 10px;
    }

    .sidebar a.logout:hover {
        background: #ffe5e5;
        color: red;
    }

    /* CONTENT */
    .content {
        margin-left: 250px;
        padding: 20px;
    }

    .topbar {
        display: flex;
        justify-content: flex-end;
        padding: 10px;
        font-weight: bold;
        font-size: 16px;
    }

    /* SEARCH */
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
        padding: 12px;
        border-bottom: 1px solid black;
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

    .action-btn {
        padding: 6px 12px;
        border: 2px solid black;
        background: white;
        cursor: pointer;
        font-size: 14px;
    }

    .action-btn:hover {
        background: #eee;
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

<!-- SIDEBAR -->
<div class="sidebar">

    <a href="../auth/dashboard/pemilik/pemilik.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>


    <a href="penyewa.php" class="active">
        <i class="bi bi-person-lines-fill"></i> Data Penghuni
    </a>

    <a href="../Kamar/create.php">
        <i class="bi bi-door-open"></i> Tambah Kamar
    </a>

    <a href="../Keluhan/lihat_keluhan.php">
        <i class="bi bi-chat-dots"></i> Keluhan
    </a>

    <a href="../pembayaran/transaksi.php">
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

    <h1>Data Penghuni</h1>

    <div class="search-box">
        <input type="text" placeholder="Cari penghuni...">
    </div>

    <table>
        <tr>
            <th></th>
            <th>NO</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Whatsapp</th>
            <th>Tanggal masuk</th>
            <th>Aksi</th>
        </tr>

        <tr>
            <td><div class="checkbox"></div></td>
            <td>1</td>
            <td>Jhon</td>
            <td>Jhon1@gmail.com</td>
            <td>0822222222</td>
            <td>01-01-2026</td>
            <td>
                <button class="action-btn">Edit</button>
                <button class="action-btn">Hapus</button>
            </td>
        </tr>

        <tr>
            <td><div class="checkbox"></div></td>
            <td>2</td>
            <td>Jhon</td>
            <td>Jhon2@gmail.com</td>
            <td>0822222222</td>
            <td>01-01-2026</td>
            <td>
                <button class="action-btn">Edit</button>
                <button class="action-btn">Hapus</button>
            </td>
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
