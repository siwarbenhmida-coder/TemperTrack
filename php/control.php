<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "sensor_data";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si une action est envoyée
    if (isset($_POST['etat'])) {
        $etat = $_POST['etat'];

        // Vérification des valeurs acceptées
        if ($etat === "ON" || $etat === "OFF") {
            // Mettre à jour l'état du ventilateur
            $sql = "UPDATE actionneurs SET etat = :etat WHERE id = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
            $stmt->execute();

            echo "État mis à jour avec succès.";
        } else {
            echo "Valeur invalide.";
        }
    } else {
        echo "Aucune donnée reçue.";
    }

    // Redirection vers la page principale après 2 secondes
    header("refresh:2;url=index.php");

} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['etat'])) {
        $etat = $_POST['etat'];

        if ($etat === "ON" || $etat === "OFF") {
            $sql = "UPDATE actionneurs SET etat = :etat WHERE id = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':etat', $etat, PDO::PARAM_STR);
            $stmt->execute();

            // Debug
            echo "État mis à jour avec succès : " . $etat;
        } else {
            echo "Valeur invalide : " . htmlspecialchars($etat);
        }
    } else {
        echo "Aucune donnée reçue.";
    }

    header("refresh:2;url=index.php");
} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
}

?>
