
import Alpine from 'alpinejs';

const THEME_KEY = 'ecozeen-theme';

function currentTheme() {
    if (document.documentElement.classList.contains('dark')) {
        return 'dark';
    }

    try {
        const saved = localStorage.getItem(THEME_KEY);
        if (saved === 'light' || saved === 'dark') {
            return saved;
        }
    } catch (e) {
        // Ignore storage errors in private browsing.
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function applyTheme(theme) {
    const dark = theme === 'dark';
    document.documentElement.classList.toggle('dark', dark);
    document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
    try {
        localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light');
    } catch (e) {
        // Ignore storage errors in private browsing.
    }
}

window.ecozeenTheme = {
    current: currentTheme,
    apply: applyTheme,
    toggle() {
        applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    },
};

window.Alpine = Alpine;

Alpine.start();
