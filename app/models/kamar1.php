<?php
require_once 'database.php';

class kamar extends Database {
    private $table = 'kamar';
    
    public function __construct() {
        parent::__construct();
    }
    
    // CRUD Methods
    public function getAllKamar($search = '') {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        
        if (!empty($search)) {
            $search = $this->escapeString($search);
            $sql .= " AND (nomor_kamar LIKE '%{$search}%' OR tipe_kamar LIKE '%{$search}%')";
        }
        
        $sql .= " ORDER BY id DESC";
        $result = $this->connection->query($sql);
        
        $kamar = [];
        while ($row = $result->fetch_assoc()) {
            $kamar[] = $row;
        }
        return $kamar;
    }
    
    public function getKamarById($id) {
        $id = $this->escapeString($id);
        $sql = "SELECT * FROM {$this->table} WHERE id = '{$id}'";
        $result = $this->connection->query($sql);
        return $result->fetch_assoc();
    }
    
    public function createKamar($data) {
        $nomor_kamar = $this->escapeString($data['nomor_kamar']);
        $tipe_kamar = $this->escapeString($data['tipe_kamar']);
        $harga = $this->escapeString($data['harga']);
        $status = $this->escapeString($data['status']);
        $fasilitas = $this->escapeString($data['fasilitas']);
        
        $sql = "INSERT INTO {$this->table} (nomor_kamar, tipe_kamar, harga, status, fasilitas) 
                VALUES ('{$nomor_kamar}', '{$tipe_kamar}', '{$harga}', '{$status}', '{$fasilitas}')";
        
        return $this->connection->query($sql);
    }
    
    public function updateKamar($id, $data) {
        $nomor_kamar = $this->escapeString($data['nomor_kamar']);
         $tipe_kamar = $this->escapeString($data['tipe_kamar']);
        $harga = $this->escapeString($data['harga']);
        $status = $this->escapeString($data['status']);
        $fasilitas = $this->escapeString($data['fasilitas']);
        
        $sql = "UPDATE {$this->table} SET 
                nomor_kamar = '{$nomor_kamar}',
                tipe_kamar = '{$tipe_kamar}',
                harga = '{$harga}',
                status = '{$status}',
                fasilitas = '{$fasilitas}'
                WHERE id = '{$id}'";
        
        return $this->connection->query($sql);
    }
    
    public function deletekamar($id) {
        $id = $this->escapeString($id);
        $sql = "DELETE FROM {$this->table} WHERE id = '{$id}'";
        return $this->connection->query($sql);
    }
}
?>