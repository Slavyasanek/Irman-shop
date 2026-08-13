import EmblaCarousel from 'embla-carousel';

document.querySelectorAll('.products').forEach((sectionEl) => {
    const viewportNode = sectionEl.querySelector('.embla__viewport');
    const dotsNode = sectionEl.querySelector('.embla__dots');

    if (!viewportNode) return;

    const emblaApi = EmblaCarousel(viewportNode, {
        loop: false,
        align: 'start',
        slidesToScroll: 1,
    });

    if (dotsNode) {
        let dots = [];

        // Динамічна генерація крапок
        const generateDots = () => {
            dotsNode.innerHTML = '';
            const scrollSnaps = emblaApi.scrollSnapList();

            dots = scrollSnaps.map((_, index) => {
                const dot = document.createElement('button');
                dot.classList.add('embla__dot');
                dot.type = 'button';
                dot.setAttribute('aria-label', `Слайд ${index + 1}`);
                dot.addEventListener('click', () => emblaApi.scrollTo(index), false);
                dotsNode.appendChild(dot);
                return dot;
            });
        };

        // Підсвічування активної крапки
        const selectDot = () => {
            const selected = emblaApi.selectedScrollSnap();
            dots.forEach((dot, index) => {
                dot.classList.toggle('is-active', index === selected);
            });
        };

        emblaApi.on('init', () => {
            generateDots();
            selectDot();
        });
        emblaApi.on('reInit', generateDots);
        emblaApi.on('select', selectDot);
    }
});