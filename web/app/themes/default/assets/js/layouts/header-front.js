export function headerFront() {
    const headerFront = document.querySelector('.header--front');
    if (!headerFront) return;

    const toggleScrolled = () => {
        headerFront.classList.toggle('header--scrolled', window.scrollY > 0);
    };
    window.addEventListener('scroll', toggleScrolled);
    window.addEventListener('resize', toggleScrolled);
    toggleScrolled();

    const headerToggle = headerFront.querySelector('.header__toggle');
    headerToggle.addEventListener('click', () => {
        headerFront.classList.toggle('header--open');
    });
}
