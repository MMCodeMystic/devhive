-- Tabelle für Rollen
CREATE TABLE IF NOT EXISTS rollen (
                                      id INT AUTO_INCREMENT PRIMARY KEY,
                                      name VARCHAR(20) NOT NULL UNIQUE,
    beschreibung TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Standard-Rollen einfügen
INSERT INTO rollen (name, beschreibung) VALUES
                                            ('admin', 'Vollzugriff auf alle Funktionen'),
                                            ('besucher', 'Eingeschränkter Zugriff, z. B. auf Inhalte'),
                                            ('gast', 'Nur Lesezugriff');

-- Tabelle für Benutzer
CREATE TABLE IF NOT EXISTS benutzers (
                                         id INT AUTO_INCREMENT PRIMARY KEY,
                                         username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,  -- BCrypt-Hash
    email VARCHAR(255) NOT NULL UNIQUE,
    rolle_id INT NOT NULL,
    letzer_login TIMESTAMP NULL,
    erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rolle_id) REFERENCES rollen(id) ON DELETE CASCADE,
    INDEX (username),
    INDEX (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabelle für Login-Versuche (Sicherheit)
CREATE TABLE IF NOT EXISTS login_versuche (
                                              id INT AUTO_INCREMENT PRIMARY KEY,
                                              benutzer_id INT NULL,
                                              ip_adresse VARCHAR(45) NOT NULL,
    erfolgreich BOOLEAN NOT NULL DEFAULT FALSE,
    versuch_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (benutzer_id) REFERENCES benutzers(id) ON DELETE SET NULL,
    INDEX (ip_adresse),
    INDEX (versuch_am)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabelle für Sitzungen (korrigiert)
CREATE TABLE IF NOT EXISTS sitzungen (
                                         id INT AUTO_INCREMENT PRIMARY KEY,
                                         benutzer_id INT NOT NULL,
                                         session_id VARCHAR(255) NOT NULL UNIQUE,
    ip_adresse VARCHAR(45) NOT NULL,
    user_agent TEXT NOT NULL,
    erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ablauf_am TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 1 HOUR),  -- Standard: 1 Stunde Ablauf
    FOREIGN KEY (benutzer_id) REFERENCES benutzers(id) ON DELETE CASCADE,
    INDEX (session_id),
    INDEX (ablauf_am)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;