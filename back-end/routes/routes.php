<?php
// Autoriser les requêtes depuis l'origine (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Origin: http://localhost:5173");

// Gérer les requêtes OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclure les fichiers nécessaires
require_once 'controllers/RoomController.php';
require_once 'controllers/ReservationController.php'; // Inclure le contrôleur pour les réservations
require_once 'config/Database.php';

$database = new Database();
$db = $database->getConnection();

// Initialiser les contrôleurs
$roomController = new RoomController($db);
$reservationController = new ReservationController($db); // Ajouter un contrôleur pour gérer les réservations

// Gérer les requêtes GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'getRooms') {
        $roomController->getRooms();
    } else {
        echo json_encode(["message" => "Action non reconnue"]);
    }
// Gérer les requêtes POST
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($_GET['action'] == 'reserveRoom') {
        // Traiter la réservation de salle
        $reservationController->reserveRoom(
            $input['user_id'], 
            $input['course_id'], 
            $input['room_id'], 
            $input['reservation_date'], 
            $input['start_time'], 
            $input['end_time']
        );
    } else {
        echo json_encode(["message" => "Action non reconnue"]);
    }
} else {
    echo json_encode(["message" => "Requête non valide"]);
}
?>
