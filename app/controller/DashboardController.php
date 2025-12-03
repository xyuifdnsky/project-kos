<?php
require_once '../models/penyewa.php';
require_once '../models/kamar.php';

class dashboardcontroller extends Controller {
    
    private $userModel;
    private $barangModel;

    public function __construct() {
        $this->checkLogin();

        $this->userModel = new User();
        $this->barangModel = new Barang();
    }

    // ================================
    // DASHBOARD UTAMA
    // ================================
    public function index() {

        $role      = $_SESSION['user']['role'];
        $user_name = $_SESSION['user']['nama_user'];

        // Jika pengguna adalah penyewa → masuk ke dashboard penyewa
        if ($role === 'penyewa') {
            return $this->penyewaDashboard();
        }

        // Untuk Admin / Pemilik Kost
        $total_users  = count($this->userModel->getAllUsers());
        $total_kamar  = count($this->barangModel->getAllKamar());

        return $this->view('dashboard/index', [
            'total_users' => $total_users,
            'total_kamar' => $total_kamar,
            'user_name'   => $user_name,
            'role'        => $role
        ]);
    }

    // ================================
    // DASHBOARD khusus PENYEWA KOST
    // ================================
    public function penyewaDashboard() {

        $id_user   = $_SESSION['user']['id_user'];
        $user_name = $_SESSION['user']['nama_user'];

        // Ambil data kamar yang disewa penyewa ini
        $kamar = $this->barangModel->getKamarSewa($id_user);

        // Pembayaran terakhir
        $riwayat_pembayaran = $this->barangModel->getPembayaranTerakhir($id_user);

        // Keluhan terbaru
        $keluhan = $this->barangModel->getKeluhanTerbaru($id_user);

        return $this->view('dashboard/penyewa', [
            'user_name'           => $user_name,
            'kamar'               => $kamar,
            'riwayat_pembayaran'  => $riwayat_pembayaran,
            'keluhan'             => $keluhan
        ]);
    }
}
?>
