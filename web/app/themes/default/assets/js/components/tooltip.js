export default class Tooltip {
	constructor(selector, options = {}) {
		this.selector = selector;
		this.validPositions = [
			'top',
			'right',
			'bottom',
			'left',
		];
		this.options = {
			message: 'Message par defaut',
			position: 'top',
			...options
		}

		if (!this.validPositions.includes(this.options.position)) {
			console.warn(`La position "${this.options.position}" n'est pas valide. Utilisation de "top".`);
			this.options.position = 'top';
		}

		this.init();
	}

	init() {
		const trigger = document.querySelector(this.selector);

		if (!trigger) {
			console.warn(`Le trigger ${this.selector} n'existe pas`);
			return;
		}

		trigger.addEventListener('mouseover', () => {
			this.show();
		});

		trigger.addEventListener('mouseout', () => {
			this.hide();
		});
	}

	show(
		message = this.options.message,
		position = this.options.position
	) {
		const tooltip = document.createElement('div');
		tooltip.className = `tooltip tooltip--${position}`;
		tooltip.innerHTML = message;

		// Tooltip insertion
		document.body.appendChild(tooltip);

		// Tooltip position
		const trigger = document.querySelector(this.selector);
		const triggerRect = trigger.getBoundingClientRect();
		const tooltipRect = tooltip.getBoundingClientRect();

		let left = 0;
		let top = 0;
		// Ajout de la prise en compte du scroll
		const scrollX = window.scrollX;
		const scrollY = window.scrollY;

		switch (position) {
			case 'top':
				left = scrollX + triggerRect.left + (triggerRect.width - tooltipRect.width) / 2;
				top = scrollY + triggerRect.top - tooltipRect.height - 10;
				break;
			case 'right':
				left = scrollX + triggerRect.right + 10;
				top = scrollY + triggerRect.top + (triggerRect.height - tooltipRect.height) / 2;
				break;
			case 'bottom':
				left = scrollX + triggerRect.left + (triggerRect.width - tooltipRect.width) / 2;
				top = scrollY + triggerRect.bottom + 10;
				break;
			case 'left':
				left = scrollX + triggerRect.left - tooltipRect.width - 10;
				top = scrollY + triggerRect.top + (triggerRect.height - tooltipRect.height) / 2;
				break;
		}

		tooltip.style.left = `${left}px`;
		tooltip.style.top = `${top}px`;
	}

	hide() {
		const tooltip = document.querySelector('.tooltip');

		if (tooltip) {
			tooltip.remove();
		}
	}
}