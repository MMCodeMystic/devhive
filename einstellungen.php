<?php
$title = "Einstellungen | DevHive";
include 'header.php';
?>

    <main>
        <section class="hero">
            <h1>Einstellungen</h1>
            <p>Passe das Erscheinungsbild von DevHive an.</p>
        </section>

        <div class="settings-container">
            <div class="setting-card">
                <h3>Themen</h3>
                <div class="theme-toggle">
                    <button id="dark-mode-btn" class="theme-btn active">Dark Mode</button>
                    <button id="bright-mode-btn" class="theme-btn">Bright Mode</button>
                </div>
            </div>

            <div class="setting-card">
                <h3>Animationen</h3>
                <div class="animation-toggle">
                    <label>
                        <input type="checkbox" id="animation-toggle" checked>
                        Animationen aktivieren
                    </label>
                </div>
            </div>
        </div>
    </main>

<?php include 'footer.php'; ?>