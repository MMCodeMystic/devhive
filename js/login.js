// Passwort anzeigen/verstecken (Hexagon bleibt erhalten)
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');

    if (passwordToggle && passwordInput) {
        // Speichere das ursprüngliche HTML des Buttons
        const originalHTML = passwordToggle.innerHTML;

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
});