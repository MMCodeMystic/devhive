<?php
$title = "Projekte | DevHive";
include 'header.php';
?>

<main>
    <section class="hero">
        <h1>Meine Projekte</h1>
        <p>Hier findest du eine Übersicht über meine Projekte und Experimente.</p>
    </section>

    <section class="section">
        <h2>Web-Projekte</h2>
        <div class="cards">
            <!-- Lernapp (externer Unterordner) -->
            <div class="card">
                <h3>Lernsoftware</h3>
                <p>Eine PHP-basierte Lernanwendung für verschiedene Themen.</p>
                <a href="lernapp/" class="btn">Zur Lernapp</a>
            </div>

            <!-- Tierheim-App (externer Unterordner) -->
            <div class="card">
                <h3>Tierheim</h3>
                <p>Eine PHP-Website für ein fiktives Tierheim.</p>
                <a href="tierheim/" class="btn">Zum Tierheim</a>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Python/Kivy-Projekte</h2>
        <div class="cards">
            <!-- Römische Zahlen Konverter -->
            <div class="card">
                <h3>Römische Zahlen Konverter</h3>
                <p>Ein Python/Kivy-Tool zur Umwandlung von arabischen in römische Zahlen und umgekehrt.</p>
                <p><strong>Hinweis:</strong> Dies ist ein Desktop-Tool und kann nicht direkt im Browser ausgeführt werden.
                Der Quellcode ist auf GitHub verfügbar.</p>
                <a href="https://github.com/dein-benutzername/roemische-zahlen-konverter" class="btn" target="_blank">Quellcode ansehen</a>
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Graphen & Netzwerke</h2>
        <div class="cards">
            <!-- Neo4j-Visualisierungen -->
            <div class="card">
                <h3>Neo4j-Graphen</h3>
                <p>Visualisierungen von Graphennetzwerken mit Neo4j.</p>
                <p><strong>Hinweis:</strong> Die Visualisierungen erfordern eine lokale Neo4j-Instanz.
                Hier ist ein Beispiel-Screenshot:</p>
                <img src="assets/images/neo4j_beispiel.png" alt="Neo4j-Graphen-Beispiel" style="max-width: 100%; margin-top: 1rem;">
                <a href="https://neo4j.com/" class="btn" target="_blank">Mehr über Neo4j</a>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>