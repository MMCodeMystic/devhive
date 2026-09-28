-- Tabelle für Kontaktanfragen
CREATE TABLE IF NOT EXISTS kontaktanfragen (
                                               id INT AUTO_INCREMENT PRIMARY KEY,
                                               name VARCHAR(100) NOT NULL,
    kategorie ENUM('kommentar', 'kontaktaufnahme', 'auftragsanfrage', 'sonstiges') NOT NULL,
    nachricht TEXT NOT NULL,
    email VARCHAR(255),
    telefon VARCHAR(20),
    kontaktart ENUM('email', 'telefon') DEFAULT NULL,  -- NULL erlaubt
    ip_adresse VARCHAR(45) NOT NULL,  -- Für Flooding-Schutz
    prüfungsstatus ENUM('keine_nachricht', 'erledigt', 'aktualisieren', 'bot') DEFAULT 'bot',
    erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (name),  -- Schnellere Abfragen für Flooding-Schutz
    INDEX (ip_adresse),
    INDEX (prüfungsstatus)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;