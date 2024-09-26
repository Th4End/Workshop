<?php
session_start(); // Démarrer la session pour stocker les informations utilisateur

require_once '../models/User.php'; // Inclure le modèle utilisateur

class LoginController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new Utilisateur($db);
    }

    // Fonction de connexion
    public function login($email, $password) {
        // Assurez-vous que le type de contenu est JSON
        header('Content-Type: application/json');

        try {
            // Vérification des valeurs d'entrée
            if (empty($email) || empty($password)) {
                echo json_encode(["message" => "Tous les champs sont requis."]);
                return;
            }

            // Essayer de trouver l'utilisateur par email
            $user = $this->user->getUserByEmail($email);

            if (!$user) {
                echo json_encode(["message" => "Utilisateur non trouvé."]);
                return;
            }

            // Vérifier si le mot de passe est correct
            if (password_verify($password, $user['password'])) {
                // Mot de passe correct, démarrer une session utilisateur
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];

                echo json_encode(["message" => "Connexion réussie", "user" => $_SESSION]);
            } else {
                // Mot de passe incorrect
                echo json_encode(["message" => "Mot de passe incorrect."]);
            }
        } catch (Exception $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de la connexion : " . $e->getMessage());
            echo json_encode(["message" => "Erreur lors de la connexion. Veuillez réessayer plus tard."]);
        }
    }

    // Fonction de déconnexion
    public function logout() {
        // Assurez-vous que le type de contenu est JSON
        header('Content-Type: application/json');

        try {
            // Détruire la session
            session_destroy();
            echo json_encode(["message" => "Déconnexion réussie."]);
        } catch (Exception $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur lors de la déconnexion : " . $e->getMessage());
            echo json_encode(["message" => "Erreur lors de la déconnexion. Veuillez réessayer plus tard."]);
        }
    }
}
?>
