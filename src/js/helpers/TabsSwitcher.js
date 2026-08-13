/**
 *
 *
 * @class TabsSwitcher
 */
export default class TabsSwitcher {
    /**
     * Creates an instance of tabsSwitcher.
     * @param {element} tabs
     * @param {string} tabListClass
     * @param {string} tabTogglerClass
     * @param {string} tabActiveClass
     * @memberof tabsSwitcher
     */

    constructor(tab, options) {
        this.options = {
            tabListClass: '.tabs__inner',
            tabTogglerClass: '.tabs__toggler',
            signleTabClass: '.tabs__item',
            tabActiveClass: 'active',
            breeakpoint: '960px',
            ...options
        }
        this.tab = typeof tab === 'string' ? document.querySelector(tab) : tab;
        console.log(this.tab);
        
        this.tabList = this.tab.querySelector(this.options.tabListClass);
        this.tabToggler = this.tab.querySelector(this.options.tabTogglerClass);
        this.signleTabClass = this.options.signleTabClass;
        this.tabActiveClass = this.options.tabActiveClass;
        this.init()
    }

    setTogglerWidth = (activeItem) => {
        let reduceCount;

        if (window.matchMedia(`(min-width: ${this.options.breeakpoint})`).matches) {
            reduceCount = 50
        } else {
           reduceCount = 5;
        }

        const newWidth = (activeItem.offsetWidth * 100) / window.innerWidth;
        const reductPercentage = (newWidth * reduceCount) / 100;
        
        this.tabToggler.style.width = `${(newWidth - reductPercentage).toFixed(1)}vw`;
    }

    setTogglerLeft = (activeItem) => {
        this.tabToggler.style.left = `${((activeItem.offsetLeft * 100) / window.innerWidth).toFixed(1)}vw`;
    }

    init = () => {
        if (this.tabList) {
            const activeSelection = this.tabList.querySelector(`.${this.tabActiveClass}`);

            this.setTogglerWidth(activeSelection);
            this.setTogglerLeft(activeSelection);

            this.tab.addEventListener("click", e => {

                if (e.target.closest(this.signleTabClass)) {
                    const target = e.target.closest(this.signleTabClass);
                    const activeSelection = this.tabList.querySelector(`.${this.tabActiveClass}`);

                    if (target === activeSelection) return;

                    activeSelection.classList.remove(this.tabActiveClass);
                    target.classList.add(this.tabActiveClass);
                    this.setTogglerWidth(target);
                    this.setTogglerLeft(target);
                }
            })
        }
    }
}