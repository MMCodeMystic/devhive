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

        // Bild einbinden (falls vorhanden)
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

        // Klick-Effekt: Zeige ausführliche Details
        timelineItem.addEventListener('click', () => {
            const content = timelineItem.querySelector('.timeline-ausfuehrlich');
            if (content) {
                content.style.display = content.style.display === 'block' ? 'none' : 'block';
            }
        });

        // Hover-Effekt
        const content = timelineItem.querySelector('.timeline-content');
        if (content) {
            timelineItem.addEventListener('mouseenter', () => {
                content.style.maxHeight = '500px';
            });
            timelineItem.addEventListener('mouseleave', () => {
                content.style.maxHeight = '100px';
            });
        }
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

        // Klick-Effekt: Zeige ausführliche Details
        timelineItem.addEventListener('click', () => {
            const content = timelineItem.querySelector('.timeline-ausfuehrlich');
            if (content) {
                content.style.display = content.style.display === 'block' ? 'none' : 'block';
            }
        });

        // Hover-Effekt
        const content = timelineItem.querySelector('.timeline-content');
        if (content) {
            timelineItem.addEventListener('mouseenter', () => {
                content.style.maxHeight = '500px';
            });
            timelineItem.addEventListener('mouseleave', () => {
                content.style.maxHeight = '100px';
            });
        }
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

// Theme-Toggle (Dark/Bright Mode)
document.addEventListener('DOMContentLoaded', () => {
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const brightModeBtn = document.getElementById('bright-mode-btn');
    const animationToggle = document.getElementById('animation-toggle');

    if (darkModeBtn && brightModeBtn) {
        darkModeBtn.addEventListener('click', () => {
            document.body.classList.remove('bright-mode');
            darkModeBtn.classList.add('active');
            brightModeBtn.classList.remove('active');
            localStorage.setItem('theme', 'dark');
        });

        brightModeBtn.addEventListener('click', () => {
            document.body.classList.add('bright-mode');
            brightModeBtn.classList.add('active');
            darkModeBtn.classList.remove('active');
            localStorage.setItem('theme', 'bright');
        });

        // Lade gespeichertes Theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'bright') {
            document.body.classList.add('bright-mode');
            brightModeBtn.classList.add('active');
            darkModeBtn.classList.remove('active');
        }
    }

    // Animationen aktivieren/deaktivieren
    if (animationToggle) {
        animationToggle.addEventListener('change', () => {
            const isChecked = animationToggle.checked;
            document.body.style.setProperty('--animation-enabled', isChecked ? '1' : '0');
            localStorage.setItem('animations', isChecked);
        });

        // Lade gespeicherte Animation-Einstellung
        const savedAnimations = localStorage.getItem('animations');
        if (savedAnimations === 'false') {
            animationToggle.checked = false;
            document.body.style.setProperty('--animation-enabled', '0');
        }
    }
});

// Footer-Easter Egg
document.addEventListener('DOMContentLoaded', () => {
    const footerHexagon = document.querySelector('.footer-hexagon');
    if (footerHexagon) {
        let clickCount = 0;
        footerHexagon.addEventListener('click', () => {
            clickCount++;
            if (clickCount >= 5) {
                alert('🐝 DevHive Easter Egg: 5x auf das Hexagon im Footer geklickt! 🎉');
                clickCount = 0;
            }
        });
    }

    // Burger-Menü-Logik (nur für Mobil)
    const burgerMenu = document.querySelector('.burger-menu');
    const navbar = document.querySelector('.navbar');
    if (burgerMenu && navbar) {
        burgerMenu.addEventListener('click', () => {
            const isExpanded = burgerMenu.getAttribute('aria-expanded') === 'true';
            burgerMenu.setAttribute('aria-expanded', !isExpanded);
            navbar.classList.toggle('active');
        });

        // Schließe das Menü, wenn auf einen Link geklickt wird
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

    // Hexagon-Animation nach dem Laden entfernen
    const hexagonAnimation = document.querySelector('.hexagon-animation');
    if (hexagonAnimation) {
        setTimeout(() => {
            hexagonAnimation.style.display = 'none';
        }, 1500);
    }

    // Timelines laden
    if (document.getElementById('lernreise-timeline')) {
        loadTechnischeTimeline();
    }
    if (document.getElementById('historisch-timeline')) {
        loadHistorischeTimeline();
    }

    // Klick auf Cards (Startseite)
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('click', () => {
            const link = card.querySelector('a');
            if (link) {
                window.location.href = link.href;
            }
        });
    });
});