<?php
$user_name = $user_name ?? "Penyewa";

require_once __DIR__ . '/../../../../../config/database.php';
$conn = DatabaseConfig::getConnection();
session_start();

$list_kamar = $conn->query("
    SELECT 
        kamar.id_kamar AS id,
        kamar.nomor_kamar,
        kamar.tipe_kamar,
        kamar.harga,
        kamar.fasilitas,
        kamar.gambar AS gambar,
        kamar.status,
        users.nama_user AS pemilik
    FROM kamar
    LEFT JOIN users ON kamar.id_pemilik = users.id_user
    WHERE kamar.status = 'tersedia'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Penyewa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        /* SIDEBAR */
        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            border-right: 1px solid #ddd;
            padding-top: 80px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: black;
            text-decoration: none;
            font-size: 16px;
            border-left: 4px solid transparent;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #0d6efd;
            color: white;
            border-left: 4px solid navy;
        }

        .sidebar a i {
            font-size: 20px;
        }

        /* NAVBAR */
        .topnav {
            width: 100%;
            height: 60px;
            background: white;
            border-bottom: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
        }

        .content {
            margin-left: 250px;
            margin-top: 80px;
            padding: 20px;
        }

        .menu-icon {
            font-size: 30px;
            color: #0d6efd;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="topnav">
    <div class="fw-bold fs-5">Dashboard Penyewa</div>

    <div class="dropdown">
        <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" 
           href="#" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            <span><?= $user_name ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">Profil</a></li>
            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
        </ul>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="penyewa_dashboard.php" class="active">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="kamar_saya.php">
        <i class="bi bi-door-open"></i> Kamar Saya
    </a>

    <a href="pembayaran.php">
        <i class="bi bi-wallet2"></i> Pembayaran
    </a>

    <a href="riwayat.php">
        <i class="bi bi-receipt"></i> Riwayat Pembayaran
    </a>

    <a href="../../../keluhan/create.php">
        <i class="bi bi-exclamation-circle"></i> Keluhan
    </a>

    <a href="../logout.php" style="color:red;">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<div class="content">

    <h3 class="mb-4">Daftar Kamar Tersedia</h3>

    <div class="row">

        <?php if (!empty($list_kamar)) : ?>
            <?php foreach ($list_kamar as $km) : ?>
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="card shadow-sm" style="border-radius:15px; overflow:hidden;">

                        <!-- FOTO KAMAR -->
                     <img src="../../../kamar/gambar/<?= $km['gambar'] ?>" 
                        class="card-img-top"
                        style="height:170px; object-fit:cover;">


                        <div class="card-body">

                            <!-- LABEL PUTRA / PUTRI + RATING -->
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-success"><?= ucfirst($km['tipe_kamar']) ?></span>
                                <span class="text-success fw-bold">
                                    <i class="bi bi-star-fill"></i> 
                                    <?= $km['rating'] ?? '4.5' ?>
                                </span>
                            </div>

                            <!-- NAMA KOST -->
                            <h6 class="fw-bold"><?= $km['nomor_kamar'] ?></h6>

                        

                            <!-- FASILITAS RINGKAS -->
                            <p class="small text-muted" style="font-size:12px;">
                                <?= $km['fasilitas'] ?>
                            </p>

                            <!-- HARGA -->
                            <?php if (!empty($km['harga_diskon'])) : ?>
                                <p class="text-danger small">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    Diskon <?= $km['diskon_label'] ?>
                                    <strike class="text-muted">Rp<?= number_format($km['harga_asli'], 0, ',', '.') ?></strike>
                                </p>
                                <p class="fw-bold fs-5 text-dark">
                                    Rp<?= number_format($km['harga_diskon'], 0, ',', '.') ?>
                                </p>
                            <?php else: ?>
                                <p class="fw-bold fs-5 text-dark">
                                    Rp<?= number_format($km['harga'], 0, ',', '.') ?>
                                </p>
                            <?php endif; ?>

                            <a href="../../../kamar/detail_kamar.php?id=<?= $km['id'] ?>" 
                               class="btn btn-primary w-100 mt-2">
                                Lihat Detail
                            </a>
                            <form action="../../../kamar/pesan/pesan_kamar.php" method="GET">
                            <input type="hidden" name="id_kamar" value="<?= $km['id'] ?>">
                            <button class="btn btn-success w-100 mt-2">
                                Pesan Kamar
                            </button>
                        </form>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p class="text-muted">Belum ada kamar tersedia.</p>
        <?php endif; ?>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
