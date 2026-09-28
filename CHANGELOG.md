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




