import { usePage } from '@inertiajs/react';
import SiteLink from './SiteLink';

export default function PageHero({ site: propSite, eyebrow, title, description, children }) {
    const page = usePage();
    const site = propSite ?? page.props.site ?? {};
    const locale = site.locale ?? 'sq';
    const breadcrumbHome = site?.navigation?.breadcrumb_home ?? (locale === 'en' ? 'Home' : 'Kryefaqja');

    return (
        <section className="page-hero">
            <div className="section-shell">
                <nav className="breadcrumb" aria-label="Breadcrumb">
                    <SiteLink href={`/${locale}`}>{breadcrumbHome}</SiteLink>
                    <span aria-hidden="true">/</span>
                    <span>{eyebrow}</span>
                </nav>
                <span className="eyebrow">{eyebrow}</span>
                <h1>{title}</h1>
                <p>{description}</p>
                {children && <div className="page-hero__actions">{children}</div>}
            </div>
        </section>
    );
}
