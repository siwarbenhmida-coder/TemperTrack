#include <ESP8266WiFi.h>
#include <DHT.h>

// Configuration pour le DHT11
#define DHTPIN D4
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// Configuration Wi-Fi
const char* ssid = "souha";
const char* password = "souha123456";

// Configuration du serveur
const char* server = " 192.168.137.1";

void setup() {
    Serial.begin(115200);
    dht.begin();

    // Connexion au Wi-Fi
    WiFi.begin(ssid, password);
    while (WiFi.status() != WL_CONNECTED) {
        delay(1000);
        Serial.println("Connexion au Wi-Fi...");
    }
    Serial.println("Connecté au Wi-Fi");
}

void loop() {
    // Lecture des données du capteur
    float humidity = dht.readHumidity();
    float temperature = dht.readTemperature();

    if (isnan(humidity) || isnan(temperature)) {
        Serial.println("Erreur de lecture du capteur DHT11");
        return;
    }

    // Envoi des données au serveur
 
WiFiClient client;
if (client.connect(server, 80)) {
   String url = "/servaillance_température/insert_data.php?temp=" + String(temperature) + "&hum=" + String(humidity);

    client.print(String("GET ") + url + " HTTP/1.1\r\n" +
                 "Host: " + server + "\r\n" +
                 "Connection: close\r\n\r\n");
    Serial.println("Données envoyées : Temp=" + String(temperature) +
                   "°C, Hum=" + String(humidity) + "%");
} else {
    Serial.println("Échec de la connexion au serveur");
}


      delay(10000); // Attente de 1 minute avant la prochaine lecture
}