import { Link, router } from '@inertiajs/react';
import AdminLayout from '../../../Layouts/AdminLayout';
import SectionIcon from '../../../Components/SectionIcon';
import { adminPath, sitePath } from '../../../lib/site';

export default function CatalogIndex(props) {
    const { site, batteries, filters, total, availableCount, categories, brands, applicationTypes, statuses, statusLabels, stockStatuses, preorderCount, applicationLabels } = props;
    const values = filters ?? {};
    const pageRows = batteries?.data ?? [];
    const pageLinks = batteries?.links ?? [];

    const handleFilterSubmit = (e) => {
        e.preventDefault();
        const formData = new FormData(e.currentTarget);
        const params = {};
        for (const [key, value] of formData.entries()) {
            if (value) params[key] = value;
        }
        router.get(adminPath('catalog'), params, { preserveState: true, replace: true });
    };

    return (
        <AdminLayout title="Katalogu i baterive">
            <section className="admin-page-intro">
                <div>
                    <span className="admin-kicker">DATABAZA</span>
                    <h2>Katalogu i baterive</h2>
                    <p>
                        Këto janë bateritë që shfaqen në faqen publike{' '}
                        <a href={sitePath(site?.locale ?? 'sq', 'products')} target="_blank" rel="noopener noreferrer">
                            /products
                        </a>
                        . Shtoni, ndryshoni ose hiqni produkte këtu.
                    </p>
                </div>
                <div className="admin-inline-form">
                    <Link href={adminPath('catalog/create')} className="admin-button admin-button--primary inline-flex items-center gap-1.5">
                        <SectionIcon name="plus" /> Shto bateri
                    </Link>
                </div>
            </section>

            <div className="admin-metrics">
                <div className="admin-metric"><div className="admin-metric__label">Bateri totale</div><strong>{total}</strong></div>
                <div className="admin-metric"><div className="admin-metric__label">Të shfaqura në faqe</div><strong>{availableCount}</strong></div>
                <div className="admin-metric"><div className="admin-metric__label">Me porosi paraprake</div><strong>{preorderCount}</strong></div>
            </div>

            <section className="admin-panel">
                <div className="admin-panel__heading">
                    <div>
                        <span className="admin-kicker">KËRKO</span>
                        <h2>Filtrimi i katalogut</h2>
                    </div>
                </div>
                <form method="GET" action={adminPath('catalog')} onSubmit={handleFilterSubmit} className="admin-form-grid">
                    <label className="admin-field">
                        <span>Kërko</span>
                        <input type="search" name="q" defaultValue={values.q ?? ''} placeholder="Model, markë, seri ose kategori…" />
                    </label>
                    {[
                        ['category', 'Kategoria', categories],
                        ['brand', 'Marka', brands],
                        ['application_type', 'Aplikimi', applicationTypes],
                        ['status', 'Gjendja', statuses],
                        ['stock_status', 'Stok', Object.keys(stockStatuses ?? {})],
                        ['available', 'Shfaqja', ['1', '0']],
                    ].map(([key, label, options]) => (
                        <label className="admin-field" key={key}>
                            <span>{label}</span>
                            <select name={key} defaultValue={values[key] ?? ''}>
                                <option value="">Të gjitha</option>
                                {(options ?? []).map((value) => (
                                    <option key={value} value={value}>
                                        {key === 'available'
                                            ? (value === '1' ? 'Vetëm të shfaqura' : 'Vetëm të fshehura')
                                            : applicationLabels?.[value] ?? statusLabels?.[value] ?? stockStatuses?.[value] ?? value}
                                    </option>
                                ))}
                            </select>
                        </label>
                    ))}
                    <div className="admin-fieldset" style={{ gridColumn: '1 / -1', display: 'flex', gap: 10, alignItems: 'end' }}>
                        <button className="admin-button admin-button--primary" type="submit">Filtro</button>
                        <Link href={adminPath('catalog')} className="admin-button admin-button--secondary">Pastro</Link>
                    </div>
                </form>
            </section>

            <section className="admin-panel">
                <div className="admin-panel__heading">
                    <div>
                        <span className="admin-kicker">REZULTATE</span>
                        <h2>{batteries?.total ?? 0} bateri</h2>
                    </div>
                </div>
                {pageRows.length === 0 ? (
                    <p className="admin-repeater__empty">Nuk u gjet asnjë bateri me këto kritere.</p>
                ) : (
                    <>
                        <div style={{ overflowX: 'auto' }}>
                            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.86rem' }}>
                                <thead>
                                    <tr style={{ textAlign: 'left', color: '#6b7686', fontSize: '0.72rem', textTransform: 'uppercase', letterSpacing: '0.04em' }}>
                                        {['Modeli', 'Marka', 'Kategoria', 'Ah', 'Aplikimi', 'Gjendja', 'Stok', 'Shfaqja', 'Veprimi'].map((label) => (
                                            <th key={label} style={{ padding: '8px 10px' }}>{label}</th>
                                        ))}
                                    </tr>
                                </thead>
                                <tbody>
                                    {pageRows.map((battery) => (
                                        <tr key={battery.id} style={{ borderTop: '1px solid #e6e9ef' }}>
                                            <td style={{ padding: 10 }}>
                                                <strong>{battery.model || '—'}</strong>
                                                {battery.serial_number && (
                                                    <>
                                                        <br />
                                                        <small style={{ color: '#6b7686' }}>{battery.serial_number}</small>
                                                    </>
                                                )}
                                            </td>
                                            <td style={{ padding: 10 }}>{battery.brand}</td>
                                            <td style={{ padding: 10 }}>{battery.category || '—'}</td>
                                            <td style={{ padding: 10 }}>{battery.capacity_ah}</td>
                                            <td style={{ padding: 10 }}>{applicationLabels?.[battery.application_type] ?? battery.application_type ?? '—'}</td>
                                            <td style={{ padding: 10 }}>
                                                <span className={`admin-badge ${battery.status === 'new' ? 'admin-badge--green' : ''}`}>
                                                    {statusLabels?.[battery.status] ?? battery.status}
                                                </span>
                                            </td>
                                            <td style={{ padding: 10 }}>
                                                <span className={`admin-badge ${battery.stock_status === 'in_stock' ? 'admin-badge--green' : battery.stock_status === 'low_stock' ? 'admin-badge--amber' : ''}`}>
                                                    {stockStatuses?.[battery.stock_status] ?? battery.stock_status}
                                                </span>
                                            </td>
                                            <td style={{ padding: 10 }}>
                                                <span className={`admin-badge ${battery.is_available ? 'admin-badge--green' : ''}`}>
                                                    {battery.is_available ? 'Po' : 'Jo'}
                                                </span>
                                            </td>
                                            <td style={{ padding: 10, textAlign: 'right', whiteSpace: 'nowrap' }}>
                                                <Link href={adminPath(`catalog/${battery.id}/edit`)} className="admin-button admin-button--secondary inline-flex items-center gap-1 mr-1">
                                                    <SectionIcon name="edit" /> Ndrysho
                                                </Link>
                                                <form
                                                    method="POST"
                                                    action={adminPath(`catalog/${battery.id}`)}
                                                    style={{ display: 'inline' }}
                                                    onSubmit={(event) => {
                                                        if (!window.confirm(`Fshihet "${battery.model}"? Nuk mund të kthehet.`)) {
                                                            event.preventDefault();
                                                        }
                                                    }}
                                                >
                                                    <input type="hidden" name="_token" value={site?.csrfToken ?? ''} />
                                                    <input type="hidden" name="_method" value="DELETE" />
                                                    <button type="submit" className="admin-button admin-button--remove inline-flex items-center gap-1">
                                                        <SectionIcon name="trash" /> Hiq
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                        <div className="admin-save-bar">
                            <span>{batteries.from}–{batteries.to} nga {batteries.total}</span>
                            <div className="flex gap-2">
                                {pageLinks.map((link, index) => link.url ? (
                                    <Link
                                        key={`${link.label}-${index}`}
                                        href={link.url}
                                        aria-current={link.active ? 'page' : undefined}
                                        className={`admin-button admin-button--secondary ${link.active ? 'is-active' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ) : (
                                    <span
                                        key={`${link.label}-${index}`}
                                        className="admin-button admin-button--secondary"
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </div>
                        </div>
                    </>
                )}
            </section>
        </AdminLayout>
    );
}
