// Escape HTML für sichere Anzeige von Code
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Syntax-Highlighting für Code (einfach, ohne externe Bibliotheken)
function highlightCode(code) {
    // HTML-Tags hervorheben
    let highlighted = code.replace(/&lt;(\/?)(\w+)([^&]*)&gt;/g, '<span class="tag">&lt;$1$2$3&gt;</span>');

    // PHP-Tags hervorheben
    highlighted = highlighted.replace(/(&lt;\?php|php\?&gt;|\?&gt;)/g, '<span class="php">$1</span>');

    // JavaScript/JSON hervorheben
    highlighted = highlighted.replace(/(\bfunction\b|\blet\b|\bconst\b|\bvar\b|\bif\b|\belse\b|\bfor\b|\bwhile\b)/g, '<span class="js">$1</span>');

    // Strings hervorheben
    highlighted = highlighted.replace(/(".*?"|'.*?')/g, '<span class="str">$1</span>');

    // Kommentare hervorheben
    highlighted = highlighted.replace(/(\/\/.*)/g, '<span class="comment">$1</span>');

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
    });

    // Hover-Effekt für Timeline-Items
    container.querySelectorAll('.timeline-item').forEach(item => {
        const content = item.querySelector('.timeline-content');
        if (content) {
            item.addEventListener('mouseenter', () => {
                content.style.maxHeight = '1000px';
            });
            item.addEventListener('mouseleave', () => {
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
    });

    // Filter-Logik für historische Timeline
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

    // Hover-Effekt für Timeline-Items
    container.querySelectorAll('.timeline-item').forEach(item => {
        const content = item.querySelector('.timeline-content');
        if (content) {
            item.addEventListener('mouseenter', () => {
                content.style.maxHeight = '1000px';
            });
            item.addEventListener('mouseleave', () => {
                content.style.maxHeight = '150px';
            });
        }
    });
}

// Initialisiere Timelines beim Laden der Seite
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('lernreise-timeline')) {
        loadTechnischeTimeline();
    }
    if (document.getElementById('historisch-timeline')) {
        loadHistorischeTimeline();
    }
});