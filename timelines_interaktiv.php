<?php
/**
 * lernreise.php – Interaktive Web-Entwicklungs-Lernreise
 *
 * Integration in bestehende Seite:
 *   include 'lernreise.php';
 *
 * Oder als eigene Seite aufrufen.
 *
 * WICHTIG: Kein BOM, kein Whitespace vor <?php
 */

// ═══════════════════════════════════════════════════════════════
// 1. BACKEND-ENDPOINT (POST)
// ═══════════════════════════════════════════════════════════════
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lr_action'])) {

    // ALLE Output-Buffer der Elternseite leeren (Fix für JSON-Fehler)
    while (ob_get_level()) {
        ob_end_clean();
    }

    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');

    $action = $_POST['lr_action'] ?? '';
    $code   = $_POST['lr_code'] ?? '';

    if ($action === 'run_php') {
        ob_start();
        try {
            // Gefährliche Funktionen blockieren
            $blocked = ['exec', 'system', 'passthru', 'shell_exec', 'proc_open'];
            foreach ($blocked as $fn) {
                $code = preg_replace('/\b' . $fn . '\s*\(/', '/* blocked */ (', $code);
            }
            eval($code);
        } catch (Throwable $e) {
            echo '<pre style="color:#e74c3c;font-family:monospace;font-size:13px">'
                . '⚠ ' . htmlspecialchars($e->getMessage()) . '</pre>';
        }
        $output = ob_get_clean();
        echo json_encode(['ok' => true, 'output' => $output ?: '<p style="color:#999">Keine Ausgabe.</p>']);
        exit;
    }

    if ($action === 'run_sql') {
        try {
            $db = new PDO('sqlite::memory:');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Setup: Tabellen + Daten für SQL-Steps
            $setup = "
                CREATE TABLE IF NOT EXISTS benutzer (
                    id INTEGER PRIMARY KEY, name TEXT, email TEXT, stadt TEXT
                );
                CREATE TABLE IF NOT EXISTS auftraege (
                    id INTEGER PRIMARY KEY, benutzer_id INTEGER, betrag REAL, status TEXT
                );
                CREATE TABLE IF NOT EXISTS produkte (
                    id INTEGER PRIMARY KEY, name TEXT, preis REAL, bestand INTEGER
                );
            ";
            $db->exec($setup);

            // Statements ausführen
            $statements = array_filter(array_map('trim', explode(';', $sql ?? $code)));
            $html = '';

            foreach ($statements as $sqlStmt) {
                if (empty($sqlStmt)) continue;
                try {
                    $pdoStmt = $db->query($sqlStmt);
                    if ($pdoStmt instanceof PDOStatement) {
                        $cols = $pdoStmt->getColumnNames();
                        if (count($cols) > 0) {
                            $html .= '<table style="border-collapse:collapse;width:100%;font-family:monospace;font-size:13px;margin:8px 0">';
                            $html .= '<tr>';
                            foreach ($cols as $c) {
                                $html .= '<th style="border:1px solid #ddd;padding:6px 10px;background:#f1f1f1;text-align:left">' . htmlspecialchars($c) . '</th>';
                            }
                            $html .= '</tr>';
                            while ($row = $pdoStmt->fetch(PDO::FETCH_ASSOC)) {
                                $html .= '<tr>';
                                foreach ($row as $v) {
                                    $html .= '<td style="border:1px solid #ddd;padding:6px 10px">' . htmlspecialchars($v ?? '') . '</td>';
                                }
                                $html .= '</tr>';
                            }
                            $html .= '</table>';
                        } else {
                            $html .= '<p style="color:#27ae60;font-size:13px">✓ ' . htmlspecialchars($sqlStmt) . '</p>';
                        }
                    }
                } catch (Throwable $e) {
                    $html .= '<p style="color:#e74c3c;font-size:13px">⚠ ' . htmlspecialchars($e->getMessage()) . '</p>';
                }
            }

            echo json_encode(['ok' => true, 'output' => $html ?: '<p style="color:#999">Keine Ausgabe.</p>']);
        } catch (Throwable $e) {
            echo json_encode(['ok' => false, 'output' => '<pre style="color:#e74c3c">' . htmlspecialchars($e->getMessage()) . '</pre>']);
        }
        exit;
    }

    echo json_encode(['ok' => false, 'output' => 'Unbekannte Aktion']);
    exit;
}

// ═══════════════════════════════════════════════════════════════
// 2. HELPER: Code in vollständiges HTML-Dokument einwrappen
// ═══════════════════════════════════════════════════════════════
function lr_wrap_html(string $code, string $type): string
{
    // Falls bereits vollständiges HTML-Dokument → unverändert
    if (stripos($code, '<!doctype') !== false || stripos($code, '<html') !== false) {
        return $code;
    }

    $head = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><style>';
    $head .= 'body{font-family:-apple-system,BlinkMacSystemFont,sans-serif;padding:16px;margin:0;color:#333}';
    $head .= 'table{border-collapse:collapse}td,th{border:1px solid #ccc;padding:4px 8px}';
    $head .= '</style></head><body>';
    $tail = '</body></html>';

    if ($type === 'js') {
        // JS-Code: direkt im Body ausführen
        return $head . '<script>' . $code . '</script>' . $tail;
    }

    return $head . $code . $tail;
}

// ═══════════════════════════════════════════════════════════════
// 3. STEP-DATEN
// ═══════════════════════════════════════════════════════════════
$steps = [

    // ─────────────── HTML (7 Steps) ───────────────
    [
        'section' => 'HTML',
        'title'   => 'Dokumentenstruktur',
        'desc'    => 'DOCTYPE, html, head und body.',
        'type'    => 'html',
        'code'    => '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Meine erste Seite</title>
</head>
<body>
    <p>Hallo Welt!</p>
</body>
</html>',
    ],
    [
        'section' => 'HTML',
        'title'   => 'Textstrukturierung',
        'desc'    => 'Überschriften (h1–h6) und Absätze (p).',
        'type'    => 'html',
        'code'    => '<h1>Mein Blog</h1>
<p>Willkommen auf meinem Blog.</p>

<h2>Erster Artikel</h2>
<p>Hier ist der Inhalt des ersten Artikels.</p>

<h3>Unterpunkt</h3>
<p>Ein weiterer Absatz mit <strong>fett</strong> und <em>kursiv</em>.</p>',
    ],
    [
        'section' => 'HTML',
        'title'   => 'Listen & Links',
        'desc'    => 'Geordnete/ungeordnete Listen und Hyperlinks.',
        'type'    => 'html',
        'code'    => '<h2>Einkaufsliste</h2>
<ul>
    <li>Äpfel</li>
    <li>Brot</li>
    <li>Milch</li>
</ul>

<h2>Rezept</h2>
<ol>
    <li>Zutaten mischen</li>
    <li>Backen</li>
    <li>Servieren</li>
</ol>

<a href="https://developer.mozilla.org">Zu MDN</a>',
    ],
    [
        'section' => 'HTML',
        'title'   => 'Semantik',
        'desc'    => 'header, nav, main, article, footer.',
        'type'    => 'html',
        'code'    => '<header style="background:#2c3e50;color:#fff;padding:16px">
    <h1 style="margin:0">Meine Website</h1>
    <nav>
        <a href="#" style="color:#eee;margin-right:12px">Start</a>
        <a href="#" style="color:#eee">Über</a>
    </nav>
</header>
<main style="padding:16px">
    <article>
        <h2>Artikel</h2>
        <p>Inhalt hier.</p>
    </article>
</main>
<footer style="background:#ecf0f1;padding:12px;text-align:center">
    <p>&copy; 2025</p>
</footer>',
    ],
    [
        'section' => 'HTML',
        'title'   => 'Medien',
        'desc'    => 'Bilder mit src, alt und dimensionsangaben.',
        'type'    => 'html',
        'code'    => '<img src="https://picsum.photos/300/150" alt="Platzhalterbild"
     width="300" style="border-radius:8px">
<p>Bild mit alt-Text und fester Breite.</p>',
    ],
    [
        'section' => 'HTML',
        'title'   => 'Tabellen',
        'desc'    => 'table, thead, tbody, tr, th, td.',
        'type'    => 'html',
        'code'    => '<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr><th>Name</th><th>Alter</th><th>Stadt</th></tr>
    </thead>
    <tbody>
        <tr><td>Anna</td><td>28</td><td>Berlin</td></tr>
        <tr><td>Boris</td><td>34</td><td>München</td></tr>
        <tr><td>Clara</td><td>22</td><td>Hamburg</td></tr>
    </tbody>
</table>',
    ],
    [
        'section' => 'HTML',
        'title'   => 'Formulare',
        'desc'    => 'input, textarea, select, button, label.',
        'type'    => 'html',
        'code'    => '<form style="font-family:sans-serif">
    <label for="name"><b>Name:</b></label><br>
    <input type="text" id="name" name="name"><br><br>

    <label for="email"><b>E-Mail:</b></label><br>
    <input type="email" id="email" name="email"><br><br>

    <label for="stadt"><b>Stadt:</b></label><br>
    <select id="stadt" name="stadt">
        <option>Berlin</option>
        <option>München</option>
        <option>Hamburg</option>
    </select><br><br>

    <label for="msg"><b>Nachricht:</b></label><br>
    <textarea id="msg" name="msg" rows="3" cols="30"></textarea><br><br>

    <button type="submit" style="padding:8px 16px">Absenden</button>
</form>',
    ],

    // ─────────────── CSS (11 Steps) ───────────────
    [
        'section' => 'CSS',
        'title'   => 'Syntax & Selektoren',
        'desc'    => 'Element-, Klassen- und ID-Selektoren.',
        'type'    => 'css',
        'code'    => '<style>
  h1 { color: navy; }
  .highlight { background: yellow; padding: 2px 6px; }
  #special { font-size: 1.8rem; color: #e74c3c; }
</style>
<h1>Titel (Element-Selektor)</h1>
<p class="highlight">Klassen-Selektor</p>
<p id="special">ID-Selektor</p>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Box-Modell',
        'desc'    => 'margin, border, padding, content, box-sizing.',
        'type'    => 'css',
        'code'    => '<style>
  .box {
    width: 250px;
    padding: 20px;
    border: 5px solid #333;
    margin: 20px;
    background: #eef;
    box-sizing: border-box;
  }
  .box span { display:block; margin-top:8px; font-size:0.8rem; color:#666; }
</style>
<div class="box">
  Content + Padding + Border + Margin
  <span>← Margin (20px) | Border (5px) | Padding (20px) | Content</span>
</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Text & Farben',
        'desc'    => 'font-family, font-size, color, background-color, line-height.',
        'type'    => 'css',
        'code'    => '<style>
  body { font-family: Georgia, serif; }
  h1 { color: #2c3e50; font-size: 2.2rem; }
  p  { color: #555; font-size: 1.1rem; line-height: 1.7; }
  .accent {
    color: white;
    background: #e74c3c;
    padding: 2px 8px;
    border-radius: 4px;
    font-family: sans-serif;
  }
  .mono { font-family: monospace; background: #f4f4f4; padding: 2px 6px; }
</style>
<h1>Typografie</h1>
<p>Georgia ist eine <span class="accent">serifene Schrift</span>.</p>
<p>Code: <span class="mono">const x = 42;</span></p>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'CSS-Variablen',
        'desc'    => 'Custom Properties mit var() – zentrale Farbwerte.',
        'type'    => 'css',
        'code'    => '<style>
  :root {
    --primary: #3498db;
    --bg: #f9f9f9;
    --radius: 8px;
    --spacing: 1rem;
  }
  body { background: var(--bg); font-family: sans-serif; padding: 2rem; }
  .btn {
    background: var(--primary);
    color: white;
    padding: 10px 24px;
    border-radius: var(--radius);
    border: none;
    font-size: 1rem;
    cursor: pointer;
  }
  .card {
    background: white;
    padding: var(--spacing);
    border-radius: var(--radius);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: var(--spacing);
  }
</style>
<button class="btn">Klicke mich</button>
<div class="card">Farbe und Radius kommen aus CSS-Variablen.</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Flexbox-Grundlagen',
        'desc'    => 'display: flex, flex-direction, justify-content, gap.',
        'type'    => 'css',
        'code'    => '<style>
  .container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    background: #eee;
    padding: 16px;
    gap: 12px;
  }
  .item {
    background: #3498db;
    color: white;
    padding: 24px;
    text-align: center;
    font-weight: bold;
    border-radius: 6px;
  }
</style>
<div class="container">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Flexbox-Layouts',
        'desc'    => 'align-items, flex-wrap, flex-grow, gap.',
        'type'    => 'css',
        'code'    => '<style>
  .gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
  }
  .card {
    flex: 1 1 140px;
    background: #2c3e50;
    color: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
  }
</style>
<div class="gallery">
  <div class="card">Karte 1</div>
  <div class="card">Karte 2</div>
  <div class="card">Karte 3</div>
  <div class="card">Karte 4</div>
  <div class="card">Karte 5</div>
</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Grid-Grundlagen',
        'desc'    => 'display: grid, grid-template-columns, gap.',
        'type'    => 'css',
        'code'    => '<style>
  .grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
  }
  .cell {
    background: #27ae60;
    color: white;
    padding: 28px;
    text-align: center;
    border-radius: 6px;
    font-size: 1.2rem;
    font-weight: bold;
  }
</style>
<div class="grid">
  <div class="cell">1</div>
  <div class="cell">2</div>
  <div class="cell">3</div>
  <div class="cell">4</div>
  <div class="cell">5</div>
  <div class="cell">6</div>
</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Grid-Layouts',
        'desc'    => 'grid-column, grid-row, grid-area – komplexe Layouts.',
        'type'    => 'css',
        'code'    => '<style>
  .layout {
    display: grid;
    grid-template-columns: 150px 1fr 150px;
    grid-template-rows: 50px 1fr 40px;
    gap: 8px;
    height: 280px;
  }
  .header  { grid-column: 1 / -1; background: #2c3e50; color: #fff; padding: 12px; }
  .sidebar { background: #34495e; color: #fff; padding: 12px; }
  .main    { background: #ecf0f1; padding: 12px; }
  .footer  { grid-column: 1 / -1; background: #2c3e50; color: #fff; padding: 8px; }
</style>
<div class="layout">
  <div class="header">Header</div>
  <div class="sidebar">Nav</div>
  <div class="main">Inhalt</div>
  <div class="sidebar">Aside</div>
  <div class="footer">Footer</div>
</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Positionierung',
        'desc'    => 'relative, absolute, fixed, sticky.',
        'type'    => 'css',
        'code'    => '<style>
  .sticky-bar {
    position: sticky;
    top: 0;
    background: #f39c12;
    padding: 10px;
    color: #fff;
    font-weight: bold;
    z-index: 10;
  }
  .parent {
    position: relative;
    height: 120px;
    background: #eee;
    margin-top: 12px;
  }
  .child {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #8e44ad;
    color: #fff;
    padding: 10px;
    border-radius: 4px;
  }
  .fixed-badge {
    position: fixed;
    bottom: 16px;
    right: 16px;
    background: #e74c3c;
    color: #fff;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
  }
</style>
<div class="sticky-bar">Sticky – bleibt beim Scrollen oben</div>
<div class="parent">
  <div class="child">Absolute (in Parent)</div>
</div>
<p style="height:200px">Scrollbereich…</p>
<div class="fixed-badge">F</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Responsive Basics',
        'desc'    => 'Relative Einheiten: %, rem, vw, clamp().',
        'type'    => 'css',
        'code'    => '<style>
  body { font-size: 1rem; margin: 0; padding: 2rem; }
  .fluid {
    width: 80%;
    max-width: 600px;
    padding: 1.5rem;
    background: #3498db;
    color: white;
    font-size: clamp(1rem, 2.5vw, 1.5rem);
    border-radius: 8px;
  }
  .half { width: 50%; background: #2ecc71; color:#fff; padding: 12px; margin-top: 8px; }
</style>
<div class="fluid">Fluid: skaliert mit der Viewport-Breite (clamp)</div>
<div class="half">50% Breite</div>',
    ],
    [
        'section' => 'CSS',
        'title'   => 'Transitionen & Animationen',
        'desc'    => 'hover-Effekte und @keyframes-Animationen.',
        'type'    => 'css',
        'code'    => '<style>
  .btn {
    padding: 14px 28px;
    background: #3498db;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    border-radius: 6px;
    transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
  }
  .btn:hover {
    background: #2980b9;
    transform: scale(1.08);
    box-shadow: 0 4px 12px rgba(52,152,219,0.4);
  }
  .btn:active { transform: scale(0.97); }

  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.15); }
  }
  .pulse {
    display: inline-block;
    animation: pulse 1.2s ease-in-out infinite;
    font-size: 1.5rem;
  }
</style>
<button class="btn">Hover mich →</button>
<p><span class="pulse">🔴</span> Pulsierende Animation</p>',
    ],

    // ─────────────── MEDIA QUERIES (5 Steps) ───────────────
    [
        'section' => 'Media Queries',
        'title'   => 'Syntax & Aufbau',
        'desc'    => '@media-Regel, Medientypen, Operatoren.',
        'type'    => 'css',
        'code'    => '<style>
  body { font-family: sans-serif; padding: 20px; }
  @media screen {
    .screen-only { display: block; }
    .print-only  { display: none; }
  }
  @media print {
    .screen-only { display: none; }
    .print-only  { display: block; }
  }
  .screen-only, .print-only {
    padding: 12px;
    border-radius: 6px;
    margin: 8px 0;
  }
  .screen-only { background: #d4edda; }
  .print-only { background: #fff3cd; }
</style>
<p class="screen-only">📱 Siehst du nur am Bildschirm.</p>
<p class="print-only">🖨️ Siehst du nur im Druck (Strg+P).</p>',
    ],
    [
        'section' => 'Media Queries',
        'title'   => 'Breakpoints',
        'desc'    => 'Kritische Breiten: 600px, 768px, 1024px.',
        'type'    => 'css',
        'code'    => '<style>
  .box {
    background: #3498db;
    color: #fff;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    font-size: 1.1rem;
  }
  @media (max-width: 600px) {
    .box { background: #e74c3c; }
  }
  @media (min-width: 601px) and (max-width: 1024px) {
    .box { background: #f39c12; }
  }
  @media (min-width: 1025px) {
    .box { background: #27ae60; }
  }
</style>
<p>→ Verändere die Browser-Breite (DevTools: Toggle Device Toolbar)</p>
<div class="box">Farbe zeigt den aktiven Breakpoint</div>',
    ],
    [
        'section' => 'Media Queries',
        'title'   => 'Mobile-First',
        'desc'    => 'Basis = mobil, dann min-width-Upgrades.',
        'type'    => 'css',
        'code'    => '<style>
  /* Basis: 1 Spalte (Mobil) */
  .grid { display: grid; gap: 10px; }
  .card {
    background: #2c3e50;
    color: #fff;
    padding: 20px;
    border-radius: 6px;
    text-align: center;
  }

  /* Tablet: 2 Spalten */
  @media (min-width: 600px) {
    .grid { grid-template-columns: 1fr 1fr; }
  }
  /* Desktop: 3 Spalten */
  @media (min-width: 900px) {
    .grid { grid-template-columns: 1fr 1fr 1fr; }
  }
</style>
<p>→ Vereng den Browser, um Spaltenwechsel zu sehen.</p>
<div class="grid">
  <div class="card">1</div>
  <div class="card">2</div>
  <div class="card">3</div>
  <div class="card">4</div>
  <div class="card">5</div>
  <div class="card">6</div>
</div>',
    ],
    [
        'section' => 'Media Queries',
        'title'   => 'Orientation & Features',
        'desc'    => 'orientation, prefers-color-scheme.',
        'type'    => 'css',
        'code'    => '<style>
  body { font-family: sans-serif; padding: 20px; transition: background 0.3s; }
  .info {
    padding: 16px;
    color: #fff;
    border-radius: 8px;
    margin: 12px 0;
  }
  @media (orientation: portrait) {
    .info { background: #3498db; }
  }
  @media (orientation: landscape) {
    .info { background: #27ae60; }
  }
  @media (prefers-color-scheme: dark) {
    body { background: #1a1a2e; color: #eee; }
  }
</style>
<div class="info">📐 Orientation wird angezeigt. Dark-Mode wird automatisch erkannt.</div>
<p>Drehe dein Gerät oder ändere die Browser-Breite.</p>',
    ],
    [
        'section' => 'Media Queries',
        'title'   => 'Container Queries',
        'desc'    => 'Abfragen basierend auf der Elternelement-Breite.',
        'type'    => 'css',
        'code'    => '<style>
  .container {
    container-type: inline-size;
    border: 2px dashed #999;
    padding: 12px;
    margin: 12px 0;
  }
  .card {
    background: #3498db;
    color: #fff;
    padding: 14px;
    border-radius: 6px;
    font-size: 0.9rem;
  }
  @container (min-width: 350px) {
    .card {
      font-size: 1.2rem;
      background: #27ae60;
      padding: 20px;
    }
  }
</style>
<div class="container" style="width: 220px">
  <div class="card">Schmal → kleine Schrift</div>
</div>
<div class="container" style="width: 400px">
  <div class="card">Breit → große Schrift, grüne Farbe</div>
</div>',
    ],

    // ─────────────── JAVASCRIPT (11 Steps) ───────────────
    [
        'section' => 'JavaScript',
        'title'   => 'Variablen & Datentypen',
        'desc'    => 'let, const, Strings, Numbers, Booleans.',
        'type'    => 'js',
        'code'    => 'let name = "Anna";
const alter = 28;
let istOnline = true;
let pi = 3.14159;

document.body.innerHTML = `
  <ul style="font-size:1.1rem;line-height:2">
    <li>Name: <b>${name}</b> → String</li>
    <li>Alter: <b>${alter}</b> → Number</li>
    <li>Online: <b>${istOnline}</b> → Boolean</li>
    <li>Pi: <b>${pi}</b> → Number</li>
  </ul>`;',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Operatoren & Logik',
        'desc'    => 'Arithmetik, Vergleich, &&, ||, !',
        'type'    => 'js',
        'code'    => 'let a = 10, b = 3;
const zeilen = [
  `a + b = ${a + b}`,
  `a - b = ${a - b}`,
  `a * b = ${a * b}`,
  `a / b = ${(a / b).toFixed(2)}`,
  `a % b = ${a % b}`,
  `a > b → ${a > b}`,
  `a === b → ${a === b}`,
  `(a > 5) && (b < 5) → ${(a > 5) && (b < 5)}`,
  `(a > 5) || (b > 5) → ${(a > 5) || (b > 5)}`,
  `!(a === b) → ${!(a === b)}`,
];
document.body.innerHTML = `<pre style="font-size:1rem;line-height:1.8">${zeilen.join("\\n")}</pre>`;',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Kontrollfluss',
        'desc'    => 'if/else, else if, switch.',
        'type'    => 'js',
        'code'    => 'let punkte = 85;
let note;
if (punkte >= 90) note = "Sehr gut";
else if (punkte >= 70) note = "Gut";
else if (punkte >= 50) note = "Befriedigend";
else note = "Nicht bestanden";

let tag = "Mittwoch";
let typ;
switch (tag) {
  case "Montag": case "Dienstag": case "Mittwoch":
  case "Donnerstag": case "Freitag":
    typ = "Werktag"; break;
  case "Samstag": case "Sonntag":
    typ = "Wochenende"; break;
  default: typ = "Unbekannt";
}

document.body.innerHTML = `
  <p style="font-size:1.1rem;line-height:2">
    ${punkte} Punkte → <b>${note}</b><br>
    ${tag} → <b>${typ}</b>
  </p>`;',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Schleifen',
        'desc'    => 'for, while, do...while.',
        'type'    => 'js',
        'code'    => 'let forOut = [];
for (let i = 1; i <= 5; i++) forOut.push(i);

let whileOut = [];
let j = 1;
while (j <= 5) { whileOut.push(j); j++; }

let doOut = [];
let k = 1;
do { doOut.push(k); k++; } while (k <= 5);

document.body.innerHTML = `
  <p style="font-size:1rem;line-height:2">
    <b>for:</b> ${forOut.join(", ")}<br>
    <b>while:</b> ${whileOut.join(", ")}<br>
    <b>do...while:</b> ${doOut.join(", ")}
  </p>`;',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Funktionen',
        'desc'    => 'Deklaration, Parameter, return, Arrow Functions.',
        'type'    => 'js',
        'code'    => 'function addiere(a, b) { return a + b; }
const multipliziere = (a, b) => a * b;

function faktoriell(n) {
  if (n <= 1) return 1;
  return n * faktoriell(n - 1);
}

document.body.innerHTML = `
  <pre style="font-size:1rem;line-height:2">
addiere(3, 4) = ${addiere(3, 4)}
multipliziere(3, 4) = ${multipliziere(3, 4)}
faktoriell(5) = ${faktoriell(5)}
faktoriell(0) = ${faktoriell(0)}
  </pre>`;',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'DOM-Selektion',
        'desc'    => 'querySelector, querySelectorAll, getElementById.',
        'type'    => 'js',
        'code'    => 'document.body.innerHTML = `
  <div id="ziel" style="padding:12px;background:#eee;margin:8px 0">Ziel-Element</div>
  <p class="par">Absatz 1</p>
  <p class="par">Absatz 2</p>
  <p class="par">Absatz 3</p>
  <div id="result" style="margin-top:12px;padding:12px;background:#d4edda"></div>
`;

const ziel = document.getElementById("ziel");
ziel.style.color = "red";
ziel.textContent = "Per getElementById verändert!";

const pars = document.querySelectorAll(".par");
pars.forEach((p, i) => {
  p.textContent = `Absatz ${i + 1} (querySelectorAll)`;
  p.style.color = "#3498db";
});

document.getElementById("result").innerHTML =
  "<b>✓</b> Alle Elemente per JS selektiert und verändert.";',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'DOM-Manipulation',
        'desc'    => 'textContent, innerHTML, setAttribute, classList.',
        'type'    => 'js',
        'code'    => 'document.body.innerHTML = `
  <div id="box" style="padding:16px;background:#eee;border:2px solid #ccc;margin:8px 0">
    Vorher: graue Box
  </div>
  <div id="neu" style="margin-top:12px"></div>
`;

const box = document.getElementById("box");
box.innerHTML = "<b>Nachher:</b> innere HTML-Struktur geändert";
box.setAttribute("border", "3px solid #3498db");
box.style.backgroundColor = "#d4edda";
box.style.borderRadius = "8px";

// Neues Element erstellen
const neu = document.createElement("p");
neu.textContent = "Neues Element per createElement()";
neu.style.color = "#27ae60";
neu.style.fontWeight = "bold";
document.getElementById("neu").appendChild(neu);',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Event Handling',
        'desc'    => 'addEventListener: click, input, submit.',
        'type'    => 'js',
        'code'    => 'document.body.innerHTML = `
  <button id="btn" style="padding:12px 24px;font-size:1rem;cursor:pointer;background:#3498db;color:#fff;border:none;border-radius:6px">
    Klicken (0)
  </button>
  <input id="inp" placeholder="Tippe hier…" style="margin-top:14px;padding:10px;width:220px;font-size:1rem;border:2px solid #ddd;border-radius:6px">
  <p id="out" style="margin-top:14px;font-size:1rem;color:#555"></p>
`;

let count = 0;
const btn = document.getElementById("btn");
btn.addEventListener("click", () => {
  count++;
  btn.textContent = `Klicken (${count})`;
  btn.style.background = `hsl(${count * 30}, 70%, 50%)`;
});

const inp = document.getElementById("inp");
const out = document.getElementById("out");
inp.addEventListener("input", (e) => {
  out.textContent = `Eingabe: "${e.target.value}" (${e.target.value.length} Zeichen)`;
});',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Arrays & Methoden',
        'desc'    => 'forEach, map, filter, reduce, sort.',
        'type'    => 'js',
        'code'    => 'const zahlen = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
const verdoppelt = zahlen.map(n => n * 2);
const gerade = zahlen.filter(n => n % 2 === 0);
const summe = zahlen.reduce((acc, n) => acc + n, 0);
const sortiert = [...zahlen].sort((a, b) => b - a);

document.body.innerHTML = `
  <pre style="font-size:0.95rem;line-height:2">
Original:   ${zahlen.join(", ")}
Map ×2:     ${verdoppelt.join(", ")}
Filter:     ${gerade.join(", ")}  (gerade)
Summe:      ${summe}
Desc sort:  ${sortiert.join(", ")}
  </pre>`;',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Promises',
        'desc'    => 'then/catch, resolve/reject, Promise.all.',
        'type'    => 'js',
        'code'    => 'const ladeDaten = () => new Promise((resolve) => {
  setTimeout(() => resolve("Daten erfolgreich geladen! ✓"), 800);
});

const fehler = () => new Promise((_, reject) => {
  setTimeout(() => reject("Fehler 404 – nicht gefunden"), 500);
});

document.body.innerHTML = `<p style="font-size:1rem">⏳ Lädt…</p>`;

ladeDaten().then(msg => {
  document.body.innerHTML += `<p style="color:#27ae60;font-size:1rem">${msg}</p>`;
});

fehler().catch(err => {
  document.body.innerHTML += `<p style="color:#e74c3c;font-size:1rem">⚠ ${err}</p>`;
});

Promise.all([ladeDaten(), ladeDaten()]).then(results => {
  document.body.innerHTML += `<p style="color:#3498db;font-size:1rem">Promise.all: ${results.length} Daten geladen</p>`;
});',
    ],
    [
        'section' => 'JavaScript',
        'title'   => 'Async/Await & Fetch',
        'desc'    => 'async/await-Syntax, fetch für API-Requests.',
        'type'    => 'js',
        'code'    => 'async function ladeBenutzer() {
  document.body.innerHTML = `<p style="font-size:1rem">⏳ Lade Daten von API…</p>`;
  try {
    const res = await fetch("https://jsonplaceholder.typicode.com/users/1");
    const user = await res.json();
    document.body.innerHTML = `
      <div style="padding:16px;background:#f0f9ff;border-radius:8px;font-size:1rem;line-height:1.8">
        <b>${user.name}</b><br>
        E-Mail: ${user.email}<br>
        Firma: ${user.company.name}<br>
        Stadt: ${user.city.name}
      </div>`;
  } catch (e) {
    document.body.innerHTML = `<p style="color:red">Fehler: ${e.message}</p>`;
  }
}
ladeBenutzer();',
    ],

    // ─────────────── PHP (10 Steps) ───────────────
    [
        'section' => 'PHP',
        'title'   => 'Serverseitige Ausführung',
        'desc'    => 'PHP läuft auf dem Server und sendet HTML an den Browser.',
        'type'    => 'php',
        'code'    => '<?php
$serverzeit = date("d.m.Y H:i:s");
$phpVersion = PHP_VERSION;
?>
<p><b>Serverzeit:</b> <?= $serverzeit ?></p>
<p><b>PHP-Version:</b> <?= $phpVersion ?></p>
<p>Diese Seite wird vom Server gerendert – der Browser erhält nur fertiges HTML.</p>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Grundsyntax & echo',
        'desc'    => '<?php ?>-Blöcke, echo, print, Kommentare.',
        'type'    => 'php',
        'code'    => '<?php
// Einzeiliger Kommentar
/* Mehrzeiliger
   Kommentar */

echo "Hallo ";       // Methode 1
print "Welt!\\n";    // Methode 2
echo "<br>";
echo "PHP-Version: " . PHP_VERSION;
echo "<br>";
echo "Zeit: " . date("H:i:s");
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Variablen & Arrays',
        'desc'    => '$-Präfix, assoziative und numerische Arrays.',
        'type'    => 'php',
        'code'    => '<?php
$name = "Anna";
$alter = 28;

$fruits = ["Apfel", "Banane", "Zitrone"];
$person = ["name" => "Boris", "stadt" => "München", "alter" => 34];

echo "<b>Variablen:</b> $name, $alter<br>";
echo "<b>Array:</b> " . implode(", ", $fruits) . "<br>";
echo "<b>Assoziativ:</b> {$person["name"]} aus {$person["stadt"]} ({$person["alter"]})<br>";
echo "<b>Letztes Element:</b> " . end($fruits);
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Kontrollstrukturen',
        'desc'    => 'if/else, switch, for, foreach.',
        'type'    => 'php',
        'code'    => '<?php
$noten = [85, 42, 71, 93, 55];
echo "<b>if/else:</b><br>";
foreach ($noten as $n) {
    echo "$n → " . ($n >= 50 ? "Bestanden ✓" : "Nicht bestanden ✗") . "<br>";
}

echo "<br><b>foreach (assoziativ):</b><br>";
$farben = ["rot" => "#e74c3c", "blau" => "#3498db", "grün" => "#27ae60"];
foreach ($farben as $name => $hex) {
    echo "$name = $hex<br>";
}
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Funktionen',
        'desc'    => 'Benutzerdefinierte Funktionen, Parameter, return.',
        'type'    => 'php',
        'code'    => '<?php
function addiere($a, $b): int {
    return $a + $b;
}

function grueße($name, $sprache = "Deutsch"): string {
    $gruesse = [
        "Deutsch" => "Hallo",
        "Englisch" => "Hello",
        "Französisch" => "Bonjour"
    ];
    return ($gruesse[$sprache] ?? "Hallo") . ", " . $name . "!";
}

echo addiere(3, 4) . "<br>";
echo grueße("Anna") . "<br>";
echo grueße("Anna", "Englisch") . "<br>";
echo grueße("Anna", "Französisch");
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Formularverarbeitung',
        'desc'    => '$_GET und $_POST für Eingabedaten.',
        'type'    => 'php',
        'code'    => '<?php
// Simuliert POST-Daten
// Im echten Einsatz: $_POST["name"], $_POST["email"]
$name = "Anna";
$email = "anna@example.com";

if (!empty($name) && !empty($email)) {
    echo "<div style=\"background:#d4edda;padding:16px;border-radius:8px;border:1px solid #c3e6cb\">";
    echo "<b>Vielen Dank, $name!</b><br>";
    echo "Deine E-Mail: $email<br>";
    echo "Gesendet am: " . date("d.m.Y H:i");
    echo "</div>";
} else {
    echo "<p style=\"color:#e74c3c\">Bitte alle Felder ausfüllen.</p>";
}
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Datenvalidierung',
        'desc'    => 'filter_var, trim, htmlspecialchars gegen XSS.',
        'type'    => 'php',
        'code'    => '<?php
$eingabe = "  <script>alert(1)</script>Anna  ";

$sauber = htmlspecialchars(trim($eingabe), ENT_QUOTES, "UTF-8");
$validEmail = filter_var("anna@test.de", FILTER_VALIDATE_EMAIL);
$invalidEmail = filter_var("nicht-email", FILTER_VALIDATE_EMAIL);

echo "<b>Roh:</b> " . $eingabe . "<br>";
echo "<b>Gereinigt:</b> $sauber<br>";
echo "<b>E-Mail gültig:</b> " . ($validEmail ? "Ja ✓" : "Nein") . "<br>";
echo "<b>E-Mail ungültig:</b> " . ($invalidEmail ? "Ja" : "Nein ✗");
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Sessions & Cookies',
        'desc'    => '$_SESSION (Server), $_COOKIE (Client).',
        'type'    => 'php',
        'code'    => '<?php
// Simuliert Session & Cookies
$_SESSION = ["user" => "Anna", "login_zeit" => time() - 3600];
$_COOKIE  = ["thema" => "dark", "sprache" => "de"];

echo "<b>Session (Server):</b><br>";
echo "User: " . $_SESSION["user"] . "<br>";
echo "Eingeloggt vor: " . (time() - $_SESSION["login_zeit"]) . " Sekunden<br>";

echo "<br><b>Cookies (Client):</b><br>";
foreach ($_COOKIE as $k => $v) {
    echo "$k = $v<br>";
}
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Dateihandling',
        'desc'    => 'file_get_contents, file_put_contents, is_file, unlink.',
        'type'    => 'php',
        'code'    => '<?php
$pfad = sys_get_temp_dir() . "/lr_demo_" . uniqid() . ".txt";

// Schreiben
file_put_contents($pfad, "Hallo aus PHP!\\n" . date("d.m.Y H:i:s") . "\\n");

echo "Datei geschrieben: " . basename($pfad) . "<br>";
echo "Existiert: " . (is_file($pfad) ? "Ja ✓" : "Nein") . "<br>";
echo "Inhalt:<br><pre>" . htmlspecialchars(file_get_contents($pfad)) . "</pre>";

// Aufräumen
unlink($pfad);
echo "Datei gelöscht. Existiert noch: " . (is_file($pfad) ? "Ja" : "Nein ✓");
?>',
    ],
    [
        'section' => 'PHP',
        'title'   => 'Datenbankverbindung (PDO)',
        'desc'    => 'PDO mit SQLite – Verbindung, Prepared Statements.',
        'type'    => 'php',
        'code'    => '<?php
$db = new PDO("sqlite::memory:");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("CREATE TABLE benutzer (
    id INTEGER PRIMARY KEY,
    name TEXT,
    stadt TEXT
)");

$stmt = $db->prepare("INSERT INTO benutzer (name, stadt) VALUES (:name, :stadt)");
$stmt->execute(["name" => "Anna", "stadt" => "Berlin"]);
$stmt->execute(["name" => "Boris", "stadt" => "München"]);
$stmt->execute(["name" => "Clara", "stadt" => "Hamburg"]);

$result = $db->query("SELECT * FROM benutzer");
echo "<table border=\"1\" cellpadding=\"6\" cellspacing=\"0\" style=\"border-collapse:collapse\">";
echo "<tr><th>ID</th><th>Name</th><th>Stadt</th></tr>";
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr><td>{$row["id"]}</td><td>{$row["name"]}</td><td>{$row["stadt"]}</td></tr>";
}
echo "</table>";
?>',
    ],

    // ─────────────── SQL (6 Steps) ───────────────
    [
        'section' => 'SQL',
        'title'   => 'Datenbankstruktur',
        'desc'    => 'CREATE TABLE, Datentypen, Constraints.',
        'type'    => 'sql',
        'code'    => "CREATE TABLE benutzer (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE,
    stadt TEXT,
    erstellt DATETIME DEFAULT CURRENT_DATE
);

CREATE TABLE auftraege (
    id INTEGER PRIMARY KEY,
    benutzer_id INTEGER,
    betrag REAL,
    status TEXT DEFAULT 'offen',
    FOREIGN KEY (benutzer_id) REFERENCES benutzer(id)
);

SELECT 'Tabellen erstellt' AS status;",
    ],
    [
        'section' => 'SQL',
        'title'   => 'SELECT & WHERE',
        'desc'    => 'Datenabruf mit Filterbedingungen.',
        'type'    => 'sql',
        'code'    => "INSERT INTO benutzer (name, email, stadt) VALUES
    ('Anna', 'anna@mail.de', 'Berlin'),
    ('Boris', 'boris@mail.de', 'München'),
    ('Clara', 'clara@mail.de', 'Hamburg'),
    ('David', 'david@mail.de', 'Berlin'),
    ('Eva', 'eva@mail.de', 'München');

SELECT name, stadt FROM benutzer WHERE stadt = 'Berlin';",
    ],
    [
        'section' => 'SQL',
        'title'   => 'ORDER BY & LIMIT',
        'desc'    => 'Sortierung und Begrenzung der Ergebnisse.',
        'type'    => 'sql',
        'code'    => "INSERT INTO benutzer (name, email, stadt) VALUES
    ('Anna', 'anna@mail.de', 'Berlin'),
    ('Boris', 'boris@mail.de', 'München'),
    ('Clara', 'clara@mail.de', 'Hamburg'),
    ('David', 'david@mail.de', 'Berlin'),
    ('Eva', 'eva@mail.de', 'München');

SELECT name, stadt FROM benutzer ORDER BY name DESC LIMIT 3;",
    ],
    [
        'section' => 'SQL',
        'title'   => 'Aggregation & GROUP BY',
        'desc'    => 'COUNT, SUM, AVG, MIN, MAX, GROUP BY.',
        'type'    => 'sql',
        'code'    => "INSERT INTO benutzer (name, email, stadt) VALUES
    ('Anna', 'anna@mail.de', 'Berlin'),
    ('Boris', 'boris@mail.de', 'München'),
    ('Clara', 'clara@mail.de', 'Hamburg'),
    ('David', 'david@mail.de', 'Berlin'),
    ('Eva', 'eva@mail.de', 'München');

SELECT stadt, COUNT(*) AS anzahl
FROM benutzer
GROUP BY stadt
ORDER BY anzahl DESC;",
    ],
    [
        'section' => 'SQL',
        'title'   => 'Joins',
        'desc'    => 'INNER JOIN, LEFT JOIN – Tabellen verbinden.',
        'type'    => 'sql',
        'code'    => "INSERT INTO benutzer (id, name, email, stadt) VALUES
    (1, 'Anna', 'anna@mail.de', 'Berlin'),
    (2, 'Boris', 'boris@mail.de', 'München'),
    (3, 'Clara', 'clara@mail.de', 'Hamburg');

INSERT INTO auftraege (id, benutzer_id, betrag, status) VALUES
    (1, 1, 49.99, 'bezahlt'),
    (2, 1, 29.99, 'offen'),
    (3, 2, 99.99, 'bezahlt');

SELECT b.name, a.betrag, a.status
FROM benutzer b
INNER JOIN auftraege a ON b.id = a.benutzer_id
ORDER BY a.betrag DESC;",
    ],
    [
        'section' => 'SQL',
        'title'   => 'INSERT / UPDATE / DELETE',
        'desc'    => 'DML: Daten einfügen, ändern, löschen.',
        'type'    => 'sql',
        'code'    => "CREATE TABLE produkte (
    id INTEGER PRIMARY KEY,
    name TEXT,
    preis REAL,
    bestand INTEGER
);

INSERT INTO produkte (name, preis, bestand) VALUES
    ('Tasse', 9.99, 50),
    ('Teller', 14.99, 30),
    ('Gabel', 4.99, 100);

UPDATE produkte SET preis = 7.99 WHERE name = 'Tasse';
DELETE FROM produkte WHERE bestand < 35;

SELECT * FROM produkte;",
    ],
];

// ═══════════════════════════════════════════════════════════════
// 4. FRONTEND-RENDERING
// ═══════════════════════════════════════════════════════════════
?>
<section id="lernreise">
    <style>
        /* ─── Reset & Basis ─── */
        #lernreise {
            --lr-primary: #2c3e50;
            --lr-accent: #3498db;
            --lr-accent-hover: #2980b9;
            --lr-bg: #f8f9fa;
            --lr-code-bg: #1e1e2e;
            --lr-code-header: #181825;
            --lr-text: #333;
            --lr-muted: #666;
            --lr-border: #e0e0e0;
            --lr-radius: 8px;
            --lr-font: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --lr-mono: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace;

            font-family: var(--lr-font);
            color: var(--lr-text);
            background: var(--lr-bg);
            padding: 3rem 0;
            margin: 0;
        }

        /* ─── Container ─── */
        .lr-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ─── Header ─── */
        .lr-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .lr-header h2 {
            font-size: 2rem;
            color: var(--lr-primary);
            margin: 0 0 0.5rem;
        }
        .lr-header p {
            color: var(--lr-muted);
            font-size: 1.1rem;
            margin: 0;
        }

        /* ─── Section-Titel ─── */
        .lr-section-title {
            text-align: center;
            margin: 3rem 0 2rem;
            padding: 14px;
            background: var(--lr-primary);
            color: #fff;
            border-radius: var(--lr-radius);
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* ─── Step-Grid ─── */
        .lr-step {
            display: grid;
            grid-template-columns: 1fr 70px 1fr;
            gap: 1.5rem;
            align-items: start;
            margin-bottom: 3rem;
            opacity: 0.2;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .lr-step.lr-active {
            opacity: 1;
            transform: translateY(0);
        }

        /* ─── Timeline (Mitte) ─── */
        .lr-timeline {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            min-height: 100%;
        }
        .lr-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #ddd;
            border-radius: 2px;
        }
        .lr-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ccc;
            border: 3px solid var(--lr-accent);
            z-index: 1;
            transition: background 0.3s, transform 0.3s;
            flex-shrink: 0;
        }
        .lr-step.lr-active .lr-dot {
            background: var(--lr-accent);
            transform: scale(1.4);
        }
        .lr-label {
            margin-top: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--lr-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
            line-height: 1.3;
            max-width: 100px;
        }

        /* ─── Code-Panel ─── */
        .lr-code-panel {
            background: var(--lr-code-bg);
            border-radius: var(--lr-radius);
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .lr-code-header {
            background: var(--lr-code-header);
            padding: 10px 14px;
            font-size: 0.8rem;
            color: #a6adc8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .lr-lang-badge {
            background: var(--lr-accent);
            color: #fff;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .lr-code {
            padding: 16px;
            overflow-x: auto;
            font-family: var(--lr-mono);
            font-size: 0.82rem;
            line-height: 1.7;
            color: #cdd6f4;
            white-space: pre;
            tab-size: 4;
            max-height: 400px;
            overflow-y: auto;
        }

        /* ─── Output-Panel ─── */
        .lr-output-panel {
            background: #fff;
            border: 1px solid var(--lr-border);
            border-radius: var(--lr-radius);
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .lr-output-header {
            background: #f1f1f1;
            padding: 10px 14px;
            font-size: 0.8rem;
            color: var(--lr-muted);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .lr-run-btn {
            background: var(--lr-accent);
            color: #fff;
            border: none;
            padding: 5px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 700;
            transition: background 0.2s;
        }
        .lr-run-btn:hover {
            background: var(--lr-accent-hover);
        }
        .lr-run-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        .lr-output-frame {
            width: 100%;
            height: 350px;
            border: none;
            display: block;
        }
        .lr-output-placeholder {
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 0.9rem;
        }
        .lr-output-content {
            padding: 16px;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ─── Responsive ─── */
        @media (max-width: 900px) {
            .lr-step {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .lr-timeline {
                flex-direction: row;
                justify-content: center;
                min-height: auto;
            }
            .lr-timeline::before { display: none; }
            .lr-label { max-width: none; margin-top: 0; margin-left: 8px; }
            .lr-output-frame,
            .lr-output-placeholder { height: 250px; }
        }
    </style>

    <div class="lr-container">
        <div class="lr-header">
            <h2>Web-Entwicklungs-Lernreise</h2>
            <p>Scrolle durch <?= count($steps) ?> Schritte – vom ersten HTML-Tag bis zur Datenbank.</p>
        </div>

        <?php
        $currentSection = '';
        foreach ($steps as $i => $step):
            // Section-Header bei Abschnittswechsel
            if ($step['section'] !== $currentSection):
                $currentSection = $step['section'];
                echo '<div class="lr-section-title">' . htmlspecialchars($currentSection) . '</div>';
            endif;
            ?>
            <div class="lr-step" data-step="<?= $i ?>" data-type="<?= $step['type'] ?>">

                <!-- Code (links) -->
                <div class="lr-code-panel">
                    <div class="lr-code-header">
                        <span>Step <?= $i + 1 ?> – <?= htmlspecialchars($step['title']) ?></span>
                        <span class="lr-lang-badge"><?= strtoupper($step['type']) ?></span>
                    </div>
                    <div class="lr-code"><?= htmlspecialchars($step['code']) ?></div>
                </div>

                <!-- Timeline (Mitte) -->
                <div class="lr-timeline">
                    <div class="lr-dot"></div>
                    <div class="lr-label"><?= htmlspecialchars($step['title']) ?></div>
                </div>

                <!-- Output (rechts) -->
                <div class="lr-output-panel">
                    <div class="lr-output-header">
                        <span>Ausgabe</span>
                        <?php if (in_array($step['type'], ['php', 'sql'])): ?>
                            <button class="lr-run-btn" data-step="<?= $i ?>">▶ Ausführen</button>
                        <?php endif; ?>
                    </div>
                    <?php if ($step['type'] === 'php' || $step['type'] === 'sql'): ?>
                        <div class="lr-output-placeholder" id="lr-out-<?= $i ?>">
                            Klicke auf „▶ Ausführen"
                        </div>
                    <?php else: ?>
                        <iframe
                                class="lr-output-frame"
                                id="lr-frame-<?= $i ?>"
                                sandbox="allow-scripts"
                                title="Ausgabe Step <?= $i + 1 ?>"
                        ></iframe>
                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>


    <script>
        const LR_STEPS = <?= json_encode(
                array_map(fn($s) => [
                        'code' => $s['code'],
                        'type' => $s['type'],
                ], $steps),
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG
        ) ?>;
    </script>

    <script>
        (function() {
            'use strict';

            // ─── Iframes mit Blob-URL befüllen (zuverlässiger als srcdoc) ───
            function initFrames() {
                LR_STEPS.forEach(function(step, i) {
                    if (step.type === 'php' || step.type === 'sql') return;

                    var frame = document.getElementById('lr-frame-' + i);
                    if (!frame) return;

                    var html = wrapInHtml(step.code, step.type);
                    var blob = new Blob([html], { type: 'text/html' });
                    frame.src = URL.createObjectURL(blob);
                });
            }

            function wrapInHtml(code, type) {
                // Bereits vollständiges HTML?
                if (/<(!doctype|html)/i.test(code)) return code;

                var head = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><style>' +
                    'body{font-family:-apple-system,BlinkMacSystemFont,sans-serif;padding:16px;margin:0;color:#333}' +
                    'table{border-collapse:collapse;width:100%}' +
                    'td,th{border:1px solid #ddd;padding:5px 10px;text-align:left}' +
                    'th{background:#f4f4f4}' +
                    '</style></head><body>';
                var tail = '</body></html>';

                if (type === 'js') {
                    return head + '<script>' + code + '<\/script>' + tail;
                }
                return head + code + tail;
            }

            // ─── IntersectionObserver für Scroll-Animation ───
            function initScroll() {
                var steps = document.querySelectorAll('#lernreise .lr-step');
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('lr-active');
                        }
                    });
                }, { threshold: 0.2, rootMargin: '0px 0px -50px 0px' });

                steps.forEach(function(s) { observer.observe(s); });
            }

            // ─── PHP/SQL-Ausführung ───
            function initRunButtons() {
                var buttons = document.querySelectorAll('#lernreise .lr-run-btn');
                buttons.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var idx = parseInt(this.dataset.step, 10);
                        var step = LR_STEPS[idx];
                        var outEl = document.getElementById('lr-out-' + idx);

                        this.disabled = true;
                        this.textContent = '⏳ Läuft…';
                        outEl.innerHTML = '<div class="lr-output-content" style="color:#999;text-align:center;padding-top:120px">Wird ausgeführt…</div>';

                        var formData = new FormData();
                        formData.append('lr_action', step.type === 'php' ? 'run_php' : 'run_sql');
                        formData.append('lr_code', step.code);

                        fetch(window.location.href, {
                            method: 'POST',
                            body: formData,
                            credentials: 'same-origin'
                        })
                            .then(function(res) { return res.json(); })
                            .then(function(data) {
                                outEl.innerHTML = '<div class="lr-output-content">' +
                                    (data.output || 'Keine Ausgabe.') + '</div>';
                            })
                            .catch(function(err) {
                                outEl.innerHTML = '<div class="lr-output-content" style="color:#e74c3c">' +
                                    'Fehler: ' + err.message + '</div>';
                            })
                            .finally(function() {
                                btn.disabled = false;
                                btn.textContent = '▶ Ausführen';
                            });
                    });
                });
            }

            // ─── Init (kompatibel mit include) ───
            function init() {
                initFrames();
                initScroll();
                initRunButtons();
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', init);
            } else {
                init();
            }
        })();
    </script>
</section>