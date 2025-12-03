<?php
class Controller {
    protected function view($view, $data = []) {
        // Ekstrak data menjadi variabel
        extract($data);

        // Mulai output buffer
        ob_start();

        // Path absolut ke folder views
        $baseViewPath = realpath(__DIR__ . '/../views');
        $viewPath = $baseViewPath . '/' . $view . '.php';

        if (!file_exists($viewPath)) {
            die("View not found: $viewPath");
        }

        require $viewPath;

        // Ambil content dari buffer dan keluarkan
        $content = ob_get_clean();
        echo $content;
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function checkLogin() {
        if (!isset($_SESSION['user'])) {
            $this->redirect('login.php?page=login');
        }
    }
}
?>
