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
          // Appelle la méthode login du contrôleur utilisateur et récupère le résultat
          if ($userController->login($input['email'], $input['password'])) {
              // Si la connexion réussit, renvoie une réponse JSON de succès
              echo json_encode([
                  'success' => true,
                  'message' => 'Connexion réussie'
              ]);
          } else {
              // Si la connexion échoue, renvoie une réponse JSON d'erreur
              echo json_encode([
                  'success' => false,
                  'message' => 'Erreur lors de la connexion. Email ou mot de passe incorrect.'
              ]);
          }
          exit(); // Assure-toi que le script s'arrête après l'envoi de la réponse
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
    }

    // Gérer les requêtes GET
    elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Récupérer tous les utilisateurs
        if ($action == 'getAllUsers') {
            try {
                $users = $userController->getAllUsers();
                echo json_encode($users);
            } catch (Exception $e) {
                echo json_encode([
                    "message" => "Erreur lors de la récupération des utilisateurs",
                    "error" => $e->getMessage()
                ]);
            }
        }

        // Récupérer un utilisateur par ID
        elseif ($action == 'getUserById') {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                try {
                    $user = $userController->getUserById($id);
                    if ($user) {
                        echo json_encode($user);
                    } else {
                        echo json_encode(["message" => "Utilisateur non trouvé."]);
                    }
                } catch (Exception $e) {
                    echo json_encode([
                        "message" => "Erreur lors de la récupération de l'utilisateur",
                        "error" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["message" => "ID manquant pour la récupération de l'utilisateur"]);
            }
        }

        // Récupérer un utilisateur par email
        elseif ($action == 'getUserByEmail') {
            if (isset($_GET['email'])) {
                $email = $_GET['email'];
                try {
                    $user = $userController->getUserByEmail($email);
                    if ($user) {
                        echo json_encode($user);
                    } else {
                        echo json_encode(["message" => "Utilisateur non trouvé."]);
                    }
                } catch (Exception $e) {
                    echo json_encode([
                        "message" => "Erreur lors de la récupération de l'utilisateur",
                        "error" => $e->getMessage()
                    ]);
                }
            } else {
                echo json_encode(["message" => "Email manquant pour la récupération de l'utilisateur"]);
            }
        }

        // Récupérer toutes les salles
        elseif ($action == 'getRooms') {
            try {
                $rooms = $roomController->getRooms();
                return  json_encode($rooms); // Affiche les salles sous forme de JSON
            } catch (Exception $e) {
                echo json_encode([
                    "message" => "Erreur lors de la récupération des salles",
                    "error" => $e->getMessage()
                ]);
            }
        }

        // Gérer d'autres requêtes GET si nécessaire...
        else {
            echo json_encode(["message" => "Action non reconnue"]);
        }
    } else {
        echo json_encode(["message" => "Méthode HTTP non supportée"]);
    }
} else {
    echo json_encode(["message" => "Action manquante dans la requête"]);
}
?>
