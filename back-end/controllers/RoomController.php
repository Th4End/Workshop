<?php
require '../models/Room.php';

class RoomController {
    private $db;
    private $room;

    public function __construct($db) {
        $this->db = $db;
        $this->room = new Room($db);
    }

    public function getRooms() {
        try {
            $result = $this->room->getAllRooms();
            echo json_encode($result); // Renvoie les salles sous forme de JSON
        } catch (Exception $e) {
            echo json_encode([
                "message" => "Erreur lors de la récupération des salles",
                "error" => $e->getMessage()
            ]);
        }
    }
    
}
?>
