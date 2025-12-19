<?php
/**
 * File Koneksi Database
 * Sistem Pencatatan Keuangan Pribadi
 */

require_once __DIR__ . '/config.php';

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $conn;

    // Koneksi ke database
    public function __construct() {
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            
            if ($this->conn->connect_error) {
                die("Koneksi gagal: " . $this->conn->connect_error);
            }
            
            // Set charset ke utf8mb4
            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    // Mendapatkan koneksi
    public function getConnection() {
        return $this->conn;
    }

    // Menutup koneksi
    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Instansiasi database
$database = new Database();
$conn = $database->getConnection();
?>

