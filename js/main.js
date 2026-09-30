document.addEventListener('DOMContentLoaded', () => {
    // ===== Theme-Toggle + Favicon-Wechsel =====
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const brightModeBtn = document.getElementById('bright-mode-btn');
    const body = document.body;
    const favicon = document.getElementById('favicon');

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

    // ===== Login-Popup =====
    const loginBtn = document.getElementById('login-btn');
    const loginPopup = document.getElementById('login-popup');
    const closeLoginBtn = document.getElementById('close-login');

    if (loginBtn && loginPopup && closeLoginBtn) {
        loginBtn.addEventListener('click', () => {
            loginPopup.classList.add('show');
        });

        closeLoginBtn.addEventListener('click', () => {
            loginPopup.classList.remove('show');
        });
    }

    // ===== Passwort anzeigen/verstecken (Hexagon bleibt erhalten) =====
    const passwordToggle = document.getElementById('password-toggle');
    const passwordInput = document.getElementById('password');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('mousedown', () => {
            passwordInput.type = 'text';
        });

        passwordToggle.addEventListener('mouseup', () => {
            passwordInput.type = 'password';
        });

        // Für Touch-Geräte
        passwordToggle.addEventListener('touchstart', () => {
            passwordInput.type = 'text';
        });

        passwordToggle.addEventListener('touchend', () => {
            passwordInput.type = 'password';
        });
    }

    // ===== Kontakt-Link in Navbar =====
    const navLinks = document.querySelector('.nav-links');
    if (navLinks) {
        const kontaktLink = document.createElement('li');
        kontaktLink.innerHTML = '<a href="kontakt.php">Kontakt</a>';
        navLinks.appendChild(kontaktLink);
    }

    // ===== Hexagon-Animation → Footer =====
    const hexagonAnimation = document.querySelector('.hexagon-animation');
    const footerHexagonContainer = document.querySelector('.footer-hexagon-container');
    if (hexagonAnimation && footerHexagonContainer) {
        setTimeout(() => {
            hexagonAnimation.style.display = 'none';
            footerHexagonContainer.style.display = 'flex';
        }, 1500);
    }

    // ===== CTA-Popup =====
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

    // ===== Footer-Easter Egg =====
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

    // ===== Burger-Menü =====
    const burgerMenu = document.querySelector('.burger-menu');
    const navbar = document.querySelector('.navbar');

    if (burgerMenu && navbar) {
        burgerMenu.addEventListener('click', () => {
            const isExpanded = burgerMenu.getAttribute('aria-expanded') === 'true';
            burgerMenu.setAttribute('aria-expanded', !isExpanded);
            navbar.classList.toggle('active');
        });

        // Schließe das Menü, wenn auf einen Link geklickt wird
        const navLinksAll = document.querySelectorAll('.nav-links a');
        navLinksAll.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    burgerMenu.setAttribute('aria-expanded', 'false');
                    navbar.classList.remove('active');
                }
            });
        });
    }

    // ===== Autofit für Textarea =====
    const textarea = document.getElementById('nachricht');
    if (textarea) {
        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        });
    }

    // ===== Kachel-Hintergrund (zufällig pro Seite) =====
    const isBrightMode = body.classList.contains('bright-mode');
    const kachelPrefix = isBrightMode ? 'kachel_bright_' : 'kachel_dark_';
    const kachelCount = 5;
    const randomKachel = Math.floor(Math.random() * kachelCount) + 1;

    const style = document.createElement('style');
    /*
    style.innerHTML = `
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;

            background:
                linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.1)),
                url('assets/images/${kachelPrefix}${randomKachel.toString().padStart(2, '0')}.png') repeat;
            background-size: 200px 200px;
            z-index: -1;
            pointer-events: none;
        }
    `;
    */
    document.head.appendChild(style);

    // ===== Skripte dynamisch laden =====
    if (document.getElementById('lernreise-timeline')) {
        const script = document.createElement('script');
        script.src = 'js/timeline.js';
        document.body.appendChild(script);
    }
    if (document.getElementById('historisch-timeline')) {
        const script = document.createElement('script');
        script.src = 'js/timeline.js';
        document.body.appendChild(script);
    }
    if (document.getElementById('login-popup')) {
        const script = document.createElement('script');
        script.src = 'js/login.js';
        document.body.appendChild(script);
    }
});