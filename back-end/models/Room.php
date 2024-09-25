<?php
class Room {
    private $conn;
    private $table = 'salles';

    public $id;
    public $room_name;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Récupérer toutes les salles
    public function getAllRooms() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Récupérer une salle par ID
    public function getRoomById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
