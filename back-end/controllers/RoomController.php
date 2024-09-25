<?php
require_once '../models/Room.php';

class RoomController {
    public function getAllRooms() {
        $rooms = Room::getAll();
        echo json_encode($rooms);
    }

    public function getRoom($id) {
        $room = Room::findById($id);
        if ($room) {
            echo json_encode($room);
        } else {
            echo json_encode(['message' => 'Room not found']);
        }
    }
}
