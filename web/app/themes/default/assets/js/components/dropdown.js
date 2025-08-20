export default class Dropdown {
	constructor() {
		this.dropdownGroups = [];

		const dropdownElements = document.querySelectorAll('.dropdown');
		if (dropdownElements.length === 0) {
			console.warn('No dropdown elements found.');
			return;
		}

		dropdownElements.forEach(dropdownElement => {
			const button = dropdownElement.querySelector('.dropdown__toggle');
			const content = dropdownElement.querySelector('.dropdown__content');

			if (!button || !content) {
				console.warn('Dropdown component is missing essential.dropdown__toggle or.dropdown__content elements.', dropdownElement);
				return;
			}

			const dropdownGroup = {
				button,
				content,
				isOpen: false
			};

			this.dropdownGroups.push(dropdownGroup);

			button.addEventListener('click', () => {
				this.toggleDropdown(dropdownGroup);
			});

			document.addEventListener('click', (event) => {
				if (!dropdownElement.contains(event.target)) {
					this.closeDropdown(dropdownGroup);
				}
			});
		});
	}

	toggleDropdown(dropdownGroup) {
		if (dropdownGroup.isOpen) {
			this.closeDropdown(dropdownGroup);
		} else {
			this.openDropdown(dropdownGroup);
		}
	}

	openDropdown(dropdownGroup) {
		this.dropdownGroups.forEach(group => {
			if (group !== dropdownGroup && group.isOpen) {
				this.closeDropdown(group);
			}
		});
		dropdownGroup.isOpen = true;
		dropdownGroup.button.closest('.dropdown').classList.add('dropdown--open');
	}

	closeDropdown(dropdownGroup) {
		dropdownGroup.isOpen = false;
		dropdownGroup.button.closest('.dropdown').classList.remove('dropdown--open');
	}
}
