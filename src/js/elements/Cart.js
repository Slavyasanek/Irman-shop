/**
 * WooCommerce AJAX Cart Component
 */

export const CART_EVENTS = {
    UPDATED: 'cart:updated',
    ITEM_ADDED: 'cart:itemAdded',
    ITEM_REMOVED: 'cart:itemRemoved',
    ERROR: 'cart:error'
};

export class CartDrawer {
    /**
     * @param {Object} options Configuration options
     * @param {string} options.drawerId - Drawer container ID
     * @param {string} options.ajaxUrl - WordPress admin-ajax.php URL
     * @param {string} options.nonce - Security nonce
     */
    constructor(options = {}) {
        this.drawer = document.getElementById(options.drawerId || 'cart-drawer');
        if (!this.drawer) return;

        this.ajaxUrl = options.ajaxUrl || '/wp-admin/admin-ajax.php';
        this.nonce = options.nonce || '';
        if (!this.nonce) {
            throw new Error('Cart nonce not defined');
        }
        this.init();
    }

    init() {
        this.bindEvents();
    }

    bindEvents() {
        // Global Click Listener for events inside or outside the cart
        document.addEventListener('click', (e) => {
            const updateBtn = e.target.closest('[data-update]');
            const removeBtn = e.target.closest('[data-cart-remove]');
            const addBtn    = e.target.closest('.add_to_cart_button:not(.product_type_variable)');

            if (updateBtn && this.drawer.contains(updateBtn)) {
                e.preventDefault();
                this.handleQuantityChange(updateBtn);
            } else if (removeBtn && this.drawer.contains(removeBtn)) {
                e.preventDefault();
                this.handleItemRemove(removeBtn);
            } else if (addBtn && !addBtn.closest('form.cart')) {
                e.preventDefault();
                this.handleAddToCartBtn(addBtn);
            }
        });

        // Global Submit Listener for product forms
        document.addEventListener('submit', (e) => {
            const form = e.target.closest('form.cart');
            if (form) {
                e.preventDefault();
                this.handleAddToCartForm(form);
            }
        });
    }

    // ==========================================
    // ACTION HANDLERS
    // ==========================================

    async handleQuantityChange(button) {
        const itemRow = button.closest('.cart-item');
        if (!itemRow) return;

        const cartKey   = itemRow.dataset.key;
        const action    = button.dataset.update;
        const valEl     = itemRow.querySelector('.quant-block__value');
        const currentQty = parseInt(valEl?.dataset.value || '1', 10);

        const newQty = action === 'plus' ? currentQty + 1 : currentQty - 1;

        await this.updateQuantity(cartKey, Math.max(0, newQty));
    }

    async handleItemRemove(button) {
        const cartKey = button.dataset.cartRemove;
        if (cartKey) {
            await this.updateQuantity(cartKey, 0);
        }
    }

    async handleAddToCartForm(form) {
        const formData  = new FormData(form);
        const submitBtn = form.querySelector('[type="submit"]');

        if (!formData.has('add-to-cart')) {
            const productID = submitBtn?.value || form.querySelector('[name="add-to-cart"]')?.value;
            if (productID) formData.append('add-to-cart', productID);
        }

        this.toggleLoading(true);

        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            if (response.ok) {
                await this.fetchCartMarkup();
                this.dispatch(CART_EVENTS.ITEM_ADDED, { form });
            } else {
                throw new Error('Failed to add item');
            }
        } catch (error) {
            console.error('Add to Cart Form Error:', error);
            this.dispatch(CART_EVENTS.ERROR, { error });
        } finally {
            this.toggleLoading(false);
        }
    }

    async handleAddToCartBtn(button) {
        const productId   = button.dataset.productId;
        const quantity    = parseInt(button.dataset.quantity || 1, 10);
        const maxQuantity = parseInt(button.dataset.maxQuantity || -1, 10);
        let currentInCart = parseInt(button.dataset.cartQuantity || 0, 10);

        if (!productId) return;

        // Apply UI Loading state
        button.classList.add('loading');
        button.disabled = true;
        this.toggleLoading(true);

        const formData = new FormData();
        formData.append('action', 'woocommerce_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity', quantity);

        try {
            const response = await fetch(`${this.ajaxUrl}?action=woocommerce_add_to_cart`, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                currentInCart += quantity;
                button.dataset.cartQuantity = currentInCart;

                await this.fetchCartMarkup();
                this.dispatch(CART_EVENTS.ITEM_ADDED, { productId, quantity });

                // Check max quantity limit
                const textSpan = button.querySelector('.btn-text') || button;
                
                if (maxQuantity > 0 && currentInCart >= maxQuantity) {
                    button.classList.add('in-cart');
                    button.disabled = true;
                    textSpan.textContent = 'Вже у кошику';
                } else {
                    textSpan.textContent = 'Додано в кошик';
                    button.classList.add('in-cart');
                    button.disabled = false;
                }
            }
        } catch (error) {
            console.error('Add to Cart Button Error:', error);
            this.dispatch(CART_EVENTS.ERROR, { error });
            button.disabled = false;
        } finally {
            button.classList.remove('loading');
            this.toggleLoading(false);
        }
    }

    async updateQuantity(cartKey, quantity) {
        this.toggleLoading(true);

        const formData = new FormData();
        formData.append('action', 'clean_update_cart_quantity');
        formData.append('nonce', this.nonce);
        formData.append('cart_key', cartKey);
        formData.append('quantity', quantity);

        try {
            const response = await fetch(this.ajaxUrl, {
                method: 'POST',
                body: formData
            });

            const res = await response.json();

            if (res.success && res.data) {
                if (res.data.fragments) {
                    this.applyFragments(res.data.fragments);
                }
                
                // Pass new count to subscriber classes like CartCounter
                this.dispatch(CART_EVENTS.UPDATED, { 
                    cartKey, 
                    quantity,
                    count: res.data.count ?? undefined 
                });

                if (quantity === 0) {
                    this.dispatch(CART_EVENTS.ITEM_REMOVED, { cartKey });
                }
            }
        } catch (error) {
            console.error('Update Quantity Error:', error);
            this.dispatch(CART_EVENTS.ERROR, { error });
        } finally {
            this.toggleLoading(false);
        }
    }

    /**
     * Single Fetch to get full cart markup and count
     */
    async fetchCartMarkup() {
        try {
            const response = await fetch(`${this.ajaxUrl}?action=clean_get_cart_drawer`);
            const res = await response.json();

            if (res.success && res.data) {
                if (res.data.fragments) {
                    this.applyFragments(res.data.fragments);
                }
                
                // Dispatch total count so CartCounter receives it
                this.dispatch(CART_EVENTS.UPDATED, {
                    count: res.data.count ?? undefined
                });
            }
        } catch (error) {
            console.error('Fetch Cart Markup Error:', error);
            this.dispatch(CART_EVENTS.ERROR, { error });
        }
    }

    // ==========================================
    // DOM UPDATES
    // ==========================================

    applyFragments(fragments) {
        Object.keys(fragments).forEach((selector) => {
            const target = this.drawer.querySelector(selector) || document.querySelector(selector);
            if (target) {
                target.outerHTML = fragments[selector];
            }
        });
    }

    toggleLoading(isLoading) {
        const loader = this.drawer.querySelector('.cart-items__loader');
        if (!loader) return;

        loader.classList.toggle('d-none', !isLoading);
        loader.setAttribute('aria-hidden', (!isLoading).toString());
    }

    dispatch(eventName, detail = {}) {
        const event = new CustomEvent(eventName, {
            bubbles: true,
            cancelable: true,
            detail
        });
        this.drawer.dispatchEvent(event);
    }
}