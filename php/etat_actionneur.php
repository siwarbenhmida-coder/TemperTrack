<?php
session_start();
require 'config.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer tous les actionneurs depuis la base de données
$query = "SELECT id, type, etat FROM actionneurs";
$stmt = $pdo->query($query);
$actionneurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gérer la soumission du formulaire pour changer l'état d'un actionneur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actionneur_id = $_POST['actionneur_id'];
    $nouvel_etat = $_POST['etat']; // ON ou OFF

    // Mettre à jour l'état de l'actionneur dans la base de données
    $update_query = "UPDATE actionneurs SET etat = :etat WHERE id = :id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->execute(['etat' => $nouvel_etat, 'id' => $actionneur_id]);

    // Rediriger pour éviter la soumission multiple du formulaire
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Actionneurs</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <h2>Actionneurs</h2>
    <div class="card-list">
        <?php foreach ($actionneurs as $actionneur): ?>
            <div class="card">
                <h2><i class="fas fa-toggle-on"></i> <?= htmlspecialchars($actionneur['type']) ?></h2>
                <p><strong>ID :</strong> <?= htmlspecialchars($actionneur['id']) ?></p>
                <p><strong>État :</strong> <?= htmlspecialchars($actionneur['etat']) ?></p>
                <div class="action-buttons">
                    <form method="POST">
                        <input type="hidden" name="actionneur_id" value="<?= $actionneur['id'] ?>">
                        <button type="submit" name="etat" value="ON" class="btn btn-on">
                            <i class="fas fa-power-off"></i> ON
                        </button>
                    </form>
                    <form method="POST">
                        <input type="hidden" name="actionneur_id" value="<?= $actionneur['id'] ?>">
                        <button type="submit" name="etat" value="OFF" class="btn btn-off">
                            <i class="fas fa-power-off"></i> OFF
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>