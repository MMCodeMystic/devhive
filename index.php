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
                <!-- Tierheim Card - Externes Verzeichnis /xampp/htdocs/tierheim/ -->
                <div class="card project-card" data-src="../tierheim/index.php">
                    <h3>Tierheim Pfotential</h3>
                    <p>PHP-basierte Website für Tiervermittlung.</p>
                    <span class="click-hint">Klicken zum Öffnen</span>
                    <a href="../tierheim/index.php" class="external-link" target="_blank" rel="noopener noreferrer">In neuem Tab öffnen</a>
                </div>
                
                <!-- Video Card - EvoCore Demo -->
                <div class="card video-card">
                    <h3>EvoCore 0.0.9 Demo</h3>
                    <p>Video-Demonstration der Anwendung.</p>
                    <div class="video-container">
                        <video class="project-video" preload="metadata" muted loop>
                            <source src="assets/videos/evoCore0.0.9.mp4" type="video/mp4">
                            <source src="assets/videos/evoCore0.0.9.webm" type="video/webm">
                            Dein Browser unterstützt das Video-Element nicht.
                        </video>
                    </div>
                    <a href="assets/videos/evoCore0.0.9.mp4" class="external-link video-external-link" target="_blank" rel="noopener noreferrer">Video in neuem Tab öffnen</a>
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
