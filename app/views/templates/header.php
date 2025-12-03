<?php
require_once __DIR__ . '/../../models/database.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$role = $_SESSION['user']['role'] ?? 'guest';

$pageFile = match($role) {
    'admin' => '../auth/dashboard/admin.php',
    'pemilik' => '../auth/dashboard/pemilik.php',
    'penyewa' => '../auth/dashboard/penyewa.php',
    default => '../../../index.php'
};


// Ambil data user dari session
$nama = $_SESSION['user']['nama_lengkap'] ?? 'Tidak diketahui';
$role = $_SESSION['user']['role'] ?? 'unknown';
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kost</title>

    <!-- Fonts + Icons + Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #3B82F6;
            --primary-hover: #2563EB;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --radius-lg: 12px;
            --shadow-sm: 0 1px 2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background: var(--gray-50);
            color: var(--gray-700);
        }

        /* SIDEBAR */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #FFFFFF 0%, #F8FAFC 100%);
            border-right: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 800;
            font-size: 1.25rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            color: var(--gray-600);
            padding: 0.75rem 1.25rem;
            margin: 4px 12px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            transition: 0.2s ease;
        }

        .nav-link:hover {
            background: var(--gray-100);
            color: var(--gray-800);
            transform: translateX(3px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white !important;
            box-shadow: var(--shadow-lg);
        }

        /* USER BOX */
        .user-info {
            background: var(--gray-50);
            border-radius: var(--radius-lg);
            padding: 1rem;
            margin: 1rem;
            border: 1px solid var(--gray-200);
            text-align: center;
        }

        .user-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            color: white;
            font-size: 1.4rem;
        }

        .main-content {
            padding: 2rem;
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <nav class="col-md-3 col-lg-2 sidebar d-md-block">
            <div class="sidebar-brand">
                <i class="fas fa-building me-2"></i>SISTEM KOST
            </div>

            <!-- USER SECTION -->
            <div class="user-info">
                <div class="user-icon mb-2">
                    <i class="fa fa-user"></i>
                </div>

              <h6 class="fw-bold mb-1"><?= $_SESSION['user']['role'] ?? '' ?></h6>

            
                        <span class="badge bg-<?= ($_SESSION['user']['role'] ?? 'unknown') == 'admin' ? 'primary' : 'success'; ?>">
            <?= ucfirst($_SESSION['user']['role'] ?? 'unknown'); ?>
        </span>

            </div>

            <!-- NAVIGATION -->
            <ul class="nav flex-column">

                            <li class="nav-item">
                    <a class="nav-link <?= ($_GET['page'] ?? '') == 'dashboard' ? 'active' : ''; ?>"
                    href="<?= $pageFile ?>?page=dashboard">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                </li>


              <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] == 'admin'): ?>

                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['page'] ?? '') == 'user' ? 'active' : ''; ?>"
                        href="index.php?page=user">
                        <i class="fas fa-users"></i> Data Pengguna
                    </a>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['page'] ?? '') == 'kamar' ? 'active' : ''; ?>"
                        href="index.php?page=kamar">
                        <i class="fas fa-door-open"></i> Data Kamar
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($_GET['page'] ?? '') == 'penghuni' ? 'active' : ''; ?>"
                        href="index.php?page=penghuni">
                        <i class="fas fa-user-friends"></i> Data Penghuni
                    </a>
                </li>

                <li class="nav-item mt-4">
                    <a class="nav-link text-danger" href="index.php?page=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>

            </ul>
        </nav>

        <!-- MAIN CONTENT WRAPPER -->
        <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
