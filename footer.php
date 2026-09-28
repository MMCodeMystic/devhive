<footer class="footer">
    <!-- Platonische Körper im Hintergrund (5 Körper) -->
    <div class="platonische-koerper-hintergrund">
        <div class="koerper tetraeder"></div>
        <div class="koerper wuerfel"></div>
        <div class="koerper oktaeder"></div>
        <div class="koerper dodekaeder"></div>
        <div class="koerper ikosaeder"></div>
    </div>



    <div class="footer-hexagon-container">
        <svg class="footer-hexagon" viewBox="0 0 100 100" width="50" height="50">
            <polygon points="50,10 90,25 90,75 50,90 10,75 10,25" fill="none" stroke="var(--accent-blue)" stroke-width="2"/>
        </svg>
    </div>
    <p>&copy; <?php echo date('Y'); ?> DevHive | M. Meier</p>
</footer>


<!-- CTA-Popup -->
<div class="cta-popup" id="cta-popup">
    <div class="cta-content">
        <p>Entdecke meine Lernreisen!</p>
        <div class="cta-buttons">
            <a href="timelines_technisch.php" class="cta-btn">Technische Lernreise</a>
            <a href="timelines_historisch.php" class="cta-btn">Historische IT</a>
        </div>
        <button class="cta-close" id="cta-close">&times;</button>
    </div>
</div>

<!-- JS-Importe (am Ende von <body>) -->
<script src="js/utils.js"></script>
<script src="js/main.js"></script>
<script src="js/timeline.js"></script>
<script src="js/login.js"></script>


</body>
</html>