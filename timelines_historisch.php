<?php
$title = "Historische IT-Entwicklung | DevHive";
include 'header.php';
?>

<main>
    <section class="timeline-section">
        <h1>Historische IT-Entwicklung</h1>
        <p>Von Philosophen über Hardware zu modernen Frameworks – eine Reise durch die Geschichte der Informatik.</p>

        <!-- Filter für Kategorien -->
        <div class="timeline-filters">
            <button class="filter-btn active" data-filter="alle">Alle</button>
            <button class="filter-btn" data-filter="theorie">Theorie</button>
            <button class="filter-btn" data-filter="hardware-planung">Hardware-Planung</button>
            <button class="filter-btn" data-filter="hardware-herstellung">Hardware-Herstellung</button>
        </div>

        <div class="timeline" id="historisch-timeline">
            <!-- Daten werden per JavaScript geladen -->
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>