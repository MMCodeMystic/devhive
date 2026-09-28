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
        // Hier die korrekte Kategorie verwenden:
        timelineItem.className = `timeline-item historisch ${item.kategorie}`;  // item.kategorie = "theorie"

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

        // Klick-Effekt
        timelineItem.addEventListener('click', () => {
            timelineItem.classList.toggle('expanded');
        });
    });

    // Filter-Logik (bleibt gleich)
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

// Bild-Klick-Logik für Timelines
document.addEventListener('DOMContentLoaded', () => {
    const timelineImages = document.querySelectorAll('.timeline-bild');
    timelineImages.forEach(img => {
        img.addEventListener('click', (e) => {
            e.stopPropagation();
            const timelineItem = img.closest('.timeline-item');
            timelineItem.classList.toggle('expanded');
        });
    });

    // Schließe alle anderen Timeline-Items, wenn eines geöffnet wird
    const timelineItems = document.querySelectorAll('.timeline-item');
    timelineItems.forEach(item => {
        item.addEventListener('click', (e) => {
            timelineItems.forEach(otherItem => {
                if (otherItem !== item) {
                    otherItem.classList.remove('expanded');
                }
            });
        });
    });
});