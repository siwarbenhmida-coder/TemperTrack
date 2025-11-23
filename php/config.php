<?php
$servername = "localhost"; // Remplace par l'adresse de ton serveur si nécessaire
$username = "root"; // Ton nom d'utilisateur MySQL
$password = ""; // Ton mot de passe MySQL (laisser vide si tu es en local)
$database = "sensor_data"; // Nom de ta base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $database);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
