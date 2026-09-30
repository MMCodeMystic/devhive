// Passwort anzeigen/verstecken + Stärke-Berechnung
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');

    // Passwort-Toggle
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

    // Passwort-Stärke-Berechnung
    if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;
            const result = calculatePasswordStrength(password);
            
            strengthBar.style.setProperty('--strength-width', result.width + '%');
            strengthBar.style.setProperty('--strength-color', result.color);
            strengthText.textContent = 'Passwortstärke: ' + result.text;
        });
    }
});

// Passwort-Stärke berechnen
function calculatePasswordStrength(password) {
    if (!password) {
        return { width: 0, color: '#555', text: 'Kein Passwort' };
    }

    let score = 0;
    const checks = {
        length: password.length >= 12,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^A-Za-z0-9]/.test(password)
    };

    // Punkte vergeben
    score += checks.length ? 20 : 0;
    score += checks.uppercase ? 20 : 0;
    score += checks.lowercase ? 20 : 0;
    score += checks.number ? 20 : 0;
    score += checks.special ? 20 : 0;

    // Score anpassen basierend auf Länge
    if (password.length >= 16) score += 10;
    else if (password.length >= 8) score += 5;

    // Score begrenzen
    score = Math.min(score, 100);

    // Farb- und Text-Zuordnung
    let color, text;
    if (score < 40) {
        color = 'var(--accent-red)';
        text = 'Schwach';
    } else if (score < 70) {
        color = '#FF8C00';
        text = 'Mittel';
    } else if (score < 90) {
        color = '#FFD700';
        text = 'Stark';
    } else {
        color = '#00FF00';
        text = 'Sehr stark';
    }

    return { width: score, color: color, text: text };
}
