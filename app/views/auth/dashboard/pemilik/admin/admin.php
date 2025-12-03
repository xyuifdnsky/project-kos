<?php
session_start();
include __DIR__ . '../../../../../../models/database.php';

$conn = databaseconfig::getConnection();

// Admin harus login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../../../login.php");
    exit;
}

$admin_id = $_SESSION['user']['id_user'];

// Ambil id pemilik yang meningkatkan admin
$qAdmin = mysqli_query($conn, "SELECT id_pemilik FROM users WHERE id_user='$admin_id'");
$adminData = mysqli_fetch_assoc($qAdmin);
$id_pemilik = $adminData['id_pemilik']; // penting!!

// Hitung kamar milik pemilik terkait
$qKamar = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kamar WHERE id_pemilik='$id_pemilik'");
$totalKamar = mysqli_fetch_assoc($qKamar)['total'] ?? 0;

// Hitung penghuni
$qPenghuni = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM users WHERE id_pemilik='$id_pemilik'"
);
$totalPenghuni = mysqli_fetch_assoc($qPenghuni)['total'] ?? 0;

// Hitung keluhan
$qKeluhan = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM keluhan WHERE id_pemilik='$id_pemilik'"
);
$totalKeluhan = mysqli_fetch_assoc($qKeluhan)['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="id">
    
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background: #f5f7fa; }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #ffffff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 80px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            font-size: 18px;
            color: black;
            text-decoration: none;
        }

        .sidebar a:hover, .sidebar a.active {
            background: #0d6efd;
            color: #fff;
        }

        .top-nav {
            position: fixed;
            top: 0;
            left: 0px;
            right: 0;
            height: 70px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            display: flex;
            align-items: center;
            padding: 0 30px;
            z-index: 100;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            padding-top: 100px;
        }

        .card-stat {
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 40px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="../../penyewa/data_penyewa.php?id_pemilik=<?= $id_pemilik ?>"><i class="bi bi-people"></i> Data Penghuni</a>
    <a href="../../../../kamar/create.php?id_pemilik=<?= $id_pemilik ?>"><i class="bi bi-door-open"></i> Data Kamar</a>
    <a href="../../Keluhan/lihat_keluhan.php?id_pemilik=<?= $id_pemilik ?>"><i class="bi bi-chat-dots"></i> Keluhan</a>
    <a href="../../Pembayaran/transaksi.php?id_pemilik=<?= $id_pemilik ?>"><i class="bi bi-cash"></i> Transaksi</a>
    <a href="../../logout.php" style="color:red;"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- TOP NAV -->
<nav class="top-nav">
    <h4 class="fw-bold">Dashboard Admin</h4>
    
    <div class="d-flex align-items-center ms-auto">
         <a href="../../../../keluhan/lihat_keluhan.php">
       <i class="bi bi-bell fs-4 me-2"></i>
    </a>
      
        <i class="bi bi-person-circle fs-4 me-2"></i>
        <span class="fw-semibold"><?= $_SESSION['user']['username'] ?></span>
    </div>
</nav>

<!-- CONTENT --> 
<div class="main-content">

    <h2 class="fw-bold mb-4">Ringkasan Data</h2>

    <div class="row g-3">
        <div class="col-lg-4 col-md-6">
            <div class="card-stat">
                <div class="stat-number"><?= $totalKamar ?></div>
                <div>Kamar</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card-stat">
                <div class="stat-number"><?= $totalPenghuni ?></div>
                <div>Penghuni</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card-stat">
                <div class="stat-number"><?= $totalKeluhan ?></div>
                <div>Keluhan</div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
