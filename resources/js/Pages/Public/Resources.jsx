import PageHero from '../../Components/PageHero';
import SectionIcon from '../../Components/SectionIcon';
import PublicLayout from '../../Layouts/PublicLayout';
import { imageUrl } from '../../lib/site';
export default function Resources({
    site,
    about = {},
    stats = [],
    certificates = [],
    certificatesEyebrow = null,
    certificatesTitle = null,
    certificatesDescription = null,
    trust = {},
    title,
    description,
}) {
    const locale = site?.locale ?? 'sq';

    return (
        <PublicLayout site={site} activeNav="resources" title={title} description={description}>
            <PageHero site={site} eyebrow={about.eyebrow} title={about.title} description={about.description} />
            <section className="about">
                <div className="section-shell">
                    <div className="about-grid">
                        {(about.points ?? []).map((point, index) => (
                            <article className="about-card" key={`${point.title}-${index}`}>
                                <SectionIcon name={point.icon} className="about-icon" />
                                <div>
                                    <h2>{point.title}</h2>
                                    <p>{point.description}</p>
                                    {point.label && (
                                        <div className="manufacturer-note">
                                            <span className="manufacturer-mark">RID</span>
                                            <span>{point.label}</span>
                                        </div>
                                    )}
                                </div>
                            </article>
                        ))}
                    </div>
                    <div className="stats-grid" aria-label="Key figures">
                        {(stats ?? []).map((stat, index) => (
                            <div className="stat-card" key={`${stat.label}-${index}`}>
                                <strong>{stat.value}</strong>
                                <span>{stat.label}</span>
                            </div>
                        ))}
                    </div>
                </div>
            </section>
            {!!certificates.length && (
                <section className="certificates-section" aria-label="Certificates">
                    <div className="section-shell">
                        <div className="certificates-inner">
                            <div className="certificates-intro">
                                {certificatesTitle && <h2>{certificatesTitle}</h2>}
                                {certificatesDescription && <p>{certificatesDescription}</p>}
                            </div>
                            <div className="certificate-grid">
                                {certificates.map((cert, index) => (
                                    <article className="certificate-card" key={cert.key || `cert-${index}`}>
                                        <div className="certificate-preview">
                                            {cert.image && !cert.image.toLowerCase().endsWith('.pdf') ? (
                                                <img src={imageUrl(cert.image)} alt={cert.title} loading="lazy" />
                                            ) : (
                                                <span className="certificate-pdf-badge">
                                                    <SectionIcon name="document" className="h-6 w-6" />
                                                    PDF
                                                </span>
                                            )}
                                        </div>
                                        <div className="certificate-content">
                                            {cert.certificate_number && (
                                                <span className="certificate-code">{cert.certificate_number}</span>
                                            )}
                                            <h3>{cert.title}</h3>
                                            {cert.description && <p>{cert.description}</p>}
                                            <dl className="certificate-details">
                                                {cert.issue_date && (
                                                    <div className="certificate-detail">
                                                        <dt>{locale === 'en' ? 'Issued on' : 'Lëshuar më'}</dt>
                                                        <dd>{cert.issue_date}</dd>
                                                    </div>
                                                )}
                                                {cert.expiration_date && (
                                                    <div className="certificate-detail">
                                                        <dt>{locale === 'en' ? 'Valid until' : 'E vlefshme deri më'}</dt>
                                                        <dd>{cert.expiration_date}</dd>
                                                    </div>
                                                )}
                                            </dl>
                                            <a
                                                href={cert.pdf_url || imageUrl(cert.image)}
                                                className="certificate-download"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                <span>{cert.pdf_label ?? (locale === 'en' ? 'View certificate (PDF)' : 'Shiko certifikatën (PDF)')}</span>
                                                <SectionIcon name="arrow-up-right" className="h-4 w-4" />
                                            </a>
                                        </div>
                                    </article>
                                ))}
                            </div>
                        </div>
                    </div>
                </section>
            )}
            {!!about.faqs?.length && (
                <section className="page-section page-section--soft faq-section">
                    <div className="section-shell">
                        <div className="section-intro section-intro--small">
                            <h2>{locale === 'en' ? 'Frequently asked questions' : 'Pyetje të shpeshta'}</h2>
                        </div>
                        <div className="faq-list">
                            {about.faqs.map((faq, index) => (
                                <details className="faq-item" key={`${faq.question}-${index}`}>
                                    <summary>{faq.question}</summary>
                                    <p>{faq.answer}</p>
                                </details>
                            ))}
                        </div>
                    </div>
                </section>
            )}
            <section className="trust-band" aria-label="Key benefits">
                <div className="trust-band__inner">
                    {(trust.items ?? []).map((item, index) => (
                        <div className="trust-item" key={`${item.text}-${index}`}>
                            <span className="trust-icon" aria-hidden="true">
                                <SectionIcon name={item.icon} />
                            </span>
                            <span>{item.text}</span>
                        </div>
                    ))}
                </div>
            </section>
        </PublicLayout>
    );
}
