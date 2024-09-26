<?php
class Reservation {
    private $conn;
    private $table = 'réservations';
    private $roomsTable = 'salles';

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

    public function createReservation() {
        if (empty($this->user_id) || empty($this->course_id) || empty($this->room_id) || empty($this->reservation_date) || empty($this->start_time) || empty($this->end_time)) {
            throw new Exception("Tous les champs de réservation doivent être renseignés.");
        }

        $query = "INSERT INTO " . $this->table . " (user_id, course_id, room_id, reservation_date, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        print_r($query);

        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        if (!$stmt->bind_param("iiissss", $this->user_id, $this->course_id, $this->room_id, $this->reservation_date, $this->start_time, $this->end_time)) {
            throw new Exception("Erreur lors du binding des paramètres : " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    public function cancelReservation($id) {
        if (empty($id)) {
            throw new Exception("L'ID de réservation est requis pour l'annulation.");
        }

        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        if (!$stmt->bind_param("i", $id)) {
            throw new Exception("Erreur lors du binding du paramètre : " . $stmt->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        if ($stmt->affected_rows === 0) {
            throw new Exception("Aucune réservation trouvée avec cet ID.");
        }

        $stmt->close();
        return true;
    }

    public function getAllRooms() {
        $query = "SELECT id, name FROM " . $this->roomsTable;
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $rooms = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            return $rooms;
        } else {
            $stmt->close();
            throw new Exception("Aucune salle trouvée.");
        }
    }
}
?>
