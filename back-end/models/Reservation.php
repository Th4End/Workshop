<?php
class Reservation {
    private $conn;
    private $table = 'réservations';
    private $roomsTable = 'salles'; // Nom de la table pour les salles

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

        // Vérification de la préparation de la requête
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        // Lier les paramètres
        if (!$stmt->bind_param("iiissss", $this->user_id, $this->course_id, $this->room_id, $this->reservation_date, $this->start_time, $this->end_time)) {
            throw new Exception("Erreur lors du binding des paramètres : " . $stmt->error);
        }

        // Exécuter la requête
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        return true; // Réservation créée avec succès
    }

    // Annuler une réservation
    public function cancelReservation($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Vérification de la préparation de la requête
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        // Lier le paramètre
        if (!$stmt->bind_param("i", $id)) {
            throw new Exception("Erreur lors du binding du paramètre : " . $stmt->error);
        }

        // Exécuter la requête
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        return true; // Réservation annulée avec succès
    }

    // Récupérer toutes les salles
    public function getAllRooms() {
        $query = "SELECT id, name FROM " . $this->roomsTable; // Utiliser la bonne table
        $stmt = $this->conn->prepare($query);
        
        // Vérification de la préparation de la requête
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        // Exécuter la requête
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        $result = $stmt->get_result();

        // Vérifier si des salles ont été trouvées
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC); // Retourner les salles sous forme de tableau associatif
        } else {
            return []; // Retourner un tableau vide si aucune salle n'est trouvée
        }
    }
}
?>
