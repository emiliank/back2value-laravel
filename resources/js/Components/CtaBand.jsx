import SiteLink from './SiteLink';

export default function CtaBand({ title, description, primaryLabel, primaryUrl, secondaryLabel, secondaryUrl }) {
    return (
        <section className="cta-band">
            <div className="section-shell">
                <div className="cta-band__inner">
                    <div className="cta-band__copy">
                        <h2>{title}</h2>
                        {description && <p>{description}</p>}
                    </div>
                    <div className="cta-band__actions">
                        {primaryLabel && <SiteLink className="primary-cta" href={primaryUrl ?? '#kontakt'}>{primaryLabel}</SiteLink>}
                        {secondaryLabel && secondaryUrl && <SiteLink className="secondary-cta" href={secondaryUrl}>{secondaryLabel}</SiteLink>}
                    </div>
                </div>
            </div>
        </section>
    );
}
