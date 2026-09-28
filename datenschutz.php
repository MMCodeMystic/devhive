<?php
$title = "Datenschutzerklärung | DevHive";
include 'header.php';
?>

    <main>
        <section class="hero">
            <h1>Datenschutzerklärung</h1>
            <p>Hier erfährst du, wie wir mit deinen Daten umgehen – oder auch nicht.</p>
        </section>

        <div class="content-container">
            <section>
                <h2>1. Verantwortlicher</h2>
                <p>
                    Verantwortlich für die Datenverarbeitung ist:<br>
                    <strong>M. Meier</strong><br>
                    DevHive<br>
                    E-Mail: kontakt@devhive.example
                </p>
            </section>

            <section>
                <h2>2. Welche Daten wir sammeln</h2>
                <p>
                    Wir sammeln nur Daten, die du uns freiwillig gibst – z. B. wenn du das Kontaktformular nutzt.
                    <strong>Keine Cookies, keine Tracker, keine dunkle Magie.</strong>
                    (Okay, vielleicht ein bisschen Magie, aber nur die gute.)
                </p>
                <p>
                    Wenn du dich einloggst, speichern wir:
                </p>
                <ul>
                    <li>Deinen Benutzernamen (damit wir wissen, wer du bist).</li>
                    <li>Dein Passwort – <strong>aber nur als Hash</strong> (also nicht lesbar, selbst für uns).</li>
                    <li>Deine IP-Adresse (für Sicherheitszwecke, z. B. um Brute-Force-Angriffe zu blockieren).</li>
                    <li>Dein Browser-Fingerprint (nur, um dich wiederzuerkennen – keine Sorge, wir verkaufen ihn nicht an die NSA).</li>
                </ul>
            </section>

            <section>
                <h2>3. Wofür wir die Daten nutzen</h2>
                <p>
                    Deine Daten nutzen wir nur für:
                </p>
                <ul>
                    <li>Die Bereitstellung unserer Dienste (z. B. Login, Kontaktformular).</li>
                    <li>Die Sicherheit unserer Website (z. B. Schutz vor Spam und Angriffen).</li>
                    <li>Die Kommunikation mit dir (wenn du uns eine Nachricht schickst).</li>
                </ul>
                <p>
                    <strong>Wir verkaufen keine Daten.</strong>
                    (Wir sind keine Datenkraken – wir haben genug eigene Probleme.)
                </p>
            </section>

            <section>
                <h2>4. Wie lange wir Daten speichern</h2>
                <p>
                    Kontaktanfragen: <strong>30 Tage</strong> (danach löschen wir sie, es sei denn, du bist ein Kunde – dann speichern wir sie so lange, wie es gesetzlich vorgeschrieben ist).<br>
                    Login-Daten: <strong>Solange dein Account existiert</strong> (oder bis du uns bittest, sie zu löschen).<br>
                    IP-Adressen in den Logs: <strong>7 Tage</strong> (danach werden sie automatisch gelöscht).
                </p>
            </section>

            <section>
                <h2>5. Deine Rechte</h2>
                <p>
                    Du hast das Recht auf:
                </p>
                <ul>
                    <li><strong>Auskunft:</strong> Welche Daten wir über dich gespeichert haben.</li>
                    <li><strong>Berichtigung:</strong> Falls wir falsche Daten haben, korrigieren wir sie.</li>
                    <li><strong>Löschung:</strong> Du kannst uns bitten, deine Daten zu löschen (außer wir müssen sie aus gesetzlichen Gründen behalten).</li>
                    <li><strong>Einschränkung:</strong> Du kannst die Verarbeitung deiner Daten einschränken.</li>
                    <li><strong>Widerspruch:</strong> Du kannst der Verarbeitung deiner Daten widersprechen.</li>
                    <li><strong>Datenübertragbarkeit:</strong> Du kannst deine Daten in einem gängigen Format erhalten.</li>
                </ul>
                <p>
                    Um diese Rechte geltend zu machen, schreibe uns einfach eine E-Mail an <strong>datenschutz@devhive.example</strong>.
                    (Wir antworten meistens innerhalb von 24 Stunden – außer wir sind im Urlaub oder haben Kaffee getrunken.)
                </p>
            </section>

            <section>
                <h2>6. Sicherheit</h2>
                <p>
                    Wir tun unser Bestes, um deine Daten zu schützen:
                </p>
                <ul>
                    <li><strong>Verschlüsselung:</strong> Alle Daten werden über HTTPS übertragen (TLS 1.3).</li>
                    <li><strong>Passwörter:</strong> Werden mit BCrypt gehasht (mit Salt und Pepper – nein, nicht das zum Essen).</li>
                    <li><strong>Zugangskontrolle:</strong> Nur Admins haben Zugriff auf die Datenbank.</li>
                    <li><strong>Backups:</strong> Werden verschlüsselt gespeichert (und nicht auf einem USB-Stick im Kühlschrank).</li>
                </ul>
                <p>
                    <strong>Aber:</strong> Kein System ist 100% sicher. Wenn jemand deine Daten stiehlt, geben wir ihm nicht die Schuld – aber wir tun alles, um es zu verhindern.
                    (Und falls doch: Wir haben eine Cyber-Versicherung. Hoffnung stirbt zuletzt.)
                </p>
            </section>

            <section>
                <h2>7. Änderungen dieser Datenschutzerklärung</h2>
                <p>
                    Wir behalten uns vor, diese Datenschutzerklärung anzupassen, wenn sich die Gesetze ändern oder wir neue Dienste anbieten.
                    (Aber keine Sorge – wir werden dich nicht heimlich ausspionieren.)
                </p>
                <p>
                    <strong>Letzte Aktualisierung:</strong> <?php echo date('d.m.Y'); ?>
                </p>
            </section>
        </div>
    </main>

<?php include 'footer.php'; ?>