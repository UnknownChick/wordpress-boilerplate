/**
 * Progressive-enhancement AJAX submission for the contact form
 * (see views/pages/contact.twig).
 *
 * POSTs to admin-ajax.php using the URL/nonce localized by
 * Theme\Services\AssetManager as `window.themeAjax`, and is handled
 * server-side by Theme\Ajax\ContactFormAjax.
 */
export function contactForm() {
    const form = document.querySelector('[data-contact-form]');

    if (!form) return;

    const feedback = form.querySelector('[data-contact-form-feedback]');
    const submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!window.themeAjax) {
            setFeedback('Configuration manquante, veuillez réessayer plus tard.', 'error');
            return;
        }

        setFeedback('', null);
        submitButton.disabled = true;

        try {
            const formData = new FormData(form);
            formData.append('action', 'contact_form');
            formData.append('nonce', window.themeAjax.nonce);

            const response = await fetch(window.themeAjax.url, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
            });

            const result = await response.json();

            if (!result.success) {
                throw new Error(result.data?.message ?? 'Une erreur est survenue.');
            }

            setFeedback(result.data.message, 'success');
            form.reset();
        } catch (error) {
            setFeedback(error.message, 'error');
        } finally {
            submitButton.disabled = false;
        }
    });

    function setFeedback(message, type) {
        if (!feedback) return;

        feedback.textContent = message;
        feedback.dataset.state = type ?? '';
    }
}
