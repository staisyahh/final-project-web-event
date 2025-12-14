import PerfectScrollbar from "perfect-scrollbar";

/**
 * Helper: isDesktop
 * Mengecek apakah viewport masuk kategori desktop (> 1200px)
 */
const isDesktop = (window) => window.innerWidth > 1200;

/**
 * Helper: Calculate Nested Children Height
 */
const calculateChildrenHeight = (el, deep = false) => {
    const children = el.children;
    let height = 0;
    for (let i = 0; i < el.childElementCount; i++) {
        const child = children[i];
        height += child.querySelector(".submenu-link").clientHeight;

        // 2-level menu
        if (deep && child.classList.contains("has-sub")) {
            const subsubmenu = child.querySelector(".submenu");
            if (subsubmenu.classList.contains("submenu-open")) {
                const childrenHeight = ~~[
                    ...subsubmenu.querySelectorAll(".submenu-link"),
                ].reduce((acc, curr) => acc + curr.clientHeight, 0);
                height += childrenHeight;
            }
        }
    }
    el.style.setProperty("--submenu-height", height + "px");
    return height;
};

/**
 * Class Sidebar
 */
class Sidebar {
    constructor(el, options = {}) {
        this.sidebarEL =
            el instanceof HTMLElement ? el : document.querySelector(el);
        this.options = options;
        this.init();
    }

    init() {
        // Prevent Duplicate Listeners
        if (this.sidebarEL.dataset.isInitialized) return;

        // Add event listener to burger buttons
        document.querySelectorAll(".burger-btn").forEach((el) => {
            // Clone node to remove old listeners if any, to be safe
            const newEl = el.cloneNode(true);
            el.parentNode.replaceChild(newEl, el);
            newEl.addEventListener("click", this.toggle.bind(this));
        });

        // Add event listener to sidebar-hide buttons
        document.querySelectorAll(".sidebar-hide").forEach((el) => {
            const newEl = el.cloneNode(true);
            el.parentNode.replaceChild(newEl, el);
            newEl.addEventListener("click", this.toggle.bind(this));
        });

        window.addEventListener("resize", this.onResize.bind(this));

        // Toggle Submenu Logic
        const toggleSubmenu = (el) => {
            if (el.classList.contains("submenu-open")) {
                el.classList.remove("submenu-open");
                el.classList.add("submenu-closed");
            } else {
                el.classList.remove("submenu-closed");
                el.classList.add("submenu-open");
            }
        };

        let sidebarItems = document.querySelectorAll(".sidebar-item.has-sub");
        for (var i = 0; i < sidebarItems.length; i++) {
            let sidebarItem = sidebarItems[i];

            // Re-query link to ensure freshness
            let link = sidebarItem.querySelector(".sidebar-link");

            // Clone to remove old listeners
            let newLink = link.cloneNode(true);
            link.parentNode.replaceChild(newLink, link);

            newLink.addEventListener("click", (e) => {
                e.preventDefault();
                let submenu = sidebarItem.querySelector(".submenu");
                toggleSubmenu(submenu);
            });

            // Submenu Level 2
            const submenuItems = sidebarItem.querySelectorAll(
                ".submenu-item.has-sub"
            );
            submenuItems.forEach((item) => {
                let subLink = item; // Usually the item itself handles click or a link inside

                // Check if we need to attach to a link inside or the item
                // Mazer logic usually attaches to item if it has sub
                item.addEventListener("click", (e) => {
                    e.stopPropagation(); // Prevent bubbling
                    const submenuLevelTwo = item.querySelector(".submenu");
                    toggleSubmenu(submenuLevelTwo);

                    // Recalculate height
                    calculateChildrenHeight(item.parentElement, true);
                });
            });
        }

        // Perfect Scrollbar Init
        const container = document.querySelector(".sidebar-wrapper");
        if (typeof PerfectScrollbar == "function" && container) {
            const ps = new PerfectScrollbar(container, {
                wheelPropagation: true,
            });
        }

        // Scroll into active sidebar item
        setTimeout(() => {
            const activeSidebarItem = document.querySelector(
                ".sidebar-item.active"
            );
            if (activeSidebarItem) {
                this.forceElementVisibility(activeSidebarItem);
            }
        }, 300);

        // Mark as initialized
        this.sidebarEL.dataset.isInitialized = "true";
    }

    onResize() {
        if (isDesktop(window)) {
            this.sidebarEL.classList.add("active");
            this.sidebarEL.classList.remove("inactive");
        } else {
            this.sidebarEL.classList.remove("active");
        }
        this.deleteBackdrop();
        this.toggleOverflowBody(true);
    }

    toggle() {
        const sidebarState = this.sidebarEL.classList.contains("active");
        if (sidebarState) {
            this.hide();
        } else {
            this.show();
        }
    }

    show() {
        this.sidebarEL.classList.add("active");
        this.sidebarEL.classList.remove("inactive");
        this.createBackdrop();
        this.toggleOverflowBody();
    }

    hide() {
        this.sidebarEL.classList.remove("active");
        this.sidebarEL.classList.add("inactive");
        this.deleteBackdrop();
        this.toggleOverflowBody();
    }

    createBackdrop() {
        if (isDesktop(window)) return;
        this.deleteBackdrop();
        const backdrop = document.createElement("div");
        backdrop.classList.add("sidebar-backdrop");
        backdrop.addEventListener("click", this.hide.bind(this));
        document.body.appendChild(backdrop);
    }

    deleteBackdrop() {
        const backdrop = document.querySelector(".sidebar-backdrop");
        if (backdrop) {
            backdrop.remove();
        }
    }

    toggleOverflowBody(active) {
        if (isDesktop(window)) return;
        const sidebarState = this.sidebarEL.classList.contains("active");
        const body = document.querySelector("body");
        if (typeof active == "undefined") {
            body.style.overflowY = sidebarState ? "hidden" : "auto";
        } else {
            body.style.overflowY = active ? "auto" : "hidden";
        }
    }

    isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <=
                (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <=
                (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    forceElementVisibility(el) {
        if (!this.isElementInViewport(el)) {
            el.scrollIntoView(false);
        }
    }
}

/**
 * Fungsi Utama yang diekspor untuk dipanggil di app.js
 */
export function initSidebar() {
    const sidebarEl = document.getElementById("sidebar");

    if (!sidebarEl) return;

    // 1. Logic On First Load (Set class Active/Inactive based on screen)
    if (isDesktop(window)) {
        sidebarEl.classList.add("active");
        sidebarEl.classList.add("sidebar-desktop");
    } else {
        sidebarEl.classList.remove("active");
    }

    // 2. Set Submenu Heights (Animation prep)
    let submenus = document.querySelectorAll(".sidebar-item.has-sub .submenu");
    for (var i = 0; i < submenus.length; i++) {
        let submenu = submenus[i];
        const sidebarItem = submenu.parentElement;

        if (sidebarItem.classList.contains("active")) {
            submenu.classList.add("submenu-open");
        } else {
            submenu.classList.add("submenu-closed");
        }

        setTimeout(() => {
            calculateChildrenHeight(submenu, true);
        }, 50);
    }

    // 3. Instantiate Class
    // Kita simpan instance di window agar tidak hilang garbage collector,
    // tapi logic init() sudah punya proteksi duplicate listener.
    window.sidebarInstance = new Sidebar(sidebarEl);
}
