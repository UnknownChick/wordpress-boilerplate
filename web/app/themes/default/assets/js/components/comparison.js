export default class Comparaison {
    constructor(selector, startPosition = 50) {
        this.container = typeof selector === 'string' ? document.querySelector(selector) : selector;

        if (!this.container) {
            console.error('Comparaison: Element not found for selector:', selector);
            return;
        }

        this.isDragging = false;
        this.currentX = startPosition;
        this.isMobile = window.innerWidth < 768;

        this.init();
        this.bindResizeEvent();
    }

    init() {
        this.createSlider();
        this.bindEvents();
        this.updatePosition(this.currentX);
    }

    createSlider() {
        this.slider = document.createElement('div');
        this.slider.className = 'comparison__cursor';
        this.slider.innerHTML = `
            <div class="comparison__cursor__handle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="7,12 17,7 17,17" fill="currentColor" />
                </svg>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="17,12 7,7 7,17" fill="currentColor" />
                </svg>
            </div>
        `;

        this.container.appendChild(this.slider);
    }

    bindEvents() {
        this.slider.addEventListener('mousedown', this.startDrag.bind(this));
        document.addEventListener('mousemove', this.drag.bind(this));
        document.addEventListener('mouseup', this.stopDrag.bind(this));

        this.slider.addEventListener('touchstart', this.startDrag.bind(this));
        document.addEventListener('touchmove', this.drag.bind(this));
        document.addEventListener('touchend', this.stopDrag.bind(this));

        this.container.addEventListener('selectstart', (e) => e.preventDefault());
    }

    bindResizeEvent() {
        window.addEventListener('resize', () => {
            const wasMobile = this.isMobile;
            this.isMobile = window.innerWidth < 768;

            if (wasMobile !== this.isMobile) {
                this.updatePosition(this.currentX);
            }
        });
    }

    startDrag(e) {
        this.isDragging = true;
        this.slider.style.cursor = 'grabbing';
        e.preventDefault();
    }

    drag(e) {
        if (!this.isDragging) return;

        e.preventDefault();

        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        const rect = this.container.getBoundingClientRect();

        let percentage;

        if (this.isMobile) {
            const y = clientY - rect.top;
            percentage = Math.max(0, Math.min(100, (y / rect.height) * 100));
        } else {
            const x = clientX - rect.left;
            percentage = Math.max(0, Math.min(100, (x / rect.width) * 100));
        }

        this.updatePosition(percentage);
    }

    stopDrag() {
        this.isDragging = false;
        this.container.style.cursor = '';
        this.slider.style.cursor = 'grab';
    }

    updatePosition(percentage) {
        this.currentX = percentage;

        if (this.isMobile) {
            this.slider.style.top = `${percentage}%`;
            this.slider.style.left = '0';
        } else {
            this.slider.style.left = `${percentage}%`;
            this.slider.style.top = '0';
        }

        const beforeImage = this.container.querySelector('.comparison__image--before');
        if (beforeImage) {
            if (this.isMobile) {
                beforeImage.style.clipPath = `inset(0 0 ${100 - percentage}% 0)`;
            } else {
                beforeImage.style.clipPath = `inset(0 ${100 - percentage}% 0 0)`;
            }
        }
    }

    setPosition(percentage) {
        this.updatePosition(Math.max(0, Math.min(100, percentage)));
    }

    getPosition() {
        return this.currentX;
    }
}
