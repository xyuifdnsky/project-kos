<?php
// PHP LOGIC (TIDAK DIUBAH)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
require_once __DIR__ . '/../../../config/database.php';

$conn = DatabaseConfig::getConnection();
if (!$conn) die("Koneksi database gagal!");

$id_pemilik = $_GET['id_pemilik'] ?? $_SESSION['user']['id_pemilik'];
session_start();

// CEK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$role = $_SESSION['user']['role'];

// --- Tambahan: Dummy data untuk contoh tampilan ---
// Anda perlu mengganti ini dengan query SQL sebenarnya
$transaksi_data = [
    [
        'id' => 101, 
        'nama' => 'John Doe', 
        'kamar' => 'A05',
        'bulan' => 'Desember 2025',
        'jumlah' => 1200000,
        'status' => 'Terverifikasi', 
        'status_class' => 'success'
    ],
    [
        'id' => 102, 
        'nama' => 'Jane Smith', 
        'kamar' => 'B12',
        'bulan' => 'Januari 2026',
        'jumlah' => 1500000,
        'status' => 'Menunggu Verifikasi', 
        'status_class' => 'warning'
    ],
    [
        'id' => 103, 
        'nama' => 'Michael Scott', 
        'kamar' => 'C01',
        'bulan' => 'Desember 2025',
        'jumlah' => 1000000,
        'status' => 'Tidak Terverifikasi', 
        'status_class' => 'danger'
    ],
];

// --- Asumsi pagination ---
$current_page = 1;
$total_pages = 2; 

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #0c3e7f;
        --bg-color: #f5f7fa;
        --sidebar-width: 260px;
    }

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: var(--bg-color);
    }

    /* === SIDEBAR === */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: white;
        border-right: 1px solid #e0e4e8;
        padding-top: 80px; 
        box-shadow: 2px 0 10px rgba(0,0,0,0.05);
        z-index: 10;
    }

    .sidebar a {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 14px 25px;
        font-size: 0.95rem;
        color: #333;
        text-decoration: none;
        border-left: 5px solid transparent;
        transition: all 0.3s;
        margin: 0;
    }

    .sidebar a:hover {
        background: #e9ecef;
        color: var(--primary-color);
        border-left: 5px solid var(--primary-color);
    }

    .sidebar a.active {
        background: var(--primary-color);
        color: white !important;
        border-left: 5px solid var(--secondary-color);
        font-weight: 600;
    }
    
    .sidebar a i {
        font-size: 1.2rem;
    }

    .sidebar a.logout {
        color: #dc3545 !important;
        margin-top: 20px;
        border-top: 1px solid #eee;
    }

    .sidebar a.logout:hover {
        background: #ffe5e5;
    }

    /* === TOP BAR === */
    .top-nav {
        position: fixed;
        top: 0;
        left: var(--sidebar-width);
        right: 0;
        height: 70px;
        background: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        padding: 0 30px;
        z-index: 50;
    }
    .top-nav .user-info {
        font-weight: 600;
        color: var(--secondary-color);
    }

    /* === CONTENT === */
    .content {
        margin-left: var(--sidebar-width);
        padding: 30px;
        padding-top: 100px; /* Jarak dari topbar */
    }

    /* Table Styling */
    .table-container {
        overflow-x: auto;
    }
    .table th, .table td {
        vertical-align: middle;
        white-space: nowrap; /* Mencegah konten tabel terpotong */
    }
    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #f8f9fa;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }

    .status-badge {
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 6px;
    }
</style>
</head>
<body>

<div class="top-nav">
    <div class="fw-bold fs-5 text-dark">Manajemen Transaksi Pembayaran</div>
    
    <div class="ms-auto user-info">
        <i class="bi bi-person-circle"></i>&nbsp; <?= $_SESSION['user']['username'] ?> | <?= ucfirst($role) ?>
    </div>
</div>

<div class="sidebar">
    <a href="../auth/dashboard/pemilik/pemilik.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="../penyewa/penyewa.php">
        <i class="bi bi-person-badge"></i> Data Penghuni
    </a>

    <a href="../kamar/create.php">
        <i class="bi bi-door-open"></i> Kelola Kamar
    </a>

    <a href="../Keluhan/lihat_keluhan.php">
        <i class="bi bi-chat-dots"></i> Keluhan
    </a>

    <a class="active" href="#">
        <i class="bi bi-credit-card"></i> Transaksi
    </a>

    <a href="../logout.php" class="logout">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</div>

<div class="content">

    <h1 class="mb-4 fw-bold text-dark"><i class="bi bi-cash-stack me-2 text-success"></i> Data Transaksi Pembayaran</h1>

    <div class="row mb-4 g-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="text" id="search-input" class="form-control" placeholder="Cari Nama/Kamar/Bulan...">
            </div>
        </div>

        <div class="col-md-3">
            <select id="status-filter" class="form-select">
                <option value="">Semua Status</option>
                <option value="Terverifikasi">Terverifikasi</option>
                <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                <option value="Tidak Terverifikasi">Gagal/Ditolak</option>
            </select>
        </div>

        <div class="col-md-5 d-flex justify-content-end">
            <button class="btn btn-outline-danger me-2" disabled id="delete-selected-btn">
                <i class="bi bi-trash"></i> Hapus Dipilih
            </button>
            <button class="btn btn-success" id="export-btn">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
            </button>
        </div>
    </div>


    <div class="card shadow-sm border-0">
        <div class="card-body table-container">
            <table class="table table-striped table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th style="width: 3%;">
                            <input class="form-check-input" type="checkbox" id="checkAll">
                        </th>
                        <th style="width: 5%;">ID Transaksi</th>
                        <th>Nama Penyewa</th>
                        <th>Kamar</th>
                        <th>Pembayaran Untuk</th>
                        <th>Jumlah Bayar</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 15%;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($transaksi_data as $row): ?>
                    <tr data-status="<?= $row['status'] ?>">
                        <td><input class="form-check-input row-checkbox" type="checkbox" data-id="<?= $row['id'] ?>"></td>
                        <td>#<?= $row['id'] ?></td>
                        <td><strong><?= $row['nama'] ?></strong></td>
                        <td><?= $row['kamar'] ?></td>
                        <td><?= $row['bulan'] ?></td>
                        <td><span class="text-success fw-bold">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></span></td>
                        <td class="text-center">
                            <?php 
                                $icon = match($row['status']) {
                                    'Terverifikasi' => 'bi-check-circle-fill',
                                    'Menunggu Verifikasi' => 'bi-clock-fill',
                                    'Tidak Terverifikasi' => 'bi-x-circle-fill',
                                    default => 'bi-question-circle-fill'
                                };
                            ?>
                            <span class="badge status-badge bg-<?= $row['status_class'] ?>">
                                <i class="bi <?= $icon ?> me-1"></i> <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info text-white me-1" title="Lihat Bukti Bayar">
                                <i class="bi bi-eye"></i> Bukti
                            </button>
                            <button class="btn btn-sm btn-<?= $row['status'] == 'Terverifikasi' ? 'secondary' : 'primary' ?>" 
                                    title="<?= $row['status'] == 'Terverifikasi' ? 'Sudah Terverifikasi' : 'Verifikasi Sekarang' ?>"
                                    <?= $row['status'] == 'Terverifikasi' ? 'disabled' : '' ?>>
                                <i class="bi bi-check-lg"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($transaksi_data)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted fst-italic">Tidak ada data transaksi saat ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted small">
            Menampilkan 1-<?= count($transaksi_data) ?> dari <?= count($transaksi_data) ?> data.
        </div>
        <nav>
            <ul class="pagination pagination-sm shadow-sm">
                <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li class="page-item <?= ($current_page == 1) ? 'active' : '' ?>"><a class="page-link" href="#">1</a></li>
                <li class="page-item <?= ($current_page == 2) ? 'active' : '' ?>"><a class="page-link" href="#">2</a></li>
                <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const deleteBtn = document.getElementById('delete-selected-btn');
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const tableRows = document.querySelectorAll('tbody tr');

    // 1. Logika Checkbox All
    checkAll.addEventListener('change', function() {
        rowCheckboxes.forEach(checkbox => {
            checkbox.checked = checkAll.checked;
        });
        updateDeleteButton();
    });

    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButton);
    });

    function updateDeleteButton() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        deleteBtn.disabled = checkedCount === 0;
        deleteBtn.textContent = checkedCount > 0 ? `Hapus ${checkedCount} Dipilih` : 'Hapus Dipilih';
    }

    // 2. Logika Pencarian dan Filter
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;

        tableRows.forEach(row => {
            // Mengambil semua teks dalam baris untuk pencarian
            const rowText = row.textContent.toLowerCase();
            const rowStatus = row.dataset.status;

            const matchesSearch = rowText.includes(searchTerm);
            const matchesStatus = selectedStatus === '' || rowStatus === selectedStatus;

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>

</body>
</html>