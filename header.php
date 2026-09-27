<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'DevHive'; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/animations.css">
    <link rel="stylesheet" href="css/timelines.css">
    <link rel="stylesheet" href="fonts/Inter/Inter.css"> <!-- Falls du lokale Fonts nutzt -->
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <div class="logo">DevHive</div>
            <div class="wireframe-container">
                <div class="platonische-koerper"></div>
            </div>
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
                <li><a href="#projekte">Projekte</a></li>
                <li><a href="#einstellungen">Einstellungen</a></li>
            </ul>
        </nav>
    </header>