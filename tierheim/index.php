<?php
// Tierheim/Pfotential - Hauptseite
$title = "Tierheim Pfotential | DevHive";

// Datenbankverbindung
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'devhive';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
}

// Tiertabelle erstellen, falls nicht vorhanden
try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tiere (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            art VARCHAR(50) NOT NULL,
            alter INT,
            geschlecht ENUM('maennlich', 'weiblich', 'unbekannt') DEFAULT 'unbekannt',
            beschreibung TEXT,
            aufnahme_datum DATE,
            status ENUM('verfuegbar', 'vermittelt', 'reserviert') DEFAULT 'verfuegbar',
            bild_url VARCHAR(255),
            erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Anfragentabelle erstellen
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tier_anfragen (
            id INT AUTO_INCREMENT PRIMARY KEY,
            tier_id INT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            telefon VARCHAR(20),
            nachricht TEXT,
            status ENUM('neu', 'bearbeitet', 'abgelehnt') DEFAULT 'neu',
            erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (tier_id) REFERENCES tiere(id)
        )
    ");
    
    // Benutzertabelle
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tierheim_benutzer (
            id INT AUTO_INCREMENT PRIMARY KEY,
            benutzername VARCHAR(50) NOT NULL UNIQUE,
            passwort_hash VARCHAR(255) NOT NULL,
            rolle ENUM('admin', 'mitarbeiter') DEFAULT 'mitarbeiter',
            email VARCHAR(100),
            erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
} catch (PDOException $e) {
    // Tabelle existiert bereits
}

// Testdaten einfuegen
try {
    $count = $pdo->query("SELECT COUNT(*) FROM tiere")->fetchColumn();
    if ($count == 0) {
        $testTiere = [
            ['Bello', 'Hund', 3, 'maennlich', 'Freundlicher Familienhund', '2023-01-15', 'verfuegbar'],
            ['Mieze', 'Katze', 2, 'weiblich', 'Verspielte Katze', '2023-02-20', 'verfuegbar'],
            ['Rex', 'Hund', 5, 'maennlich', 'Erfahrener Wachhund', '2023-03-10', 'vermittelt'],
            ['Luna', 'Katze', 1, 'weiblich', 'Suesse Kitten', '2023-04-05', 'verfuegbar'],
        ];
        
        $stmt = $pdo->prepare("INSERT INTO tiere (name, art, alter, geschlecht, beschreibung, aufnahme_datum, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($testTiere as $tier) {
            $stmt->execute($tier);
        }
    }
} catch (PDOException $e) {
    // Ignorieren
}

// Alle Tiere abrufen
$tiere = $pdo->query("SELECT * FROM tiere ORDER BY erstellt_am DESC")->fetchAll(PDO::FETCH_ASSOC);

// Formularverarbeitung
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['anfrage'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $nachricht = trim($_POST['nachricht'] ?? '');
    $tier_id = intval($_POST['tier_id'] ?? 0);
    
    if (empty($name) || empty($email) || empty($nachricht)) {
        $error = "Bitte fuellen Sie alle Pflichtfelder aus.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Bitte geben Sie eine gueltige E-Mail-Adresse ein.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tier_anfragen (tier_id, name, email, telefon, nachricht) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$tier_id, $name, $email, $telefon, $nachricht]);
            $success = "Ihre Anfrage wurde erfolgreich gesendet!";
        } catch (PDOException $e) {
            $error = "Fehler beim Speichern: " . $e->getMessage();
        }
    }
}

// Header einbinden
include '../header.php';
?>

<main>
    <section class="hero">
        <h1>Tierheim Pfotential</h1>
        <p>Finden Sie Ihr neues Haustier - Wir vermitteln Tiere in liebevolle Haende.</p>
    </section>

    <section class="section">
        <h2 class="section-title">Unsere Tiere</h2>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <div class="tiere-grid">
            <?php foreach ($tiere as $tier): ?>
            <div class="tier-card card">
                <h3><?php echo htmlspecialchars($tier['name']); ?></h3>
                <p><strong>Art:</strong> <?php echo htmlspecialchars($tier['art']); ?></p>
                <p><strong>Alter:</strong> <?php echo $tier['alter'] ?? 'Unbekannt'; ?> Jahre</p>
                <p><strong>Geschlecht:</strong> <?php echo htmlspecialchars($tier['geschlecht']); ?></p>
                <p><strong>Status:</strong> 
                    <span class="status-badge <?php echo $tier['status']; ?>">
                        <?php echo htmlspecialchars($tier['status']); ?>
                    </span>
                </p>
                <p><?php echo htmlspecialchars($tier['beschreibung'] ?? ''); ?></p>
                
                <?php if ($tier['status'] === 'verfuegbar'): ?>
                <button class="anfrage-btn" onclick="showAnfrageForm(<?php echo $tier['id']; ?>, '<?php echo htmlspecialchars(addslashes($tier['name'])); ?>')">
                    Anfrage stellen
                </button>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <h2 class="section-title">Ueber uns</h2>
        <div class="about-content card">
            <p>Das Tierheim Pfotential ist eine private Initiative, die sich der Vermittlung von Tieren in liebevolle Haende widmet.</p>
            <p>Wir arbeiten mit ehrenamtlichen Helfern und bieten den Tieren ein temporaeres Zuhause.</p>
        </div>
    </section>

    <p style="text-align: center; margin: 2rem 0;">
        <a href="login.php" class="btn">Zum Login / Admin-Bereich</a>
    </p>
</main>

<!-- Anfrage-Formular Popup -->
<div class="anfrage-popup" id="anfrage-popup">
    <div class="anfrage-container">
        <span class="close-btn" id="close-anfrage">&times;</span>
        <h2>Anfrage fuer <span id="tier-name"></span></h2>
        <form id="anfrage-form" method="post">
            <input type="hidden" name="anfrage" value="1">
            <input type="hidden" name="tier_id" id="tier-id" value="">
            
            <div class="form-group">
                <label for="anfrage-name"><strong>Ihr Name:*</strong></label>
                <input type="text" id="anfrage-name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="anfrage-email"><strong>E-Mail:*</strong></label>
                <input type="email" id="anfrage-email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="anfrage-telefon"><strong>Telefon:</strong></label>
                <input type="tel" id="anfrage-telefon" name="telefon" value="<?php echo htmlspecialchars($_POST['telefon'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="anfrage-nachricht"><strong>Nachricht:*</strong></label>
                <textarea id="anfrage-nachricht" name="nachricht" required rows="4"><?php echo htmlspecialchars($_POST['nachricht'] ?? ''); ?></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Anfrage absenden</button>
        </form>
    </div>
</div>

<style>
    /* Tierheim-spezifische Stile */
    .tiere-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        padding: 1rem;
        width: 100%;
    }
    
    .tier-card {
        background: var(--card-bg);
        padding: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid var(--accent-green);
        transition: transform 0.3s, box-shadow 0.3s;
        min-height: 250px;
    }
    
    .tier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-badge.verfuegbar {
        background: var(--accent-green);
        color: white;
    }
    
    .status-badge.vermittelt {
        background: var(--accent-blue);
        color: white;
    }
    
    .status-badge.reserviert {
        background: #8B4513;
        color: white;
    }
    
    .anfrage-btn {
        background: var(--accent-blue);
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 1rem;
        transition: background 0.3s;
        display: inline-block;
    }
    
    .anfrage-btn:hover {
        background: var(--accent-red);
    }
    
    .btn {
        display: inline-block;
        background: var(--accent-green);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
    }
    
    .btn:hover {
        background: var(--accent-blue);
    }
    
    /* Anfrage Popup */
    .anfrage-popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 3000;
        justify-content: center;
        align-items: center;
    }
    
    .anfrage-popup.show {
        display: flex;
    }
    
    .anfrage-container {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 8px;
        max-width: 500px;
        width: 90%;
        position: relative;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }
    
    .anfrage-container h2 {
        margin-bottom: 1.5rem;
        color: var(--accent-red);
    }
    
    .anfrage-container .form-group {
        margin-bottom: 1rem;
    }
    
    .anfrage-container label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text);
    }
    
    .anfrage-container input,
    .anfrage-container textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        background: var(--nav-bg);
        color: var(--text);
    }
    
    .anfrage-container textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .submit-btn {
        background: var(--accent-green);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 1rem;
        font-size: 1rem;
        transition: background 0.3s;
        width: 100%;
    }
    
    .submit-btn:hover {
        background: var(--accent-blue);
    }
    
    .close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text);
    }
    
    .about-content {
        width: 100%;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 4px;
        margin: 1rem auto;
        max-width: 800px;
        text-align: center;
    }
    
    .alert.error {
        background: #ffdddd;
        color: #cc0000;
        border: 1px solid #ff9999;
    }
    
    .alert.success {
        background: #ddffdd;
        color: #006600;
        border: 1px solid #99ff99;
    }
    
    /* Bright Mode */
    .bright-mode .tier-card {
        color: var(--text-dark);
    }
    
    .bright-mode .anfrage-container input,
    .bright-mode .anfrage-container textarea {
        background: var(--card-bg);
        border: 1px solid var(--accent-blue);
        color: var(--text-dark);
    }
    
    .bright-mode .alert.error {
        background: #ffe0e0;
        color: #990000;
        border: 1px solid #ffb3b3;
    }
    
    .bright-mode .alert.success {
        background: #e0ffe0;
        color: #004d00;
        border: 1px solid #b3ffb3;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .tiere-grid {
            grid-template-columns: 1fr;
        }
        
        .anfrage-container {
            width: 95%;
            padding: 1.5rem;
        }
        
        .hero {
            padding: 2rem 1rem;
        }
    }
</style>

<script>
    function showAnfrageForm(tierId, tierName) {
        document.getElementById('tier-id').value = tierId;
        document.getElementById('tier-name').textContent = tierName;
        document.getElementById('anfrage-popup').classList.add('show');
    }
    
    document.getElementById('close-anfrage').addEventListener('click', () => {
        document.getElementById('anfrage-popup').classList.remove('show');
    });
    
    document.getElementById('anfrage-popup').addEventListener('click', (e) => {
        if (e.target === document.getElementById('anfrage-popup')) {
            document.getElementById('anfrage-popup').classList.remove('show');
        }
    });
</script>

<?php include '../footer.php'; ?>
