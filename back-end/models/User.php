<?php
class Utilisateur {
    private $conn;
    private $table = 'utilisateurs';
    private $encryption_key;

    public function __construct($db) {
        $this->conn = $db;
        $this->encryption_key = 'E548OPR845645TREASDFTMP7896'; // Utilisez une clé suffisamment complexe
    }

    // Générer un salt
    private function generateSalt() {
        return bin2hex(random_bytes(16)); // Génère un salt aléatoire
    }

    // Hacher le mot de passe avec un salt
    private function hashPassword($password, $salt) {
        return hash('sha256', $password . $salt); // Hachage avec SHA-256
    }

    // Chiffrer le mot de passe avec AES-256
    private function encryptPassword($password) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($password, 'aes-256-cbc', $this->encryption_key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    // Déchiffrer le mot de passe avec AES-256
    private function decryptPassword($encryptedPassword) {
        list($encrypted_data, $iv) = explode('::', base64_decode($encryptedPassword), 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $this->encryption_key, 0, $iv);
    }

    // Valider l'email avec regex
    private function isValidEmail($email) {
        return preg_match("/^[a-zA-Z0-9.]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email);
    }

    // Valider le mot de passe avec regex
    private function isValidPassword($password) {
        return preg_match("/^(?=.*[&!.@])[A-Za-z\d&!.@]{8,12}$/", $password);
    }

    // Créer un nouvel utilisateur
    public function createUser($name, $email, $password) {
        header('Content-Type: application/json');

        if (empty($name) || empty($email) || empty($password)) {
            echo json_encode(["message" => "Tous les champs sont requis."]);
            return;
        }

        if (!$this->isValidEmail($email)) {
            echo json_encode(["message" => "Email invalide."]);
            return;
        }

        if (!$this->isValidPassword($password)) {
            echo json_encode(["message" => "Le mot de passe doit contenir entre 8 et 12 caractères et inclure au moins un des caractères suivants : [&!.@]."]);
            return;
        }

        if ($this->getUserByEmail($email)) {
            echo json_encode(["message" => "L'utilisateur existe déjà avec cet email."]);
            return;
        }

        try {
            $salt = $this->generateSalt();
            $hashedPassword = $this->hashPassword($password, $salt); // Hachage avec le salt
            $encryptedPassword = $this->encryptPassword($hashedPassword); // Chiffrement du mot de passe haché
            
            $query = "INSERT INTO " . $this->table . " (name, email, password, salt) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $name, $email, $encryptedPassword, $salt);
            if ($stmt->execute()) {
                echo json_encode(["message" => "Utilisateur créé avec succès"]);
            } else {
                echo json_encode(["message" => "Erreur lors de la création de l'utilisateur."]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "message" => "Erreur lors de la création de l'utilisateur",
                "error" => $e->getMessage()
            ]);
        }
    }

    // Récupérer un utilisateur par email
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // Connexion de l'utilisateur
    public function login($email, $password) {
        header('Content-Type: application/json');

        if (empty($email) || empty($password)) {
            echo json_encode(["message" => "Tous les champs sont requis."]);
            return;
        }

        if (!$this->isValidEmail($email)) {
            echo json_encode(["message" => "Email invalide."]);
            return;
        }

        $user = $this->getUserByEmail($email);
        if ($user) {
            $decryptedPassword = $this->decryptPassword($user['password']);
            $hashedPassword = $this->hashPassword($decryptedPassword, $user['salt']);
            if ($hashedPassword === $this->hashPassword($password, $user['salt'])) {
                echo json_encode(["message" => "Connexion réussie"]);
            } else {
                echo json_encode(["message" => "Email ou mot de passe incorrect."]);
            }
        } else {
            echo json_encode(["message" => "Email ou mot de passe incorrect."]);
        }
    }

    // Mettre à jour un utilisateur
    public function updateUser($id, $name, $email, $password = null) {
        $query = "UPDATE " . $this->table . " SET name = ?, email = ?" . ($password ? ", password = ? WHERE id = ?" : " WHERE id = ?");
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }

        if ($password) {
            $salt = $this->generateSalt();
            $hashedPassword = $this->hashPassword($password, $salt);
            $encryptedPassword = $this->encryptPassword($hashedPassword);
            $stmt->bind_param("sssi", $name, $email, $encryptedPassword, $id);
        } else {
            $stmt->bind_param("ssi", $name, $email, $id);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Supprimer un utilisateur
    public function deleteUser($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Récupérer un utilisateur par ID
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

}
?>
