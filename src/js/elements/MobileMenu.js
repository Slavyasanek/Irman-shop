import { disableScroll, enableScroll } from "../helpers/help-functions";

/**
 * @class MobileMenu
 */
export class MobileMenu {
    /**
     * Creates an instance of MobileMenu.
     * @param {string} mobMenuClass
     */
    constructor(mobMenuClass) {
        this.mobMenuClass = mobMenuClass;
        this.mobMenu = document.querySelector(mobMenuClass);
        this.activeClass = 'active';
        this.openButtonClass = '[data-mobmenu-open]';
        this.closeButtonClass = '[data-mobmenu-close]';
        
        // Track the button that opened the menu to restore focus later
        this.lastFocusedElement = null; 

        if (this.mobMenu) {
            this.init();
        }
    }

    handleDelegatedClicks = (e) => {
        const openBtn = e.target.closest(this.openButtonClass);
        if (openBtn) {
            this.lastFocusedElement = openBtn; // Save the trigger element
            this.openMenu();
            return;
        }

        if (e.target.closest(this.closeButtonClass)) {
            this.closeMenu();
            return; 
        }

        const isMenuOpen = this.mobMenu.classList.contains(this.activeClass);
        if (isMenuOpen && !e.target.closest(this.mobMenuClass)) {
            this.closeMenu();
        }
    }

    // Allow closing the menu with the Escape key
    handleKeydown = (e) => {
        const isMenuOpen = this.mobMenu.classList.contains(this.activeClass);
        if (e.key === 'Escape' && isMenuOpen) {
            this.closeMenu();
        }
    }

    openMenu = () => {
        this.mobMenu.classList.add(this.activeClass);
        document.querySelector('.header').classList.add('menu-open');
        if (typeof disableScroll === 'function') disableScroll();
        
        // A11y updates on open
        this.mobMenu.setAttribute('aria-hidden', 'false');
        if (this.lastFocusedElement) {
            this.lastFocusedElement.setAttribute('aria-expanded', 'true');
        }

        // Shift focus into the menu so keyboard users can navigate it
        if (!this.mobMenu.hasAttribute('tabindex')) {
            this.mobMenu.setAttribute('tabindex', '-1'); // Make it programmatically focusable
        }
        this.mobMenu.focus();
    }

    closeMenu = () => {
        this.mobMenu.classList.remove(this.activeClass);
        document.querySelector('.header').classList.remove('menu-open');
        if (typeof enableScroll === 'function') enableScroll();
        
        // A11y updates on close
        this.mobMenu.setAttribute('aria-hidden', 'true');
        if (this.lastFocusedElement) {
            this.lastFocusedElement.setAttribute('aria-expanded', 'false');
            
            // Return focus back to the button that opened it
            this.lastFocusedElement.focus(); 
        }
    }

    init = () => {
        // Initial A11y setup
        this.mobMenu.setAttribute('aria-hidden', 'true');
        
        document.querySelectorAll(this.openButtonClass).forEach(btn => {
            btn.setAttribute('aria-expanded', 'false');
            
            // If the menu has an ID (e.g. id="mobile-nav"), link the button to it
            if (this.mobMenu.id) {
                btn.setAttribute('aria-controls', this.mobMenu.id);
            }
        });

        // Event listeners
        document.addEventListener("click", this.handleDelegatedClicks);
        document.addEventListener("keydown", this.handleKeydown);
    }
}


