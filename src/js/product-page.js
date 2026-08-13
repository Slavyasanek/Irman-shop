import EmblaCarousel from 'embla-carousel';
import PhotoSwipeLightbox from "photoswipe/lightbox";

document.addEventListener("DOMContentLoaded", () => {
    // -- SLIDERS
    if (document.querySelector(".product-page")) {
        const mainNode = document.querySelector("#productMainSlider .embla__viewport");
        const thumbsNode = document.querySelector("#productThumbnails .embla__viewport");
        const dotsNode = document.querySelector("#productMainSlider .embla__dots");
        const prevBtnNode = document.querySelector("#productMainSlider .embla__prev");
        const nextBtnNode = document.querySelector("#productMainSlider .embla__next");

        if (mainNode && thumbsNode) {
            // 1. Ініціалізація головного слайдера
            const mainEmbla = EmblaCarousel(mainNode, {
                loop: false,
            });

            // 2. Ініціалізація слайдера мініатюр (thumbnails)
            const thumbsEmbla = EmblaCarousel(thumbsNode, {
                containScroll: 'keepSnaps',
                dragFree: true,
            });

            // Кліки по стрілках навігації
            if (prevBtnNode && nextBtnNode) {
                prevBtnNode.addEventListener('click', () => mainEmbla.scrollPrev(), false);
                nextBtnNode.addEventListener('click', () => mainEmbla.scrollNext(), false);

                const updateArrowButtonsState = () => {
                    const canScrollPrev = mainEmbla.canScrollPrev();
                    const canScrollNext = mainEmbla.canScrollNext();

                    prevBtnNode.disabled = !canScrollPrev;
                    nextBtnNode.disabled = !canScrollNext;

                    prevBtnNode.classList.toggle('is-disabled', !canScrollPrev);
                    nextBtnNode.classList.toggle('is-disabled', !canScrollNext);
                };

                mainEmbla.on('select', updateArrowButtonsState);
                mainEmbla.on('init', updateArrowButtonsState);
                mainEmbla.on('reInit', updateArrowButtonsState);
            }

            // Мобільні крапки (Dots) для головного слайдера
            let dots = [];
            if (dotsNode) {
                const generateDots = () => {
                    dotsNode.innerHTML = '';
                    const scrollSnaps = mainEmbla.scrollSnapList();
                    dots = scrollSnaps.map((_, index) => {
                        const dot = document.createElement('button');
                        dot.classList.add('embla__dot');
                        dot.type = 'button';
                        dot.setAttribute('aria-label', `Слайд ${index + 1}`);
                        dot.addEventListener('click', () => mainEmbla.scrollTo(index));
                        dotsNode.appendChild(dot);
                        return dot;
                    });
                };

                const selectDot = () => {
                    const selected = mainEmbla.selectedScrollSnap();
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('is-active', index === selected);
                    });
                };

                mainEmbla.on('init', () => { generateDots(); selectDot(); });
                mainEmbla.on('reInit', generateDots);
                mainEmbla.on('select', selectDot);
            }

            // Синхронізація мініатюр з головним слайдером
            const updateThumbState = () => {
                const selectedIndex = mainEmbla.selectedScrollSnap();
                const thumbSlides = thumbsEmbla.slideNodes();

                thumbSlides.forEach((slide, index) => {
                    slide.classList.toggle('is-active', index === selectedIndex);
                });

                thumbsEmbla.scrollTo(selectedIndex);
            };

            thumbsEmbla.slideNodes().forEach((slide, index) => {
                slide.addEventListener('click', () => mainEmbla.scrollTo(index));
            });

            mainEmbla.on('select', updateThumbState);
            mainEmbla.on('init', updateThumbState);
        }

        // PhotoSwipe Lightbox
        if (document.querySelector("#lightgallery")) {
            const lightbox = new PhotoSwipeLightbox({
                gallery: "#lightgallery",
                children: "a.product-slider__img-link",
                pswpModule: () => import('photoswipe')
            });

            lightbox.init();
        }
    }

    // -- RELATED PRODUCTS SLIDER
    const relatedSection = document.querySelector(".related");
    if (relatedSection) {
        const viewportNode = relatedSection.querySelector(".embla__viewport");
        const dotsNode = relatedSection.querySelector(".embla__dots");

        if (viewportNode) {
            const relatedEmbla = EmblaCarousel(viewportNode, {
                loop: false,
                align: 'start',
                slidesToScroll: 1,
            });

            if (dotsNode) {
                let dots = [];

                const generateDots = () => {
                    dotsNode.innerHTML = '';
                    const scrollSnaps = relatedEmbla.scrollSnapList();
                    dots = scrollSnaps.map((_, index) => {
                        const dot = document.createElement('button');
                        dot.classList.add('embla__dot');
                        dot.type = 'button';
                        dot.setAttribute('aria-label', `Схожі товари сторінка ${index + 1}`);
                        dot.addEventListener('click', () => relatedEmbla.scrollTo(index));
                        dotsNode.appendChild(dot);
                        return dot;
                    });
                };

                const selectDot = () => {
                    const selected = relatedEmbla.selectedScrollSnap();
                    dots.forEach((dot, index) => {
                        dot.classList.toggle('is-active', index === selected);
                    });
                };

                relatedEmbla.on('init', () => { generateDots(); selectDot(); });
                relatedEmbla.on('reInit', generateDots);
                relatedEmbla.on('select', selectDot);
            }
        }
    }
});