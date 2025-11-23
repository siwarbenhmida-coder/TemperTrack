<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sensor_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

//$temperature = isset($_GET['temp']) ? floatval($_GET['temp']) : null;
//$humidity = isset($_GET['hum']) ? floatval($_GET['hum']) : null;


$temperature = $_GET['temp'];
$humidity = $_GET['hum'];

// Debug : Afficher les valeurs reçues


if ($temperature !== null && $humidity !== null) {
    $stmt = $conn->prepare("INSERT INTO readings (temperature, humidity) VALUES (?, ?)");
    $stmt->bind_param("dd", $temperature, $humidity);

    if ($stmt->execute()) {
        echo "Données insérées avec succès : Température = $temperature °C, Humidité = $humidity %";
    } else {
        echo "Erreur : " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Paramètres manquants ou invalides.";
}
var_dump($_GET);

