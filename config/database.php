
<?php
class Database {
    private $host = "localhost";
    private $dbname = "altera_db";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host .
                ";dbname=" . $this->dbname .
                ";charset=utf8mb4",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $this->conn;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            die("Koneksi database gagal. Silakan periksa konfigurasi database.");
        }
    }
}
?>
