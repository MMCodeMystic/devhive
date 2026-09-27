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
    let highlighted = code
        .replace(/&lt;(\/?)(\w+)([^&]*)&gt;/g, '<span class="tag">&lt;$1$2$3&gt;</span>')
        .replace(/(&lt;\?php|\?&gt;)/g, '<span class="php">$1</span>')
        .replace(/(\bfunction\b|\blet\b|\bconst\b|\bvar\b|\bif\b|\belse\b|\bfor\b|\bwhile\b)/g, '<span class="js">$1</span>')
        .replace(/(".*?"|'.*?')/g, '<span class="str">$1</span>')
        .replace(/(\/\/.*)/g, '<span class="comment">$1</span>');
    return highlighted;
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
        timelineItem.className = 'timeline-item technisch';

        const codeHighlighted = item.code
            ? `<pre><code>${highlightCode(escapeHtml(item.code))}</code></pre>`
            : '';

        timelineItem.innerHTML = `
            <div class="timeline-header">
                <h3>${item.oberbegriff}</h3>
                <span class="timeline-nr">Tag ${item.nr}</span>
            </div>
            <div class="timeline-content">
                <p><strong>Kurze Details:</strong> ${item.kurze_details}</p>
                <p><strong>Ausführlich:</strong> ${item.ausfuehrliche_details}</p>
                ${codeHighlighted}
            </div>
        `;
        container.appendChild(timelineItem);

        // Hover-Effekt
        const content = timelineItem.querySelector('.timeline-content');
        if (content) {
            timelineItem.addEventListener('mouseenter', () => {
                content.style.maxHeight = '1000px';
            });
            timelineItem.addEventListener('mouseleave', () => {
                content.style.maxHeight = '150px';
            });
        }
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
            ? `<img src="${item.bild_url}" alt="${item.oberbegriff}">`
            : '';

        timelineItem.innerHTML = `
            <div class="timeline-header">
                <h3>${item.oberbegriff}</h3>
                <span class="timeline-nr">${item.nr}</span>
            </div>
            <div class="timeline-content">
                <p><strong>Kurze Details:</strong> ${item.kurze_details}</p>
                <p><strong>Ausführlich:</strong> ${item.ausfuehrliche_details}</p>
                <p><strong>Beteiligte:</strong> ${item.beteiligte}</p>
                <div class="technik-details">
                    <p><strong>Entwicklung:</strong> ${item.technik_entwicklung.lang}</p>
                    <p><strong>Herstellung:</strong> ${item.technik_herstellung.lang}</p>
                </div>
                ${bildHtml}
            </div>
        `;
        container.appendChild(timelineItem);

        // Hover-Effekt
        const content = timelineItem.querySelector('.timeline-content');
        if (content) {
            timelineItem.addEventListener('mouseenter', () => {
                content.style.maxHeight = '1000px';
            });
            timelineItem.addEventListener('mouseleave', () => {
                content.style.maxHeight = '150px';
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

// Burger-Menü-Toggle + Easter Egg
document.addEventListener('DOMContentLoaded', () => {
    const burgerMenu = document.querySelector('.burger-menu');
    const navbar = document.querySelector('.navbar');
    const hexagonAnimation = document.querySelector('.hexagon-animation');
    const burgerHexagon = document.querySelector('.burger-menu .hexagon');

    // Burger-Menü-Logik
    if (burgerMenu && navbar) {
        let clickCount = 0;

        burgerMenu.addEventListener('click', (e) => {
            // Easter Egg: 5x Klick auf Hexagon
            if (e.target === burgerHexagon || e.target === burgerMenu.querySelector('.hexagon')) {
                clickCount++;
                if (clickCount >= 5) {
                    alert('🐝 DevHive Easter Egg: 5x geklickt! 🎉');
                    clickCount = 0;
                }
                return; // Verhindere, dass das Menü geöffnet wird
            }

            // Normales Menü-Toggle
            const isExpanded = burgerMenu.getAttribute('aria-expanded') === 'true';
            burgerMenu.setAttribute('aria-expanded', !isExpanded);
            navbar.classList.toggle('active');
        });

        // Reset Counter, wenn ein Menüpunkt gewählt wird
        const navLinks = document.querySelectorAll('.nav-links a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                clickCount = 0;
                if (window.innerWidth <= 768) {
                    burgerMenu.setAttribute('aria-expanded', 'false');
                    navbar.classList.remove('active');
                }
            });
        });
    }

    // Hexagon-Animation nach dem Laden entfernen
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

    // Platonische Körper generieren
    const koerper = ['tetraeder', 'wuerfel', 'oktaeder', 'dodekaeder', 'ikosaeder'];
    const zufaelligerKoerper = koerper[Math.floor(Math.random() * koerper.length)];
    const wireframeContainer = document.querySelector('.wireframe-container');
    if (wireframeContainer) {
        const koerperElement = document.createElement('div');
        koerperElement.className = `platonische-koerper ${zufaelligerKoerper}`;
        const anzahlFlaechen = {
            tetraeder: 4,
            wuerfel: 6,
            oktaeder: 8,
            dodekaeder: 5,
            ikosaeder: 10
        };
        for (let i = 0; i < anzahlFlaechen[zufaelligerKoerper]; i++) {
            const flaeche = document.createElement('div');
            koerperElement.appendChild(flaeche);
        }
        wireframeContainer.appendChild(koerperElement);
    }
});