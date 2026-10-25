import { Link, usePage } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import SectionIcon from '../../../Components/SectionIcon';
import { fieldError } from '../../../lib/errors';
import { adminPath, sitePath } from '../../../lib/site';

function FieldError({ name }) {
    const { errors } = usePage().props;
    const message = fieldError(errors, name);
    return message ? <span className="admin-field-error">{message}</span> : null;
}

export default function CatalogForm(props) {
    const { site, battery, action, method, title, categories, brands, technologies, applicationTypes, statusLabels, stockStatuses, specKeys } = props;
    const field = (label, name, value, options = {}) => (
        <label className={`admin-field ${options.wide ? 'admin-field--wide' : ''}`} key={name}>
            <span>{label}{options.required && <em> *</em>}</span>
            {options.select
                ? <select name={name} defaultValue={value ?? ''} required={options.required}>{options.empty && <option value="">Pa caktuar</option>}{Object.entries(options.select).map(([key, option]) => <option key={key} value={key}>{option}</option>)}</select>
                : <input type={options.type ?? 'text'} name={name} defaultValue={value ?? ''} required={options.required} min={options.min} step={options.step} list={options.list}/>}
            {options.hint && <small>{options.hint}</small>}<FieldError name={name}/>
        </label>
    );

    return (
        <AdminLayout title={title}>
            <section className="admin-page-intro">
                <div>
                    <span className="admin-kicker">KATALOGU</span>
                    <h2>{title}</h2>
                    <p>Ndryshimet shfaqen menjëherë në faqen publike <a href={sitePath(site?.locale ?? 'sq', 'products')} target="_blank" rel="noopener noreferrer">/products</a>.</p>
                </div>
                <Link href={adminPath('catalog')} className="admin-button admin-button--secondary inline-flex items-center gap-1.5">
                    <SectionIcon name="chevron-left" className="w-3.5 h-3.5" /> Kthehu te lista
                </Link>
            </section>
            <form method="POST" action={action} className="admin-form-grid">
                <input type="hidden" name="_token" value={site.csrfToken}/>{method !== 'POST' && <input type="hidden" name="_method" value={method}/>}
                <section className="admin-panel"><div className="admin-panel__heading"><div><span className="admin-kicker">TË DHËNAT</span><h2>Identifikimi</h2></div></div><div className="admin-form-grid">
                    {field('Marka', 'brand', battery.brand, { required: true, list: 'brand-options' })}<datalist id="brand-options">{brands.map((brand) => <option key={brand} value={brand}/>)}</datalist>
                    {field('Modeli', 'model', battery.model, { required: true })}
                    {field('Numri i serës', 'serial_number', battery.serial_number)}
                    {field('Kategoria', 'category', battery.category, { list: 'category-options', hint: 'Përdoret për grupimin në faqen publike.' })}<datalist id="category-options">{categories.map((category) => <option key={category} value={category}/>)}</datalist>
                    {field('Teknologjia', 'technology', battery.technology, { list: 'technology-options' })}<datalist id="technology-options">{technologies.map((technology) => <option key={technology} value={technology}/>)}</datalist>
                    {field('Aplikimi', 'application_type', battery.application_type, { select: Object.fromEntries(applicationTypes.map((type) => [type, type])), empty: true })}
                    {field('Kapaciteti (Ah)', 'capacity_ah', battery.capacity_ah, { type: 'number', min: 1, required: true })}
                    {field('Voltazhi', 'voltage', battery.voltage)}
                    {field('Gjendja e stokut', 'stock_status', battery.stock_status, { select: stockStatuses, required: true, hint: 'Produktet jashtë stokut apo me porosi paraprake mbeten të dukshme në faqe.' })}
                    <label className="admin-field admin-field--checkbox"><input type="checkbox" name="is_available" value="1" defaultChecked={Boolean(battery.is_available)}/><span>Shfaq në faqen publike</span></label>
                </div></section>
                <section className="admin-panel"><div className="admin-panel__heading"><div><span className="admin-kicker">SPECIFIKAT</span><h2>Detajet teknike</h2><p>Shfaqen si tabela në kartën e produktit.</p></div></div><div className="admin-form-grid">
                    {specKeys.map((key) => field(key.replaceAll('_', ' '), `specs[${key}]`, battery.specs?.[key]))}
                    <label className="admin-field admin-field--wide"><span>Përshkrimi</span><textarea name="description" rows="4" defaultValue={battery.description ?? ''}/><FieldError name="description"/></label>
                </div></section>
                <section className="admin-panel"><div className="admin-panel__heading"><div><span className="admin-kicker">ÇMIMI</span><h2>Shitja dhe garancia</h2></div></div><div className="admin-form-grid">
                    {field('Gjendja', 'status', battery.status, { select: statusLabels, required: true })}
                    {field('Çmimi i blerjes', 'purchase_price', battery.purchase_price, { type: 'number', min: 0, step: '0.01' })}
                    {field('Çmimi i shitjes', 'sale_price', battery.sale_price, { type: 'number', min: 0, step: '0.01' })}
                    {field('Garancia (muaj)', 'warranty_months', battery.warranty_months, { type: 'number', min: 0 })}
                </div></section>
                <div className="admin-save-bar">
                    <span>{method === 'POST' ? 'Ruajtja do të shtojë baterinë në katalog.' : 'Ruaj ndryshimet për këtë bateri.'}</span>
                    <div className="flex items-center gap-2">
                        <Link href={adminPath('catalog')} className="admin-button admin-button--secondary">Anulo</Link>
                        <button type="submit" className="admin-button admin-button--primary inline-flex items-center gap-1.5">
                            <SectionIcon name="check" /> Ruaj baterinë
                        </button>
                    </div>
                </div>
            </form>
        </AdminLayout>
    );
}
