## Changelog

### [1.0] - 27.09.2026
- Grundgerüst `index.php` mit Header, Navbar, Cards für Timelines/Projekte.
- Responsive Design (Burger-Menü für Mobil).
- CSS-Variablen für Dark Mode.

### [1.1] - 27.09.2026
- Hinzugefügt: `header.php`, `footer.php` mit Animationen (Platonische Körper, Muster).
- Neue Seiten: `timelines_technisch.php`, `timelines_historisch.php`.
- JSON-Daten in `data/lernreise.json` und `data/historisch.json`.

### [1.2] - 27.09.2026
- Burger-Menü mit Hexagon-Animation für Mobilansicht hinzugefügt.
- Responsive Navbar für Desktop/Tablet implementiert.
- Navigationselemente (Home, Timelines, Projekte, Einstellungen) verlinkt.

### [1.3] - 28.09.2026
- **Navbar**:
  - Sticky Navbar immer sichtbar (Desktop/Mobil).
- **Technische Lernreise**:
  - Filterfunktion für Kategorien hinzugefügt.
  - JSON-Daten um `kategorie`-Feld erweitert.
- **Easter Egg**:
  - Hexagon im Footer mit Animation (5x Klick → Easter Egg).
- **Hintergrund**:
  - Animierte Platonische Körper als Wireframes im Hintergrund.
- **Projektseite**:
  - Neue Seite `projekte.php` mit Einbettung von Tools.
- **Bilder**:
  - `bild_url`-Felder in JSON hinzugefügt.
  - Namensvorschläge für Bilder umgesetzt.

### [1.4] - 28.09.2026
- **Navigation**:
  - Projekte und Einstellungen in Navbar/Burger-Menü verlinkt.
- **Einstellungen-Seite**:
  - Neue Seite `einstellungen.php` hinzugefügt.
- **Hintergrund**:
  - Animierte Platonische Körper und Muster im Hintergrund.
- **JSON**:
  - Bilder in `lernreise.json` und `historisch.json` eingebunden.


### [1.5] - 28.09.2026
- **Backend**:
  - SQL-Tabelle `kontaktanfragen` für Kontaktformular erstellt.
  - Flooding-Schutz: Prüft, ob `name`, `ip_adresse` oder `telefon` bereits eine ungelesene Nachricht hat.
  - Prüfungsbenachrichtigung: Popup mit 4 Optionen (Standard: "Ich bin ein Bot").
- **Frontend**:
  - Kontaktformular mit Validierung (Pflichtfelder, E-Mail/Telefon bei bestimmten Kategorien).
  - Autofit-Textarea für Nachrichtenfeld.
  - Prüfungs-Popup-Styling.
- **Datenbank**:
  - MySQL-Tabelle mit Indizes für schnelle Abfragen.
- ## Changelog

### [WIP - 28.09.2026]
- **Frontend:**
  - **Kontaktformular:**
    - Vollständiges Design für Desktop und Mobile.
    - Validierung für Pflichtfelder (Name, Nachricht, Kategorie).
    - Autofit-Textarea für Nachrichtenfeld.
    - Testdaten-Vorausfüllung für schnelles Testen.
  - **Login-Popup:**
    - Hexagon-Button für Passwort-Toggle (Anzeige bei Klick).
    - Passwortstärke-Anzeige mit Entropie-Berechnung.
    - Sicherheitshinweise und Tipps für sichere Passwörter.
  - **UI/UX:**
    - Footer-Links für Datenschutz und Impressum.
    - Mobile-Optimierung für Login-Popup und Kontaktformular.

- **Backend (SQL):**
  - Datenbanktabellen für Benutzerrollen (`rollen`, `benutzer`, `login_versuche`, `sitzungen`).
  - Vorbereitung für Flooding-Schutz in Kontaktformular.

- **Known Issues (temporär):**
  - Timelines (technisch & historisch) werden aktuell nicht angezeigt (UI-Stabilisierung).
  - Cards auf der Homepage funktionieren nicht (Priorisierung von Kontaktformular/Login).
  - Filter-Buttons für Timelines reagieren nicht (wird in nächster Iteration behoben).

- **Nächste Schritte:**
  - Timelines und Cards wiederherstellen.
  - Filter-Logik für Timelines finalisieren.
  - Bild-Animationen für Timeline-Items implementieren.

## Changelog

### [1.5] - 28.09.2026
- **Frontend:**
  - **Login-Popup:**
    - Passwort-Toggle mit Hexagon-Button (bleibt nach Nutzung erhalten).
    - Passwortstärke-Anzeige mit Entropie-Berechnung.
  - **Kontaktformular:**
    - Vollständiges Design für Desktop und Mobile.
    - Testdaten-Vorausfüllung.
  - **Timelines:**
    - Technische und historische Timelines werden angezeigt.
    - Filter-Funktion für historische Timeline korrigiert.
  - **Cards:**
    - Modularisierung: `cards.css` und `cards.js` erstellt.
    - Klick-Logik für Cards auf der Startseite.
  - **UI/UX:**
    - Footer-Links für Datenschutz und Impressum.
    - Mobile-Optimierung für alle Komponenten.

- **Backend (SQL):**
  - Datenbanktabellen für Benutzerrollen (`rollen`, `benutzer`, `login_versuche`, `sitzungen`).
  - Vorbereitung für Flooding-Schutz in Kontaktformular.

- **Code-Struktur:**
  - Modularisierung von CSS/JS (keine `style.css` oder `script.js` mehr).
  - Alle Stile in `base.css`, `layout.css`, `components.css`, etc.
  - Alle Skripte in `utils.js`, `main.js`, `timeline.js`, `login.js`, `cards.js`.






