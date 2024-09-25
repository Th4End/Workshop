<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Gérer les requêtes CORS OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclure les fichiers nécessaires
require_once __DIR__ . '/../controllers/RoomController.php';
require_once __DIR__ . '/../controllers/ReservationController.php';
require_once '../config/Database.php';

// Initialiser la connexion à la base de données
try {
    $database = new Database();
    $db = $database->getConnection();
} catch (Exception $e) {
    echo json_encode([
        "message" => "Erreur de connexion à la base de données",
        "error" => $e->getMessage()
    ]);
    exit();
}

// Créer les instances des contrôleurs
$roomController = new RoomController($db);
$reservationController = new ReservationController($db);

// Vérifier l'existence du paramètre 'action'
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Gérer les requêtes GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        switch ($action) {
            case 'getRooms':
                $roomController->getRooms();
                break;
            default:
                echo json_encode(["message" => "Action non reconnue"]);
                break;
        }
    // Gérer les requêtes POST
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Décoder les données JSON reçues
        $input = json_decode(file_get_contents('php://input'), true);

        // Vérifier que les données sont valides
        if (!$input || !is_array($input)) {
            echo json_encode(["message" => "Données JSON invalides ou manquantes"]);
            exit();
        }

        if ($action == 'reserveRoom') {
            // Vérifier que toutes les informations nécessaires sont fournies
            if (isset($input['user_id'], $input['course_id'], $input['room_id'], $input['reservation_date'], $input['start_time'], $input['end_time'])) {
                try {
                    $reservationController->reserveRoom(
                        $input['user_id'], 
                        $input['course_id'], 
                        $input['room_id'], 
                        $input['reservation_date'], 
                        $input['start_time'], 
                        $input['end_time']
                    );
                    echo json_encode(["message" => "Réservation réussie"]); // Retourne un message de succès
                } catch (Exception $e) {
                    echo json_encode([
                        "message" => "Erreur lors de la réservation",
                        "error" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["message" => "Paramètres manquants pour la réservation"]);
            }
        } else {
            echo json_encode(["message" => "Action non reconnue"]);
        }
    } else {
        echo json_encode(["message" => "Méthode HTTP non supportée"]);
    }
} else {
    echo json_encode(["message" => "Action manquante dans la requête"]);
}
?>
