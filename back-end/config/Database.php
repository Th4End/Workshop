<?php
class Database {
    private $host = '192.168.1.145';
    private $db_name = 'badgeit';
    private $username = 'root@localhost';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        } catch (Exception $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
