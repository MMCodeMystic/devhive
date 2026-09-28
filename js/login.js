// Passwort anzeigen/verstecken (nur bei gedrücktem Button)
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('password-toggle');

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
});