import { preloader } from "./helpers/help-functions";
import TabsSwitcher from "./helpers/TabsSwitcher";

class Shop {
    constructor() {
        if (typeof wc_load_more_params === "undefined") return;

        this.currentPage = parseInt(wc_load_more_params.current_page);
        this.maxPage = parseInt(wc_load_more_params.max_page);
        this.ajaxUrl = wc_load_more_params.ajax_url;
        this.nonce = wc_load_more_params.nonce;
        this.queryVars = wc_load_more_params.query_vars;
        this.baseUrl = wc_load_more_params.base_url;

        this.btn = document.querySelector(".catalogue__load-more");
        this.productGrid = document.querySelector(".catalogue__products");

        this.init();
    }

    init() {
        if (!this.btn || !this.productGrid) return;
        if (this.currentPage >= this.maxPage) {
            this.btn.style.display = "none";
            return;
        }

        this.bindEvents();
    }

    bindEvents() {
        this.btn.addEventListener("click", (e) => this.fetchProducts(e));
        window.addEventListener("popstate", (e) => {
            if (e.state && e.state.page) {
                window.location.reload();
            }
        });
    }

    async fetchProducts(e) {
        e.preventDefault();

        if (this.currentPage >= this.maxPage) return;

        preloader(true);

        this.currentPage++;

        // set params
        const params = new URLSearchParams();
        params.append("action", "wc_load_more");
        params.append("nonce", this.nonce);
        params.append("page", this.currentPage);
        params.append("query_vars", this.queryVars);

        try {
            const response = await fetch(this.ajaxUrl, {
                method: "POST",
                body: params,
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8"
                }
            });

            if (!response.ok) {
                throw new Error("Network response was not ok");
            }

            const data = await response.json();

            if (data.success) {
                this.updateDOM(data.data);
                this.updateURL();
            } else {
                preloader(false);
            }
        } catch (error) {
            console.error("Fetch error:", error);
            preloader(false);
        }
    }

    updateDOM(html) {
        this.productGrid.insertAdjacentHTML("beforeend", html);

        preloader(false);

        if (this.currentPage >= this.maxPage) {
            this.btn.style.display = "none";
        }
    }

    updateURL() {
        const newUrl = `${this.baseUrl}page/${this.currentPage}/`;
        window.history.pushState({ page: this.currentPage }, "", newUrl);
    }
}

document.addEventListener("DOMContentLoaded", () => {
    if (document.querySelector(".catalogue")) {
        const catalogue = new Shop();

        if (document.querySelector(".tabs--shop")) {
            const switcher = new TabsSwitcher(".tabs--shop");
        }
    }
});
