document.addEventListener('DOMContentLoaded', () => {
    const togglePasswords = document.querySelectorAll('.toggle_password');
    console.log('Toggle Passwords:', togglePasswords);
    togglePasswords.forEach(togglePassword => {
        togglePassword.addEventListener('click', () => {
            const passwordField = document.getElementById(togglePassword.dataset.target)
            if (passwordField) {
                const eyeIcon = togglePassword.querySelector('[data-lucide="eye"]');
                const eyeOffIcon = togglePassword.querySelector('[data-lucide="eye-off"]');

                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    if (eyeIcon) eyeIcon.classList.add('hidden');
                    if (eyeOffIcon) eyeOffIcon.classList.remove('hidden');
                } else {
                    passwordField.type = 'password';
                    if (eyeIcon) eyeIcon.classList.remove('hidden');
                    if (eyeOffIcon) eyeOffIcon.classList.add('hidden');
                }
            }

        });
    })
});
