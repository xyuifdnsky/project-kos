<?php
$user_name = $user_name ?? "Penyewa";

require_once __DIR__ . '/../../../config/database.php';
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
    <title>Daftar Kamar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>

/* ---- RESET ---- */
body {
     font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5; /* Warna latar belakang umum */
            padding-top: 80px;
}

/* ---- HEADER ---- */
.header {
    background-color: #fff;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;    
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    border-bottom: 1px solid #ddd;
}

/* ---- NAV MENU ---- */
.nav-menu {
    display: flex;
    list-style: none;
    gap: 15px;
    margin: 0;
    padding: 0;
}

.nav-menu a {
    text-decoration: none;
    color: #000;
    font-weight: 600;
}

.nav-menu li {
    position: relative;
}

/* ---- DROPDOWN ---- */
.dropdown-content {
    display: none;
    position: absolute;
    background: white;
    list-style: none;
    padding: 8px 0;
    min-width: 150px;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.dropdown-content li a {
    display: block;
    padding: 8px 14px;
    color: #000;
}

.dropdown-content li a:hover {
    background: #eee;
}

.dropdown:hover .dropdown-content {
    display: block;
}

        /* Style umum tombol navbar */
.header .btn {
    text-decoration: none; /* Hilangkan underline */
    font-weight: bold;
    padding: 8px 18px;
    border-radius: 6px;
    margin-left: 10px;
    transition: 0.3s ease;
}

/* LOGIN → penuh warna */
#openRoleUser {
    background-color: #1a2a4b;
    color: white;
    border: none;
}

#openRoleUser:hover {
    background-color: #0d1936;
    transform: translateY(-2px);
}


.role-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(6px);
    display: none; 
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Kotak Popup */
.role-popup {
    background: white;
    border-radius: 16px;
    padding: 30px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    animation: fadeIn 0.3s ease;
    text-align: center;
}

@keyframes fadeIn {
    from { transform: scale(0.9); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

/* Tombol */
.role-btn {
    width: 100%;
    max-width: 350px;         /* tombol tidak selebar modal */
    padding: 15px;
    margin: 0 auto 15px auto; /* benar-benar ke tengah */

    display: flex;
    flex-direction: column;   /* teks utama + teks kecil vertikal */
    align-items: center;
    justify-content: center;

    border-radius: 12px;
    text-decoration: none;
    font-weight: bold;
    color: white;
    transition: transform 0.2s ease;
}

.role-btn:hover {
    transform: translateY(-3px);
}

.role-icon {
    font-size: 1.5rem;
    margin-right: 15px;
    width: 30px;
    text-align: center;
}

.role-text small {
    display: block;
    margin-top: 2px;
    font-weight: normal;
    opacity: 0.8;
}


/* Warna tombol */
.btn-penyewa { background-color: #3B82F6; }
.btn-pemilik { background-color: #10B981; }

/* ---- KAMAR CARD ---- */
.kamar-card {
    background: white;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: 0.3s;
}

.kamar-card:hover {
    transform: scale(1.02);
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

.kamar-img img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

.kamar-body {
    padding: 14px;
}

.kamar-top {
    display: flex;
    justify-content: space-between;
}

.badge-tipe {
    background: #28a745;
    color: white;
    padding: 3px 6px;
    border-radius: 5px;
    font-size: 12px;
}

.rating {
    color: #28a745;
    font-weight: bold;
}

.kamar-title {
    font-weight: bold;
    margin-top: 8px;
}

.kamar-fasilitas {
    font-size: 12px;
    color: #666;
    height: 36px;
    overflow: hidden;
}

/* ---- PRICE ---- */
.harga-final {
    font-weight: bold;
    font-size: 20px;
    color: #1a2a4b;
}

.harga-asli {
    text-decoration: line-through;
    color: #999;
    font-size: 13px;
}

.btn-detail {
    background: #1a2a4b;
    color: white;
    display: block;
    margin-top: 12px;
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
}

.btn-detail:hover {
    background: #0d1936;
}

</style>
</head>

<body>

<!-- HEADER -->
<header class="header">

    <div class="logo" style="display:flex; align-items:center; gap:10px;">
        <img src="../../../gambar/logo-silokos.png" style="height:40px;">
        <span style="font-size:1.5em; font-weight:bold;">SiLoKos</span>
    </div>

    <nav>
        <ul class="nav-menu">

            <li class="dropdown">
                <a href="#">Cari Apa?</a>
                <ul class="dropdown-content">
                    <li><a href="app/views/kamar/index.php">Booking</a></li>
                    <li><a href="#">Menu 2</a></li>
                    <li><a href="#">Menu 3</a></li>
                </ul>
            </li>

            <li><a href="#">Pusat Bantuan</a></li>
            <li><a href="ketentuan/syarat_ketentuan.php">Syarat dan Ketentuan</a></li>
            <li><a href="#" id="openRoleUserBar">Login</a></li>

        </ul>
    </nav>

</header>
<!-- CONTENT -->
<div class="container mt-4">

    <h3 class="mb-4 fw-bold">Daftar Kamar Tersedia</h3>

    <div class="row g-4">

        <?php foreach ($list_kamar as $km): ?>

        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="kamar-card">

                <div class="kamar-img">
                    <img src="gambar/<?= $km['gambar'] ?>">
                </div>

                <div class="kamar-body">

                    <div class="kamar-top">
                        <span class="badge-tipe"><?= ucfirst($km['tipe_kamar']) ?></span>
                        <span class="rating">⭐ 4.5</span>
                    </div>

                    <h6 class="kamar-title"><?= $km['nomor_kamar'] ?></h6>

                    <p class="kamar-fasilitas"><?= $km['fasilitas'] ?></p>

                    <span class="harga-final">
                        Rp<?= number_format($km['harga'],0,',','.') ?>
                    </span>

                    <a href="detail_kamar.php?id=<?= $km['id'] ?>" class="btn-detail">
                        Lihat Detail
                    </a>

                </div>
            </div>
        </div>

        <?php endforeach; ?>
    
        <div id="roleUserOverlayBar" class="role-overlay">
    <div class="role-popup">

        <h3 class="mb-4">Login Sebagai Apa?</h3>
        <p class="text-muted mb-4">Pilih jenis akun yang sesuai dengan kebutuhan Anda.</p>

        <a href="../auth/login.php?role=penyewa" class="role-btn btn-penyewa">
            <span class="role-icon"><i class="fas fa-bed"></i></span>
            <span class="role-text">
                Penyewa
                <small>Mencari dan Menyewa Kos</small>
            </span>
        </a>

        <a href="../auth/login.php?role=pemilik" class="role-btn btn-pemilik">
            <span class="role-icon"><i class="fas fa-house-user"></i></span>
            <span class="role-text">
                Pemilik Kos
                <small>Mengelola Properti & Penyewa</small>
            </span>
        </a>

        <button id="closeUserBar" class="btn btn-secondary mt-3 w-100">Tutup</button>

    </div>

    </div>

</div>

<script>
document.getElementById('openRoleUserBar').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('roleUserOverlayBar').style.display = 'flex';
});

document.getElementById('closeUserBar').addEventListener('click', function() {
    document.getElementById('roleUserOverlayBar').style.display = 'none';
});
</script>
</body>
</html>
