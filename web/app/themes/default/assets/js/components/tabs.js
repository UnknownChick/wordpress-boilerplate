export default class Tabs {
	constructor(selector, options = {}) {
		this.tabGroups = [];
		this.saveSelection = options.saveSelection ?? false;

		const tabElements = typeof selector === 'string'
			? document.querySelectorAll(selector)
			: (selector instanceof Element ? [selector] : []);

		if (!tabElements.length) {
			console.warn('Aucun élément tabs trouvé.');
			return;
		}

		tabElements.forEach(tabElement => {
			const nav = tabElement.querySelector('.tabs__nav');
			const content = tabElement.querySelector('.tabs__content');

			if (!nav || !content) {
				console.warn('Le composant Tabs manque des éléments essentiels .tabs__nav ou .tabs__content.', tabElement);
				return;
			}

			const buttons = Array.from(nav.querySelectorAll('.tabs__nav__button'));
			const panels = Array.from(content.querySelectorAll('.tabs__panel'));
			const tabsContainerId = tabElement.id || '';

			if (buttons.length === 0 || panels.length === 0) {
				console.warn('Le composant Tabs manque des éléments .tabs__nav__button ou .tabs__panel.', tabElement);
				return;
			}
			
			buttons.forEach(btn => {
				if (!btn.id) {
					console.warn('Un bouton d\'onglet n\'a pas d\'ID, requis pour la gestion du hash URL.', btn);
				}
			});

			const groupData = {
				element: tabElement,
				nav,
				buttons,
				content,
				panels,
				tabsContainerId,
				activatedByCurrentHash: false
			};
			this.tabGroups.push(groupData);

			this._initSingleTabGroup(groupData);
		});

		if (this.tabGroups.length > 0 && this.saveSelection) {
			this._handleHash(false);
			window.addEventListener('hashchange', () => this._handleHash(true), false);
		} else if (this.tabGroups.length > 0) {
			// Active le premier onglet par défaut si saveSelection est false
			this.tabGroups.forEach(groupData => {
				const preSelectedButton = groupData.buttons.find(btn => btn.getAttribute('aria-selected') === 'true' && btn.id);
				if (preSelectedButton) {
					this.activateTab(preSelectedButton, groupData, false, true);
				} else if (groupData.buttons.length > 0 && groupData.buttons[0].id) {
					this.activateTab(groupData.buttons[0], groupData, false, true);
				}
			});
		}
	}

	_initSingleTabGroup(groupData) {
		groupData.nav.addEventListener('click', (e) => {
			const button = e.target.closest('.tabs__nav__button');
			if (button && button.id) {
				this.activateTab(button, groupData, true, false);
			}
		});
	}

	activateTab(buttonToActivate, groupData, setFocus = true, isHashChange = false) {
		if (!buttonToActivate || !buttonToActivate.id) {
			console.warn('Tentative d\'activation d\'un onglet sans bouton ou ID valide.');
			return;
		}
		const { buttons, panels, tabsContainerId } = groupData;
		const targetPanelId = buttonToActivate.getAttribute('aria-controls');

		buttons.forEach(btn => {
			btn.setAttribute('aria-selected', 'false');
			btn.tabIndex = -1;
		});
		buttonToActivate.setAttribute('aria-selected', 'true');
		buttonToActivate.tabIndex = 0;
		if (setFocus) {
			buttonToActivate.focus();
		}

		panels.forEach(panel => {
			if (panel.id === targetPanelId) {
				panel.removeAttribute('hidden');
			} else {
				panel.setAttribute('hidden', '');
			}
		});

		if (this.saveSelection && !isHashChange) {
			const hashValue = tabsContainerId ? `${tabsContainerId}-${buttonToActivate.id}` : buttonToActivate.id;
			if (window.location.hash !== `#${hashValue}`) {
				history.replaceState(null, '', `#${hashValue}`);
			}
		}
	}

	_handleHash(setFocusOnActivation) {
		const hash = window.location.hash.substring(1);

		this.tabGroups.forEach(group => group.activatedByCurrentHash = false);

		if (hash) {
			for (const groupData of this.tabGroups) {
				const buttonToActivate = groupData.buttons.find(btn => {
					if (!btn.id) return false;
					const expectedHash = groupData.tabsContainerId ? `${groupData.tabsContainerId}-${btn.id}` : btn.id;
					return hash === expectedHash;
				});

				if (buttonToActivate) {
					this.activateTab(buttonToActivate, groupData, setFocusOnActivation, true);
					groupData.activatedByCurrentHash = true;
				}
			}
		}

		this.tabGroups.forEach(groupData => {
			if (groupData.activatedByCurrentHash) {
				return;
			}

			const preSelectedButton = groupData.buttons.find(btn => btn.getAttribute('aria-selected') === 'true' && btn.id);
			if (preSelectedButton) {
				this.activateTab(preSelectedButton, groupData, false, true);
			} else if (groupData.buttons.length > 0 && groupData.buttons[0].id) {
				this.activateTab(groupData.buttons[0], groupData, false, true);
			} else if (groupData.buttons.length > 0 && !groupData.buttons[0].id) {
                console.warn('Impossible d\'activer l\'onglet par défaut car le premier bouton n\'a pas d\'ID.', groupData.element);
            }
		});
	}
}