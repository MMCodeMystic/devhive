// Burger-Menü-Toggle
document.addEventListener('DOMContentLoaded', () => {
    const burgerMenu = document.querySelector('.burger-menu');
    const navbar = document.querySelector('.navbar');

    if (burgerMenu && navbar) {
        burgerMenu.addEventListener('click', () => {
            const isExpanded = burgerMenu.getAttribute('aria-expanded') === 'true';
            burgerMenu.setAttribute('aria-expanded', !isExpanded);
            navbar.classList.toggle('active');
        });
    }

    // Schließe das Menü, wenn auf einen Link geklickt wird (für Mobil)
    const navLinks = document.querySelectorAll('.nav-links a');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                burgerMenu.setAttribute('aria-expanded', 'false');
                navbar.classList.remove('active');
            }
        });
    });
});

// Dark/Bright Mode Toggle (Platzhalter für späteren Schalter)
const body = document.body;
// Beispiel: Toggle per Button (später in Navbar einbauen)
// document.getElementById('theme-toggle').addEventListener('click', () => {
//     body.classList.toggle('bright-mode');
// });

// Sprachwechsel (UI nur)
const langButtons = document.querySelectorAll('.lang-btn');
langButtons.forEach(button => {
    button.addEventListener('click', () => {
        langButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
        // Hier später Inhaltsanpassung einbauen
    });
});

// Zufälliger Platonischer Körper (1 von 5)
const koerper = ['tetraeder', 'wuerfel', 'oktaeder', 'dodekaeder', 'ikosaeder'];
const zufaelligerKoerper = koerper[Math.floor(Math.random() * koerper.length)];
const wireframeContainer = document.querySelector('.wireframe-container');
if (wireframeContainer) {
    const koerperElement = document.createElement('div');
    koerperElement.className = `platonische-koerper ${zufaelligerKoerper}`;
    // Erstelle die benötigten <div>-Elemente für den Körper
    const anzahlFlaechen = {
        tetraeder: 4,
        wuerfel: 6,
        oktaeder: 8,
        dodekaeder: 5, // Vereinfacht
        ikosaeder: 10 // Vereinfacht
    };
    for (let i = 0; i < anzahlFlaechen[zufaelligerKoerper]; i++) {
        const flaeche = document.createElement('div');
        koerperElement.appendChild(flaeche);
    }
    wireframeContainer.appendChild(koerperElement);
}