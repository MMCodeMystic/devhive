<?php
$title = "Einstellungen | DevHive";
include 'header.php';
?>

<main>
    <section class="hero">
        <h1>Einstellungen</h1>
        <p>Passe das Erscheinungsbild und die Funktionen von DevHive an.</p>
    </section>

    <section class="section">
        <h2>Themen</h2>
        <div class="settings-container">
            <div class="setting-card">
                <h3>Dark Mode / Bright Mode</h3>
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
    </section>

    <section class="section">
        <h2>Sprache</h2>
        <div class="settings-container">
            <div class="setting-card">
                <div class="lang-switcher">
                    <button class="lang-btn active" data-lang="de">Deutsch</button>
                    <button class="lang-btn" data-lang="en">English</button>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>