à<?php
$servername = "localhost";
$username = "root"; // Remplacez par votre utilisateur MySQL
$password = ""; // Remplacez par votre mot de passe MySQL
$dbname = "sensor_data";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Récupérer l'état actuel du ventilateur
$sql = "SELECT etat FROM actionneurs WHERE id = 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$etat = $row['etat'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contrôle Ventilateur</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
   .p img{
    transform: rotate(90deg); /* pivote de 180 degrés */
  width: 200px; /* optionnel */
  height: auto;
  margin-top: 500px;
  margin-left: -1000px; 
   }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-image: url('WhatsApp Image 2025-02-26 à 19.32.49_bb50b9be.jpg'); /* Remplace par ton image */
      background-size: cover;
      background-position: center;
      height: 100vh;
      display: flex;
      justify-content: flex-start;
      align-items: center;
    }

    .content {
      background-color: rgba(255, 255, 255, 0.85);
      padding: 80px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 40px 40px rgba(0, 0, 0, 0.15);
      margin-left: 35%; /* Ajuste selon tes besoins */
      
    }

    h1 {
      font-size: 32px;
      color: #004d40;
      margin-bottom: 20px;
    }

    .etat {
      font-size: 20px;
      margin-bottom: 25px;
      color: #333;
    }

    .etat span {
      font-weight: bold;
      padding: 5px 10px;
      border-radius: 8px;
    }

    .etat .ON {
      background-color: #C8E6C9;
      color: #388E3C;
    }

    .etat .OFF {
      background-color: #FFCDD2;
      color: #D32F2F;
    }

    .buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    button {
      padding: 12px 28px;
      font-size: 16px;
      border: none;
      border-radius: 10px;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s ease;
    }

    button.on {
      background-color: #4CAF50;
    }

    button.on:hover {
      background-color: #43a047;
    }

    button.off {
      background-color: #F44336;
    }

    button.off:hover {
      background-color: #e53935;
    }

    @media (max-width: 600px) {
      .buttons {
        flex-direction: column;
      }

      button {
        width: 100%;
      }
      
    }
    .a {
        width: 40px; /* Vous pouvez ajuster la taille ici */
        position: absolute; /* Utilisation de position absolute pour un placement précis */
        top: 0; /* Place l'image en haut */
        right: 250px; /* Place l'image à droite */
    }
    .ap {
        width: 40px; /* Vous pouvez ajuster la taille ici */
        position: absolute; /* Utilisation de position absolute pour un placement précis */
        top: 0; /* Place l'image en haut */
        right: 150px; /* Place l'image à droite */
    }
    .te {
        width: 40px; /* Vous pouvez ajuster la taille ici */
        position: absolute; /* Utilisation de position absolute pour un placement précis */
        top: 0; /* Place l'image en haut */
        right: 50px; /* Place l'image à droite */
    }

  </style>
</head>
<body>
<a href="project1.html"><img src="page-daccueil.png" class="a"></a>
    <a href="a-propos-de-nous.html"><img src="a-propos-de-nous.png" class="ap"></a>
    <a href="contacter-nous.html"><img src="telephoner.png" class="te"></a>
  <div class="content">
    <h1>Contrôle Ventilateur</h1>
    <div class="etat">
      État du Ventilateur : 
      <span class="<?php echo $etat; ?>"><?php echo $etat; ?></span>
    </div>
    <form action="control.php" method="POST" class="buttons">
      <button type="submit" name="etat" value="ON" class="on">Allumer</button>
      <button type="submit" name="etat" value="OFF" class="off">Éteindre</button>
    </form>
  </div>
  <div class="p">
  <a href="get_data.php"> <img  src="féche.png"></a>
</div>

  
</body>
</html>
