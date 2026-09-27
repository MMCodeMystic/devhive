<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevHive | Portfolio & Lernreise</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=Roboto+Mono&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="logo">DevHive</div>
        <button class="burger-menu" aria-label="Menü öffnen">
            <svg class="hexagon" viewBox="0 0 100 100" width="30" height="30">
                <polygon points="50,10 90,25 90,75 50,90 10,75 10,25" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
        </button>
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="#timelines">Timelines</a></li>
                <li><a href="#projekte">Projekte</a></li>
                <li><a href="#einstellungen">Einstellungen</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Willkommen im DevHive</h1>
            <p>Eine Lernreise von Logikgattern bis zu modernen Webtechnologien.</p>
        </section>

        <section id="timelines" class="section">
            <h2>Timelines</h2>
            <div class="cards">
                <div class="card">
                    <h3>Technische Lernreise</h3>
                    <p>Meine 2-wöchige Reise durch HTML, CSS, JS, Git, PHP & SQL.</p>
                </div>
                <div class="card">
                    <h3>Historische IT-Entwicklung</h3>
                    <p>Von Philosophen über Hardware zu modernen Frameworks.</p>
                </div>
            </div>
        </section>

        <section id="projekte" class="section">
            <h2>Projekte</h2>
            <div class="cards">
                <div class="card">
                    <h3>Tierheim</h3>
                    <p>PHP-basierte Website (extern eingebunden).</p>
                </div>
                <div class="card">
                    <h3>Lernsoftware</h3>
                    <p>PHP-basierte Lernanwendung.</p>
                </div>
                <div class="card">
                    <h3>Sonstige Projekte</h3>
                    <p>Kleinere Experimente und Übungen.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="lang-switcher">
            <button class="lang-btn active" data-lang="de">DE</button>
            <button class="lang-btn" data-lang="en">EN</button>
        </div>
        <p>&copy; 2026 DevHive | M. Meier</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>