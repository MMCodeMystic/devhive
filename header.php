<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'DevHive'; ?></title>
    <!-- Favicon für Dark Mode (Standard) -->
    <link id="favicon" rel="icon" href="assets/favicon/favicon-dark.ico" type="image/x-icon">
    <!-- CSS-Dateien -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/themes.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/timelines.css">
    <link rel="stylesheet" href="css/kontakt.css">
    <link rel="stylesheet" href="css/cards.css">
    <!-- Skripte einbinden -->
    <script src="js/utils.js"></script>
    <script src="js/main.js"></script>
    <script src="js/timeline.js"></script>
    <script src="js/login.js"></script>

</head>
<body>
    <header class="header">
        <!-- Hexagon-Animation (Anfangsanimation) -->
        <div class="hexagon-animation">
            <svg class="hexagon" viewBox="0 0 100 100" width="100" height="100">
                <polygon points="50,10 90,25 90,75 50,90 10,75 10,25" fill="none" stroke="var(--accent-blue)" stroke-width="2"/>
            </svg>
        </div>

        <div class="logo-container">
            <div class="logo">DevHive</div>
        </div>

        <!-- Burger-Menü (nur Mobil) -->
        <button class="burger-menu" aria-label="Menü öffnen" aria-expanded="false">
            <svg class="hexagon" viewBox="0 0 100 100" width="30" height="30">
                <polygon points="50,10 90,25 90,75 50,90 10,75 10,25" fill="none" stroke="currentColor" stroke-width="2"/>
            </svg>
        </button>

        <!-- Navbar (Desktop/Tablet) -->
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="timelines_technisch.php">Technische Lernreise</a></li>
                <li><a href="timelines_historisch.php">Historische IT</a></li>
                <li><a href="projekte.php">Projekte</a></li>
                <li><a href="einstellungen.php">Einstellungen</a></li>
            </ul>
        </nav>
        <!-- Animierte Platonische Körper im Hintergrund -->
        <div class="platonische-koerper-hintergrund">
            <div class="koerper tetraeder"></div>
            <div class="koerper wuerfel"></div>
            <div class="koerper oktaeder"></div>
            <div class="koerper dodekaeder"></div>
            <div class="koerper ikosaeder"></div>
        </div>

        <!-- Login-Button (in Navbar) -->
        <li class="nav-item">
            <button id="login-btn" class="login-btn">Login</button>
        </li>

        <!-- Login-Popup -->
        <div class="login-popup" id="login-popup">
            <div class="login-container">
                <span class="close-btn" id="close-login">&times;</span>
                <h2>Login</h2>
                <form id="login-form" method="post" action="login.php">
                    <div class="form-group">
                        <label for="username">Benutzername:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Passwort:</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" required>
                            <button type="button" class="password-toggle" id="password-toggle">
                                <svg class="hexagon" viewBox="0 0 100 100" width="20" height="20">
                                    <polygon points="50,10 90,25 90,75 50,90 10,75 10,25" fill="none" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" id="strength-bar"></div>
                            <div class="strength-text" id="strength-text">Passwortstärke: Schwach</div>
                        </div>
                        <div class="password-tip">
                            <strong>Tipps für sichere Passwörter:</strong>
                            <ul>
                                <li>Mindestens 12 Zeichen</li>
                                <li>Groß- und Kleinbuchstaben</li>
                                <li>Zahlen und Sonderzeichen</li>
                                <li>Keine persönlichen Daten</li>
                            </ul>
                            <div class="entropy-example">
                                <strong>Beispiel:</strong>
                                <p>Ein Passwort wie <code>Tr0ub4dour&3</code> hat eine Entropie von ~60 Bit.</p>
                                <p>Mit einem Quantencomputer: ~1 Jahr zum Knacken.</p>
                                <p>Ein Passwort wie <code>aBc123!@#</code> hat eine Entropie von ~30 Bit.</p>
                                <p>Mit einem Quantencomputer: ~1 Sekunde zum Knacken.</p>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Einloggen</button>
                </form>
                <div class="security-info">
                    <strong>Sicherheitsmaßnahmen:</strong>
                    <ul>
                        <li>BCrypt-Passwort-Hashing (Salt + Pepper)</li>
                        <li>HTTPS-Verschlüsselung (TLS 1.3)</li>
                        <li>Rate-Limiting (5 Versuche pro Minute)</li>
                        <li>CSRF-Schutz</li>
                        <li>SQL-Injection-Schutz (Prepared Statements)</li>
                    </ul>
                </div>
            </div>
        </div>

    </header>

