<?php
class Database {
    private static $instance = null;
    private $conn;

    // Kredensial Database (Sesuaikan jika ada password di MySQL kamu)
    private $host = 'localhost';
    private $user = 'root'; 
    private $pass = ''; 
    private $name = 'inventaris_db';

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->name}", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Koneksi gagal: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>