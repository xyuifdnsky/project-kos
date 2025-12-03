<?php
session_start();
require_once '../../../../models/database.php';

$conn = databaseconfig::getConnection();

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

// Ambil data user dari session
$user_id = $_SESSION['user']['id_user'];
$id_pemilik = $_SESSION['user']['id_user'];

// Hitung kamar sesuai pemilik
$query = "SELECT COUNT(*) AS total FROM kamar WHERE id_pemilik = '$id_pemilik'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
$totalKamar = $data['total'] ?? 0;

// Ambil data kamar pemilik
$result = mysqli_query($conn, "SELECT * FROM kamar WHERE id_pemilik='$id_pemilik'");

// Ambil detail user
$qUser = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$user_id'");
$dataUser = mysqli_fetch_assoc($qUser);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pemilik</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background: #f5f7fa; }

        /* SIDEBAR */
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
            gap: 10px;
            padding: 12px 20px;
            font-size: 18px;
            color: black;
            text-decoration: none;
            border-left: 5px solid transparent;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #0d6efd;
            color: white;
            border-left: 5px solid navy;
        }

        /* NAVBAR */
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
            min-height: 100vh;
        }

        /* CARD STAT */
        .card-stat {
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: 0.25s;
        }
        .card-stat:hover {
            transform: scale(1.03);
        }
        .stat-number {
            font-size: 40px;
            font-weight: bold;
        }

        .card-stat-link {
            text-decoration: none;
            color: inherit;
        }

        .chart-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .chart-image {
            width: 270px;
            margin-top: 20px;
        }

    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="../../penyewa/data_penyewa.php"><i class="bi bi-person"></i> Data Penghuni</a>
    <a href="../../../kamar/create.php"><i class="bi bi-door-open"></i> Tambah Kamar</a>
    <a href="../../../Keluhan/lihat_keluhan.php"><i class="bi bi-chat-dots"></i> Keluhan</a>
    <a href="../../../Pembayaran/transaksi.php"><i class="bi bi-credit-card"></i> Transaksi</a>
    <a href="admin/tambah_admin.php"><i class="bi bi-people"></i> Admin</a>
    <a href="../logout.php" style="color:red;"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

        <!-- NAVBAR -->
        <nav class="top-nav">
            <div class="title">Dashboard Pemilik</div>
            <div class="dropdown user-profile ms-auto">


  <img src="../../kamar/gambar/<?= $km['gambar'] ?>" 
                        class="card-img-top"
                        style="height:170px; object-fit:cover;">

   <img src="../../profil/<?= $foto ?>" 
     class="rounded-circle" 
     style="width:40px; height:40px; object-fit:cover;">


        <a class="fw-semibold text-dark text-decoration-none dropdown-toggle"
           href="#" data-bs-toggle="dropdown">
            <?= $_SESSION['user']['username'] ?>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="../../profil.php">Profil</a></li>
            <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<!-- CONTENT -->
<div class="main-content">

    <h2 class="fw-bold mb-4">Dashboard</h2>

    <div class="row g-3">

        <!-- CARD KAMAR -->
        <div class="col-lg-4 col-md-6">
            <a href="../../Kamar/create.php" class="card-stat-link">
                <div class="card-stat">
                    <div class="stat-number"><?= $totalKamar ?></div>
                    <div class="fw-semibold mt-2">Kamar</div>
                </div>
            </a>
        </div>

        <!-- CARD PENGHUNI -->
        <div class="col-lg-4 col-md-6">
            <div class="card-stat">
                <div class="stat-number">0</div>
                <div class="fw-semibold mt-2">Penghuni</div>
            </div>
        </div>

        <!-- CARD KELUHAN -->
        <div class="col-lg-4 col-md-6">
            <div class="card-stat">
                <div class="stat-number">0</div>
                <div class="fw-semibold mt-2">Keluhan</div>
            </div>
        </div>

    </div>

    <br>

    <div class="row g-3">

        <div class="col-lg-6">
            <div class="chart-box text-center">
                <h5 class="mb-3">Grafik Keuangan</h5>
                <img src="https://img.icons8.com/external-others-pike-picture/500/000000/external-Analytics-marketing-and-seo-others-pike-picture.png" class="chart-image">
            </div>
        </div>

        
        <div class="col-lg-6">
            <div class="chart-box text-center">
                <h5 class="mb-3">Grafis Pemasukan</h5>
                <img src="https://img.icons8.com/external-outline-photoshaman/500/000000/external-growth-finance-outline-outline-photoshaman.png" class="chart-image">

                <button class="btn btn-outline-dark mt-3">
                    <i class="bi bi-download"></i> Unduh Gambar
                </button>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
