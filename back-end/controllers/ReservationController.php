<?php
require_once '../models/Reservation.php';

class ReservationController {
    private $db;
    private $reservation;

    public function __construct($db) {
        $this->db = $db;
        $this->reservation = new Reservation($db);
    }

    // Créer une réservation
    public function reserveRoom($user_id, $course_id, $room_id, $reservation_date, $start_time, $end_time) {
        $this->reservation->user_id = $user_id;
        $this->reservation->course_id = $course_id;
        $this->reservation->room_id = $room_id;
        $this->reservation->reservation_date = $reservation_date;
        $this->reservation->start_time = $start_time;
        $this->reservation->end_time = $end_time;

        try {
            if ($this->reservation->createReservation()) {
                echo json_encode(["message" => "Réservation réussie"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la création de la réservation."]);
            }
        } catch (Exception $e) {
            echo json_encode(["message" => "Erreur lors de la réservation: " . $e->getMessage()]);
        }
    }

    // Annuler une réservation
    public function cancelReservation($reservation_id) {
        try {
            if ($this->reservation->cancelReservation($reservation_id)) {
                echo json_encode(["message" => "Annulation réussie"]);
            } else {
                echo json_encode(["message" => "Aucune réservation trouvée avec cet ID."]);
            }
        } catch (Exception $e) {
            echo json_encode(["message" => "Erreur lors de l'annulation: " . $e->getMessage()]);
        }
    }

    // Récupérer les réservations
    public function getReservations() {
        // Implémentez la logique pour récupérer les réservations si nécessaire
    }
}
?>
