import { CART_EVENTS } from "./Cart";

export class CartCounter {
    /**
     * @param {string|HTMLElement} selector - CSS selector or target DOM element
     */
    constructor(selector = '.cart-btn') {
        this.button = typeof selector === 'string' ? document.querySelector(selector) : selector;

        if (!this.button) return;

        this.init();
    }

    init() {
        // Listen globally for cart events dispatched from CartDrawer
        document.addEventListener(CART_EVENTS.UPDATED, (e) => {
            
            if (e.detail && typeof e.detail.count !== 'undefined') {
                this.updateCount(e.detail.count);
            }
        });
    }

    /**
     * Updates the data-cart attribute and accessibility label
     * @param {number|string} count 
     */
    updateCount(count) {
        const parsedCount = parseInt(count, 10) || 0;
        this.button.dataset.cart = parsedCount;
    }
}