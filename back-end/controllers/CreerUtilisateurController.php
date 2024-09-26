<?php
require_once '../models/User.php'; // Inclure le modèle utilisateur

class UserController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db = $db;
        $this->user = new Utilisateur($db);
    }

    // Fonction pour créer un nouvel utilisateur
    public function createUser($name, $email, $password) {
        // Assurez-vous que le type de contenu est JSON
        header('Content-Type: application/json');

        // Vérification des valeurs d'entrée
        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(["message" => "Tous les champs sont requis."]);
            return;
        }

        // Vérification si l'utilisateur existe déjà
        if ($this->user->getUserByEmail($email)) {
            echo json_encode(["message" => "L'utilisateur existe déjà avec cet email."]);
            return;
        }

        try {
            // Créer un nouvel utilisateur
            if ($this->user->createUser($name, $email, $password)) {
                echo json_encode(["message" => "Utilisateur créé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la création de l'utilisateur."]);
            }
        } catch (Exception $e) {
            // Gestion d'erreurs
            echo json_encode([
                "message" => "Erreur lors de la création de l'utilisateur",
                "error" => $e->getMessage()
            ]);
        }
    }

       // Fonction pour récupérer un utilisateur par email
       public function getUserByEmail($email) {
        // Assurez-vous que le type de contenu est JSON
        header('Content-Type: application/json');

        // Vérification de l'entrée
        if (empty($email)) {
            echo json_encode(["message" => "L'email est requis."]);
            return;
        }

        try {
            $user = $this->user->getUserByEmail($email);

            if ($user) {
                // Retourner les informations de l'utilisateur
                echo json_encode($user);
            } else {
                echo json_encode(["message" => "Utilisateur non trouvé."]);
            }
        } catch (Exception $e) {
            // Gestion d'erreurs
            echo json_encode([
                "message" => "Erreur lors de la récupération de l'utilisateur",
                "error" => $e->getMessage()
            ]);
        }
    }

    // Fonction pour mettre à jour un utilisateur
    public function updateUser($id, $name, $email, $password) {
        // Assurez-vous que le type de contenu est JSON
        header('Content-Type: application/json');

        // Vérification des valeurs d'entrée
        if (empty($id) || empty($name) || empty($email)) {
            echo json_encode(["message" => "ID, nom et email sont requis."]);
            return;
        }

        try {
            // Mettre à jour l'utilisateur
            if ($this->user->updateUser($id, $name, $email, $password)) {
                echo json_encode(["message" => "Utilisateur mis à jour avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la mise à jour de l'utilisateur."]);
            }
        } catch (Exception $e) {
            // Gestion d'erreurs
            echo json_encode([
                "message" => "Erreur lors de la mise à jour de l'utilisateur",
                "error" => $e->getMessage()
            ]);
        }
    }

    // Fonction pour supprimer un utilisateur
    public function deleteUser($id) {
        // Assurez-vous que le type de contenu est JSON
        header('Content-Type: application/json');

        // Vérification de l'ID
        if (empty($id)) {
            echo json_encode(["message" => "L'ID de l'utilisateur est requis."]);
            return;
        }

        try {
            // Supprimer l'utilisateur
            if ($this->user->deleteUser($id)) {
                echo json_encode(["message" => "Utilisateur supprimé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la suppression de l'utilisateur."]);
            }
        } catch (Exception $e) {
            // Gestion d'erreurs
            echo json_encode([
                "message" => "Erreur lors de la suppression de l'utilisateur",
                "error" => $e->getMessage()
            ]);
        }
    }
}
?>
