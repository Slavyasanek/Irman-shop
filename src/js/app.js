import { CART_EVENTS, CartDrawer } from "./elements/Cart.js";
import { CartCounter } from "./elements/CartButton.js";
import { MobileMenu } from "./elements/MobileMenu";
import { Modal } from "./elements/Modal";

document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector('[data-mobmenu]')) {
        new MobileMenu('[data-mobmenu]');
    }

    const modalHandler = new Modal();

    const cart = new CartDrawer({
        drawerId: 'cart-drawer',
        nonce: window.cleanThemeData?.nonce ?? ''
    });

    new CartCounter();

    const drawerEl = document.getElementById('cart-drawer');

    drawerEl.addEventListener(CART_EVENTS.ITEM_ADDED, (e) => {
        modalHandler.openModal(cart.drawer)
    });

    if (document.querySelector('[data-lazy-video]')) {
        const lazyVideos = document.querySelectorAll('video[data-lazy-video]');
        
        if ('IntersectionObserver' in window) {
            const videoObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        let video = entry.target;
                        video.preload = "auto";
                        video.play();
                        videoObserver.unobserve(video);
                    }
                });
            });

            lazyVideos.forEach((video) => {
                videoObserver.observe(video);
            });
        }
    }
});


