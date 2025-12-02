const THEME_KEY = "theme";

/**
 * Helper: Set theme for mazer
 * @param {"dark"|"light"} theme
 * @param {boolean} persist
 */
function setTheme(theme, persist = false) {
    document.body.classList.add(theme);

    // Opsional: Hapus class lawan agar tidak menumpuk (misal: body punya class 'light' dan 'dark' sekaligus)
    if (theme === "dark") document.body.classList.remove("light");
    else document.body.classList.remove("dark");

    document.documentElement.setAttribute("data-bs-theme", theme);

    if (persist) {
        localStorage.setItem(THEME_KEY, theme);
    }
}

/**
 * Fungsi Utama yang diekspor untuk dipanggil di app.js
 */
export function initTheme() {
    // 1. Deteksi Tema Awal (Storage > System > Default)
    const storedTheme = localStorage.getItem(THEME_KEY);

    if (storedTheme) {
        setTheme(storedTheme);
    } else {
        // Fallback ke preferensi sistem jika tidak ada di storage
        if (
            window.matchMedia &&
            window.matchMedia("(prefers-color-scheme: dark)").matches
        ) {
            setTheme("dark");
        } else {
            setTheme("light");
        }
    }

    // 2. Logic Tombol Toggler (Switch)
    const toggler = document.getElementById("toggle-dark");

    if (toggler) {
        // PENTING: Reset element dengan cloneNode untuk menghapus event listener lama
        // agar tidak terjadi duplikasi saat navigasi Livewire (SPA)
        const newToggler = toggler.cloneNode(true);
        toggler.parentNode.replaceChild(newToggler, toggler);

        // Sinkronisasi status checked tombol dengan tema saat ini
        // Cek apakah saat ini dark mode?
        const currentTheme =
            document.documentElement.getAttribute("data-bs-theme");
        newToggler.checked = currentTheme === "dark";

        // Pasang Event Listener Baru
        newToggler.addEventListener("input", (e) => {
            setTheme(e.target.checked ? "dark" : "light", true);
        });
    }
}
