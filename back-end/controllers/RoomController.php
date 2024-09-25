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
        // Appel de getAllRooms() qui retourne déjà un tableau associatif
        $result = $this->room->getAllRooms();

        // Le résultat est déjà un tableau, pas besoin de fetch_assoc
        echo json_encode($result); // Renvoie les salles sous forme de JSON
    }
}
?>