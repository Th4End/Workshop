<?php
// Inclusion des fichiers nécessaires
require_once 'controllers/RoomController.php';
require_once 'controllers/ReservationController.php';
require_once 'config/Database.php';

// Configuration de la base de données
$database = new Database();
$db = $database->getConnection();

// Initialiser les contrôleurs
$roomController = new RoomController($db);
$reservationController = new ReservationController($db);

// Déterminer l'action en fonction de la requête
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    if ($_GET['action'] == 'getRooms') {
        $roomController->getRooms();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($_GET['action'] == 'reserveRoom') {
        $reservationController->reserveRoom($input['user_id'], $input['course_id'], $input['room_id'], $input['reservation_date'], $input['start_time'], $input['end_time']);
    } elseif ($_GET['action'] == 'cancelReservation') {
        $reservationController->cancelReservation($input['reservation_id']);
    }
}
?>
