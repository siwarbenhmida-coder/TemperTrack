<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Nom d'utilisateur par défaut
$password = "";     // Mot de passe par défaut
$dbname = "sensor_data"; // Nom de la base de données

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

// Récupérer les données de température, humidité et horodatage de la journée
$query = "SELECT temperature, humidity, DATE_FORMAT(timestamp, '%H:%i') AS time 
          FROM readings 
          WHERE DATE(timestamp) = CURDATE()
          ORDER BY timestamp ASC";
$result = $conn->query($query);

$temperatures = [];
$humidities = [];
$times = [];
$currentTemperature = null;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $temperatures[] = $row['temperature'];
        $humidities[] = $row['humidity'];
        $times[] = $row['time'];
        // Garder la dernière température pour l'alerte
        $currentTemperature = $row['temperature'];
    }
}

// Fermer la connexion
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surveillance des Capteurs</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --background-color: #f5f5f5;
            --text-color: #333;
            --card-background: #fff;
            --button-background: #4facfe;
            --button-color: #fff;
            --alert-color: #ff0000;
        }

        body.dark-mode {
            --background-color: #121212;
            --text-color: #e0e0e0;
            --card-background: #1e1e1e;
            --button-background: #00f2fe;
            --button-color: #000;
            --alert-color: #ff0000;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            width: 100%;
        }

        header h1 {
            font-size: 2rem;
        }

        .dark-mode-toggle {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--button-background);
            color: var(--button-color);
            border: none;
            border-radius: 15px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 1rem;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background-color: var(--card-background);
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }

        .alert {
            background-color: var(--alert-color);
            color: #fff;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        #chartsContainer {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        canvas {
            width: 100%;
            max-width: 500px;
        }

        footer {
            margin-top: 20px;
            font-size: 0.9rem;
            text-align: center;
        }
        .logout-btn {
    position: absolute;
    top: 10px;
    left: 10px;
    background-color:rgb(17, 148, 255);
    color: white;
    border: none;
    border-radius: 15px;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 1rem;
}
.logout-btn:hover {
    background-color:rgb(4, 205, 255);
}
.alert-button {
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #fff;
    color: #ff0000;
    border: 2px solid #fff;
    font-weight: bold;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
}
.alert-button:hover {
    background-color: #ff0000;
    color: #fff;
    border-color: #ff0000;
}

    </style>
</head>
<body>
    <header>
        <h1>Surveillance des Données des Capteurs</h1>
        <button class="dark-mode-toggle">Mode sombre</button>
    </header>

    <main>
        <!-- Afficher l'alerte si la température actuelle dépasse 30°C -->
        <?php if ($currentTemperature > 30): ?>



              
            <div class="alert">
                Attention : Température élevée (<?= htmlspecialchars($currentTemperature) ?> °C) !
                <button onclick="window.location.href='index.php'" class="alert-button">Cliquer pour changer l etat de ventulateur</button>
            </div>
        <?php endif; ?>
        <!-- Alerte pour l'humidité actuelle -->
<?php if (end($humidities) > 70): ?>
    <div class="alert">
        Attention : Humidité élevée (<?= htmlspecialchars(end($humidities)) ?> %) !
        <button onclick="window.location.href='index.php'" class="alert-button">Cliquer pour changer l etat de ventulateur</button>
    </div>
<?php endif; ?>

        <div class="card">
            <h2>Données Actuelles</h2>
            <div class="sensor-data">
                <p><strong>Température :</strong> <?= htmlspecialchars($currentTemperature) ?> °C</p>
                <p><strong>Humidité :</strong> <?= htmlspecialchars(end($humidities)) ?> %</p>
            </div>
        </div>
        <button class="logout-btn" onclick="logout()">Déconnexion</button>
    


        <div id="chartsContainer">
            <canvas id="temperatureChart"></canvas>
            <canvas id="humidityChart"></canvas>
        </div>
    </main>

    <footer>
        <p>© 2025 Temptrack. Tous droits réservés.</p>
    </footer>

    <script>
        const toggleButton = document.querySelector('.dark-mode-toggle');
        const body = document.body;

        toggleButton.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            toggleButton.textContent = body.classList.contains('dark-mode') ? 'Mode clair' : 'Mode sombre';
        });

        // Données pour les graphiques
        const temperatures = <?= json_encode($temperatures) ?>;
        const humidities = <?= json_encode($humidities) ?>;
        const times = <?= json_encode($times) ?>;

        const ctxTemperature = document.getElementById('temperatureChart').getContext('2d');
        new Chart(ctxTemperature, {
            type: 'line',
            data: {
                labels: times,
                datasets: [{
                    label: 'Température (°C)',
                    data: temperatures,
                    borderColor: '#4facfe',
                    backgroundColor: 'rgba(79, 172, 254, 0.2)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: '#4facfe'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Temps (HH:MM)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Température (°C)'
                        }
                    }
                }
            }
        });

        const ctxHumidity = document.getElementById('humidityChart').getContext('2d');
        new Chart(ctxHumidity, {
            type: 'line',
            data: {
                labels: times,
                datasets: [{
                    label: 'Humidité (%)',
                    data: humidities,
                    borderColor: '#ff7f50',
                    backgroundColor: 'rgba(255, 127, 80, 0.2)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: '#ff7f50'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Temps (HH:MM)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Humidité (%)'
                        }
                    }
                }
            }
        });
    </script>
    <script>
    function logout() {
        window.location.href = 'logout.php';
    }
</script>

</body>
</html>
