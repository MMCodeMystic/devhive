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
                <div class="card" data-src="../tierheim/index.php">
                    <h3>Tierheim Pfotential</h3>
                    <p>PHP-basierte Website für Tiervermittlung.</p>
                    <span class="click-hint">Klicken zum Öffnen</span>
                    <a href="../tierheim/index.php" class="external-link" target="_blank" rel="noopener noreferrer">In neuem Tab öffnen</a>
                </div>
                
                <!-- EvoCore EXE Einbettung -->
                <div class="card exe-card" data-exe="evoCore0.0.9.exe">
                    <h3>EvoCore 0.0.9</h3>
                    <p>Lokale Anwendung (EXE-Datei).</p>
                    <span class="click-hint">Klicken zum Starten</span>
                    <a href="#" class="external-link exe-download" data-exe="evoCore0.0.9.exe" target="_blank">In neuem Tab öffnen</a>
                </div>
                
                <div class="card">
                    <h3>Sonstige Projekte</h3>
                    <p>Kleinere Experimente und Übungen.</p>
                    <a href="projekte.php" class="btn">Zu den Projekten</a>
                </div>
            </div>
        </section>
    </main>

    <style>
        /* EXE-Kachel spezifische Stile */
        .exe-card {
            cursor: pointer;
        }
        
        .external-link {
            display: none;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--accent-blue);
            text-decoration: none;
        }
        
        .card.loaded .external-link {
            display: block;
        }
        
        .card.loaded .click-hint {
            display: none;
        }
    </style>

    <script>
        // EXE-Download/Start für lokale Dateien
        document.addEventListener('DOMContentLoaded', () => {
            const exeCards = document.querySelectorAll('.card[data-exe]');
            exeCards.forEach(card => {
                card.addEventListener('click', () => {
                    if (card.classList.contains('loaded')) return;
                    
                    const exeName = card.dataset.exe;
                    const exePath = `assets/exe/${exeName}`;
                    
                    // Link zum direkten Download/Öffnen anzeigen
                    const link = card.querySelector('.exe-download');
                    if (link) {
                        link.href = exePath;
                        link.style.display = 'block';
                    }
                    
                    card.classList.add('loaded');
                });
            });
            
            // Externes Tierheim - Iframe Einbettung
            const tierheimCard = document.querySelector('.card[data-src="../tierheim/index.php"]');
            if (tierheimCard) {
                tierheimCard.addEventListener('click', () => {
                    if (tierheimCard.classList.contains('loaded')) return;
                    
                    const iframe = document.createElement('iframe');
                    iframe.src = '../tierheim/index.php';
                    iframe.style.width = '100%';
                    iframe.style.height = '250px';
                    iframe.style.border = 'none';
                    iframe.style.position = 'absolute';
                    iframe.style.top = '0';
                    iframe.style.left = '0';
                    iframe.style.background = 'var(--nav-bg)';
                    iframe.style.zIndex = '1';
                    iframe.style.borderRadius = '8px';
                    
                    tierheimCard.appendChild(iframe);
                    tierheimCard.classList.add('loaded');
                    
                    // Externen Link anzeigen
                    const externalLink = tierheimCard.querySelector('.external-link');
                    if (externalLink) {
                        externalLink.style.display = 'block';
                    }
                });
            }
        });
    </script>

    <?php include 'footer.php'; ?>
