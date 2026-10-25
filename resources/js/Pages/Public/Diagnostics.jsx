import { useForm, usePage } from '@inertiajs/react';
import CtaBand from '../../Components/CtaBand';
import DropOffPoints from '../../Components/DropOffPoints';
import PageHero from '../../Components/PageHero';
import SectionIcon from '../../Components/SectionIcon';
import PublicLayout from '../../Layouts/PublicLayout';
import { errorList, fieldError as firstError } from '../../lib/errors';
import { sitePath } from '../../lib/site';

export default function Diagnostics({ site, page, dropOffPoints, title, description, today }) {
    const { flash, errors: pageErrors, oldInput } = usePage().props;
    const value = (key) => oldInput?.[key] ?? '';
    const { data, setData, post, processing, errors: formErrors, reset } = useForm({
        customer_name: value('customer_name'),
        company_name: value('company_name'),
        email: value('email'),
        phone: value('phone'),
        sector: value('sector'),
        battery_type: value('battery_type'),
        preferred_date: value('preferred_date'),
        service_preference: value('service_preference'),
        notes: value('notes'),
    });

    const activeErrors = { ...pageErrors, ...formErrors };
    const fieldError = (key) => firstError(activeErrors, key);
    const summaryErrors = errorList(activeErrors);

    const handleSubmit = (event) => {
        event.preventDefault();
        post(sitePath(site.locale, 'diagnostics/bookings'), {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    };

    return (
        <PublicLayout site={site} activeNav="diagnostics" title={title} description={description}>
            <PageHero site={site} eyebrow={page.eyebrow} title={page.title} description={page.description} />
            <section className="page-section">
                <div className="section-shell">
                    <div className="form-shell">
                        <div className="form-card">
                            {flash?.status && (
                                <div className="status-alert inline-flex items-center gap-2" role="status">
                                    <SectionIcon name="check" className="h-5 w-5 shrink-0" />
                                    <span>{flash.status}</span>
                                </div>
                            )}
                            {summaryErrors.length > 0 && (
                                <div className="error-summary" role="alert">
                                    <div className="inline-flex items-center gap-2 mb-2">
                                        <SectionIcon name="alert" className="h-5 w-5 shrink-0 text-red-600" />
                                        <strong>{page.error_summary}</strong>
                                    </div>
                                    <ul>{summaryErrors.map((error) => <li key={error}>{error}</li>)}</ul>
                                </div>
                            )}
                            <form method="POST" action={sitePath(site.locale, 'diagnostics/bookings')} onSubmit={handleSubmit} className="form-grid">
                                <input type="hidden" name="_token" value={site.csrfToken} />
                                <label className="field">
                                    <span className="field__label">{page.label_name}</span>
                                    <input className="field__control" name="customer_name" value={data.customer_name} onChange={(e) => setData('customer_name', e.target.value)} maxLength={255} required />
                                    {fieldError('customer_name') && <span className="field__error">{fieldError('customer_name')}</span>}
                                </label>
                                <label className="field">
                                    <span className="field__label">{page.label_company}</span>
                                    <input className="field__control" name="company_name" value={data.company_name} onChange={(e) => setData('company_name', e.target.value)} maxLength={255} />
                                    {fieldError('company_name') && <span className="field__error">{fieldError('company_name')}</span>}
                                </label>
                                <label className="field">
                                    <span className="field__label">{page.label_email}</span>
                                    <input className="field__control" type="email" name="email" value={data.email} onChange={(e) => setData('email', e.target.value)} maxLength={255} required />
                                    {fieldError('email') && <span className="field__error">{fieldError('email')}</span>}
                                </label>
                                <label className="field">
                                    <span className="field__label">{page.label_phone}</span>
                                    <input className="field__control" type="tel" name="phone" value={data.phone} onChange={(e) => setData('phone', e.target.value)} maxLength={50} required />
                                    {fieldError('phone') && <span className="field__error">{fieldError('phone')}</span>}
                                </label>
                                <label className="field">
                                    <span className="field__label">{page.label_sector}</span>
                                    <select className="field__control" name="sector" value={data.sector} onChange={(e) => setData('sector', e.target.value)} required>
                                        <option value="">{page.placeholder_select}</option>
                                        {Object.entries(page.sectors ?? {}).map(([key, label]) => <option key={key} value={key}>{label}</option>)}
                                    </select>
                                    {fieldError('sector') && <span className="field__error">{fieldError('sector')}</span>}
                                </label>
                                <label className="field">
                                    <span className="field__label">{page.label_battery_type}</span>
                                    <select className="field__control" name="battery_type" value={data.battery_type} onChange={(e) => setData('battery_type', e.target.value)} required>
                                        <option value="">{page.placeholder_select}</option>
                                        {Object.entries(page.battery_types ?? {}).map(([key, label]) => <option key={key} value={key}>{label}</option>)}
                                    </select>
                                    {fieldError('battery_type') && <span className="field__error">{fieldError('battery_type')}</span>}
                                </label>
                                <label className="field">
                                    <span className="field__label">{page.label_date}</span>
                                    <input className="field__control" type="date" name="preferred_date" value={data.preferred_date} onChange={(e) => setData('preferred_date', e.target.value)} min={today} required />
                                    {fieldError('preferred_date') && <span className="field__error">{fieldError('preferred_date')}</span>}
                                </label>
                                <label className="field">
                                    <span className="field__label">{page.label_service_preference}</span>
                                    <select className="field__control" name="service_preference" value={data.service_preference} onChange={(e) => setData('service_preference', e.target.value)} required>
                                        <option value="">{page.placeholder_select}</option>
                                        {Object.entries(page.service_preferences ?? {}).map(([key, label]) => <option key={key} value={key}>{label}</option>)}
                                    </select>
                                    {fieldError('service_preference') && <span className="field__error">{fieldError('service_preference')}</span>}
                                </label>
                                <label className="field field--wide">
                                    <span className="field__label">{page.label_notes}</span>
                                    <textarea className="field__control" name="notes" maxLength={1500} value={data.notes} onChange={(e) => setData('notes', e.target.value)} />
                                    {fieldError('notes') && <span className="field__error">{fieldError('notes')}</span>}
                                </label>
                                <div className="form-actions field--wide">
                                    <button className="primary-cta" type="submit" disabled={processing}>{processing ? (site.locale === 'en' ? 'Submitting…' : 'Duke dërguar…') : page.submit_label}</button>
                                    <span className="field__hint">{page.submit_hint}</span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <DropOffPoints section={dropOffPoints} />
            <CtaBand
                title={page.cta_title}
                description={page.cta_description}
                primaryLabel={page.cta_primary}
                primaryUrl={sitePath(site.locale, page.cta_primary_target ?? 'services')}
                secondaryLabel={page.cta_secondary}
                secondaryUrl={sitePath(site.locale, page.cta_secondary_target ?? 'resources')}
            />
        </PublicLayout>
    );
}
