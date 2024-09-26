<?php
require_once __DIR__ . '/../models/Utilisateur.php';

class UserController {
    private $db;
    private $utilisateurModel;

    public function __construct($db) {
        $this->db = $db;
        $this->utilisateurModel = new Utilisateur($this->db);
    }

    // Créer un nouvel utilisateur
    public function createUser($name, $email, $password) {
        // Valider les données d'entrée
        if ($this->isEmailValid($email) && $this->isPasswordValid($password)) {
            if ($this->utilisateurModel->createUser($name, $email, $password)) {
                echo json_encode(["message" => "Utilisateur créé avec succès."]);
            } else {
                throw new Exception("Erreur lors de la création de l'utilisateur.");
            }
        } else {
            throw new Exception("Email ou mot de passe invalide.");
        }
    }

    // Connexion de l'utilisateur
    public function login($email, $password) {
        $user = $this->utilisateurModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie
            echo json_encode(["message" => "Connexion réussie.", "user" => $user]);
        } else {
            throw new Exception("Identifiants invalides.");
        }
    }

    // Mettre à jour un utilisateur
    public function updateUser($id, $name, $email, $password) {
        // Valider les données d'entrée
        if ($this->isEmailValid($email) && $this->isPasswordValid($password)) {
            if ($this->utilisateurModel->updateUser($id, $name, $email, $password)) {
                echo json_encode(["message" => "Utilisateur mis à jour avec succès."]);
            } else {
                throw new Exception("Erreur lors de la mise à jour de l'utilisateur.");
            }
        } else {
            throw new Exception("Email ou mot de passe invalide.");
        }
    }

    // Valider l'email
    private function isEmailValid($email) {
        // Regex pour valider l'email (caractères spéciaux limités)
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
    }

    // Valider le mot de passe
    private function isPasswordValid($password) {
        // Vérifie la longueur et les caractères requis
        return preg_match('/^(?=.*[&!.@])[A-Za-z0-9&!.@]{8,12}$/', $password);
    }
}
?>
