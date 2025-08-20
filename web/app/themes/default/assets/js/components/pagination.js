export default class Pagination {
	// Static method to initialize pagination components based on a selector
	static initialize(options = {}) {
		const defaults = {
			selector: '.pagination', // Default selector
		};

		// Merge user options with defaults
		const config = { ...defaults, ...options };

		// Find elements using the specified or default selector
		const paginationElements = document.querySelectorAll(config.selector);

		if (paginationElements.length === 0) {
			console.warn(`Pagination: No elements found matching selector "${config.selector}"`);
			return []; // Return empty array if no elements found
		}

		// Create a new Pagination instance for each found element
		const instances = [];
		paginationElements.forEach(el => {
			instances.push(new Pagination(el, config)); // Pass the element and the config
		});
		return instances; // Return the created instances
	}

	// Constructor now accepts the element and the configuration object
	constructor(containerElement, config = {}) {
		this.container = containerElement;
		this.config = config; // Store the configuration

		if (!this.container) {
			// This check might be less necessary if initialize handles querying,
			// but good for robustness if constructor is called directly.
			console.error("Pagination: Invalid container element provided.");
			return;
		}

		// Query elements relative to the specific container
		this.list = this.container.querySelector('.pagination__list');
		this.items = this.container.querySelectorAll('.pagination__item');
		this.paginationLinks = this.container.querySelectorAll('.pagination__link:not(.pagination__link--prev):not(.pagination__link--next)');
		this.prevLinkItem = this.container.querySelector('.pagination__item:has(.pagination__link--prev)');
		this.nextLinkItem = this.container.querySelector('.pagination__item:has(.pagination__link--next)');
		this.prevLink = this.prevLinkItem?.querySelector('.pagination__link--prev');
		this.nextLink = this.nextLinkItem?.querySelector('.pagination__link--next');

		// Check if essential elements were found within this container
		if (!this.list || !this.paginationLinks) {
			console.warn("Pagination: Could not find essential elements (.pagination__list, .pagination__link) within:", this.container);
		}

		this.bindEvents();
		this.init();
	}
	
	updateActiveState(targetLink) {
		if (!targetLink) return;

		// Remove active state from all items within this specific container
		this.container.querySelectorAll('.pagination__item--active').forEach(item => {
			item.classList.remove('pagination__item--active');
			item.querySelector('.pagination__link')?.removeAttribute('aria-current');
		});

		// Add active state to the target link's parent item
		const parentItem = targetLink.closest('.pagination__item');
		if (parentItem) {
			parentItem.classList.add('pagination__item--active');
			targetLink.setAttribute('aria-current', 'page');
		}

		this.updatePrevNextState();
	}


	// ... updatePrevNextState method remains the same ...
	updatePrevNextState() {
		const activeItem = this.container.querySelector('.pagination__item--active');
		// Find the first *numbered* page item within this container
		const firstPageItem = this.container.querySelector('.pagination__item:not(:has(.pagination__link--prev)):not(:has(.pagination__ellipsis))');
		// Find the last *numbered* page item within this container
		const numberedItems = Array.from(this.container.querySelectorAll('.pagination__item:has(.pagination__link:not(.pagination__link--prev):not(.pagination__link--next))'));
		const lastPageItem = numberedItems[numberedItems.length - 1];


		if (this.prevLinkItem && this.prevLink) {
			if (activeItem === firstPageItem) {
				this.prevLinkItem.classList.add('pagination__item--disabled');
				this.prevLink.setAttribute('aria-disabled', 'true');
			} else {
				this.prevLinkItem.classList.remove('pagination__item--disabled');
				this.prevLink.removeAttribute('aria-disabled');
			}
		}

		if (this.nextLinkItem && this.nextLink) {
			if (activeItem === lastPageItem) {
				this.nextLinkItem.classList.add('pagination__item--disabled');
				this.nextLink.setAttribute('aria-disabled', 'true');
			} else {
				this.nextLinkItem.classList.remove('pagination__item--disabled');
				this.nextLink.removeAttribute('aria-disabled');
			}
		}
	}


	handlePageClick(event) {
		event.preventDefault();
		const link = event.currentTarget;
		const parentItem = link.closest('.pagination__item');
		if (!parentItem || parentItem.classList.contains('pagination__item--active') || parentItem.classList.contains('pagination__item--disabled')) {
			return;
		}
		this.updateActiveState(link);
	}


	handlePrevClick(event) {
		event.preventDefault();
		if (this.prevLinkItem?.classList.contains('pagination__item--disabled')) return;

		const activeItem = this.container.querySelector('.pagination__item--active');
		let prevItem = activeItem?.previousElementSibling;

		// Skip ellipsis if moving backwards onto one
		if (prevItem && prevItem.querySelector('.pagination__ellipsis')) {
			prevItem = prevItem.previousElementSibling;
		}

		const prevLink = prevItem?.querySelector('.pagination__link:not(.pagination__link--prev)');
		if (prevLink) {
			this.updateActiveState(prevLink);
		}
	}


	// ... handleNextClick method remains the same ...
	handleNextClick(event) {
		event.preventDefault();
		if (this.nextLinkItem?.classList.contains('pagination__item--disabled')) return;

		const activeItem = this.container.querySelector('.pagination__item--active');
		let nextItem = activeItem?.nextElementSibling;

		// Skip ellipsis if moving forwards onto one
		if (nextItem && nextItem.querySelector('.pagination__ellipsis')) {
			nextItem = nextItem.nextElementSibling;
		}

		const nextLink = nextItem?.querySelector('.pagination__link:not(.pagination__link--next)');
		if (nextLink) {
			this.updateActiveState(nextLink);
		}
	}


	// ... bindEvents method remains the same ...
	bindEvents() {
		// Ensure links exist before adding listeners
		this.paginationLinks?.forEach(link => {
			link.addEventListener('click', this.handlePageClick.bind(this));
		});

		if (this.prevLink) {
			this.prevLink.addEventListener('click', this.handlePrevClick.bind(this));
		}

		if (this.nextLink) {
			this.nextLink.addEventListener('click', this.handleNextClick.bind(this));
		}
	}


	init() {
		// Set initial state for prev/next buttons within this container
		const initialActiveLink = this.container.querySelector('.pagination__item--active .pagination__link');
		if (initialActiveLink) {
			// Call updatePrevNextState directly as updateActiveState would re-apply active class unnecessarily
			this.updatePrevNextState();
		} else {
			// Check if prevLinkItem exists before trying to add class/attribute
			if (this.prevLinkItem && this.prevLink) {
				this.prevLinkItem.classList.add('pagination__item--disabled');
				this.prevLink.setAttribute('aria-disabled', 'true');
			}
		}
	}

}