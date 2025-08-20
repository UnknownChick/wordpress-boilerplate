export default class Drawer {
	constructor(selector, options = {}) {
		this.selector = selector;
		this.validPositions = [
			'top',
			'right',
			'bottom',
			'left',
		];
		this.options = {
			position: 'bottom',
			...options
		};

		if (!this.validPositions.includes(this.options.position)) {
			console.warn(`La position "${this.options.position}" n'est pas valide. Utilisation de "bottom".`);
			this.options.position = 'bottom';
		}

		this.init();
	}

	init() {
		const trigger = document.querySelector(this.selector);

		if (!trigger) {
			console.error(`Le drawer avec le sélecteur "${this.selector}" n'a pas été trouvé.`);
			return;
		}

		trigger.addEventListener('click', () => {
			this.open();
		});
	}

	open(
		position = this.options.position,
	) {
		const drawer = document.createElement('div');
		drawer.className = `drawer drawer--${position}`;
		drawer.setAttribute('aria-hidden', 'false');

		document.body.appendChild(drawer);
	}
}