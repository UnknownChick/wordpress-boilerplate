export function header() {
	const header = document.querySelector('.header');
	if (!header) return;

	const headerToggle = header.querySelector('.header__toggle');
	const headerNav = header.querySelector('.header__menu');
	const overlay = header.querySelector('.header__overlay');

	if (!headerToggle || !headerNav) return;

	const MD_BREAKPOINT = 1024;

	function isMobile() {
		return window.innerWidth < MD_BREAKPOINT;
	}

	function openMenu() {
		header.classList.add('header--open');
		headerToggle.setAttribute('aria-expanded', 'true');
		headerToggle.setAttribute('aria-label', 'Fermer le menu');
		headerNav.removeAttribute('inert');
		document.body.style.overflow = 'hidden';

		// Move focus to first focusable item
		const firstFocusable = headerNav.querySelector('a, button');
		if (firstFocusable) firstFocusable.focus();
	}

	function closeMenu() {
		header.classList.remove('header--open');
		headerToggle.setAttribute('aria-expanded', 'false');
		headerToggle.setAttribute('aria-label', 'Ouvrir le menu');
		document.body.style.overflow = '';

		if (isMobile()) {
			headerNav.setAttribute('inert', '');
		}
	}

	function toggleMenu() {
		if (header.classList.contains('header--open')) {
			closeMenu();
			headerToggle.focus();
		} else {
			openMenu();
		}
	}

	headerToggle.addEventListener('click', toggleMenu);

	// Close on Escape key
	document.addEventListener('keydown', (e) => {
		if (e.key === 'Escape' && header.classList.contains('header--open')) {
			closeMenu();
			headerToggle.focus();
		}
	});

	// Close on overlay click
	if (overlay) {
		overlay.addEventListener('click', () => {
			closeMenu();
			headerToggle.focus();
		});
	}

	// Close when a nav link is clicked (on mobile, navigation occurs)
	headerNav.querySelectorAll('a').forEach((link) => {
		link.addEventListener('click', () => {
			if (isMobile()) closeMenu();
		});
	});

	// Handle viewport resize: sync inert state and close open menu
	function handleResize() {
		if (!isMobile()) {
			headerNav.removeAttribute('inert');
			header.classList.remove('header--open');
			headerToggle.setAttribute('aria-expanded', 'false');
			headerToggle.setAttribute('aria-label', 'Ouvrir le menu');
			document.body.style.overflow = '';
		} else if (!header.classList.contains('header--open')) {
			headerNav.setAttribute('inert', '');
		}
	}

	// Debounce resize handler
	let resizeTimer;
	window.addEventListener('resize', () => {
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(handleResize, 100);
	});

	// Set initial state
	handleResize();
}
