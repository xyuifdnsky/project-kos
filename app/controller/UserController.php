<?php
require_once 'controller.php';
require_once '../models/User.php';

class UserController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->checkLogin();

        // Semua role boleh akses, tapi hanya boleh lihat sesama role
        $this->userModel = new User();
    }
    
    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $currentLevel = $_SESSION['user']['level'];

        // Ambil hanya user yang level-nya sama dengan level user login
        $users = $this->userModel->getUsersByLevel($currentLevel, $search);

        $this->view('user/index', [
            'users' => $users,
            'search' => $search
        ]);
    }
    
    public function create() {
        // Hanya boleh buat user dengan level yang sama
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['level'] != $_SESSION['user']['level']) {
                $_SESSION['error'] = "Anda hanya dapat menambahkan user dengan level yang sama!";
                $this->redirect('index.php?page=user');
            }

            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password'],
                'nama_lengkap' => $_POST['nama_lengkap'],
                'email' => $_POST['email'],
                'level' => $_POST['level']
            ];
            
            if ($this->userModel->createUser($data)) {
                $_SESSION['success'] = "User berhasil ditambahkan!";
                $this->redirect('index.php?page=user');
            } else {
                $_SESSION['error'] = "Gagal menambahkan user!";
            }
        }
        
        $this->view('user/create');
    }
    
    public function edit($id) {
        $user = $this->userModel->getUserById($id);

        // Cegah edit user dengan level berbeda
        if ($user['level'] != $_SESSION['user']['level']) {
            $_SESSION['error'] = "Anda tidak memiliki akses mengedit user level lain!";
            $this->redirect('index.php?page=user');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Tidak boleh mengubah ke level lain
            if ($_POST['level'] != $_SESSION['user']['level']) {
                $_SESSION['error'] = "Level tidak boleh diubah ke role lain!";
                $this->redirect('index.php?page=user');
            }

            $data = [
                'username' => $_POST['username'],
                'nama_lengkap' => $_POST['nama_lengkap'],
                'email' => $_POST['email'],
                'level' => $_POST['level']
            ];
            
            if ($this->userModel->updateUser($id, $data)) {
                $_SESSION['success'] = "User berhasil diupdate!";
                $this->redirect('index.php?page=user');
            } else {
                $_SESSION['error'] = "Gagal mengupdate user!";
            }
        }

        $this->view('user/edit', ['user' => $user]);
    }
    
    public function delete($id) {
        $user = $this->userModel->getUserById($id);

        // Cegah hapus user dengan level berbeda
        if ($user['level'] != $_SESSION['user']['level']) {
            $_SESSION['error'] = "Tidak dapat menghapus user dengan level berbeda!";
            $this->redirect('index.php?page=user');
        }

        // Mencegah user menghapus akun sendiri
        if ($id == $_SESSION['user']['id']) {
            $_SESSION['error'] = "Tidak dapat menghapus akun sendiri!";
        } else {
            if ($this->userModel->deleteUser($id)) {
                $_SESSION['success'] = "User berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus user!";
            }
        }
        $this->redirect('index.php?page=user');
    }
}
?>
