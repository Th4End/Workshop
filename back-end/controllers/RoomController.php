<?php
require_once '../models/Room.php';

class RoomController {
    private $db;
    private $room;

    public function __construct($db) {
        $this->db = $db;
        $this->room = new Room($db);
    }

    // Récupérer toutes les salles sans passer de paramètres
    public function getRooms() {
        $result = $this->room->getAllRooms();
        $roomsArray = [];

        while ($row = $result->fetch_assoc()) {
            $roomsArray[] = $row;
        }

        echo json_encode($roomsArray); // Renvoie les salles sous forme de JSON
    }
}
?>
