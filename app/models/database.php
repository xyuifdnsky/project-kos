<?php
// Tambahkan require_once untuk DatabaseConfig
require_once __DIR__ . '/../../config/database.php';

class database {
    protected $connection;
    
    public function __construct() {
        $this->connection = databaseconfig::getConnection();
        
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }
}
?>                                                                                                  