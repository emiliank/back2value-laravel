import { Link } from '@inertiajs/react';
import CtaBand from '../../Components/CtaBand';
import PageHero from '../../Components/PageHero';
import SectionIcon from '../../Components/SectionIcon';
import PublicLayout from '../../Layouts/PublicLayout';
import { sitePath } from '../../lib/site';

export default function Sustainability({ site, page, title, description }) {
    const locale = site?.locale ?? 'sq';

    return (
        <PublicLayout site={site} activeNav="sustainability" title={title} description={description}>
            <PageHero site={site} eyebrow={page.eyebrow} title={page.title} description={page.description}>
                <Link className="primary-cta" href={sitePath(locale, 'diagnostics')}>
                    {page.hero_cta_primary}
                    <SectionIcon name="arrow" />
                </Link>
                <Link className="secondary-cta" href={sitePath(locale, page.hero_cta_secondary_target ?? 'services')}>
                    {page.hero_cta_secondary}
                </Link>
            </PageHero>
            <section className="about">
                <div className="section-shell">
                    <div className="section-intro section-intro--dark">
                        <span className="eyebrow">{page.commitments_eyebrow}</span>
                        <h2>{page.commitments_title}</h2>
                        <p>{page.commitments_description}</p>
                    </div>
                    <div className="commitment-grid">
                        {(page.commitments ?? []).map((item, index) => (
                            <article className="commitment-card" key={`${item.title}-${index}`}>
                                <SectionIcon name={item.icon} className="commitment-card__icon" />
                                <h3>{item.title}</h3>
                                <p>{item.description}</p>
                            </article>
                        ))}
                    </div>
                    <div className="stats-grid" aria-label="Sustainability metrics">
                        {(page.metrics ?? []).map((metric, index) => (
                            <div className="stat-card" key={`${metric.label}-${index}`}>
                                <strong>{metric.value}</strong>
                                <span>{metric.label}</span>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            <section className="page-section">
                <div className="section-shell">
                    <div className="compliance-panel">
                        <div>
                            <span className="eyebrow">{page.compliance_eyebrow}</span>
                            <h2>{page.compliance_title}</h2>
                            <p>{page.compliance_description}</p>
                        </div>
                        <ul className="info-list">
                            {(page.compliance_points ?? []).map((point) => (
                                <li key={point}>{point}</li>
                            ))}
                        </ul>
                    </div>
                </div>
            </section>
            <CtaBand
                title={page.cta_title}
                description={page.cta_description}
                primaryLabel={page.cta_primary}
                primaryUrl={sitePath(locale, page.cta_primary_target ?? 'diagnostics')}
                secondaryLabel={page.cta_secondary}
                secondaryUrl={sitePath(locale, page.cta_secondary_target ?? 'resources')}
            />
        </PublicLayout>
    );
}
