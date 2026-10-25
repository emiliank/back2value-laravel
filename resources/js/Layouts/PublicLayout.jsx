import { useEffect, useState } from 'react';
import { Head, usePage } from '@inertiajs/react';
import SectionIcon from '../Components/SectionIcon';
import SiteLink from '../Components/SiteLink';
import ThemeToggle from '../Components/ThemeToggle';
import { initMotion } from '../lib/motion';

export default function PublicLayout({ site: propSite, activeNav, title, description, children }) {
    const [menuOpen, setMenuOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const page = usePage();
    const site = propSite ?? page.props.site ?? {};
    const settings = site.settings ?? {};
    const navigation = site.navigation ?? {};
    const locales = site.locales ?? {};
    const currentUrl = page.url;
    const currentPath = currentUrl.split('?')[0];
    const search = currentUrl.includes('?') ? '?' + currentUrl.split('?')[1] : '';
    const pathWithoutLocale = currentPath.replace(/^\/(sq|en)(?=\/|$)/, '') || '/';
    const mapUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(settings.map_query ?? settings.address ?? '')}`;
    const safeAccent = /^#[0-9a-f]{6}$/i.test(settings.accent_color ?? '') ? settings.accent_color : '#17b78b';
    const phoneClean = (settings.phone ?? '').replace(/[^\d+]/g, '');

    useEffect(() => {
        if (site.locale) {
            document.documentElement.lang = site.locale.replace('_', '-');
        }
    }, [site.locale]);

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 8);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    useEffect(() => {
        setMenuOpen(false);
        return initMotion();
    }, [children]);

    useEffect(() => {
        const onKeyDown = (e) => {
            if (e.key === 'Escape') {
                setMenuOpen(false);
            }
        };
        window.addEventListener('keydown', onKeyDown);
        return () => window.removeEventListener('keydown', onKeyDown);
    }, []);

    const localeHref = (locale) => `/${locale}${pathWithoutLocale === '/' ? '' : pathWithoutLocale}${search}`;
    const navLabel = (item) => (
        item.target === 'resources'
            ? (site.locale === 'en' ? 'About us' : 'Rreth nesh')
            : item.label
    );
    const renderLocales = (className) => (
        <div className={`language-switcher ${className}`} role="group" aria-label="Language">
            {Object.entries(locales).map(([code, meta]) => (
                <a
                    key={code}
                    href={localeHref(code)}
                    hreflang={meta.og_locale ?? code}
                    title={meta.native ?? code.toUpperCase()}
                    aria-label={meta.native ?? code.toUpperCase()}
                    lang={code}
                    aria-current={code === site.locale ? 'true' : undefined}
                    className={`language-switcher__option ${code === site.locale ? 'is-active' : ''}`}
                >
                    <span className="language-switcher__label" lang={code}>{meta.code ?? code.toUpperCase()}</span>
                </a>
            ))}
        </div>
    );

    const navItems = navigation.header_items ?? [];

    return (
        <div style={{ '--green': safeAccent }}>
            <Head title={title ?? settings.meta_title ?? 'Back2Value'}>
                <meta name="description" content={description ?? settings.meta_description ?? ''} />
                <meta property="og:site_name" content={settings.meta_title ?? 'Back2Value'} />
                <meta property="og:type" content="website" />
                <meta property="og:title" content={title ?? settings.meta_title ?? 'Back2Value'} />
                <meta property="og:description" content={description ?? settings.meta_description ?? ''} />
                <meta property="og:image" content="/images/back2value-logo.png" />
                <meta property="og:locale" content={(locales[site.locale]?.og_locale ?? site.locale).replace('-', '_')} />
                <meta name="twitter:card" content="summary_large_image" />
                <meta name="twitter:image" content="/images/back2value-logo.png" />
                {Object.entries(locales).map(([code, meta]) => (
                    <link key={code} rel="alternate" hreflang={meta.og_locale ?? code} href={localeHref(code)} />
                ))}
                <link rel="alternate" hreflang="x-default" href={localeHref(settings.default_locale ?? 'sq')} />
            </Head>
            <header className={`site-header${scrolled ? ' is-scrolled' : ''}`}>
                <div className="site-header__inner">
                    <SiteLink className="site-brand-mark" href={`/${site.locale}`} aria-label={navigation.brand_alt ?? 'Back2Value logo'}>
                        {site.logo
                            ? <img className="site-brand-mark__image" src={site.logo} alt={navigation.brand_alt ?? 'Back2Value logo'} />
                            : <span className="site-brand-word">Back<span>2</span>Value</span>}
                    </SiteLink>
                    <nav className="main-nav" aria-label="Main navigation">
                        {navItems.map((item) => (
                            <SiteLink key={item.target} className={activeNav === item.target ? 'is-active' : ''} href={item.href}>
                                {navLabel(item)}
                            </SiteLink>
                        ))}
                    </nav>
                    <div className="header-actions">
                        <ThemeToggle locale={site.locale} />
                        {renderLocales('')}
                        <SiteLink className="secondary-cta secondary-cta--compact" href={site.secondaryCtaUrl}>
                            {navigation.header_cta_secondary ?? 'Rezervo Diagnostikim'}
                        </SiteLink>
                        <a className="primary-cta header-cta" href={site.whatsappUrl}>{navigation.header_cta_primary ?? 'Na Kontaktoni'}</a>
                    </div>
                    <button type="button" className="mobile-toggle" aria-expanded={menuOpen} aria-controls="mobile-menu" aria-label={menuOpen ? 'Close menu' : 'Open menu'} onClick={() => setMenuOpen(!menuOpen)}>
                        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
                            {menuOpen
                                ? <path d="M6 6l12 12M18 6 6 18" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />
                                : <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" />}
                        </svg>
                    </button>
                    <nav id="mobile-menu" className="mobile-menu" aria-label="Mobile navigation" hidden={!menuOpen}>
                        {navItems.map((item) => (
                            <SiteLink key={item.target} href={item.href} className={activeNav === item.target ? 'is-active' : ''} onClick={() => setMenuOpen(false)}>
                                {navLabel(item)}
                            </SiteLink>
                        ))}
                        {renderLocales('language-switcher--mobile')}
                        <ThemeToggle locale={site.locale} showLabel />
                        <SiteLink href={site.secondaryCtaUrl} onClick={() => setMenuOpen(false)}>{navigation.header_cta_secondary ?? 'Rezervo Diagnostikim'}</SiteLink>
                        <a className="primary-cta" href={site.whatsappUrl}>{navigation.header_cta_primary ?? 'Na Kontaktoni'}</a>
                    </nav>
                </div>
            </header>
            <main id="main">{children}</main>
            <footer className="site-footer">
                <div className="site-footer__inner">
                    <SiteLink className="site-footer__brand" href={`/${site.locale}`} aria-label={navigation.brand_alt ?? 'Back2Value logo'}>
                        {site.logo
                            ? <img className="site-footer__logo" src={site.logo} alt={navigation.brand_alt ?? 'Back2Value logo'} />
                            : <span className="site-footer__word">Back<span>2</span>Value</span>}
                    </SiteLink>
                    <nav className="footer-nav" aria-label="Quick links">
                        {(navigation.footer_items ?? []).map((item) => (
                            <SiteLink key={item.target} href={item.href} className={activeNav === item.target ? 'is-active' : ''}>{item.label}</SiteLink>
                        ))}
                    </nav>
                    <div className="footer-contact">
                        <a href={`tel:${phoneClean}`}>{settings.phone}</a>
                        <a href={`mailto:${settings.email ?? ''}`}>{settings.email}</a>
                        <a href={site.whatsappUrl}>{navigation.footer_whatsapp_label ?? 'WhatsApp'}</a>
                        <a href={mapUrl} target="_blank" rel="noopener noreferrer">{settings.map_label ?? 'Hap në Maps'}</a>
                    </div>
                    <p className="footer-description">{settings.footer_description}</p>
                    <div className="footer-bottom">
                        <span>&copy; {new Date().getFullYear()} {navigation.footer_copyright ?? 'Back2Value shpk. Të gjitha të drejtat e rezervuara.'}</span>
                        {renderLocales('language-switcher--footer')}
                        {navigation.footer_partner_line && <span>{navigation.footer_partner_line}</span>}
                    </div>
                </div>
            </footer>
            <a href={site.whatsappUrl} className="floating-wa" aria-label={navigation.floating_whatsapp_label ?? 'Na kontaktoni në WhatsApp'}>
                <SectionIcon name="whatsapp" />
            </a>
        </div>
    );
}
