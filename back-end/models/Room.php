<?php
class Room {
    private $conn;
    private $table = 'salles'; // Le nom de la table à interroger

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fonction pour récupérer toutes les lignes de la table 'salles'
    public function getAllRooms() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
