import { useState } from 'react';
import { Link } from '@inertiajs/react';
import CtaBand from '../../Components/CtaBand';
import DropOffPoints from '../../Components/DropOffPoints';
import PageHero from '../../Components/PageHero';
import SectionIcon from '../../Components/SectionIcon';
import PublicLayout from '../../Layouts/PublicLayout';
import { sitePath } from '../../lib/site';

export default function Services({ site, page, dropOffPoints, title, description }) {
    const [feedback, setFeedback] = useState(null);
    const [sending, setSending] = useState(false);

    const submitInquiry = async (event) => {
        event.preventDefault();
        setSending(true);
        setFeedback(null);

        const form = event.currentTarget;
        const payload = Object.fromEntries(new FormData(form));
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': site.csrfToken,
            },
            body: JSON.stringify(payload),
        });
        const result = await response.json();
        setSending(false);

        if (!response.ok) {
            setFeedback({ type: 'error', message: Object.values(result.errors ?? {}).flat().join(' ') || result.message });
            return;
        }

        form.reset();
        setFeedback({ type: 'success', message: result.message });
    };

    const showSecondary = page.hero_cta_secondary
        && page.hero_cta_secondary_target !== 'calculator'
        && !['kalkulator kursimi', 'savings calculator'].includes(page.hero_cta_secondary.trim().toLowerCase());

    return (
        <PublicLayout site={site} activeNav="services" title={title} description={description}>
            <PageHero site={site} eyebrow={page.eyebrow} title={page.title} description={page.description}>
                <Link className="primary-cta" href={sitePath(site.locale, 'diagnostics')}>{page.hero_cta_primary}</Link>
                {showSecondary && <Link className="secondary-cta" href={sitePath(site.locale, page.hero_cta_secondary_target)}>{page.hero_cta_secondary}</Link>}
            </PageHero>
            <section className="page-section">
                <div className="section-shell">
                    <div className="service-detail-grid">
                        {(page.items ?? []).map((item, index) => (
                            <article className="service-detail" key={`${item.title}-${index}`}>
                                <SectionIcon name={item.icon} className="service-detail__icon"/>
                                <div><h3>{item.title}</h3><p>{item.description}</p>{item.features?.length > 0 && <ul className="feature-list">{item.features.map((feature) => <li key={feature}>{feature}</li>)}</ul>}</div>
                            </article>
                        ))}
                    </div>
                    <div className="page-hero__actions"><Link className="link-cta link-cta--green inline-flex items-center gap-1" href={sitePath(site.locale, page.detail_link_target ?? 'regeneration')}><span>{page.detail_link}</span> <SectionIcon name="arrow" className="h-3.5 w-3.5" /></Link></div>
                </div>
            </section>
            <DropOffPoints section={dropOffPoints}/>
            <section className="page-section page-section--soft">
                <div className="section-shell">
                    <div className="section-intro section-intro--small"><span className="eyebrow">{page.maintenance_eyebrow}</span><h2>{page.maintenance_title}</h2><p>{page.maintenance_description}</p></div>
                    <div className="form-shell"><div className="form-card">
                        {feedback && (
                            <div className={`status-alert inline-flex items-center gap-2 ${feedback.type === 'error' ? 'status-alert--error' : ''}`} role={feedback.type === 'error' ? 'alert' : 'status'}>
                                <SectionIcon name={feedback.type === 'error' ? 'alert' : 'check'} className="h-5 w-5 shrink-0" />
                                <span>{feedback.message}</span>
                            </div>
                        )}
                        <form method="POST" action="/api/maintenance-inquiries" onSubmit={submitInquiry} className="form-grid">
                            <label className="field"><span className="field__label">{page.form_company_label}</span><input className="field__control" name="company_name" maxLength={255} required/></label>
                            <label className="field"><span className="field__label">{page.form_contact_label}</span><input className="field__control" name="contact_name" maxLength={255} required/></label>
                            <label className="field"><span className="field__label">{page.form_email_label}</span><input className="field__control" type="email" name="email" maxLength={255} required/></label>
                            <label className="field"><span className="field__label">{page.form_phone_label}</span><input className="field__control" type="tel" name="phone" maxLength={50} required/></label>
                            <label className="field"><span className="field__label">{page.form_sector_label}</span><select className="field__control" name="sector" required>{Object.entries(page.form_sectors ?? {}).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select></label>
                            <label className="field"><span className="field__label">{page.form_fleet_label}</span><input className="field__control" type="number" name="fleet_size" min="1" step="1" defaultValue={page.form_fleet_default} required/></label>
                            <label className="field field--wide"><span className="field__label">{page.form_requirements_label}</span><textarea className="field__control" name="requirements" maxLength={2000} required/><span className="field__hint">{page.form_requirements_hint}</span></label>
                            <label className="field field--wide"><span className="field__label">{page.form_message_label}</span><textarea className="field__control" name="message" maxLength={2000}/></label>
                            <div className="form-actions field--wide"><button className="primary-cta" type="submit" disabled={sending}>{sending ? (site.locale === 'en' ? 'Sending…' : 'Duke dërguar…') : page.form_submit}</button><span className="field__hint">{page.form_hint}</span></div>
                        </form>
                    </div></div>
                </div>
            </section>
            <CtaBand title={page.cta_title} description={page.cta_description} primaryLabel={page.cta_primary} primaryUrl={sitePath(site.locale, 'diagnostics')} secondaryLabel={page.cta_secondary} secondaryUrl={sitePath(site.locale, page.cta_secondary_target ?? 'products')}/>
        </PublicLayout>
    );
}
