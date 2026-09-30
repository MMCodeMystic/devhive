// ===== Main JavaScript für DevHive =====
document.addEventListener('DOMContentLoaded', () => {
    
    // ===== 1. Theme-Toggle + Favicon-Wechsel =====
    const darkModeBtn = document.getElementById('dark-mode-btn');
    const brightModeBtn = document.getElementById('bright-mode-btn');
    const body = document.body;
    const favicon = document.getElementById('favicon');

    // Lade gespeichertes Theme
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

    // ===== 2. Login-Popup =====
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

        // Close popup when clicking outside
        loginPopup.addEventListener('click', (e) => {
            if (e.target === loginPopup) {
                loginPopup.classList.remove('show');
            }
        });
    }

    // ===== 3. Burger-Menü - KORRIGIERT =====
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

    // ===== 4. Passwort anzeigen/verstecken =====
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

    // ===== 5. Hexagon-Animation -> Footer =====
    const hexagonAnimation = document.querySelector('.hexagon-animation');
    const footerHexagonContainer = document.querySelector('.footer-hexagon-container');
    if (hexagonAnimation && footerHexagonContainer) {
        setTimeout(() => {
            hexagonAnimation.style.display = 'none';
            footerHexagonContainer.style.display = 'flex';
        }, 1500);
    }

    // ===== 6. CTA-Popup =====
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

    // ===== 7. Footer-Easter Egg =====
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

    // ===== 8. Autofit für Textarea =====
    const textarea = document.getElementById('nachricht');
    if (textarea) {
        textarea.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        });
    }

    // ===== 9. Skripte dynamisch laden =====
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
});
