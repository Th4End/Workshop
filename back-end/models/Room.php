<?php
require_once '../config/Database.php';

// Instanciation de la base de données
try {
    $database = new Database();
    $db = $database->getConnection(); // Établir la connexion à la base de données
} catch (Exception $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

class Room {
    private $conn;
    private $table = 'salles';

    public $id;
    public $room_name;

    public function __construct($db) {
        if (!$db) {
            throw new Exception("Erreur de connexion à la base de données : Connexion non initialisée");
        }
        $this->conn = $db;
    }

    // Récupérer toutes les salles
    public function getAllRooms() {
        $query = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($query);
    
        if ($result === false) {
            throw new Exception("Erreur lors de l'exécution de la requête: " . $this->conn->error);
        }
    
        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
    
        return $rooms; // Retourne un tableau associatif
    }
    

    // Récupérer une salle par ID
    public function getRoomById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        }

        $result = $stmt->get_result();
        if ($result === false) {
            throw new Exception("Erreur lors de la récupération des résultats : " . $stmt->error);
        }

        // Vérifier si la salle a été trouvée
        $room = $result->fetch_assoc();
        if (!$room) {
            throw new Exception("Aucune salle trouvée avec cet ID.");
        }
        return $room; // Retourner la salle sous forme de tableau associatif
    }
}

?>
