<?php
header('Content-Type: application/json');
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
require_once __DIR__ . '/../controllers/UtilisateurController.php';
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
$userController = new UtilisateurController($db);


// Vérifier l'existence du paramètre 'action'
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // Gérer les requêtes POST uniquement pour les actions liées à l'utilisateur
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Décoder les données JSON reçues
        $input = json_decode(file_get_contents('php://input'), true);

        // Vérifier que les données sont valides
        if (!$input || !is_array($input)) {
            echo json_encode(["message" => "Données JSON invalides ou manquantes"]);
            exit();
        }

        // Connexion de l'utilisateur
        if ($action == 'login') {
            if (isset($input['email'], $input['password'])) {
                try {
                    $userController->login($input['email'], $input['password']);
                } catch (Exception $e) {
                    echo json_encode([
                        "message" => "Erreur lors de la connexion",
                        "error" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["message" => "Paramètres manquants pour la connexion"]);
            }
        }

        // Création d'un utilisateur
        elseif ($action == 'createUser') {
            if (isset($input['name'], $input['email'], $input['password'])) {
                try {
                    $userController->createUser($input['name'], $input['email'], $input['password']);
                    echo json_encode(["message" => "Utilisateur créé avec succès"]);
                } catch (Exception $e) {
                    echo json_encode([
                        "message" => "Erreur lors de la création de l'utilisateur",
                        "error" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["message" => "Paramètres manquants pour la création de l'utilisateur"]);
            }
        }

        // Mise à jour d'un utilisateur
        elseif ($action == 'updateUser') {
            if (isset($input['id'], $input['name'], $input['email'], $input['password'])) {
                try {
                    $userController->updateUser($input['id'], $input['name'], $input['email'], $input['password']);
                    echo json_encode(["message" => "Utilisateur mis à jour avec succès"]);
                } catch (Exception $e) {
                    echo json_encode([
                        "message" => "Erreur lors de la mise à jour de l'utilisateur",
                        "error" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["message" => "Paramètres manquants pour la mise à jour de l'utilisateur"]);
            }
        }

        // Réserver une salle
        elseif ($action == 'reserveRoom') {
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
                    echo json_encode(["message" => "Réservation réussie"]);
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
