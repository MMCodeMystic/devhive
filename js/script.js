// Escape HTML für sichere Anzeige von Code
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Syntax-Highlighting für Code
function highlightCode(code) {
    return code
        .replace(/&lt;(\/?)(\w+)([^&]*)&gt;/g, '<span class="tag">&lt;$1$2$3&gt;</span>')
        .replace(/(&lt;\?php|\?&gt;)/g, '<span class="php">$1</span>')
        .replace(/(\bfunction\b|\blet\b|\bconst\b|\bvar\b|\bif\b|\belse\b|\bfor\b|\bwhile\b)/g, '<span class="js">$1</span>')
        .replace(/(".*?"|'.*?')/g, '<span class="str">$1</span>')
        .replace(/(\/\/.*)/g, '<span class="comment">$1</span>');
}

// Lade die technische Lernreise-Timeline
async function loadTechnischeTimeline() {
    const response = await fetch('data/lernreise.json');
    const data = await response.json();
    const container = document.getElementById('lernreise-timeline');
    if (!container) return;

    container.innerHTML = '';
    data.forEach(item => {
        const timelineItem = document.createElement('div');
        timelineItem.className = `timeline-item technisch ${item.kategorie}`;

        const codeHighlighted = item.code
            ? `<pre><code>${highlightCode(escapeHtml(item.code))}</code></pre>`
            : '';

        const bildHtml = item.bild_url
            ? `<img src="${item.bild_url}" alt="${item.oberbegriff}" class="timeline-bild">`
            : '';

        timelineItem.innerHTML = `
            <div class="timeline-header">
                <h3>${item.oberbegriff}</h3>
                <span class="timeline-nr">Tag ${item.nr}</span>
            </div>
            <div class="timeline-content">
                <p><strong>Kurze Details:</strong> ${item.kurze_details}</p>
                <p class="timeline-ausfuehrlich"><strong>Ausführlich:</strong> ${item.ausfuehrliche_details}</p>
                ${bildHtml}
                ${codeHighlighted}
            </div>
        `;
        container.appendChild(timelineItem);

        // Klick-Effekt: Zeige alle Infos + Bild wird größer
        timelineItem.addEventListener('click', () => {
            timelineItem.classList.toggle('expanded');
        });
    });

    // Filter-Logik
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            const filter = button.getAttribute('data-filter');
            container.querySelectorAll('.timeline-item').forEach(item => {
                if (filter === 'alle' || item.classList.contains(filter)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
}

// Lade die historische Timeline
async function loadHistorischeTimeline() {
    const response = await fetch('data/historisch.json');
    const data = await response.json();
    const container = document.getElementById('historisch-timeline');
    if (!container) return;

    container.innerHTML = '';
    data.forEach(item => {
        const timelineItem = document.createElement('div');
        const category = item.technik_entwicklung.schlagwort.toLowerCase().replace(/\s+/g, '-');
        timelineItem.className = `timeline-item historisch ${category}`;

        const bildHtml = item.bild_url
            ? `<img src="${item.bild_url}" alt="${item.oberbegriff}" class="timeline-bild">`
            : '';

        timelineItem.innerHTML = `
            <div class="timeline-header">
                <h3>${item.oberbegriff}</h3>
                <span class="timeline-nr">${item.nr}</span>
            </div>
            <div class="timeline-content">
                <p><strong>Kurze Details:</strong> ${item.kurze_details}</p>
                <p class="timeline-ausfuehrlich"><strong>Ausführlich:</strong> ${item.ausfuehrliche_details}</p>
                <p><strong>Beteiligte:</strong> ${item.beteiligte}</p>
                <div class="technik-details">
                    <p><strong>Entwicklung:</strong> ${item.technik_entwicklung.lang}</p>
                    <p><strong>Herstellung:</strong> ${item.technik_herstellung.lang}</p>
                </div>
                ${bildHtml}
            </div>
        `;
        container.appendChild(timelineItem);

        // Klick-Effekt: Zeige alle Infos + Bild wird größer
        timelineItem.addEventListener('click', () => {
            timelineItem.classList.toggle('expanded');
        });
    });

    // Filter-Logik für historische Timeline
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            const filter = button.getAttribute('data-filter');

            container.querySelectorAll('.timeline-item').forEach(item => {
                const itemCategory = item.className.split(' ').find(cls => cls.startsWith('theorie') || cls.startsWith('hardware'));
                if (filter === 'alle' || (itemCategory && itemCategory.includes(filter))) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
}

// Haupt-Event-Listener (nur einmal!)
document.addEventListener('DOMContentLoaded', () => {
    // Theme-Toggle + Favicon-Wechsel
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const brightModeBtn = document.getElementById('bright-mode-btn');
    const body = document.body;
    const favicon = document.getElementById('favicon');

    // --- Hier beginnt dein bestehender Code ---

    // Lade gespeichertes Theme + Favicon
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'bright') {
        body.classList.add('bright-mode');
        if (darkModeBtn && brightModeBtn) {
            brightModeBtn.classList.add('active');
            darkModeBtn.classList.remove('active');
        }
        if (favicon) favicon.href = "assets/favicon/favicon-bright.ico";
    } else {
        if (favicon) favicon.href = "assets/favicon/favicon-dark.ico";
    }

    // Theme-Toggle-Logik
    if (darkModeBtn && brightModeBtn) {
        darkModeBtn.addEventListener('click', () => {
            body.classList.remove('bright-mode');
            darkModeBtn.classList.add('active');
            brightModeBtn.classList.remove('active');
            localStorage.setItem('theme', 'dark');
            if (favicon) favicon.href = "assets/favicon/favicon-dark.ico";
        });

        brightModeBtn.addEventListener('click', () => {
            body.classList.add('bright-mode');
            brightModeBtn.classList.add('active');
            darkModeBtn.classList.remove('active');
            localStorage.setItem('theme', 'bright');
            if (favicon) favicon.href = "assets/favicon/favicon-bright.ico";
        });
    }

    // --- Hier fügst du den Kontaktformular-Code ein ---
    // Kontaktformular-Validierung
    const kontaktform = document.getElementById('kontaktform');
    if (kontaktform) {
        const kategorieSelect = document.getElementById('kategorie');
        const emailInput = document.getElementById('email');
        const telefonInput = document.getElementById('telefon');
        const emailRadio = document.querySelector('input[name="kontaktart"][value="email"]');
        const telefonRadio = document.querySelector('input[name="kontaktart"][value="telefon"]');

        // Kategorie-Änderung: Aktiviere/Deaktiviere Radiobuttons
        kategorieSelect.addEventListener('change', () => {
            const kategorie = kategorieSelect.value;
            const hasEmail = emailInput.value.trim() !== '';
            const hasTelefon = telefonInput.value.trim() !== '';

            emailRadio.disabled = !(hasEmail || kategorie === 'sonstiges' || kategorie === 'kommentar');
            telefonRadio.disabled = !(hasTelefon || kategorie === 'sonstiges' || kategorie === 'kommentar');

            if (kategorie === 'kontaktaufnahme' || kategorie === 'auftragsanfrage') {
                if (hasEmail && !hasTelefon) {
                    emailRadio.checked = true;
                    telefonRadio.checked = false;
                } else if (hasTelefon && !hasEmail) {
                    telefonRadio.checked = true;
                    emailRadio.checked = false;
                } else if (hasEmail && hasTelefon) {
                    emailRadio.checked = true;
                    telefonRadio.checked = false;
                }
            } else {
                emailRadio.checked = false;
                telefonRadio.checked = false;
            }
        });

        // E-Mail/Telefon-Änderung: Aktualisiere Radiobuttons
        emailInput.addEventListener('input', () => {
            const kategorie = kategorieSelect.value;
            const hasEmail = emailInput.value.trim() !== '';
            const hasTelefon = telefonInput.value.trim() !== '';

            if (kategorie === 'kontaktaufnahme' || kategorie === 'auftragsanfrage') {
                if (hasEmail) {
                    emailRadio.disabled = false;
                    if (!hasTelefon) {
                        emailRadio.checked = true;
                    }
                } else {
                    emailRadio.disabled = true;
                    emailRadio.checked = false;
                }
            }
        });

        telefonInput.addEventListener('input', () => {
            const kategorie = kategorieSelect.value;
            const hasEmail = emailInput.value.trim() !== '';
            const hasTelefon = telefonInput.value.trim() !== '';

            if (kategorie === 'kontaktaufnahme' || kategorie === 'auftragsanfrage') {
                if (hasTelefon) {
                    telefonRadio.disabled = false;
                    if (!hasEmail) {
                        telefonRadio.checked = true;
                    }
                } else {
                    telefonRadio.disabled = true;
                    telefonRadio.checked = false;
                }
            }
        });

        // Formular-Validierung beim Absenden
        kontaktform.addEventListener('submit', (e) => {
            const kategorie = kategorieSelect.value;
            const hasEmail = emailInput.value.trim() !== '';
            const hasTelefon = telefonInput.value.trim() !== '';
            const hasKontaktart = emailRadio.checked || telefonRadio.checked;

            if ((kategorie === 'kontaktaufnahme' || kategorie === 'auftragsanfrage') && !hasEmail && !hasTelefon) {
                e.preventDefault();
                alert('Bitte gib mindestens eine E-Mail-Adresse oder Telefonnummer an, wenn du "Kontaktaufnahme" oder "Auftragsanfrage" auswählst.');
            } else if ((kategorie === 'kontaktaufnahme' || kategorie === 'auftragsanfrage') && !hasKontaktart) {
                e.preventDefault();
                alert('Bitte wähle eine gewünschte Kontaktaufnahme aus.');
            }
        });

        // Autofit für Textarea
        const textarea = document.getElementById('nachricht');
        if (textarea) {
            textarea.addEventListener('input', () => {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            });
        }
    }

    // CTA-Popup
    const ctaPopup = document.getElementById('cta-popup');
    const ctaClose = document.getElementById('cta-close');
    if (ctaPopup && ctaClose) {
        setTimeout(() => {
            ctaPopup.classList.add('show');
        }, 5000);

        ctaClose.addEventListener('click', () => {
            ctaPopup.classList.remove('show');
        });
    }

    // Footer-Easter Egg
    const footerHexagon = document.querySelector('.footer-hexagon');
    if (footerHexagon) {
        let clickCount = 0;
        footerHexagon.addEventListener('click', () => {
            clickCount++;
            if (clickCount >= 5) {
                alert('🐝 DevHive Easter Egg: 5x auf das Hexagon geklickt! 🎉');
                clickCount = 0;
            }
        });
    }

    // Burger-Menü
    const burgerMenu = document.querySelector('.burger-menu');
    const navbar = document.querySelector('.navbar');
    if (burgerMenu && navbar) {
        burgerMenu.addEventListener('click', () => {
            const isExpanded = burgerMenu.getAttribute('aria-expanded') === 'true';
            burgerMenu.setAttribute('aria-expanded', !isExpanded);
            navbar.classList.toggle('active');
        });

        const navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    burgerMenu.setAttribute('aria-expanded', 'false');
                    navbar.classList.remove('active');
                }
            });
        });
    }

    // Hexagon-Animation → Footer
    const hexagonAnimation = document.querySelector('.hexagon-animation');
    const footerHexagonContainer = document.querySelector('.footer-hexagon-container');
    if (hexagonAnimation && footerHexagonContainer) {
        setTimeout(() => {
            hexagonAnimation.style.display = 'none';
            footerHexagonContainer.style.display = 'flex';
        }, 1500);
    }

    // Timelines laden
    if (document.getElementById('lernreise-timeline')) {
        loadTechnischeTimeline();
    }
    if (document.getElementById('historisch-timeline')) {
        loadHistorischeTimeline();
    }

    // Klick auf Cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('click', () => {
            const link = card.querySelector('a');
            if (link) {
                window.location.href = link.href;
            }
        });
    });

    // Platonische Körper im Hintergrund (5 Körper)
    const hintergrund = document.querySelector('.platonische-koerper-hintergrund');
    if (hintergrund && hintergrund.children.length === 0) {
        const koerper = ['tetraeder', 'wuerfel', 'oktaeder', 'dodekaeder', 'ikosaeder'];
        koerper.forEach((typ) => {
            const koerperElement = document.createElement('div');
            koerperElement.className = `koerper ${typ}`;
            hintergrund.appendChild(koerperElement);
        });
    }
});