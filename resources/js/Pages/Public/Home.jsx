import { Link, usePage } from '@inertiajs/react';
import PartnerMarquee from '../../Components/PartnerMarquee';
import SectionIcon from '../../Components/SectionIcon';
import PublicLayout from '../../Layouts/PublicLayout';
import { imageUrl, sitePath } from '../../lib/site';

function Intro({ section, dark = false }) {
    return (
        <div className={`section-intro section-intro--small${dark ? ' section-intro--dark' : ''}`}>
            <span className="eyebrow">{section.eyebrow}</span>
            <h2>{section.title}</h2>
            <p>{section.description}</p>
        </div>
    );
}

export default function Home(props) {
    const page = usePage();
    const site = props.site ?? page.props.site ?? {};
    const settings = site.settings ?? {};
    const { services = [], valueProps = {}, productCategories = {}, diagnosticsTeaser = {}, regenerationPage = {}, partners = {}, trust = {}, circularProcess = {}, about = {}, stats = [], complianceBanner: banner, contactCards = {} } = props;
    const whatsappNumber = (settings.whatsapp ?? '355692734476').replace(/\D/g, '');
    const whatsappUrl = site.whatsappUrl ?? `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(settings.whatsapp_message ?? 'Po interesohem për një ofertë për bateri industriale.')}`;
    const mapUrl = `https://maps.google.com/?q=${encodeURIComponent(settings.map_query ?? settings.address ?? '')}`;

    return (
        <PublicLayout site={site} activeNav="home" title={props.title} description={props.description}>
            <section id="top" className="hero">
                <div className="hero__content">
                    <div className="hero__badge">{settings.hero_badge}</div>
                    <h1>{settings.hero_title}<br/><span>{settings.hero_highlight}</span><br/>{settings.hero_subtitle}</h1>
                    <p>{settings.hero_description}</p>
                    <div className="hero__actions">
                        <a className="primary-cta primary-cta--large" href={whatsappUrl}>{settings.hero_cta}<span aria-hidden="true">→</span></a>
                        <Link className="secondary-cta" href={sitePath(site.locale, 'products')}>{settings.hero_products_cta}</Link>
                    </div>
                    <div className="hero__visual">
                        <img src={imageUrl(settings.hero_image) ?? '/images/hero-illustration.svg'} alt={settings.hero_image_alt ?? ''} width={settings.hero_image_width ?? 960} height={settings.hero_image_height ?? 440}/>
                    </div>
                </div>
            </section>

            {banner?.enabled !== false && (
                <div className="promo-strip">
                    <p className="promo-strip__compliance">
                        {banner?.prefix}{' '}
                        <a className="promo-strip__link" href={banner?.first_url ?? '#'} target="_blank" rel="noreferrer">{banner?.first_label}</a>
                        {' '}{banner?.middle}{' '}
                        <a className="promo-strip__link" href={banner?.second_url ?? '#'} target="_blank" rel="noreferrer">{banner?.second_label}</a>
                        {' '}{banner?.suffix}
                    </p>
                </div>
            )}

            <section className="trust-band" aria-label="Key benefits">
                <div className="trust-band__inner">
                    {(trust.items ?? []).map((item, index) => (
                        <div className="trust-item" key={`${item.text}-${index}`}>
                            <span className="trust-icon" aria-hidden="true"><SectionIcon name={item.icon}/></span>
                            <span>{item.text}</span>
                        </div>
                    ))}
                </div>
            </section>

            <section className="page-section page-section--categories" aria-labelledby="product-categories-title">
                <div className="section-shell">
                    <Intro section={productCategories}/>
                    <div className="product-category-grid">
                        {(productCategories.items ?? []).map((category) => (
                            <Link className="product-category-card" key={category.application} href={`${sitePath(site.locale, 'products')}?application=${encodeURIComponent(category.application)}`}>
                                <img className="product-category-card__image" src={imageUrl(category.image) ?? '/images/battery-generic.svg'} alt={category.image_alt ?? ''} loading="lazy"/>
                                <span className="product-category-card__content">
                                    <span className="product-category-card__title">{category.title}</span>
                                    <span className="product-category-card__description">{category.description}</span>
                                    <span className="product-category-card__link">{productCategories.cta_label}</span>
                                    <span className="product-category-card__arrow" aria-hidden="true">→</span>
                                </span>
                            </Link>
                        ))}
                    </div>
                </div>
            </section>

            <section className="page-section page-section--soft">
                <div className="section-shell">
                    <Intro section={valueProps}/>
                    <div className="value-grid">
                        {(valueProps.items ?? []).map((item, index) => (
                            <article className="value-prop" key={`${item.title}-${index}`}>
                                <SectionIcon name={item.icon} className="value-prop__icon"/>
                                <span className="value-prop__value">{item.value}</span>
                                <h3>{item.title}</h3>
                                <p>{item.description}</p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>

            <section className="page-section page-section--green">
                <div className="section-shell">
                    <Intro section={diagnosticsTeaser}/>
                    <div className="steps-grid">
                        {(diagnosticsTeaser.steps ?? []).map((step, index) => (
                            <article className="step-card" key={`${step.title}-${index}`}>
                                <span className="step-card__index">{index + 1}</span><h3>{step.title}</h3><p>{step.description}</p>
                            </article>
                        ))}
                    </div>
                    <div className="page-hero__actions">
                        <Link className="primary-cta" href={sitePath(site.locale, 'diagnostics')}>{diagnosticsTeaser.cta}</Link>
                        {diagnosticsTeaser.secondary_cta && <Link className="secondary-cta" href={sitePath(site.locale, 'services')}>{diagnosticsTeaser.secondary_cta}</Link>}
                    </div>
                </div>
            </section>

            <section id="sherbimet" className="services">
                <div className="section-shell">
                    <Intro section={{ eyebrow: settings.services_eyebrow, title: settings.services_title, description: settings.services_description }}/>
                    <div className="service-grid">
                        {services.map((service, index) => (
                            <article className="service-card" key={`${service.title}-${index}`}>
                                <SectionIcon name={service.icon} className="service-icon"/><h3>{service.title}</h3><p>{service.description}</p>
                            </article>
                        ))}
                    </div>
                    <div className="page-hero__actions"><Link className="link-cta link-cta--green" href={sitePath(site.locale, 'services')}>{settings.services_detail_link ?? 'Detajet e shërbimeve'} <span aria-hidden="true">→</span></Link></div>
                </div>
            </section>

            <section id="rigjenerimi" className="page-section page-section--soft">
                <div className="section-shell">
                    <Intro section={{ eyebrow: regenerationPage.teaser_eyebrow, title: regenerationPage.teaser_title, description: regenerationPage.teaser_description }}/>
                    <div className="solution-grid">
                        {(regenerationPage.tools ?? []).map((tool, index) => (
                            <article className="solution-card" key={`${tool.title}-${index}`}>
                                <SectionIcon name={tool.icon} className="solution-card__icon"/><h3>{tool.title}</h3><p className="solution-card__audience">{tool.model}</p><p>{tool.description}</p>
                                <ul className="feature-list">{(tool.features ?? []).map((feature) => <li key={feature}>{feature}</li>)}</ul>
                            </article>
                        ))}
                    </div>
                    <div className="page-hero__actions">
                        <Link className="primary-cta" href={sitePath(site.locale, 'regeneration')}>{regenerationPage.teaser_cta}</Link>
                        <Link className="secondary-cta" href={sitePath(site.locale, 'diagnostics')}>{regenerationPage.teaser_secondary_cta}</Link>
                    </div>
                </div>
            </section>

            <section id="procesi" className="page-section process-section">
                <div className="section-shell">
                    <Intro section={circularProcess}/>
                    <ol className="process-flow">
                        {(circularProcess.steps ?? []).map((step, index) => (
                            <li className={`process-step${step.key === 'activation' ? ' process-step--not-offered' : ''}`} key={`${step.title}-${index}`}>
                                <span className="process-step__node"><SectionIcon name={step.icon} className="process-step__icon"/></span>
                                <div className="process-step__body"><h3>{step.title}</h3><p>{step.description}</p></div>
                            </li>
                        ))}
                    </ol>
                    <div className="process-branch" aria-hidden="true"><span className="process-branch__stem"/><span className="process-branch__bar"/><span className="process-branch__drop process-branch__drop--left"/><span className="process-branch__drop process-branch__drop--right"/></div>
                    <div className="process-outcomes">
                        {(circularProcess.outcomes ?? []).map((outcome, index) => (
                            <article className={`process-outcome${outcome.key === 'recycling' ? ' process-outcome--not-offered' : ''}${outcome.key ? ` process-outcome--${outcome.key}` : ''}`} key={`${outcome.title}-${index}`}>
                                <SectionIcon name={outcome.icon} className="process-outcome__icon"/><div><h3>{outcome.title}</h3><p>{outcome.description}</p></div>
                            </article>
                        ))}
                    </div>
                </div>
            </section>

            <section id="pse-back2value" className="about">
                <div className="section-shell">
                    <Intro section={about} dark/>
                    <div className="about-grid">
                        {(about.points ?? []).map((point, index) => (
                            <article className="about-card" key={`${point.title}-${index}`}>
                                <SectionIcon name={index === 0 ? 'shield' : 'refresh'} className="about-icon"/>
                                <div><h3>{point.title}</h3><p>{point.description}</p>{point.label && <div className="manufacturer-note"><span className="manufacturer-mark">RID</span><span>{point.label}</span></div>}</div>
                            </article>
                        ))}
                    </div>
                    <div className="stats-grid" aria-label="Key figures">
                        {stats.map((stat, index) => <div className="stat-card" key={`${stat.label}-${index}`}><strong>{stat.value}</strong><span>{stat.label}</span></div>)}
                    </div>
                </div>
            </section>

            <section className="page-section page-section--soft partner-section">
                <div className="section-shell">
                    <Intro section={partners}/>
                    <PartnerMarquee partners={partners}/>
                </div>
            </section>

            <section id="kontakt" className="contact section-shell">
                <Intro section={{ eyebrow: settings.contact_eyebrow, title: settings.contact_title, description: settings.contact_description }}/>
                <div className="contact-grid">
                    <a className="contact-card contact-card--green" href={whatsappUrl}><span className="contact-ico contact-ico--wa"><SectionIcon name="whatsapp"/></span><strong>{contactCards.whatsapp_label ?? 'WhatsApp'}</strong><span>{settings.phone}</span></a>
                    <a className="contact-card" href={`mailto:${settings.email}`}><span className="contact-ico"><SectionIcon name="mail"/></span><strong>{contactCards.email_label ?? 'Email'}</strong><span>{settings.email}</span></a>
                    <a className="contact-card" href={`tel:${(settings.phone ?? '').replace(/[^\d+]/g, '')}`}><span className="contact-ico"><SectionIcon name="phone"/></span><strong>{contactCards.phone_label ?? 'Telefon'}</strong><span>{settings.phone}</span></a>
                </div>
                <div className="map-wrap"><a className="map-link" href={mapUrl} target="_blank" rel="noopener noreferrer">{contactCards.map_label ?? 'Hap në Maps'} <SectionIcon name="external" className="inline-block w-4 h-4 ml-1" /></a><iframe title={`Vendndodhja e Back2Value: ${settings.address}`} src={`https://www.google.com/maps?q=${encodeURIComponent(settings.map_query ?? settings.address ?? '')}&output=embed`} loading="lazy" referrerPolicy="no-referrer-when-downgrade" allowFullScreen/></div>
                <p className="contact-hours">{settings.address} · {settings.hours}</p>
            </section>
        </PublicLayout>
    );
}
