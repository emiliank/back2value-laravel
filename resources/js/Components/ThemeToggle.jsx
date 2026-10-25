import { useState } from 'react';
import SectionIcon from './SectionIcon';
import { applyTheme, currentTheme, storeTheme } from '../lib/theme';

const labels = {
    sq: {
        dark: 'Ndrysho në modalitet të errët',
        light: 'Ndrysho në modalitet të çelët',
    },
    en: {
        dark: 'Switch to dark mode',
        light: 'Switch to light mode',
    },
};

export default function ThemeToggle({ locale = 'sq', showLabel = false }) {
    const [theme, setTheme] = useState(currentTheme);

    const isDark = theme === 'dark';
    const copy = labels[locale] ?? labels.sq;
    const action = isDark ? copy.light : copy.dark;

    const toggle = () => {
        const next = isDark ? 'light' : 'dark';

        setTheme(next);
        applyTheme(next);
        storeTheme(next);
    };

    return (
        <button
            type="button"
            className={`theme-toggle${showLabel ? ' theme-toggle--menu' : ''}`}
            onClick={toggle}
            aria-pressed={isDark}
            aria-label={action}
            title={action}
        >
            <SectionIcon name="sun" className="theme-toggle__icon theme-toggle__icon--sun" />
            <SectionIcon name="moon" className="theme-toggle__icon theme-toggle__icon--moon" />
            {showLabel && <span className="theme-toggle__label">{action}</span>}
        </button>
    );
}
