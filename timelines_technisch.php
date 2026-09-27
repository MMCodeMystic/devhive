<?php
$title = "Technische Lernreise | DevHive";
include 'header.php';
?>

<main>
    <section class="timeline-section">
        <h1>Technische Lernreise</h1>
        <p>Meine 2-wöchige Reise durch die Grundlagen der Webentwicklung.</p>

	<!-- Filter für Kategorien -->
        <div class="timeline-filters">
            <button class="filter-btn active" data-filter="alle">Alle</button>
            <button class="filter-btn" data-filter="html">HTML</button>
            <button class="filter-btn" data-filter="css">CSS</button>
            <button class="filter-btn" data-filter="javascript">JavaScript</button>
            <button class="filter-btn" data-filter="php">PHP</button>
            <button class="filter-btn" data-filter="sql">SQL</button>
            <button class="filter-btn" data-filter="github">GitHub</button>
        </div>

        <div class="timeline" id="lernreise-timeline">
            <!-- Daten werden per JavaScript geladen -->
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>