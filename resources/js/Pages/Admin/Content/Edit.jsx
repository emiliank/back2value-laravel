import { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import SectionIcon from '../../../Components/SectionIcon';
import AdminLayout from '../../../Layouts/AdminLayout';
import { fieldError } from '../../../lib/errors';
import { adminPath, imageUrl } from '../../../lib/site';

function getPathValue(source, path) {
    return path.split('.').reduce((value, key) => value?.[key], source);
}

function inputName(path) {
    const [head, ...tail] = path.split('.');
    return `${head}${tail.map((part) => `[${part}]`).join('')}`;
}

function acceptFor(mimes) {
    const map = new Map([
        ['jpg', 'image/jpeg'], ['jpeg', 'image/jpeg'], ['png', 'image/png'],
        ['webp', 'image/webp'], ['svg', 'image/svg+xml'], ['pdf', 'application/pdf'],
    ]);
    return Array.from(new Set(
        mimes.split(',').map((ext) => map.get(ext.trim().toLowerCase())).filter(Boolean),
    )).join(',');
}

function textValue(field, value) {
    if (field.type === 'list' && Array.isArray(value)) {
        return value.join('\n');
    }

    if (field.type === 'pairs' && value && typeof value === 'object') {
        return Object.entries(value).map(([key, item]) => `${key}: ${item}`).join('\n');
    }

    return value ?? '';
}

function Field({ path, name, field, value, errors }) {
    const fieldName = name ?? inputName(path);
    const fieldId = `field-${path.replace(/[^a-zA-Z0-9_-]/g, '-')}`;
    const error = fieldError(errors, path);
    const errorId = `${fieldId}-error`;
    const hint = field.hint;
    const wide = field.wide ? ' admin-field--wide' : '';

    if (field.type === 'checkbox') {
        return <label className={`admin-field admin-field--checkbox${wide}`}><input type="hidden" name={fieldName} value="0"/><input type="checkbox" id={fieldId} name={fieldName} value="1" defaultChecked={Boolean(value)}/><span>{field.label}</span>{error && <span className="admin-field-error">{error}</span>}</label>;
    }

    if (field.type === 'repeater') {
        return <Repeater path={path} name={fieldName} field={field} rows={value ?? []} errors={errors}/>;
    }

    if (field.type === 'image') {
        const src = imageUrl(value);
        const isPdf = typeof value === 'string' && value.toLowerCase().endsWith('.pdf');
        const namePrefix = fieldName.replace(/\[[^\]]+\]$/, '');
        const nameSuffix = fieldName.slice(namePrefix.length + 1, -1);
        const pathName = `${namePrefix}[${nameSuffix}_path]`;
        const removeName = `${namePrefix}[${nameSuffix}_remove]`;
        const accept = field.mimes ? acceptFor(field.mimes) : 'image/jpeg,image/png,image/webp,image/svg+xml,application/pdf';
        return <div className="admin-field admin-field--wide admin-image-field"><span>{field.label}</span>{src && isPdf && (<div className="admin-product-preview"><span className="certificate-pdf-badge"><SectionIcon name="document" className="h-6 w-6" /> PDF</span></div>)}{src && !isPdf && (<div className="admin-product-preview"><img src={src} alt="" loading="lazy"/></div>)}{src && (<label className="admin-inline-checkbox"><input type="checkbox" name={removeName} value="1" />{src && isPdf ? <span>Hiq certifikatën</span> : <span>Hiq imazhin aktual</span>}</label>)}{hint && <small>{hint}</small>}<input type="hidden" name={pathName} value={value ?? ''}/><input type="file" id={fieldId} name={fieldName} accept={accept} aria-invalid={error ? 'true' : undefined}/>{error && <span className="admin-field-error">{error}</span>}</div>;
    }

    if (field.type === 'select') {
        return <label className="admin-field"><span>{field.label}</span><select id={fieldId} name={fieldName} defaultValue={value ?? ''} aria-invalid={error ? 'true' : undefined} aria-describedby={error ? errorId : undefined}>{field.optional && <option value="">— Pa zgjedhje —</option>}{Object.entries(field.options ?? {}).map(([optionValue, optionLabel]) => <option key={optionValue} value={optionValue}>{optionLabel}</option>)}</select>{hint && <small>{hint}</small>}{error && <span className="admin-field-error" id={errorId}>{error}</span>}</label>;
    }

    if (['textarea', 'list', 'pairs'].includes(field.type)) {
        return <label className={`admin-field${wide}`}><span>{field.label}</span><textarea id={fieldId} name={fieldName} rows={field.rows ?? 4} maxLength={field.max ?? undefined} defaultValue={textValue(field, value)} aria-invalid={error ? 'true' : undefined} aria-describedby={error ? errorId : undefined}/>{hint && <small>{hint}</small>}{error && <span className="admin-field-error" id={errorId}>{error}</span>}</label>;
    }

    return <label className={`admin-field${wide}`}><span>{field.label}</span><input type={field.type === 'number' ? 'number' : field.input ?? 'text'} min={field.type === 'number' ? 0 : undefined} id={fieldId} name={fieldName} defaultValue={value ?? ''} maxLength={field.max ?? undefined} aria-invalid={error ? 'true' : undefined} aria-describedby={error ? errorId : undefined}/>{hint && <small>{hint}</small>}{error && <span className="admin-field-error" id={errorId}>{error}</span>}</label>;
}

function Repeater({ path, name, field, rows = [], errors }) {
    const [items, setItems] = useState(() => Object.entries(rows).map(([key, row], index) => ({
        key: typeof key === 'string' && key.startsWith('item-') ? key : `item-${index}`,
        value: row,
        removed: false,
    })));
    const titleField = field.title_field ?? 'title';

    const addRow = () => {
        const key = `item-new-${Date.now()}-${Math.random().toString(36).slice(2, 7)}`;
        setItems((current) => [...current, { key, value: {}, removed: false }]);
    };

    return <div className="admin-repeater" data-repeater={path}>
        <div className="admin-repeater__head"><span className="admin-subheading">{field.label}</span><button className="admin-button admin-button--secondary inline-flex items-center gap-1.5" type="button" onClick={addRow}><SectionIcon name="plus" className="h-4 w-4" /> <span>{field.add_label ?? 'Shto rresht'}</span></button></div>
        <div className="admin-repeater__list">
            {items.length === 0 && <p className="admin-repeater__empty">Nuk ka ende rreshta. Shtoni rreshtin e parë me butonin sipër.</p>}
            {items.map((item) => {
                const rowPath = `${path}.${item.key}`;
                const rowName = `${name}[${item.key}]`;
                return <article className="admin-panel admin-repeat-card" data-repeat-card key={item.key} hidden={item.removed}>
                    <div className="admin-repeat-card__heading"><div><span className="admin-kicker">RRESHT</span><h3>{item.value?.[titleField] || 'Rresht i ri'}</h3></div><div className="admin-repeat-card__actions"><button className="admin-button admin-button--remove inline-flex items-center gap-1" type="button" onClick={() => setItems((current) => current.map((row) => row.key === item.key ? { ...row, removed: true } : row))}><SectionIcon name="trash" className="h-3.5 w-3.5" /> <span>Hiq</span></button></div></div>
                    <input type="hidden" name={`${rowName}[remove]`} value={item.removed ? '1' : '0'}/>
                    <div className="admin-form-grid">{(field.fields ?? []).map((child) => {
                        const childPath = `${rowPath}.${child.key}`;
                        return <Field key={child.key} path={childPath} name={`${rowName}[${child.key}]`} field={child} value={item.value?.[child.key]} errors={errors}/>;
                    })}</div>
                </article>;
            })}
        </div>
    </div>;
}

function ContentField({ sectionKey, field, value, errors }) {
    const path = `${sectionKey}.${field.key}`;
    const name = inputName(path);

    if (field.type === 'repeater') {
        return <Repeater path={path} name={name} field={field} rows={value ?? []} errors={errors}/>;
    }

    return <Field path={path} name={name} field={field} value={value} errors={errors}/>;
}

export default function EditContent({ site, page, slug, sectionDefinitions, content, locales, locale, title }) {
    const { errors, oldInput } = usePage().props;
    const pageTitle = title ?? page.label;

    return (
        <AdminLayout title={pageTitle}>
            <section className="admin-page-intro"><div><span className="admin-kicker">{page.group}</span><h2>{page.label}</h2><p>{page.description}</p></div>
                <form method="POST" action={adminPath(`content/${slug}/${locale}/reset`)} onSubmit={(event) => { if (!window.confirm('Ktheni të gjithë fushat e kësaj faqeje te vlerat fillestare?')) event.preventDefault(); }}><input type="hidden" name="_token" value={site.csrfToken}/><button className="admin-button admin-button--secondary inline-flex items-center gap-1.5" type="submit"><SectionIcon name="refresh" className="h-3.5 w-3.5" /> <span>Kthe te default</span></button></form>
            </section>
            <nav className="admin-locale-tabs" aria-label="Gjuha e përmbajtjes">{Object.entries(locales ?? {}).map(([code, meta]) => <Link key={code} href={adminPath(`content/${slug}/${code}`)} className={code === locale ? 'is-active' : ''}><span aria-hidden="true">{meta.flag}</span> {meta.native ?? code.toUpperCase()}</Link>)}</nav>
            <form method="POST" action={adminPath(`content/${slug}/${locale}`)} encType="multipart/form-data" className="admin-form">
                <input type="hidden" name="_token" value={site.csrfToken}/><input type="hidden" name="_method" value="PUT"/><input type="hidden" name="locale" value={locale}/>
                {(page.sections ?? []).map((sectionKey) => {
                    const section = sectionDefinitions[sectionKey];
                    if (!section) return null;
                    return <section className="admin-panel admin-content-section" key={sectionKey}>
                        <div className="admin-panel__heading"><div><span className="admin-kicker">{section.kicker}</span><h2>{section.title}</h2><p>{section.description}</p></div></div>
                        {section.shape === 'list'
                            ? <Repeater path={sectionKey} name={sectionKey} field={{ ...section, ...sectionDefinitions[sectionKey], ...section.repeater, type: 'repeater', label: section.title, fields: section.fields ?? section.groups?.[0]?.fields ?? [] }} rows={getPathValue(oldInput, sectionKey) ?? content?.[sectionKey] ?? []} errors={errors}/>
                            : (section.groups ?? []).map((group, groupIndex) => <div className="admin-content-group" key={`${sectionKey}-${groupIndex}`}><span className="admin-subheading">{group.label}</span><div className="admin-form-grid">{(group.fields ?? []).map((field) => {
                                const path = `${sectionKey}.${field.key}`;
                                const currentValue = getPathValue(oldInput, path) ?? getPathValue(content, path);
                                return <ContentField key={field.key} sectionKey={sectionKey} field={field} value={currentValue} errors={errors}/>;
                            })}</div></div>)}
                    </section>;
                })}
                <div className="admin-save-bar"><span>{page.sections?.length ?? 0} seksione në këtë faqe.</span><button className="admin-button admin-button--primary inline-flex items-center gap-2" type="submit"><span>Ruaj ndryshimet</span> <span className="admin-inline-icon"><SectionIcon name="check" className="h-4 w-4" /></span></button></div>
            </form>
        </AdminLayout>
    );
}
