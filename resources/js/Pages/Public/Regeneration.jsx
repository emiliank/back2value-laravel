import { Link } from '@inertiajs/react';
import CtaBand from '../../Components/CtaBand';
import PageHero from '../../Components/PageHero';
import SectionIcon from '../../Components/SectionIcon';
import PublicLayout from '../../Layouts/PublicLayout';
import { imageUrl, sitePath } from '../../lib/site';

export default function Regeneration({ site, page, title, description }) {
    const locale = site?.locale ?? 'sq';

    return (
        <PublicLayout site={site} activeNav="regeneration" title={title} description={description}>
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
                    <div className="section-intro section-intro--small">
                        <span className="eyebrow">{page.tools_eyebrow}</span>
                        <h2>{page.tools_title}</h2>
                        <p>{page.tools_description}</p>
                    </div>
                    <div className="solution-grid">
                        {(page.tools ?? []).map((tool, index) => (
                            <article className="solution-card" key={`${tool.title}-${index}`}>
                                <SectionIcon name={tool.icon} className="solution-card__icon" />
                                <h3>{tool.title}</h3>
                                <p className="solution-card__audience">{tool.model}</p>
                                <p>{tool.description}</p>
                                <ul className="feature-list">
                                    {(tool.features ?? []).map((feature) => (
                                        <li key={feature}>{feature}</li>
                                    ))}
                                </ul>
                            </article>
                        ))}
                    </div>
                </div>
            </section>
            {page.lab_steps?.length > 0 && (
                <section className="page-section">
                    <div className="section-shell">
                        <div className="section-intro section-intro--small">
                            <span className="eyebrow">{page.lab_eyebrow}</span>
                            <h2>{page.lab_title}</h2>
                            <p>{page.lab_description}</p>
                        </div>
                        <div className="lab-flow">
                            <div className="lab-flow__head">
                                <p className="lab-flow__headline">{page.lab_panel_title}</p>
                                <div className="lab-flow__brands">
                                    <span className="lab-flow__brand lab-flow__brand--site">
                                        {site?.logo ? <img src={site.logo} alt="Back2Value logo" loading="lazy" /> : 'Back2Value'}
                                    </span>
                                    {page.lab_brand_logo && (
                                        <span className="lab-flow__brand">
                                            <img src={imageUrl(page.lab_brand_logo)} alt={page.lab_brand_alt ?? 'RID Battery · Germany'} loading="lazy" />
                                        </span>
                                    )}
                                </div>
                            </div>
                            <ol className="lab-flow__grid">
                                {page.lab_steps.map((step, index) => (
                                    <li className="lab-flow__card" key={`${step.title}-${index}`}>
                                        <span className="lab-flow__figure">
                                            <img src={imageUrl(step.image)} alt={step.alt ?? step.title} width={step.width} height={step.height} loading="lazy" />
                                        </span>
                                        <h3 className="lab-flow__ribbon">
                                            <span className="lab-flow__ribbon-text">{index + 1}. {step.title}</span>
                                        </h3>
                                        <p className="lab-flow__copy">{step.description}</p>
                                    </li>
                                ))}
                            </ol>
                            <div className="lab-flow__note">
                                <p>{page.lab_note}</p>
                                {page.lab_badge_image && (
                                    <img className="lab-flow__badge" src={imageUrl(page.lab_badge_image)} alt={page.lab_badge_alt ?? ''} width={page.lab_badge_width} height={page.lab_badge_height} loading="lazy" />
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            )}
            <section className="page-section page-section--soft">
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
            <section className="page-section page-section--green">
                <div className="section-shell">
                    <div className="section-intro section-intro--small">
                        <span className="eyebrow">{page.offer_eyebrow}</span>
                        <h2>{page.offer_title}</h2>
                        <p>{page.offer_description}</p>
                    </div>
                    <div className="page-hero__actions">
                        <Link className="primary-cta" href={sitePath(locale, page.cta_primary_target ?? 'diagnostics')}>
                            {page.cta_primary}
                            <SectionIcon name="arrow" />
                        </Link>
                        <Link className="secondary-cta" href={sitePath(locale, page.cta_secondary_target ?? 'services')}>
                            {page.cta_secondary}
                        </Link>
                    </div>
                </div>
            </section>
            <CtaBand
                title={page.cta_title}
                description={page.cta_description}
                primaryLabel={page.cta_primary}
                primaryUrl={sitePath(locale, page.cta_primary_target ?? 'diagnostics')}
                secondaryLabel={page.cta_secondary}
                secondaryUrl={sitePath(locale, page.cta_secondary_target ?? 'services')}
            />
        </PublicLayout>
    );
}
