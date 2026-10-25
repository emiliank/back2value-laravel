export const THEME_STORAGE_KEY = 'back2value-theme';

const inBrowser = () => typeof window !== 'undefined' && typeof document !== 'undefined';

export const storedTheme = () => {
    if (!inBrowser()) {
        return null;
    }

    try {
        const value = window.localStorage.getItem(THEME_STORAGE_KEY);

        return value === 'dark' || value === 'light' ? value : null;
    } catch {
        return null;
    }
};

export const preferredTheme = () => {
    if (!inBrowser() || typeof window.matchMedia !== 'function') {
        return 'light';
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
};

export const currentTheme = () => {
    if (!inBrowser()) {
        return 'light';
    }

    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
};

export const applyTheme = (theme) => {
    if (!inBrowser()) {
        return;
    }

    const isDark = theme === 'dark';

    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.dataset.theme = isDark ? 'dark' : 'light';

    const themeColor = document.querySelector('meta[name="theme-color"]');

    if (themeColor) {
        themeColor.setAttribute('content', isDark ? '#0b111d' : '#101b32');
    }
};

export const storeTheme = (theme) => {
    if (!inBrowser()) {
        return;
    }

    try {
        window.localStorage.setItem(THEME_STORAGE_KEY, theme);
    } catch {
        // Storage can be blocked in private mode; the theme still applies for this page.
    }
};
