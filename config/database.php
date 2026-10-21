
<?php
class Database {
    private $host = "127.0.0.1";
    private $dbname = "altera_db";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        try {
            $dsn = "mysql:host=" . $this->host .
                   ";port=3306" .
                   ";dbname=" . $this->dbname .
                   ";charset=utf8mb4";

            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );

            return $this->conn;

        } catch (PDOException $e) {
            error_log($e->getMessage());

            die("Koneksi database gagal. Silakan periksa konfigurasi database.");
        }
    }
}
?>
