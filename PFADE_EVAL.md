\# 🐝 DevHive



Minimalistische Web-Oberfläche mit Dark-Mode-Support.



\## Struktur



devhive/

├── index.php

├── css/style.css

├── js/script.js

├── assets/

├── README.md

└── CHANGELOG.md



\## Starten



php -S localhost:8000



\## Erweiterungen



\- Hexagon-Logo (assets/)

\- Responsives Grid

\- PHP-Routing

\- API-Endpunkt

\- CI/CD   



\----



devhive/

├── index.php

├── timelines\_technisch.php  # Neue Seite

├── timelines\_historisch.php # Neue Seite

├── css/

│   ├── style.css            # Globales CSS

│   └── timelines.css        # Spezifisch für Timelines (optional)

├── js/

│   └── script.js

├── assets/

└── README.md                # Changelog/Doku



\------

devhive/

├── index.php

├── timelines\_technisch.php

├── timelines\_historisch.php

├── header.php          # Wiederverwendbarer Header mit Animation

├── footer.php          # Wiederverwendbarer Footer

├── css/

│   ├── style.css       # Globales CSS

│   └── animations.css  # CSS-Animationen (Platonische Körper, Muster)

├── js/

│   ├── script.js       # Globales JS (Burger-Menü, Theme-Toggle)

│   └── timelines.js    # JS für Timelines (Datenladen, Hover-Effekte)

├── data/               # JSON-Daten

│   ├── lernreise.json  # Technische Lernreise

│   └── historisch.json  # Historische IT-Entwicklung

└── README.md

\-----

devhive/

└── assets/

&#x20;   └── images/

&#x20;       └── historisch/

&#x20;           ├── 01\_boolesche\_algebra.png

&#x20;           ├── 02\_binaeres\_zahlensystem.png

&#x20;           └── ...


------------------
css/
├── base.css          # Grundstile (Variablen, Reset, Fonts)
├── layout.css        # Layout (Header, Footer, Navbar, Main)
├── components.css    # Komponenten (Cards, Timelines, Buttons)
├── animations.css    # Animationen (Platonische Körper, Hexagon, Hover-Effekte)
└── themes.css        # Themen (Dark Mode, Bright Mode)

----

assets/
└── favicon/
└── favicon.ico  /* Enthält alle Größen */

------

js/
├── main.js          # Haupt-Event-Listener, globale Funktionen
├── timeline.js      # Timeline-Funktionen (loadTechnischeTimeline, loadHistorischeTimeline)
├── login.js         # Login-Popup, Passwortstärke, etc.
├── filter.js        # Filter-Logik für Timelines
└── utils.js         # Hilfsfunktionen (escapeHtml, highlightCode)



