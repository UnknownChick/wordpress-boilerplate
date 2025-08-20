export default class Toast {
	constructor(selector, options = {}) {
		this.selector = selector;
		this.positionMap = {
			'top left': 'tl',
			'top right': 'tr',
			'bottom left': 'bl',
			'bottom right': 'br'
		};
		this.options = {
			duration: 5000,
			message: 'Message par defaut',
			position: 'bottom right',
			showProgress: true,
			title: 'Titre par defaut',
			transitionDuration: 300,
			...options
		};
		this.activeToasts = [];

		if (!this.positionMap[this.options.position]) {
			console.warn(`Toast: Position "${this.options.position}" non valide. Utilisation de la position par défaut 'bottom right'.`);
		}

		this.init();
	}

	init() {
		const triggers = document.querySelectorAll(this.selector);

		if (!triggers.length) {
			console.warn(`Toast: Aucun élément trouvé pour le sélecteur "${this.selector}"`);
			return;
		}

		triggers.forEach(trigger => {
			trigger.addEventListener('click', () => {
				this.show(
					this.options.message,
					this.options.position,
					this.options.showProgress,
					this.options.title
				);
			});
		});
	}

	show(
		message = this.options.message,
		position = this.options.position,
		showProgress = this.options.showProgress,
		title = this.options.title, 
	) {
		const positionClass = this.positionMap[position] || this.positionMap['bottom right'];

		const toastElement = document.createElement('div');
		toastElement.className = `toast toast--${positionClass}`;
		toastElement.setAttribute('role', 'alert');
		toastElement.setAttribute('aria-live', 'assertive');
		toastElement.setAttribute('aria-atomic', 'true');

		let progressBar;
		if (showProgress) {
			progressBar = document.createElement('div');
			progressBar.className = 'toast__progress';
			progressBar.style.animationDuration = `${this.options.duration / 1000}s`;
			toastElement.appendChild(progressBar);
		}

		const content = document.createElement('div');
		content.className = 'toast__content';
		content.innerHTML = message;

		if (title) {
			const titleElement = document.createElement('b');
			titleElement.innerHTML = title;
			content.prepend(titleElement);
		}

		toastElement.appendChild(content);

		document.body.appendChild(toastElement);
		this.activeToasts.push(toastElement);

		toastElement.offsetHeight;

		toastElement.classList.add('toast--active');

		const timeoutId = setTimeout(() => {
			this.close(toastElement);
		}, this.options.duration);

		toastElement.dataset.timeoutId = timeoutId;
		toastElement.dataset.position = positionClass;
	}

	close(toastElement) {
		if (!toastElement || !document.body.contains(toastElement)) return;

		const timeoutId = toastElement.dataset.timeoutId;
		if (timeoutId) {
			clearTimeout(timeoutId);
		}

		const positionClass = toastElement.dataset.position;
		const isLeft = positionClass && (positionClass.includes('l'));
		const closingClass = isLeft ? 'toast--closing-left' : 'toast--closing-right';

		toastElement.classList.remove('toast--active');
		toastElement.classList.add(closingClass);

		toastElement.addEventListener('transitionend', () => {
			if (document.body.contains(toastElement)) {
				document.body.removeChild(toastElement);
			}
			this.activeToasts = this.activeToasts.filter(t => t !== toastElement);
		}, { once: true });

		setTimeout(() => {
			if (document.body.contains(toastElement)) {
				console.warn('Toast: transitionend event did not fire, removing element via fallback timeout.');
				document.body.removeChild(toastElement);
				this.activeToasts = this.activeToasts.filter(t => t !== toastElement);
			}
		}, this.options.transitionDuration + 50);
	}
}