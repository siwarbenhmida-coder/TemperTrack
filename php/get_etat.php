<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sensor_data";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT etat FROM actionneurs WHERE id = 1";
    $stmt = $conn->query($sql);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $result['etat']; // Affiche ON ou OFF
} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>
