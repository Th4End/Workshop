<?php
require_once __DIR__ . '/../models/User.php';

class UtilisateurController {
    private $db;
    private $utilisateurModel;

    public function __construct($db) {
        $this->db = $db;
        $this->utilisateurModel = new Utilisateur($this->db);
    }

    // Créer un nouvel utilisateur
    public function createUser($name, $email, $password) {
        if ($this->isEmailValid($email) && $this->isPasswordValid($password)) {
            if ($this->utilisateurModel->createUser($name, $email, $password)) {
                return json_encode(["message" => "Utilisateur créé avec succès."]);
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
            return json_encode(["message" => "Connexion réussie.", "user" => $user]);
        } else {
            throw new Exception("Identifiants invalides.");
        }
    }

    // Mettre à jour un utilisateur
    public function updateUser($id, $name, $email, $password) {
        if ($this->isEmailValid($email) && $this->isPasswordValid($password)) {
            if ($this->utilisateurModel->updateUser($id, $name, $email, $password)) {
                return json_encode(["message" => "Utilisateur mis à jour avec succès."]);
            } else {
                throw new Exception("Erreur lors de la mise à jour de l'utilisateur.");
            }
        } else {
            throw new Exception("Email ou mot de passe invalide.");
        }
    }

    // Récupérer tous les utilisateurs
    public function getAllUsers() {
        $users = $this->utilisateurModel->getAllUsers();
        return json_encode($users);
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $user = $this->utilisateurModel->getUserById($id);
        if ($user) {
            return json_encode($user);
        } else {
            throw new Exception("Utilisateur non trouvé.");
        }
    }

    // Valider l'email
    private function isEmailValid($email) {
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email);
    }

    // Valider le mot de passe
    private function isPasswordValid($password) {
        return preg_match('/^(?=.*[&!.@])[A-Za-z0-9&!.@]{8,12}$/', $password);
    }

    // Récupérer un utilisateur par email
    public function getUserByEmail($email) {
        return $this->utilisateurModel->getUserByEmail($email);
    }
}
?>
