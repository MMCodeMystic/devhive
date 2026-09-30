<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevHive | Portfolio & Lernreise</title>

</head>
<body>
    <?php
    $title = "DevHive | Portfolio & Lernreise";
    include 'header.php';
    ?>


    <main>
        <section class="hero">
            <h1>Willkommen im DevHive</h1>
            <p>Eine Lernreise von Logikgattern bis zu modernen Webtechnologien.</p>
        </section>

        <section id="timelines" class="section homepage">
            <h2 class="section-title">Timelines</h2>
            <div class="cards">
                <div class="card">
                    <h3>Technische Lernreise</h3>
                    <p>Meine 2-wöchige Reise durch HTML, CSS, JS, Git, PHP & SQL.</p>
                    <a href="timelines_technisch.php" class="btn">Zur technischen Lernreise</a>
                </div>
                <div class="card">
                    <h3>Historische IT-Entwicklung</h3>
                    <p>Von Philosophen über Hardware zu modernen Frameworks.</p>
                    <a href="timelines_historisch.php" class="btn">Zur historischen Timeline</a>
                </div>
            </div>
        </section>

        <section id="projekte" class="section homepage">
            <h2 class="section-title">Projekte</h2>
            <div class="cards">
                <div class="card" data-src="tierheim/index.php">
                    <h3>Tierheim</h3>
                    <p>PHP-basierte Website (extern eingebunden).</p>
                    <span class="click-hint">Klicken zum Öffnen</span>
                </div>
                <div class="card" data-src="lernapp/index.php">
                    <h3>Lernsoftware</h3>
                    <p>PHP-basierte Lernanwendung.</p>
                    <span class="click-hint">Klicken zum Öffnen</span>
                </div>
                <div class="card">
                    <h3>Sonstige Projekte</h3>
                    <p>Kleinere Experimente und Übungen.</p>
                    <a href="projekte.php" class="btn">Zu den Projekten</a>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
