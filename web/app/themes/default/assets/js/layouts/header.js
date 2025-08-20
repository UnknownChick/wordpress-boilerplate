export function header() {
    const header = document.querySelector('.header');
    if (!header) return;

    const headerToggle = header.querySelector('.header__toggle');
    headerToggle.addEventListener('click', () => {
        header.classList.toggle('header--open');
    });
}
