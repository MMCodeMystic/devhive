<?php
// Tierheim Admin-Seite
session_start();

$title = "Admin | Tierheim Pfotential";

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

// Prüfen, ob Benutzer eingeloggt ist
if (!isset($_SESSION['tierheim_logged_in']) || !$_SESSION['tierheim_logged_in']) {
    header('Location: login.php');
    exit;
}

// Logout-Logik
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

// Tiere verwalten
$error = '';
$success = '';

// Neues Tier hinzufügen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tier'])) {
    $name = trim($_POST['name'] ?? '');
    $art = trim($_POST['art'] ?? '');
    $alter = intval($_POST['alter'] ?? 0);
    $geschlecht = $_POST['geschlecht'] ?? 'unbekannt';
    $beschreibung = trim($_POST['beschreibung'] ?? '');
    $status = $_POST['status'] ?? 'verfügbar';
    
    if (empty($name) || empty($art)) {
        $error = "Bitte füllen Sie mindestens Name und Art aus.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tiere (name, art, alter, geschlecht, beschreibung, status, aufnahme_datum) VALUES (?, ?, ?, ?, ?, ?, CURDATE())");
            $stmt->execute([$name, $art, $alter, $geschlecht, $beschreibung, $status]);
            $success = "Tier erfolgreich hinzugefügt!";
        } catch (PDOException $e) {
            $error = "Fehler beim Hinzufügen: " . $e->getMessage();
        }
    }
}

// Tier bearbeiten
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_tier'])) {
    $id = intval($_POST['tier_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $art = trim($_POST['art'] ?? '');
    $alter = intval($_POST['alter'] ?? 0);
    $geschlecht = $_POST['geschlecht'] ?? 'unbekannt';
    $beschreibung = trim($_POST['beschreibung'] ?? '');
    $status = $_POST['status'] ?? 'verfügbar';
    
    if (empty($name) || empty($art)) {
        $error = "Bitte füllen Sie mindestens Name und Art aus.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE tiere SET name = ?, art = ?, alter = ?, geschlecht = ?, beschreibung = ?, status = ? WHERE id = ?");
            $stmt->execute([$name, $art, $alter, $geschlecht, $beschreibung, $status, $id]);
            $success = "Tier erfolgreich aktualisiert!";
        } catch (PDOException $e) {
            $error = "Fehler beim Aktualisieren: " . $e->getMessage();
        }
    }
}

// Tier löschen
if (isset($_GET['delete_tier'])) {
    $id = intval($_GET['delete_tier'] ?? 0);
    try {
        $stmt = $pdo->prepare("DELETE FROM tiere WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Tier erfolgreich gelöscht!";
    } catch (PDOException $e) {
        $error = "Fehler beim Löschen: " . $e->getMessage();
    }
}

// Alle Tiere abrufen
$tiere = $pdo->query("SELECT * FROM tiere ORDER BY erstellt_am DESC")->fetchAll(PDO::FETCH_ASSOC);

// Alle Anfragen abrufen
$anfragen = $pdo->query("SELECT a.*, t.name as tier_name FROM tier_anfragen a LEFT JOIN tiere t ON a.tier_id = t.id ORDER BY a.erstellt_am DESC")->fetchAll(PDO::FETCH_ASSOC);

// Anfragen-Status aktualisieren
if (isset($_GET['update_anfrage'])) {
    $anfrage_id = intval($_GET['update_anfrage'] ?? 0);
    $status = $_GET['status'] ?? 'neu';
    try {
        $stmt = $pdo->prepare("UPDATE tier_anfragen SET status = ? WHERE id = ?");
        $stmt->execute([$status, $anfrage_id]);
        $success = "Anfrage-Status aktualisiert!";
    } catch (PDOException $e) {
        $error = "Fehler beim Aktualisieren: " . $e->getMessage();
    }
}

// Header einbinden
ob_start();
include '../header.php';
$headerContent = ob_get_clean();

// Burger-Menü und Navbar aus Header entfernen für Admin-Seite
$headerContent = str_replace('<button class="burger-menu"', '<!-- ', $headerContent);
$headerContent = str_replace('</button>', ' -->', $headerContent);
$headerContent = str_replace('<nav class="navbar">', '<!-- nav ', $headerContent);
$headerContent = str_replace('</nav>', ' nav -->', $headerContent);
$headerContent = str_replace('<div class="nav-login">', '<!-- login ', $headerContent);
$headerContent = str_replace('</div>', ' login -->', $headerContent);

echo $headerContent;
?>

<main>
    <section class="hero">
        <h1>Tierheim Pfotential - Admin-Panel</h1>
        <p>Willkommen, <?php echo htmlspecialchars($_SESSION['tierheim_benutzername']); ?>! (Rolle: <?php echo htmlspecialchars($_SESSION['tierheim_rolle']); ?>)</p>
        <a href="?logout=1" class="logout-btn">Logout</a>
    </section>

    <section class="section">
        <div class="admin-tabs">
            <button class="tab-btn active" data-tab="tiere">Tiere verwalten</button>
            <button class="tab-btn" data-tab="anfragen">Anfragen (<?php echo count($anfragen); ?>)</button>
        </div>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Tiere verwalten -->
        <div class="admin-container" id="tiere-tab">
            <h2>Tiere verwalten</h2>
            
            <div class="add-tier-form">
                <h3>Neues Tier hinzufügen</h3>
                <form method="post">
                    <input type="hidden" name="add_tier" value="1">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name"><strong>Name:*</strong></label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="art"><strong>Art:*</strong></label>
                            <input type="text" id="art" name="art" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="alter"><strong>Alter (Jahre):</strong></label>
                            <input type="number" id="alter" name="alter" min="0" max="50">
                        </div>
                        <div class="form-group">
                            <label for="geschlecht"><strong>Geschlecht:</strong></label>
                            <select id="geschlecht" name="geschlecht">
                                <option value="männlich">Männlich</option>
                                <option value="weiblich">Weiblich</option>
                                <option value="unbekannt" selected>Unbekannt</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="beschreibung"><strong>Beschreibung:</strong></label>
                        <textarea id="beschreibung" name="beschreibung" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="status"><strong>Status:</strong></label>
                        <select id="status" name="status">
                            <option value="verfügbar" selected>Verfügbar</option>
                            <option value="vermittelt">Vermittelt</option>
                            <option value="reserviert">Reserviert</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="submit-btn">Tier hinzufügen</button>
                </form>
            </div>

            <div class="tiere-list">
                <h3>Vorhandene Tiere (<?php echo count($tiere); ?>)</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Art</th>
                                <th>Alter</th>
                                <th>Geschlecht</th>
                                <th>Status</th>
                                <th>Aufnahme</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tiere as $tier): ?>
                            <tr>
                                <td><?php echo $tier['id']; ?></td>
                                <td><?php echo htmlspecialchars($tier['name']); ?></td>
                                <td><?php echo htmlspecialchars($tier['art']); ?></td>
                                <td><?php echo $tier['alter'] ?? '?'; ?></td>
                                <td><?php echo htmlspecialchars($tier['geschlecht']); ?></td>
                                <td><span class="status-badge <?php echo $tier['status']; ?>"><?php echo htmlspecialchars($tier['status']); ?></span></td>
                                <td><?php echo date('d.m.Y', strtotime($tier['aufnahme_datum'] ?? 'now')); ?></td>
                                <td>
                                    <a href="#" class="action-btn edit" onclick="editTier(<?php echo $tier['id']; ?>)">Bearbeiten</a>
                                    <a href="?delete_tier=<?php echo $tier['id']; ?>" class="action-btn delete" onclick="return confirm('Sind Sie sicher?')">Löschen</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Anfragen verwalten -->
        <div class="admin-container" id="anfragen-tab" style="display: none;">
            <h2>Anfragen verwalten</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tier</th>
                            <th>Name</th>
                            <th>E-Mail</th>
                            <th>Telefon</th>
                            <th>Nachricht</th>
                            <th>Status</th>
                            <th>Datum</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($anfragen as $anfrage): ?>
                        <tr>
                            <td><?php echo $anfrage['id']; ?></td>
                            <td><?php echo htmlspecialchars($anfrage['tier_name'] ?? 'Unbekannt'); ?></td>
                            <td><?php echo htmlspecialchars($anfrage['name']); ?></td>
                            <td><?php echo htmlspecialchars($anfrage['email']); ?></td>
                            <td><?php echo htmlspecialchars($anfrage['telefon'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars(substr($anfrage['nachricht'], 0, 50) . (strlen($anfrage['nachricht']) > 50 ? '...' : '')); ?></td>
                            <td><span class="status-badge <?php echo $anfrage['status']; ?>"><?php echo htmlspecialchars($anfrage['status']); ?></span></td>
                            <td><?php echo date('d.m.Y H:i', strtotime($anfrage['erstellt_am'])); ?></td>
                            <td>
                                <a href="?update_anfrage=<?php echo $anfrage['id']; ?>&status=bearbeitet" class="action-btn bearbeitet">Bearbeitet</a>
                                <a href="?update_anfrage=<?php echo $anfrage['id']; ?>&status=abgelehnt" class="action-btn abgelehnt">Abgelehnt</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>

<!-- Bearbeiten-Popup -->
<div class="edit-popup" id="edit-popup">
    <div class="edit-container">
        <span class="close-btn" id="close-edit">&times;</span>
        <h2>Tier bearbeiten</h2>
        <form method="post" id="edit-form">
            <input type="hidden" name="edit_tier" value="1">
            <input type="hidden" name="tier_id" id="edit-tier-id" value="">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit-name"><strong>Name:*</strong></label>
                    <input type="text" id="edit-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="edit-art"><strong>Art:*</strong></label>
                    <input type="text" id="edit-art" name="art" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="edit-alter"><strong>Alter (Jahre):</strong></label>
                    <input type="number" id="edit-alter" name="alter" min="0" max="50">
                </div>
                <div class="form-group">
                    <label for="edit-geschlecht"><strong>Geschlecht:</strong></label>
                    <select id="edit-geschlecht" name="geschlecht">
                        <option value="männlich">Männlich</option>
                        <option value="weiblich">Weiblich</option>
                        <option value="unbekannt">Unbekannt</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label for="edit-beschreibung"><strong>Beschreibung:</strong></label>
                <textarea id="edit-beschreibung" name="beschreibung" rows="3"></textarea>
            </div>
            
            <div class="form-group">
                <label for="edit-status"><strong>Status:</strong></label>
                <select id="edit-status" name="status">
                    <option value="verfügbar">Verfügbar</option>
                    <option value="vermittelt">Vermittelt</option>
                    <option value="reserviert">Reserviert</option>
                </select>
            </div>
            
            <button type="submit" class="submit-btn">Tier aktualisieren</button>
        </form>
    </div>
</div>

<style>
    .logout-btn {
        display: inline-block;
        background: var(--accent-red);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        margin-left: 1rem;
        transition: background 0.3s;
    }
    
    .logout-btn:hover {
        background: var(--accent-blue);
    }
    
    .admin-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin: 2rem 0;
    }
    
    .tab-btn {
        background: var(--card-bg);
        color: var(--text);
        border: 1px solid var(--accent-blue);
        padding: 0.75rem 2rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: background 0.3s, color 0.3s;
    }
    
    .tab-btn:hover {
        background: rgba(26, 58, 92, 0.3);
    }
    
    .tab-btn.active {
        background: var(--accent-blue);
        color: white;
        border-color: var(--accent-blue);
    }
    
    .admin-container {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 8px;
        border-left: 4px solid var(--accent-blue);
        margin: 1rem 0;
    }
    
    .admin-container h2 {
        color: var(--accent-red);
        margin-bottom: 1.5rem;
    }
    
    .add-tier-form {
        background: rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    
    .add-tier-form h3 {
        color: var(--accent-green);
        margin-bottom: 1rem;
    }
    
    .form-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .form-group {
        flex: 1;
        min-width: 200px;
        margin-bottom: 1rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text);
        font-weight: 600;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        background: var(--nav-bg);
        color: var(--text);
        font-size: 1rem;
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }
    
    .submit-btn {
        background: var(--accent-green);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: background 0.3s;
        margin-top: 1rem;
    }
    
    .submit-btn:hover {
        background: var(--accent-blue);
    }
    
    .table-container {
        overflow-x: auto;
        margin-top: 1rem;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        background: var(--nav-bg);
        border-radius: 8px;
        overflow: hidden;
    }
    
    th, td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    th {
        background: var(--accent-blue);
        color: white;
        font-weight: 600;
    }
    
    tr:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-badge.verfügbar {
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
    
    .status-badge.neu {
        background: #666;
        color: white;
    }
    
    .status-badge.bearbeitet {
        background: var(--accent-green);
        color: white;
    }
    
    .status-badge.abgelehnt {
        background: var(--accent-red);
        color: white;
    }
    
    .action-btn {
        display: inline-block;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.85rem;
        margin: 0 0.2rem;
        transition: background 0.3s;
    }
    
    .action-btn.edit {
        background: var(--accent-blue);
        color: white;
    }
    
    .action-btn.delete {
        background: var(--accent-red);
        color: white;
    }
    
    .action-btn.bearbeitet {
        background: var(--accent-green);
        color: white;
    }
    
    .action-btn.abgelehnt {
        background: #8B4513;
        color: white;
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
    
    /* Bearbeiten-Popup */
    .edit-popup {
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
    
    .edit-popup.show {
        display: flex;
    }
    
    .edit-container {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 8px;
        max-width: 600px;
        width: 90%;
        position: relative;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }
    
    .edit-container h2 {
        margin-bottom: 1.5rem;
        color: var(--accent-red);
    }
    
    .close-btn {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text);
    }
    
    @media (max-width: 768px) {
        .admin-container {
            padding: 1rem;
        }
        
        .form-row {
            flex-direction: column;
        }
        
        .form-group {
            min-width: 100%;
        }
        
        .edit-container {
            width: 95%;
            padding: 1rem;
        }
        
        .admin-tabs {
            margin: 1rem;
            flex-wrap: wrap;
        }
        
        .tab-btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
    
    /* Bright Mode Anpassungen */
    .bright-mode .admin-container {
        color: var(--text-dark);
    }
    
    .bright-mode .form-group input,
    .bright-mode .form-group select,
    .bright-mode .form-group textarea {
        background: var(--card-bg);
        border: 1px solid var(--accent-blue);
        color: var(--text-dark);
    }
    
    .bright-mode .form-group label {
        color: var(--text-dark);
    }
    
    .bright-mode table {
        background: var(--card-bg);
    }
    
    .bright-mode th {
        background: var(--accent-blue);
        color: white;
    }
    
    .bright-mode th,
    .bright-mode td {
        border-bottom: 1px solid var(--accent-blue);
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
    
    .bright-mode .edit-container {
        color: var(--text-dark);
    }
    
    .bright-mode .edit-container input,
    .bright-mode .edit-container select,
    .bright-mode .edit-container textarea {
        background: var(--card-bg);
        border: 1px solid var(--accent-blue);
        color: var(--text-dark);
    }
</style>

<script>
    // Tab-Wechsel
    document.querySelectorAll('.admin-tabs .tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.admin-tabs .tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            document.querySelectorAll('.admin-container').forEach(container => {
                container.style.display = 'none';
            });
            
            const tabId = btn.getAttribute('data-tab');
            document.getElementById(tabId + '-tab').style.display = 'block';
        });
    });
    
    // Bearbeiten-Popup
    function editTier(tierId) {
        fetch('<?php echo $_SERVER['PHP_SELF']; ?>?get_tier=' + tierId)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-tier-id').value = data.id;
                document.getElementById('edit-name').value = data.name;
                document.getElementById('edit-art').value = data.art;
                document.getElementById('edit-alter').value = data.alter || '';
                document.getElementById('edit-geschlecht').value = data.geschlecht || 'unbekannt';
                document.getElementById('edit-beschreibung').value = data.beschreibung || '';
                document.getElementById('edit-status').value = data.status || 'verfügbar';
                document.getElementById('edit-popup').classList.add('show');
            });
    }
    
    document.getElementById('close-edit').addEventListener('click', () => {
        document.getElementById('edit-popup').classList.remove('show');
    });
    
    document.getElementById('edit-popup').addEventListener('click', (e) => {
        if (e.target === document.getElementById('edit-popup')) {
            document.getElementById('edit-popup').classList.remove('show');
        }
    });
</script>

<?php include '../footer.php'; ?>
