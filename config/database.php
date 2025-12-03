
<?php
class databaseconfig {
    private static $instance = null;
    public $conn;

    private function __construct() {
        $this->conn = mysqli_connect("localhost", "root", "", "kost_app");

        if (!$this->conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new databaseconfig(); // dibuat sekali
        }
        return self::$instance->conn;
    }
}
