import gsap from "gsap";

// --- HERO ANIMATION ----
document.querySelectorAll('.hero').forEach(section => {

    const heroRefs = {
        contentBlock: section.querySelector('.hero__content-block'),
        grid: section.querySelector('.hero__grid'),
        title: section.querySelector('.hero__title p'),
        subtitle: section.querySelector('.hero__subtitle p'),
        button: section.querySelector('.hero__link'),
        imgs: Array.from(section.querySelectorAll('.hero__img'))
    };

    let mm = gsap.matchMedia();

    mm.add('(min-width: 960px)', () => {

        gsap.set([heroRefs.title, heroRefs.subtitle], {
            yPercent: -110,
        });

        gsap.set(heroRefs.button, {
            autoAlpha: 0,
        });

        gsap.set(heroRefs.grid, {
            xPercent: -52,
        });

        // --- LEFT SIDE
        gsap.set(heroRefs.imgs[1], { xPercent: -154 });
        gsap.set(heroRefs.imgs[3], { xPercent: -154 });
        gsap.set(heroRefs.imgs[6], { xPercent: -54 });

        // --- RIGHT SIDE
        gsap.set(heroRefs.imgs[5], { xPercent: 146 });
        gsap.set(heroRefs.imgs[heroRefs.imgs.length - 1], { xPercent: 100 });

        // ANIMATION
        const heroTl = gsap.timeline({
            delay: 0.8
        });

        heroTl
            .to(heroRefs.imgs, {
                xPercent: 0,
                duration: 1.2,
            })
            .to(heroRefs.grid, {
                xPercent: 0,
                duration: 1.2
            }, "<")
            .to([heroRefs.title, heroRefs.subtitle, heroRefs.button], {
                yPercent: 0,
                duration: .7,
            })
            .to(heroRefs.button, {
                autoAlpha: 1,
                duration: .5
            });
    });
});

// --- END HERO ANIMATION ---