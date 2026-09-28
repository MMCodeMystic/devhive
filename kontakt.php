<?php
$title = "Kontakt | DevHive";
include 'header.php';

// Datenbankverbindung (XAMPP Standard)
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'devhive'; // Anpassen, falls deine Datenbank anders heißt

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}

// Variablen initialisieren
$error = '';
$success = '';
$prüfungsnachricht = false;
$prüfungsstatus = 'bot'; // Standardauswahl

// Formular wurde abgesendet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Eingaben bereinigen
    $name = trim($_POST['name'] ?? '');
    $kategorie = trim($_POST['kategorie'] ?? '');
    $nachricht = trim($_POST['nachricht'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $kontaktart = $_POST['kontaktart'] ?? null;
    $ip_adresse = $_SERVER['REMOTE_ADDR']; // IP-Adresse des Absenders

    // Validierung
    if (empty($name) || empty($kategorie) || empty($nachricht)) {
        $error = "Bitte fülle alle Pflichtfelder aus.";
    } else {
        // Prüfe, ob der Nutzer bereits eine ungelesene Nachricht hat
        $stmt = $pdo->prepare("
            SELECT COUNT(*) AS count
            FROM kontaktanfragen
            WHERE (name = ? OR ip_adresse = ? OR telefon = ?)
            AND prüfungsstatus = 'bot'
        ");
        $stmt->execute([$name, $ip_adresse, $telefon]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            // Nutzer hat bereits eine ungelesene Nachricht → Prüfungsbenachrichtigung anzeigen
            $prüfungsnachricht = true;
        } else {
            // Prüfe, ob bei Kontaktaufnahme/Auftragsanfrage E-Mail oder Telefon ausgefüllt ist
            if (($kategorie === 'kontaktaufnahme' || $kategorie === 'auftragsanfrage') && empty($email) && empty($telefon)) {
                $error = "Bitte gib mindestens eine E-Mail-Adresse oder Telefonnummer an.";
            } else {
                // Speichere die Anfrage in der Datenbank
                $stmt = $pdo->prepare("
                    INSERT INTO kontaktanfragen
                    (name, kategorie, nachricht, email, telefon, kontaktart, ip_adresse, prüfungsstatus)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $name,
                    $kategorie,
                    $nachricht,
                    $email,
                    $telefon,
                    $kontaktart,
                    $ip_adresse,
                    $prüfungsstatus
                ]);

                $success = "Deine Nachricht wurde erfolgreich gesendet!";
            }
        }
    }
}

// Prüfungsbenachrichtigung wurde abgesendet
if (isset($_POST['prüfungsstatus'])) {
    $prüfungsstatus = $_POST['prüfungsstatus'];
    // Aktualisiere den Status der letzten Anfrage dieses Nutzers
    $stmt = $pdo->prepare("
        UPDATE kontaktanfragen
        SET prüfungsstatus = ?
        WHERE (name = ? OR ip_adresse = ? OR telefon = ?)
        AND prüfungsstatus = 'bot'
        ORDER BY erstellt_am DESC
        LIMIT 1
    ");
    $stmt->execute([$prüfungsstatus, $name, $ip_adresse, $telefon]);
    $success = "Danke für deine Rückmeldung!";
    $prüfungsnachricht = false; // Verstecke das Popup nach der Auswahl
}
?>

<main>
    <section class="hero">
        <h1>Kontakt</h1>
        <p>Schreibe mir eine Nachricht – ich freue mich auf deine Anfrage!</p>
    </section>

    <div class="form-container">
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <?php if ($prüfungsnachricht): ?>
            <!-- Prüfungsbenachrichtigungs-Popup -->
            <div class="prüfungs-popup">
                <p>Du hast bereits eine ungelesene Nachricht gesendet. Bitte wähle eine Option:</p>
                <form method="post">
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <input type="hidden" name="kategorie" value="<?php echo htmlspecialchars($kategorie); ?>">
                    <input type="hidden" name="nachricht" value="<?php echo htmlspecialchars($nachricht); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <input type="hidden" name="telefon" value="<?php echo htmlspecialchars($telefon); ?>">
                    <input type="hidden" name="kontaktart" value="<?php echo htmlspecialchars($kontaktart); ?>">

                    <div class="radio-group">
                        <label>
                            <input type="radio" name="prüfungsstatus" value="keine_nachricht" required> Ich hatte keine Nachricht gesendet
                        </label>
                        <label>
                            <input type="radio" name="prüfungsstatus" value="erledigt" required> Nachricht hat sich erledigt
                        </label>
                        <label>
                            <input type="radio" name="prüfungsstatus" value="aktualisieren" required> Ich möchte meine Nachricht aktualisieren
                        </label>
                        <label>
                            <input type="radio" name="prüfungsstatus" value="bot" required checked> Ich bin ein Bot und versuche zu trollen
                        </label>
                    </div>
                    <button type="submit" class="submit-btn">Bestätigen</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Normales Kontaktformular -->
            <form class="kontakt" id="kontaktform" method="post">
                <div class="form-group">
                    <label for="name"><strong>Name:*</strong></label>
                    <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($name ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="kategorie"><strong>Kategorie:*</strong></label>
                    <select id="kategorie" name="kategorie" required>
                        <option value="sonstiges" <?php echo (isset($kategorie) && $kategorie === 'sonstiges') ? 'selected' : ''; ?>>Sonstiges</option>
                        <option value="kommentar" <?php echo (isset($kategorie) && $kategorie === 'kommentar') ? 'selected' : ''; ?>>Kommentar</option>
                        <option value="kontaktaufnahme" <?php echo (isset($kategorie) && $kategorie === 'kontaktaufnahme') ? 'selected' : ''; ?>>Kontaktaufnahme</option>
                        <option value="auftragsanfrage" <?php echo (isset($kategorie) && $kategorie === 'auftragsanfrage') ? 'selected' : ''; ?>>Auftragsanfrage</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nachricht"><strong>Nachricht:*</strong></label>
                    <textarea id="nachricht" name="nachricht" required placeholder="Deine Nachricht..." rows="1" style="resize: none; overflow: hidden;"><?php echo htmlspecialchars($nachricht ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="email"><strong>E-Mail:</strong></label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="telefon"><strong>Telefon:</strong></label>
                    <input type="tel" id="telefon" name="telefon" value="<?php echo htmlspecialchars($telefon ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label><strong>Gewünschte Kontaktaufnahme:*</strong></label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="kontaktart" value="email" <?php echo (isset($kontaktart) && $kontaktart === 'email') ? 'checked' : ''; ?>> E-Mail
                        </label>
                        <label>
                            <input type="radio" name="kontaktart" value="telefon" <?php echo (isset($kontaktart) && $kontaktart === 'telefon') ? 'checked' : ''; ?>> Telefon
                        </label>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Absenden</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include 'footer.php'; ?>
