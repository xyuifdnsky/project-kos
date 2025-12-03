<?php
require_once 'database.php';

class user extends database {
    private $table = 'users';
    
    public function __construct() {
        parent::__construct();
    }

    // ==========================
    // LOGIN USER
    // ==========================
    public function login($username, $password) {
        $username = $this->escapeString($username);

        $sql = "SELECT * FROM {$this->table} 
                WHERE username = '{$username}' 
                LIMIT 1";

        $result = $this->connection->query($sql);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Karena password di database masih plain text
            if ($password === $user['password']) {
                return $user;
            }
        }
        return false;
    }

    // ==========================
    // GET ALL USERS
    // ==========================
    public function getAllUsers($search = '') {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";

        if (!empty($search)) {
            $search = $this->escapeString($search);
            $sql .= " AND (username LIKE '%{$search}%' 
                        OR nama_user LIKE '%{$search}%' 
                        OR email LIKE '%{$search}%')";
        }

        $sql .= " ORDER BY id_user DESC";

        $result = $this->connection->query($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    // ==========================
    // GET USER BY ID
    // ==========================
    public function getUserById($id) {
        $id = $this->escapeString($id);

        $sql = "SELECT * FROM {$this->table} WHERE id_user = '{$id}' LIMIT 1";
        $result = $this->connection->query($sql);

        return $result->fetch_assoc();
    }

    // ==========================
    // CREATE USER
    // ==========================
    public function createUser($data) {

        $username   = $this->escapeString($data['username']);
        $nama_user  = $this->escapeString($data['nama_user']);
        $telepon    = $this->escapeString($data['telepon']);
        $email      = $this->escapeString($data['email']);
        $password   = $this->escapeString($data['password']);  // plain text
        $role       = $this->escapeString($data['role']);

        $sql = "INSERT INTO {$this->table} 
                (username, nama_user, telepon, email, password, role)
                VALUES 
                ('{$username}', '{$nama_user}', '{$telepon}', '{$email}', '{$password}', '{$role}')";

        return $this->connection->query($sql);
    }

    // ==========================
    // UPDATE USER
    // ==========================
    public function updateUser($id, $data) {

        $username   = $this->escapeString($data['username']);
        $nama_user  = $this->escapeString($data['nama_user']);
        $telepon    = $this->escapeString($data['telepon']);
        $email      = $this->escapeString($data['email']);
        $role       = $this->escapeString($data['role']);

        $sql = "UPDATE {$this->table} SET 
                username = '{$username}',
                nama_user = '{$nama_user}',
                telepon = '{$telepon}',
                email = '{$email}',
                role = '{$role}'
                WHERE id_user = '{$id}'";

        return $this->connection->query($sql);
    }

    // ==========================
    // DELETE USER
    // ==========================
    public function deleteUser($id) {
        $id = $this->escapeString($id);

        $sql = "DELETE FROM {$this->table} WHERE id_user = '{$id}'";

        return $this->connection->query($sql);
    }

public function getUsersByLevel($level, $search = '') {
    $sql = "SELECT * FROM users WHERE level = :level AND username LIKE :search";
    $query = $this->db->prepare($sql);
    $query->execute([
        ':level' => $level,
        ':search' => "%$search%"
    ]);
    return $query->fetchAll();
}
}
?>
