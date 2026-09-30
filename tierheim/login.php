<?php
// Tierheim Login-Seite
session_start();
$title = "Login | Tierheim Pfotential";

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

// Benutzertabelle erstellen
try {
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

// Testbenutzer erstellen
try {
    $count = $pdo->query("SELECT COUNT(*) FROM tierheim_benutzer WHERE rolle = 'admin'")->fetchColumn();
    if ($count == 0) {
        $passwortHash = password_hash('admin123', PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("INSERT INTO tierheim_benutzer (benutzername, passwort_hash, rolle, email) VALUES (?, ?, ?, ?)");
        $stmt->execute(['admin', $passwortHash, 'admin', 'admin@tierheim.de']);
    }
} catch (PDOException $e) {
    // Ignorieren
}

// Login-Logik
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $benutzername = trim($_POST['benutzername'] ?? '');
    $passwort = $_POST['passwort'] ?? '';
    
    if (empty($benutzername) || empty($passwort)) {
        $error = "Bitte geben Sie Benutzername und Passwort ein.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM tierheim_benutzer WHERE benutzername = ?");
            $stmt->execute([$benutzername]);
            $benutzer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($benutzer && password_verify($passwort, $benutzer['passwort_hash'])) {
                // Login erfolgreich
                $_SESSION['tierheim_benutzer_id'] = $benutzer['id'];
                $_SESSION['tierheim_benutzername'] = $benutzer['benutzername'];
                $_SESSION['tierheim_rolle'] = $benutzer['rolle'];
                $_SESSION['tierheim_logged_in'] = true;
                
                header('Location: admin.php');
                exit;
            } else {
                $error = "Ungueltiger Benutzername oder Passwort.";
            }
        } catch (PDOException $e) {
            $error = "Fehler beim Login: " . $e->getMessage();
        }
    }
}

// Registrierungs-Logik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $benutzername = trim($_POST['benutzername'] ?? '');
    $passwort = $_POST['passwort'] ?? '';
    $passwort_wiederholen = $_POST['passwort_wiederholen'] ?? '';
    $email = trim($_POST['email'] ?? '');
    
    if (empty($benutzername) || empty($passwort) || empty($email)) {
        $error = "Bitte fuellen Sie alle Pflichtfelder aus.";
    } elseif ($passwort !== $passwort_wiederholen) {
        $error = "Die Passwoerter stimmen nicht ueberein.";
    } elseif (strlen($passwort) < 8) {
        $error = "Das Passwort muss mindestens 8 Zeichen lang sein.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Bitte geben Sie eine gueltige E-Mail-Adresse ein.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM tierheim_benutzer WHERE benutzername = ?");
            $stmt->execute([$benutzername]);
            if ($stmt->fetch()) {
                $error = "Der Benutzername ist bereits vergeben.";
            } else {
                $passwortHash = password_hash($passwort, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO tierheim_benutzer (benutzername, passwort_hash, rolle, email) VALUES (?, ?, 'mitarbeiter', ?)");
                $stmt->execute([$benutzername, $passwortHash, $email]);
                $success = "Registrierung erfolgreich! Sie koennen sich jetzt einloggen.";
            }
        } catch (PDOException $e) {
            $error = "Fehler bei der Registrierung: " . $e->getMessage();
        }
    }
}

// Header einbinden
include '../header.php';
?>

<main>
    <section class="hero">
        <h1>Tierheim Pfotential - Login</h1>
        <p>Bitte melden Sie sich an, um auf das Admin-Panel zuzugreifen.</p>
    </section>

    <section class="section">
        <div class="login-tabs">
            <button class="tab-btn active" data-tab="login">Login</button>
            <button class="tab-btn" data-tab="register">Registrieren</button>
        </div>

        <?php if ($error): ?>
            <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Login-Formular -->
        <div class="login-form-container card" id="login-form">
            <form method="post">
                <input type="hidden" name="login" value="1">
                
                <div class="form-group">
                    <label for="benutzername"><strong>Benutzername:</strong></label>
                    <input type="text" id="benutzername" name="benutzername" required value="<?php echo htmlspecialchars($_POST['benutzername'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="passwort"><strong>Passwort:</strong></label>
                    <input type="password" id="passwort" name="passwort" required>
                </div>
                
                <button type="submit" class="submit-btn">Einloggen</button>
            </form>
            <p style="margin-top: 1rem; text-align: center;">
                <strong>Testzugang:</strong><br>
                Benutzername: admin | Passwort: admin123
            </p>
        </div>

        <!-- Registrierungs-Formular -->
        <div class="login-form-container card" id="register-form" style="display: none;">
            <form method="post">
                <input type="hidden" name="register" value="1">
                
                <div class="form-group">
                    <label for="reg_benutzername"><strong>Benutzername:*</strong></label>
                    <input type="text" id="reg_benutzername" name="benutzername" required value="<?php echo htmlspecialchars($_POST['benutzername'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="reg_email"><strong>E-Mail:*</strong></label>
                    <input type="email" id="reg_email" name="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="reg_passwort"><strong>Passwort:*</strong></label>
                    <input type="password" id="reg_passwort" name="passwort" required>
                    <div class="password-hint">
                        <small>Mindestens 8 Zeichen empfohlen.</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="reg_passwort_wiederholen"><strong>Passwort wiederholen:*</strong></label>
                    <input type="password" id="reg_passwort_wiederholen" name="passwort_wiederholen" required>
                </div>
                
                <button type="submit" class="submit-btn">Registrieren</button>
            </form>
        </div>
        
        <p style="text-align: center; margin-top: 2rem;">
            <a href="../index.php" class="btn">Zurueck zur Hauptseite</a>
        </p>
    </section>
</main>

<style>
    .login-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin: 2rem 0;
        width: 100%;
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
    
    .login-form-container {
        background: var(--card-bg);
        padding: 2rem;
        border-radius: 8px;
        border-left: 4px solid var(--accent-blue);
        max-width: 500px;
        margin: 0 auto 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--text);
        font-weight: 600;
    }
    
    .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        background: var(--nav-bg);
        color: var(--text);
        font-size: 1rem;
    }
    
    .password-hint {
        margin-top: 0.5rem;
        color: var(--text);
        font-size: 0.85rem;
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
        width: 100%;
    }
    
    .submit-btn:hover {
        background: var(--accent-blue);
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
    .bright-mode .login-form-container {
        color: var(--text-dark);
    }
    
    .bright-mode .form-group input {
        background: var(--card-bg);
        border: 1px solid var(--accent-blue);
        color: var(--text-dark);
    }
    
    .bright-mode .form-group label {
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
        .login-form-container {
            padding: 1.5rem;
            margin: 0 1rem 1rem;
        }
        
        .login-tabs {
            margin: 1rem;
        }
        
        .tab-btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
    }
</style>

<script>
    // Tab-Wechsel
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            document.querySelectorAll('.login-form-container').forEach(container => {
                container.style.display = 'none';
            });
            
            const tabId = btn.getAttribute('data-tab');
            document.getElementById(tabId + '-form').style.display = 'block';
        });
    });
</script>

<?php include '../footer.php'; ?>
