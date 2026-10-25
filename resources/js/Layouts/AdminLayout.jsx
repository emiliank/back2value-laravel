import { Head, Link, usePage } from '@inertiajs/react';
import SectionIcon from '../Components/SectionIcon';
import { errorList } from '../lib/errors';
import { adminPath, sitePath } from '../lib/site';

export default function AdminLayout({ title, children }) {
    const page = usePage();
    const { site, auth, flash, errors } = page.props;
    const currentPath = page.url.split('?')[0];
    const pages = site.adminPages ?? {};
    const user = auth?.user;
    const pageEntries = Object.entries(pages);
    const summaryErrors = errorList(errors);

    return (
        <div className="admin-body">
            <Head title={`${title ?? 'Përmbledhja'} · Back2Value CMS`}>
                <meta name="robots" content="noindex, nofollow" />
            </Head>
            <div className="admin-shell">
                <aside className="admin-sidebar">
                    <Link href={adminPath()} className="admin-brand">
                        <span className="brand-box admin-brand__logo">{site.logo && <img src={site.logo} alt="" className="admin-brand__logo__image" />}</span>
                        <span className="admin-brand__caption">Back2Value<small>CMS</small></span>
                    </Link>
                    <nav className="admin-nav">
                        <Link className={currentPath === adminPath() || currentPath === `${adminPath()}/` ? 'is-active' : ''} href={adminPath()}>
                            <span className="admin-nav__icon"><SectionIcon name="home" /></span>Përmbledhja
                        </Link>
                        {pageEntries.map(([slug, page], index) => {
                            const previousGroup = pageEntries[index - 1]?.[1]?.group;
                            return (
                                <div key={slug}>
                                    {previousGroup !== page.group && <span className="admin-nav__label">{page.group}</span>}
                                    <Link className={currentPath.includes(`/content/${slug}/`) ? 'is-active' : ''} href={adminPath(`content/${slug}/${site.defaultLocale}`)}>
                                        <span className="admin-nav__icon"><SectionIcon name={page.icon} /></span>{page.label}
                                    </Link>
                                </div>
                            );
                        })}
                    </nav>
                    <div className="admin-sidebar__bottom">
                        <a href={sitePath(site.locale)} target="_blank" rel="noopener noreferrer" className="admin-site-link">
                            <span className="admin-nav__icon"><SectionIcon name="external" /></span>Shfaq faqen
                        </a>
                        <form method="POST" action={adminPath('logout')}>
                            <input type="hidden" name="_token" value={site.csrfToken} />
                            <button type="submit" className="admin-logout">
                                <span className="admin-nav__icon"><SectionIcon name="logout" /></span>Dil
                            </button>
                        </form>
                    </div>
                </aside>
                <main className="admin-main">
                    <header className="admin-topbar">
                        <div>
                            <p><span>CMS</span> / {title ?? 'Përmbledhja'}</p>
                            <h1>{title ?? 'Përmbledhja'}</h1>
                        </div>
                        <div className="admin-topbar__user">
                            <span className="admin-avatar">{user?.name?.slice(0, 1).toUpperCase() ?? 'A'}</span>
                            <span>{user?.name ?? 'Admin'}</span>
                        </div>
                    </header>
                    {flash?.status && (
                        <div className="admin-alert admin-alert--success inline-flex items-center gap-2" role="status">
                            <SectionIcon name="check" className="h-4 w-4 shrink-0" />
                            <span>{flash.status}</span>
                        </div>
                    )}
                    {summaryErrors.length > 0 && (
                        <div className="admin-alert admin-alert--error" role="alert">
                            <div className="inline-flex items-center gap-2 mb-1.5">
                                <SectionIcon name="alert" className="h-4 w-4 shrink-0" />
                                <strong>Plotësoni gabimet më poshtë:</strong>
                            </div>
                            <ul>{summaryErrors.map((message) => <li key={message}>{message}</li>)}</ul>
                        </div>
                    )}
                    {children}
                </main>
            </div>
        </div>
    );
}
