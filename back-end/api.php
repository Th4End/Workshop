<?php
// Configurer la connexion à la base de données MySQL
$host = 'localhost';  // Si c'est sur ton serveur local
$username = 'root'; // Remplace par ton utilisateur MySQL
$password = ''; // Remplace par ton mot de passe MySQL
$database = 'badgeit'; // Remplace par le nom de ta base de données

$mysqli = new mysqli(hostname: $host, username: $username, password: $password, database: $database);

// Vérifie si la connexion est réussie
if ($mysqli->connect_error) {
    die('Erreur de connexion (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

// Permettre les requêtes depuis le front-end Vue.js (CORS)
header(header: "Access-Control-Allow-Origin: *");
header(header: "Content-Type: application/json");

// Différencier les méthodes GET et POST
$method = $_SERVER['REQUEST_METHOD'];

// Gestion des requêtes GET (lecture des données)
if ($method === 'GET') {
    $query = "SELECT * FROM utilisateurs";  // Change 'utilisateurs' par ta table
    $result = $mysqli->query(query: $query);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    echo json_encode(value: $users);  // Retourne les résultats sous forme de JSON
}

// Gestion des requêtes POST (ajout des données)
if ($method === 'POST') {
    // Récupérer les données envoyées en POST
    $data = json_decode(json: file_get_contents(filename: "php://input"), associative: true);  // Récupère les données JSON envoyées

    $name = $data['name'];
    $email = $data['email'];

    // Insertion dans la base de données
    $stmt = $mysqli->prepare(query: "INSERT INTO utilisateurs (name, email) VALUES (?, ?)");
    $stmt->bind_param(types: "ss", var: $name, vars: $email);

    if ($stmt->execute()) {
        echo json_encode(value: ['message' => 'Utilisateur ajouté avec succès']);
    } else {
        echo json_encode(value: ['error' => 'Erreur lors de l\'ajout de l\'utilisateur']);
    }
}

$mysqli->close();  // Fermer la connexion
?>
