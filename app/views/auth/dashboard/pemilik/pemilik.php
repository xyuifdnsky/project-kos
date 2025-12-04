<?php
// PHP LOGIC (TIDAK DIUBAH)
session_start();
require_once '../../../../models/database.php';

$conn = databaseconfig::getConnection();

// Cek login
if (!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
    exit;
}

// Ambil data user
$level = $_SESSION['user']['level'];
$id_pemilik = $_SESSION['user']['id_user'];

// Tentukan id_pemilik berdasarkan level user
if ($level == 'pemilik') {
    $id_pemilik = $user_id; // pemilik = dirinya sendiri
} else if ($level == 'admin') {
    $id_pemilik = $_SESSION['user']['id_pemilik']; // admin melihat milik pemilik
}

// ======================
// HITUNG TOTAL KAMAR
// ======================
$qKamar = mysqli_query($conn, "SELECT COUNT(*) AS total FROM kamar WHERE id_pemilik='$id_pemilik'");
$totalKamar = mysqli_fetch_assoc($qKamar)['total'] ?? 0;

// ======================
// HITUNG TOTAL PENGHUNI
// ======================
$qPenghuni = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM sewa 
    JOIN kamar ON sewa.id_kamar = kamar.id_kamar
    WHERE kamar.id_pemilik = '$id_pemilik'
      AND sewa.status = 'Disetujui'
");
$totalPenghuni = mysqli_fetch_assoc($qPenghuni)['total'] ?? 0;

// ======================
// HITUNG TOTAL KELUHAN
// ======================
$qKeluhan = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM keluhan 
    WHERE id_pemilik = '$id_pemilik'
");
$totalKeluhan = mysqli_fetch_assoc($qKeluhan)['total'] ?? 0;

// Ambil detail user
$qUser = mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_pemilik'");
$dataUser = mysqli_fetch_assoc($qUser);

// Tambahan: Ambil nama user dan foto untuk tampilan
$username = $_SESSION['user']['username'] ?? 'Pemilik';
$foto = $dataUser['foto'] ?? 'default.jpg'; // Asumsi kolom 'foto' ada di tabel users
$nama_user = $dataUser['nama_user'] ?? $username;

// Tambahan: Dummy data untuk statistik lain (karena query belum ada)
$totalPenghuni = 5; // Ganti dengan query penghuni
$totalKeluhan = 2;  // Ganti dengan query keluhan
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Pemilik</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #0c3e7f; /* Warna biru lebih gelap */
            --bg-color: #f5f7fa;
            --sidebar-width: 260px;
        }

        body { 
            background: var(--bg-color); 
            font-family: 'Poppins', sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--secondary-color);
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 80px;
            z-index: 10;
        }

        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            padding: 15px 20px;
            background: var(--secondary-color);
            position: absolute;
            top: 0;
            width: 100%;
            text-align: center;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 25px;
            font-size: 1rem;
            color: #d0d8e0;
            text-decoration: none;
            border-left: 5px solid transparent;
            transition: all 0.3s;
        }
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .sidebar a.active {
            background: var(--primary-color);
            color: white;
            border-left: 5px solid white;
            font-weight: 600;
        }
        .sidebar a i {
            font-size: 1.25rem;
        }
        .sidebar .logout-link {
            color: #ffcccc !important;
            margin-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar .logout-link:hover {
            background: #e74c3c !important;
            color: white !important;
        }

        /* NAVBAR */
        .top-nav {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 70px;
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.07);
            padding: 0 30px;
            z-index: 50;
        }
        .top-nav .title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            padding-top: 100px;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        /* CARD STAT */
        .card-stat {
            padding: 30px;
            border-radius: 12px;
            background: linear-gradient(135deg, white 60%, #eef5ff 100%);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .card-stat:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transform: translateY(-3px);
        }
        .stat-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--secondary-color);
        }
        .stat-label {
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 5px;
        }
        .card-stat-link {
            text-decoration: none;
            color: inherit;
        }
        
        /* CHART BOX */
        .chart-box {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            min-height: 350px;
        }
        .chart-image {
            width: 100%;
            max-width: 300px;
            height: auto;
            margin-top: 20px;
            opacity: 0.7; /* Membuat gambar placeholder lebih halus */
        }
        
        /* USER PROFILE DROPDOWN */
        .user-profile {
            display: flex;
            align-items: center;
        }
        .user-profile img {
            border: 2px solid var(--primary-color);
        }
        .user-profile a {
            color: #333 !important;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                left: -260px; /* Sembunyikan sidebar di mobile */
                transition: left 0.3s;
            }
            .sidebar.show {
                left: 0;
            }
            .main-content {
                margin-left: 0;
                padding-top: 100px;
            }
            .top-nav {
                left: 0;
                justify-content: space-between;
            }
            .menu-toggle {
                display: block !important;
            }
        }
    </style>
</head>

<body>

<div class="sidebar" id="sidebar">
    <div class="logo">SiLoKos Admin</div>
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

        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li><a class="dropdown-item" href="../../profil.php"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
        </ul>
    </div>
</nav>

<div class="main-content">

    <h2 class="fw-bold mb-5 text-dark">Ringkasan Operasional</h2>

    <div class="row g-4">

        <div class="col-lg-4 col-md-6">
            <a href="../../../kamar/create.php" class="card-stat-link">
                <div class="card-stat">
                    <i class="bi bi-door-closed stat-icon text-primary"></i>
                    <div class="stat-number text-primary"><?= $totalKamar ?></div>
                    <div class="stat-label mt-1">Total Kamar</div>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card-stat">
                <div class="stat-number">0</div>
                <div class="fw-semibold mt-2">Penghuni</div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card-stat">
                <div class="stat-number">0</div>
                <div class="fw-semibold mt-2">Keluhan</div>
            </div>
        </div>

    </div>

    <h3 class="fw-bold mt-5 mb-4 text-dark">Analisis dan Laporan</h3>
    <div class="row g-4">

        <div class="col-lg-6">
            <div class="chart-box">
                <h5 class="fw-semibold mb-3"><i class="bi bi-graph-up me-2"></i> Grafik Keuangan Bulanan</h5>
                <p class="text-muted text-center">
                    [Placeholder untuk Grafik Keuangan]
                </p>
                <div class="text-center">
                    <i class="bi bi-bar-chart-fill" style="font-size: 5rem; color: #ced4da;"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="chart-box">
                <h5 class="fw-semibold mb-3"><i class="bi bi-pie-chart-fill me-2"></i> Distribusi Pemasukan</h5>
                <p class="text-muted text-center">
                    [Placeholder untuk Grafik Pemasukan]
                </p>
                <div class="text-center">
                    <i class="bi bi-pie-chart-fill" style="font-size: 5rem; color: #ced4da;"></i>
                </div>

                <div class="text-center mt-4">
                    <button class="btn btn-outline-dark fw-semibold">
                        <i class="bi bi-download"></i> Unduh Laporan
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menu-toggle');
    
    // Toggle sidebar for mobile view
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // JS Interaktif: Tambahkan kelas hover saat mouse masuk/keluar card stat
    document.querySelectorAll('.card-stat').forEach(card => {
        card.addEventListener('mouseover', () => {
            card.style.boxShadow = '0 10px 30px rgba(0,0,0,0.18)';
        });
        card.addEventListener('mouseout', () => {
            card.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });
    });
});
</script>
</body>
</html>