<?php
// PHP LOGIC (TIDAK DIUBAH)
$user_name = $user_name ?? "Penyewa";

require_once __DIR__ . '/../../../../../config/database.php';
$conn = DatabaseConfig::getConnection();
session_start();

$list_kamar_result = $conn->query("
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

// Pastikan $list_kamar adalah array yang dapat diiterasi
$list_kamar = $list_kamar_result->fetch_all(MYSQLI_ASSOC);

// Dummy data untuk rating, harga diskon, dll. (karena tidak ada di query asli)
// Anda bisa menghapus bagian ini jika kolom 'rating', 'harga_diskon', dll., sudah ada di database.
function enhance_kamar_data($kamar) {
    // Simulasi data tambahan
    $kamar['rating'] = '4.7'; // Dummy rating
    
    // Simulasi diskon 10% untuk 1 kamar pertama
    if ($kamar['id'] == 1) { 
        $kamar['harga_asli'] = $kamar['harga'];
        $kamar['harga_diskon'] = $kamar['harga'] * 0.90;
        $kamar['diskon_label'] = '10%!';
    } else {
        $kamar['harga_diskon'] = null;
    }

    // Ambil maksimal 3 fasilitas pertama
    if (!empty($kamar['fasilitas'])) {
        $fasilitas_array = array_filter(preg_split('/,\s*|\r\n|\n/', $kamar['fasilitas']));
        $kamar['fasilitas_ringkas'] = implode(', ', array_slice($fasilitas_array, 0, 3));
        if (count($fasilitas_array) > 3) {
            $kamar['fasilitas_ringkas'] .= '...';
        }
    } else {
        $kamar['fasilitas_ringkas'] = 'Fasilitas belum tersedia.';
    }

    return $kamar;
}

$list_kamar_enhanced = array_map('enhance_kamar_data', $list_kamar);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Penyewa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #305FCA;
            --secondary-color: #5AA952;
            --background-color: #f5f7fa;
            --sidebar-width: 250px;
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background-color);
        }

        /* NAVBAR */
        .topnav {
            height: 65px;
            background: white;
            border-bottom: 1px solid #e0e4e8;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding-left: calc(var(--sidebar-width) + 20px);
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #2c3e50; /* Warna sidebar gelap */
            color: white;
            padding-top: 65px;
            transition: all 0.3s;
        }

        .sidebar .logo {
            padding: 15px 20px;
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary-color);
            background: white;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 16px 20px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 1rem;
            border-left: 5px solid transparent;
            transition: all 0.3s;
        }

        .sidebar a:hover {
            background: #34495e;
            color: white;
        }

        .sidebar a.active {
            background: #34495e;
            color: white;
            border-left: 5px solid var(--primary-color);
            font-weight: 600;
        }

        .sidebar .logout-link {
            color: #e74c3c !important;
            margin-top: 20px;
            border-top: 1px solid #4a5d70;
            padding-top: 15px;
        }
        .sidebar .logout-link:hover {
            background: #e74c3c !important;
            color: white !important;
        }
        
        /* MAIN CONTENT */
        .content {
            margin-left: var(--sidebar-width);
            margin-top: 65px;
            padding: 30px;
            transition: margin-left 0.3s;
        }

        /* KAMAR CARD */
        .kamar-card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%; /* Agar tinggi card seragam */
        }
        .kamar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .kamar-card .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        .price-text {
            color: var(--primary-color);
            font-size: 1.5rem;
            font-weight: 700;
        }
        .discount-text {
            color: #e74c3c;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        .btn-primary:hover, .btn-success:hover {
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 0;
                overflow: hidden;
                padding-top: 0;
            }
            .content {
                margin-left: 0;
            }
            .topnav {
                padding-left: 25px;
            }
            .menu-icon {
                display: block !important;
            }
        }
    </style>
</head>
<body>

<div class="topnav d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center">
        <i class="bi bi-list menu-icon me-3 d-none"></i> 
        <div class="fw-bold fs-5 text-dark">DASHBOARD PENYEWA</div>
    </div>

    <div class="dropdown">
        <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" 
           href="#" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle fs-4 me-2 text-primary"></i>
            <span class="fw-semibold d-none d-md-inline"><?= $user_name ?></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
        </ul>
    </div>
</div>

<div class="sidebar" id="sidebar">
    <div class="logo">SiLoKos</div>
    
    <a href="penyewa_dashboard.php" class="active">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="kamar_saya.php">
        <i class="bi bi-door-open"></i> Kamar Saya
    </a>

    <a href="../logout.php" class="logout-link">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<div class="content">

    <h3 class="mb-5 text-dark fw-bold"> Kamar Kos Tersedia Untuk Anda</h3>

    <div class="row">

        <?php if (!empty($list_kamar_enhanced)) : ?>
            <?php foreach ($list_kamar_enhanced as $km) : ?>
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card kamar-card shadow-sm">

                        <img src="../../../kamar/gambar/<?= $km['gambar'] ?? 'default.jpg' ?>" 
                            class="card-img-top"
                            alt="Foto Kamar">

                        <div class="card-body d-flex flex-column">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge rounded-pill bg-primary"><?= ucfirst($km['tipe_kamar']) ?></span>
                                <span class="text-warning fw-bold small">
                                    <i class="bi bi-star-fill"></i> 
                                    <?= $km['rating'] ?>
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-1"><?= $km['nomor_kamar'] ?></h5>
                            <p class="small text-muted mb-3">Oleh: <?= $km['pemilik'] ?></p>

                            <p class="small text-muted mb-3" style="font-size:13px;">
                                <i class="bi bi-house-door me-1"></i> 
                                <?= $km['fasilitas_ringkas'] ?>
                            </p>

                            <div class="mt-auto"> <?php if (!empty($km['harga_diskon'])) : ?>
                                    <p class="discount-text mb-0">
                                        <i class="bi bi-lightning-charge-fill"></i> Diskon <?= $km['diskon_label'] ?>
                                        <br><strike class="text-muted">Rp<?= number_format($km['harga_asli'], 0, ',', '.') ?></strike>
                                    </p>
                                    <p class="price-text mb-3">
                                        Rp<?= number_format($km['harga_diskon'], 0, ',', '.') ?>
                                        <span class="small text-muted">/ bulan</span>
                                    </p>
                                <?php else: ?>
                                    <p class="price-text mb-3">
                                        Rp<?= number_format($km['harga'], 0, ',', '.') ?>
                                        <span class="small text-muted">/ bulan</span>
                                    </p>
                                <?php endif; ?>

                                <a href="../../../kamar/detail_kamar.php?id=<?= $km['id'] ?>" 
                                   class="btn btn-primary w-100 mb-2">
                                    Lihat Detail
                                </a>
                                
                                <form action="../../../kamar/pesan/pesan_kamar.php" method="GET">
                                    <input type="hidden" name="id_kamar" value="<?= $km['id'] ?>">
                                    <button class="btn btn-success w-100">
                                        Pesan Kamar
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i> Belum ada kamar tersedia saat ini. Silakan cek kembali nanti.
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>