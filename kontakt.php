<?php
$title = "Kontakt | DevHive";
include 'header.php';
?>
    <!-- Spezifische CSS für Kontaktformular -->
    <link rel="stylesheet" href="css/kontakt.css">
    <main>
        <section class="hero">
            <h1>Kontakt</h1>
            <p>Schreibe mir eine Nachricht – ich freue mich auf deine Anfrage!</p>
        </section>

        <div class="form-container">
            <form class="kontakt" id="kontaktform" action="kontakt.php" method="post">
                <!-- Name (required) -->
                <div class="form-group">
                    <label for="name"><strong>Name:*</strong></label>
                    <input type="text" id="name" name="name" required placeholder="Dein Name">
                </div>

                <!-- Kategorie (Dropdown, Standard: "Sonstiges") -->
                <div class="form-group">
                    <label for="kategorie"><strong>Kategorie:*</strong></label>
                    <select id="kategorie" name="kategorie" required>
                        <option value="sonstiges" selected>Sonstiges</option>
                        <option value="kommentar">Kommentar</option>
                        <option value="kontaktaufnahme">Kontaktaufnahme</option>
                        <option value="auftragsanfrage">Auftragsanfrage</option>
                    </select>
                </div>

                <!-- Freitext (required, autofit) -->
                <div class="form-group">
                    <label for="nachricht"><strong>Nachricht:*</strong></label>
                    <textarea id="nachricht" name="nachricht" required placeholder="Deine Nachricht..." rows="1" style="resize: none; overflow: hidden;"></textarea>
                </div>

                <!-- E-Mail (conditional required) -->
                <div class="form-group" id="email-group">
                    <label for="email"><strong>E-Mail:</strong></label>
                    <input type="email" id="email" name="email" placeholder="Deine E-Mail-Adresse">
                </div>

                <!-- Telefon (conditional required) -->
                <div class="form-group" id="telefon-group">
                    <label for="telefon"><strong>Telefon:</strong></label>
                    <input type="tel" id="telefon" name="telefon" placeholder="Deine Telefonnummer">
                </div>

                <!-- Gewünschte Kontaktaufnahme (Radiobuttons, conditional) -->
                <div class="form-group" id="kontaktart-group">
                    <label><strong>Gewünschte Kontaktaufnahme:*</strong></label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="kontaktart" value="email" disabled> E-Mail
                        </label>
                        <label>
                            <input type="radio" name="kontaktart" value="telefon" disabled> Telefon
                        </label>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Absenden</button>
            </form>
        </div>
    </main>

<?php include 'footer.php'; ?>