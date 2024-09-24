<?php
  // Connexion à la base de données
  $db_host = 'your_database_host';
  $db_username = 'your_database_username';
  $db_password = 'your_database_password';
  $db_name = 'your_database_name';

  $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

  // Vérifier la connexion
  if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
  }

  // Récupérer les données envoyées
  $name = $_POST['name'];
  $email = $_POST['email'];

  // Insérer les données dans la base de données
  $sql = "INSERT INTO your_table_name (name, email) VALUES ('$name', '$email')";
  $result = $conn->query($sql);

  // Fermer la connexion
  $conn->close();
?>