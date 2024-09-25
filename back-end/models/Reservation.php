<?php
class Reservation {
    private $conn;
    private $table = 'réservations';

    public $id;
    public $user_id;
    public $course_id;
    public $room_id;
    public $reservation_date;
    public $start_time;
    public $end_time;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Créer une réservation
    public function createReservation() {
        $query = "INSERT INTO " . $this->table . " (user_id, course_id, room_id, reservation_date, start_time, end_time) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiisss", $this->user_id, $this->course_id, $this->room_id, $this->reservation_date, $this->start_time, $this->end_time);
        return $stmt->execute();
    }

    // Annuler une réservation
    public function cancelReservation($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
