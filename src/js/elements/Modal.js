/**
 * Accessible Modal class using event delegation and data attributes.
 *
 * @class Modal
 */
export class Modal {
    /**
     * Creates an instance of Modal.
     * @param {string} [activeClass='active']
     * @memberof Modal
     */
    constructor(activeClass = 'active') {
        this.activeClass = activeClass;
        
        // Track state for accessibility
        this.activeModal = null;
        this.lastFocusedElement = null;

        this.init();
    }

    openModal = (modal, triggerEl = null) => {
        if (!modal) return;

        // Save trigger to restore focus on close
        this.lastFocusedElement = triggerEl || document.activeElement;
        this.activeModal = modal;

        modal.classList.add(this.activeClass);

        // Update ARIA attributes
        modal.setAttribute('aria-hidden', 'false');
        if (this.lastFocusedElement && this.lastFocusedElement.hasAttribute('aria-expanded')) {
            this.lastFocusedElement.setAttribute('aria-expanded', 'true');
        }

        if (typeof disableScroll === 'function') disableScroll();

        // Focus the modal or its first focusable child
        this.setInitialFocus(modal);
    }

    closeModal = (modal = this.activeModal) => {
        if (!modal) return;

        modal.classList.remove(this.activeClass);

        // Update ARIA attributes
        modal.setAttribute('aria-hidden', 'true');

        if (this.lastFocusedElement) {
            if (this.lastFocusedElement.hasAttribute('aria-expanded')) {
                this.lastFocusedElement.setAttribute('aria-expanded', 'false');
            }
            // Return focus to the element that opened the modal
            this.lastFocusedElement.focus();
        }

        if (typeof enableScroll === 'function') enableScroll();

        this.activeModal = null;
        this.lastFocusedElement = null;
    }

    // Move focus inside the modal on open
    setInitialFocus = (modal) => {
        const focusable = this.getFocusableElements(modal);
        if (focusable.length > 0) {
            focusable[0].focus();
        } else {
            // Fallback: make container focusable programmatically
            if (!modal.hasAttribute('tabindex')) {
                modal.setAttribute('tabindex', '-1');
            }
            modal.focus();
        }
    }

    // Get all interactive elements inside a element
    getFocusableElements = (container) => {
        const selectors = [
            'a[href]',
            'button:not([disabled])',
            'textarea:not([disabled])',
            'input:not([disabled])',
            'select:not([disabled])',
            '[tabindex]:not([tabindex="-1"])'
        ];
        return Array.from(container.querySelectorAll(selectors.join(',')))
            .filter(el => el.offsetWidth > 0 || el.offsetHeight > 0);
    }

    // Focus Trap: keeps Tab / Shift+Tab cycling inside the open modal
    handleFocusTrap = (e) => {
        if (!this.activeModal || e.key !== 'Tab') return;

        const focusables = this.getFocusableElements(this.activeModal);
        if (focusables.length === 0) return;

        const firstEl = focusables[0];
        const lastEl = focusables[focusables.length - 1];

        if (e.shiftKey && document.activeElement === firstEl) {
            e.preventDefault();
            lastEl.focus();
        } else if (!e.shiftKey && document.activeElement === lastEl) {
            e.preventDefault();
            firstEl.focus();
        }
    }

    // Keyboard controls (Escape to close, Tab to cycle)
    handleKeydown = (e) => {
        if (!this.activeModal) return;

        if (e.key === 'Escape') {
            this.closeModal(this.activeModal);
        } else if (e.key === 'Tab') {
            this.handleFocusTrap(e);
        }
    }

    handleClick = (e) => {
        const openBtn = e.target.closest('[data-modal-target]');
        if (openBtn) {
            const modalId = openBtn.getAttribute('data-modal-target');
            const modal = document.querySelector(`[data-modal="${modalId}"]`);
            this.openModal(modal, openBtn);
            return;
        }

        const closeBtn = e.target.closest('[data-modal-close]');
        if (closeBtn) {
            const modal = closeBtn.closest('[data-modal]');
            this.closeModal(modal);
            return;
        }

        // Click outside (backdrop click)
        if (e.target.hasAttribute('data-modal') && e.target.classList.contains(this.activeClass)) {
            this.closeModal(e.target);
        }
    }

    setupA11yAttributes = () => {
        // Setup initial ARIA roles and states on initialization
        document.querySelectorAll('[data-modal]').forEach(modal => {
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            modal.setAttribute('aria-hidden', 'true');
        });

        document.querySelectorAll('[data-modal-target]').forEach(btn => {
            btn.setAttribute('aria-expanded', 'false');
            const targetId = btn.getAttribute('data-modal-target');
            const modal = document.querySelector(`[data-modal="${targetId}"]`);
            
            // If the modal has an ID, link it using aria-controls
            if (modal && modal.id) {
                btn.setAttribute('aria-controls', modal.id);
            }
        });
    }

    init = () => {
        this.setupA11yAttributes();
        document.addEventListener("click", this.handleClick);
        document.addEventListener("keydown", this.handleKeydown);
    }

    destroy = () => {
        document.removeEventListener("click", this.handleClick);
        document.removeEventListener("keydown", this.handleKeydown);
    }
}