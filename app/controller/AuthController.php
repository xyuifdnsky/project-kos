


<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/../models/users.php';
require_once __DIR__ . '/../../config/database.php';

class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $user = $this->userModel->login($username, $password);
            
            if ($user) {
                session_start();
             $_SESSION['user'] = $user;
                // Arahkan ke dashboard sesuai level
                if ($user['level'] == 'admin') {
                    $this->redirect('../views/dashboard/admin.php');
                } elseif ($user['level'] == 'pemilik') {
                    $this->redirect('../views/dashboard/pemilik.php');
                } else { // penyewa / pencari
                    $this->redirect('../views/dashboard/penyewa.php');
                }

            } else {
                $error = "Username atau password salah!";
                $this->view('auth/login', ['error' => $error]);
            }
        } else {
            $this->view('auth/login');
        }
    }
    
    public function logout() {
        session_start();
        session_destroy();
        $this->redirect('login.php?page=login');
    }
}
