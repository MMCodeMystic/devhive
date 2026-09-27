<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'DevHive'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/timelines.css">
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

        <!-- Burger-Menü (Mobil & Desktop) -->
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
                <li><a href="#projekte">Projekte</a></li>
                <li><a href="#einstellungen">Einstellungen</a></li>
            </ul>
        </nav>
    </header>