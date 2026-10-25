import { Link } from '@inertiajs/react';
import SectionIcon from '../../Components/SectionIcon';
import AdminLayout from '../../Layouts/AdminLayout';
import { adminPath, sitePath } from '../../lib/site';

const QUICK_ACTION_SLUGS = ['settings', 'homepage', 'services', 'catalog'];

export default function Dashboard({ site, groups, totalPages, totalCustomised }) {
    // Build a flat map of slug -> page for quick lookup across all groups.
    const allPages = {};
    for (const pages of Object.values(groups ?? {})) {
        for (const [slug, page] of Object.entries(pages)) {
            allPages[slug] = page;
        }
    }

    const quickActions = QUICK_ACTION_SLUGS.filter((slug) => slug in allPages).map((slug) => {
        const page = allPages[slug];
        return {
            slug,
            label: page.label,
            description: page.description,
            icon: page.icon,
        };
    });

    return (
        <AdminLayout title="Përmbledhja">
            <section className="admin-welcome">
                <div>
                    <span className="admin-kicker">CMS</span>
                    <h2>Mirë se vini në panelin e përmbajtjes</h2>
                    <p>Editojni tekstet, imazhet dhe butonat e faqeve publike pa shkruar kod. Filloni nga veprimet më poshtë ose nga menja e majtas.</p>
                </div>
                <Link href={sitePath(site.locale)} target="_blank" rel="noopener noreferrer" className="admin-button admin-button--primary">
                    <span className="admin-nav__icon"><SectionIcon name="external" /></span>Shfaq faqen publike
                </Link>
            </section>
            <div className="admin-metrics">
                {[
                    ['Faqe edituese', totalPages, 'home', '#seksionet', false],
                    ['Të personalizuara', totalCustomised, 'edit', '#seksionet', false],
                    ['Faqja publike', 'Live', 'external', sitePath(site.locale), true],
                ].map(([label, value, iconName, href, isExternal]) => (
                    <div className="admin-metric" key={label}>
                        <div className={`admin-metric__icon admin-metric__icon--green`}>
                            <SectionIcon name={iconName} className="admin-icon" />
                        </div>
                        <div className="admin-metric__label">{label}</div>
                        <strong>{value}</strong>
                        {isExternal
                            ? <a href={href} target="_blank" rel="noopener noreferrer">{label === 'Faqja publike' ? 'Shfaq faqen' : 'Shiko seksionet'}</a>
                            : <a href={href}>{label === 'Faqja publike' ? 'Shfaq faqen' : 'Shiko seksionet'}</a>}
                    </div>
                ))}
            </div>
            <section className="admin-quick-panel">
                <div className="admin-panel__heading">
                    <div>
                        <h2>Veprime të shpejta</h2>
                        <p>Seksionet që redaktohet më e shpeshtë.</p>
                    </div>
                </div>
                <div className="admin-quick-links">
                    {quickActions.map((action) => (
                        <Link href={adminPath(`content/${action.slug}/${site.defaultLocale}`)} key={action.slug} className="admin-quick-link">
                            <span className="admin-quick-link__icon">
                                <SectionIcon name={action.icon} className="admin-icon" />
                            </span>
                            <span className="admin-quick-link__body">
                                <strong>{action.label}</strong>
                                <small>{action.description}</small>
                            </span>
                            <span className="admin-quick-link__arrow"><SectionIcon name="arrow" /></span>
                        </Link>
                    ))}
                </div>
                <p className="admin-quick-note">
                    Të gjitha seksionet e tjetra janë në <strong>menynë e majtas</strong>.
                </p>
            </section>
        </AdminLayout>
    );
}
