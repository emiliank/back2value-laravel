import { Link } from '@inertiajs/react';
import CtaBand from '../../Components/CtaBand';
import PageHero from '../../Components/PageHero';
import SectionIcon from '../../Components/SectionIcon';
import PublicLayout from '../../Layouts/PublicLayout';
import { sitePath } from '../../lib/site';

export default function Solutions({ site, page, applicationFilters = {}, title, description }) {
    const locale = site?.locale ?? 'sq';

    return (
        <PublicLayout site={site} activeNav="solutions" title={title} description={description}>
            <PageHero site={site} eyebrow={page.eyebrow} title={page.title} description={page.description}>
                <Link className="primary-cta" href={sitePath(locale, 'diagnostics')}>
                    {page.hero_cta_primary}
                    <SectionIcon name="arrow" />
                </Link>
                <Link className="secondary-cta" href={sitePath(locale, page.hero_cta_secondary_target ?? 'services')}>
                    {page.hero_cta_secondary}
                </Link>
            </PageHero>
            <section className="page-section">
                <div className="section-shell">
                    <div className="solution-grid">
                        {(page.items ?? []).map((item, index) => (
                            <article className="solution-card" key={`${item.title}-${index}`}>
                                <SectionIcon name={item.icon} className="solution-card__icon" />
                                <h3>{item.title}</h3>
                                <p className="solution-card__audience">{item.audience}</p>
                                <p>{item.description}</p>
                                {item.benefits?.length > 0 && (
                                    <ul className="feature-list">
                                        {item.benefits.map((benefit) => (
                                            <li key={benefit}>{benefit}</li>
                                        ))}
                                    </ul>
                                )}
                                {item.applications?.length > 0 && (
                                    <div className="solution-apps">
                                        {item.applications.map((application) => (
                                            <Link
                                                className="solution-app"
                                                key={application}
                                                href={`${sitePath(locale, 'products')}?application=${encodeURIComponent(application)}`}
                                            >
                                                {applicationFilters?.[application]?.label ?? application}
                                            </Link>
                                        ))}
                                    </div>
                                )}
                            </article>
                        ))}
                    </div>
                </div>
            </section>
            <section className="page-section page-section--green">
                <div className="section-shell">
                    <div className="section-intro section-intro--small">
                        <span className="eyebrow">{page.process_eyebrow}</span>
                        <h2>{page.process_title}</h2>
                        <p>{page.process_description}</p>
                    </div>
                    <div className="steps-grid">
                        {(page.process_steps ?? []).map((step, index) => (
                            <article className="step-card" key={`${step.title}-${index}`}>
                                <span className="step-card__index">{index + 1}</span>
                                <h3>{step.title}</h3>
                                <p>{step.description}</p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>
            <CtaBand
                title={page.cta_title}
                description={page.cta_description}
                primaryLabel={page.cta_primary}
                primaryUrl={sitePath(locale, page.cta_primary_target ?? 'diagnostics')}
                secondaryLabel={page.cta_secondary}
                secondaryUrl={sitePath(locale, page.cta_secondary_target ?? 'products')}
            />
        </PublicLayout>
    );
}
