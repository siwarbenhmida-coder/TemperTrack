<?php
$host = "localhost";
$dbname = "sensor_data";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$query = "SELECT etat FROM actionneurs";
$stmt = $pdo->query($query);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['etat'];
}
?>