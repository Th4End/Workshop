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

        // Vérification de la préparation de la requête
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Vérifier si des salles ont été trouvées
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC); // Retourner les salles sous forme de tableau associatif
        } else {
            throw new Exception("Aucune salle trouvée dans la base de données."); // Gestion d'erreur si aucune salle n'est trouvée
        }
    }

    // Récupérer une salle par ID
    public function getRoomById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        // Vérification de la préparation de la requête
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Vérifier si la salle a été trouvée
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Retourner la salle sous forme de tableau associatif
        } else {
            throw new Exception("Aucune salle trouvée avec cet ID."); // Gestion d'erreur si la salle n'est pas trouvée
        }
    }
}
?>
