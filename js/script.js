// Burger-Menü für Mobil
const burgerMenu = document.querySelector('.burger-menu');
const navbar = document.querySelector('.navbar');

burgerMenu.addEventListener('click', () => {
    navbar.classList.toggle('active');
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