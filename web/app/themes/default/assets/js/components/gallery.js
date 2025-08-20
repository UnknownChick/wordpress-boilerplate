export default class Gallery {
  /**
   * @param {string|Element} selector - CSS selector or DOM element
   * @param {Object} options
   * @param {number} [options.speed=1] - Drag speed multiplier
   * @param {string} [options.type='grid'] - 'grid' or 'masonry'
   * @param {boolean} [options.vertical=false] - Enable vertical scroll
   */
  constructor(selector, {speed = 1, type = 'grid', vertical = false} = {}) {
    this.gallery = typeof selector === 'string' ? document.querySelector(selector) : selector;
    if (!this.gallery) throw new Error('Gallery element not found');
    this.speed = speed;
    this.vertical = vertical;
    this.type = type === 'masonry' ? 'masonry' : 'grid';
    this.isDown = false;
    this.startX = 0;
    this.startY = 0;
    this.scrollLeft = 0;
    this.scrollTop = 0;
    this._init();
  }

  _init() {
    this.gallery.classList.add(`gallery--${this.type}`);
    this.gallery.addEventListener('mousedown', this._onMouseDown);
    this.gallery.addEventListener('mouseleave', this._onMouseLeave);
    this.gallery.addEventListener('mouseup', this._onMouseUp);
    this.gallery.addEventListener('mousemove', this._onMouseMove);
    // Touch events
    this.gallery.addEventListener('touchstart', this._onTouchStart, {passive: false});
    this.gallery.addEventListener('touchend', this._onTouchEnd);
    this.gallery.addEventListener('touchmove', this._onTouchMove, {passive: false});
  }

  _onMouseDown = (e) => {
    this.isDown = true;
    this.gallery.classList.add('dragging');
    this.startX = e.pageX - this.gallery.offsetLeft;
    this.startY = e.pageY - this.gallery.offsetTop;
    this.scrollLeft = this.gallery.scrollLeft;
    this.scrollTop = this.gallery.scrollTop;
  };

  _onTouchStart = (e) => {
    if (e.touches.length !== 1) return;
    this.isDown = true;
    this.gallery.classList.add('dragging');
    const touch = e.touches[0];
    this.startX = touch.pageX - this.gallery.offsetLeft;
    this.startY = touch.pageY - this.gallery.offsetTop;
    this.scrollLeft = this.gallery.scrollLeft;
    this.scrollTop = this.gallery.scrollTop;
  };

  _onMouseLeave = () => {
    this.isDown = false;
    this.gallery.classList.remove('dragging');
  };

  _onTouchEnd = () => {
    this.isDown = false;
    this.gallery.classList.remove('dragging');
  };

  _onMouseUp = () => {
    this.isDown = false;
    this.gallery.classList.remove('dragging');
  };

  _onMouseMove = (e) => {
    if (!this.isDown) return;
    e.preventDefault();
    if (this.vertical) {
      const y = e.pageY - this.gallery.offsetTop;
      const walkY = (y - this.startY) * this.speed;
      this.gallery.scrollTop = this.scrollTop - walkY;
    } else {
      const x = e.pageX - this.gallery.offsetLeft;
      const walkX = (x - this.startX) * this.speed;
      this.gallery.scrollLeft = this.scrollLeft - walkX;
    }
  };

  _onTouchMove = (e) => {
    if (!this.isDown || e.touches.length !== 1) return;
    e.preventDefault();
    const touch = e.touches[0];
    if (this.vertical) {
      const y = touch.pageY - this.gallery.offsetTop;
      const walkY = (y - this.startY) * this.speed;
      this.gallery.scrollTop = this.scrollTop - walkY;
    } else {
      const x = touch.pageX - this.gallery.offsetLeft;
      const walkX = (x - this.startX) * this.speed;
      this.gallery.scrollLeft = this.scrollLeft - walkX;
    }
  };
}
