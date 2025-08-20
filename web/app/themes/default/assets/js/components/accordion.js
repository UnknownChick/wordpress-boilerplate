export default class Accordion {
	static instance = null;
	static accordions = [];

	constructor() {
		if (Accordion.instance) {
			return Accordion.instance;
		}
		Accordion.instance = this;
		this.accordions = [];
		this.init();
	}

	init() {
		const accordions = document.querySelectorAll('.accordion');
		Accordion.accordions = [];
		accordions.forEach((el) => {
			const acc = {
				el,
				summary: el.querySelector('.accordion__title'),
				content: el.querySelector('.accordion__content'),
				animation: null,
				isClosing: false,
				isExpanding: false
			};
			acc.summary.addEventListener('click', (e) => this.onClick(e, acc));
			Accordion.accordions.push(acc);
		});
	}

	onClick(e, acc) {
		e.preventDefault();
		acc.el.style.overflow = 'hidden';
		if (acc.isClosing || !acc.el.open) {
			this.open(acc);
		} else if (acc.isExpanding || acc.el.open) {
			this.shrink(acc);
		}
	}

	shrink(acc) {
		acc.isClosing = true;
		const startHeight = `${acc.el.offsetHeight}px`;
		const endHeight = `${acc.summary.offsetHeight}px`;

		if (acc.animation) {
			acc.animation.cancel();
		}

		acc.animation = acc.el.animate({
			height: [startHeight, endHeight]
		}, {
			duration: 400,
			easing: 'ease-out'
		});

		acc.animation.onfinish = () => this.onAnimationFinish(acc, false);
		acc.animation.oncancel = () => acc.isClosing = false;
	}

	open(acc) {
		const groupName = acc.el.getAttribute('name');
		if (groupName) {
			Accordion.accordions.forEach(other => {
				if (
					other !== acc &&
					other.el.getAttribute('name') === groupName &&
					other.el.open
				) {
					this.shrink(other);
				}
			});
		}
		acc.el.style.height = `${acc.el.offsetHeight}px`;
		acc.el.open = true;
		window.requestAnimationFrame(() => this.expand(acc));
	}

	expand(acc) {
		acc.isExpanding = true;
		const startHeight = `${acc.el.offsetHeight}px`;
		const endHeight = `${acc.summary.offsetHeight + acc.content.offsetHeight}px`;

		if (acc.animation) {
			acc.animation.cancel();
		}

		acc.animation = acc.el.animate({
			height: [startHeight, endHeight]
		}, {
			duration: 400,
			easing: 'ease-out'
		});
		acc.animation.onfinish = () => this.onAnimationFinish(acc, true);
		acc.animation.oncancel = () => acc.isExpanding = false;
	}

	onAnimationFinish(acc, open) {
		acc.el.open = open;
		acc.animation = null;
		acc.isClosing = false;
		acc.isExpanding = false;
		acc.el.style.height = acc.el.style.overflow = '';
	}
}