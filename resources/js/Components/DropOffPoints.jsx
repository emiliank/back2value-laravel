export default function DropOffPoints({ section }) {
    if (!section) {
        return null;
    }

    return (
        <section className="drop-off section-shell">
            <div className="section-intro section-intro--small">
                <span className="eyebrow">{section.eyebrow}</span>
                <h2>{section.title}</h2>
                <p>{section.description}</p>
            </div>
            <div className="drop-off-grid">
                {(section.items ?? []).map((point, index) => (
                    <article className="drop-off-card" key={`${point.title}-${index}`}>
                        <span className={`drop-off-type drop-off-type--${point.category ?? 'licensed'}`}>{point.type}</span>
                        <h3>{point.title}</h3>
                        <dl>
                            <div><dt>{section.address_label ?? 'Adresa'}</dt><dd>{point.address}</dd></div>
                            <div><dt>{section.phone_label ?? 'Telefon'}</dt><dd><a href={`tel:${(point.phone ?? '').replace(/[^\d+]/g, '')}`}>{point.phone}</a></dd></div>
                            <div><dt>{section.hours_label ?? 'Orari'}</dt><dd>{point.hours}</dd></div>
                        </dl>
                    </article>
                ))}
            </div>
        </section>
    );
}
