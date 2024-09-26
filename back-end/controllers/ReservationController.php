<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
        header('Content-Type: application/json');

        if (empty($user_id) || empty($course_id) || empty($room_id) || empty($reservation_date) || empty($start_time) || empty($end_time)) {
            echo json_encode(["message" => "Tous les champs sont requis."]);
            return;
        }

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
            error_log("Erreur lors de la réservation: " . $e->getMessage());
            echo json_encode(["message" => "Erreur lors de la réservation: " . $e->getMessage()]);
        }
    }

    // Annuler une réservation
    public function cancelReservation($reservation_id) {
        header('Content-Type: application/json');

        try {
            if ($this->reservation->cancelReservation($reservation_id)) {
                echo json_encode(["message" => "Annulation réussie"]);
            } else {
                echo json_encode(["message" => "Aucune réservation trouvée avec cet ID."]);
            }
        } catch (Exception $e) {
            error_log("Erreur lors de l'annulation: " . $e->getMessage());
            echo json_encode(["message" => "Erreur lors de l'annulation: " . $e->getMessage()]);
        }
    }

    // Récupérer les réservations
    public function getReservations($user_id = null) {
    
        try {
            $reservations = $this->reservation->getAllReservations($user_id); // Méthode à implémenter dans le modèle Reservation
    
            if (empty($reservations)) {
                echo json_encode(["message" => "Aucune réservation trouvée."]);
            } else {
                echo json_encode(["reservations" => $reservations]);
            }
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des réservations: " . $e->getMessage());
            echo json_encode(["message" => "Erreur lors de la récupération des réservations: " . $e->getMessage()]);
        }
    }
}
?>
