export default class Modal {
    constructor() {
        if (Modal.instance) {
            return Modal.instance;
        }
        Modal.instance = this;

        this.modalsTarget = document.querySelectorAll('[data-modal-target]');
        this.modalsClose = document.querySelectorAll('[data-modal-close]');
        this.modalCloseButtons = document.querySelectorAll('.modal__close');
        this.init();
    }

    init() {
        if (!this.modalsTarget || !this.modalsClose) return;

        this.modalsTarget.forEach(modal =>
            modal.addEventListener('click', (e) => this.openModal(e))
        );

        this.modalsClose.forEach(modal =>
            modal.addEventListener('click', (e) => this.closeModal(e))
        );

        this.modalCloseButtons.forEach(button =>
            button.addEventListener('click', (e) => this.closeParentModal(e))
        );
    }

    openModal(e) {
        e.preventDefault();
        const modalId = e.target.dataset.modalTarget;
        const modalEl = document.querySelector(`#${modalId}`);
        modalEl.showModal();
        document.body.style.overflow = 'hidden';
    }

    closeModal(e) {
        e.preventDefault();
        const modalId = e.target.dataset.modalClose;
        const modalEl = document.querySelector(`#${modalId}`);
        modalEl.close();
        document.body.style.overflow = 'auto';
    }

    closeParentModal(e) {
        e.preventDefault();
        const modalEl = e.target.closest('dialog');
        if (modalEl) {
            modalEl.close();
            document.body.style.overflow = 'auto';
        }
    }
}
