import { Head, Link, usePage } from '@inertiajs/react';
import { fieldError } from '../../lib/errors';
import SectionIcon from '../../Components/SectionIcon';

export default function Login({ site, adminConfigured }) {
    const { errors, oldInput } = usePage().props;
    const emailError = fieldError(errors, 'email');
    const passwordError = fieldError(errors, 'password');
    const homeUrl = `/${site?.locale ?? 'sq'}`;

    return (
        <div className="admin-login-body">
            <Head title="Hyrja në panel · Back2Value"><meta name="robots" content="noindex, nofollow"/></Head>
            <main className="admin-login-card">
                <Link href={homeUrl} className="admin-login-brand" aria-label="Back2Value">
                    <span className="brand-box admin-brand__logo">{site?.logo && <img src={site.logo} alt="" className="admin-brand__logo__image"/>}</span>
                    <span className="admin-brand__caption"><small>ADMIN PANEL</small></span>
                </Link>
                <p className="admin-login-eyebrow">MENAXHIMI I FAQES</p>
                <h1>Mirë se u kthyet</h1>
                <p className="admin-login-copy">Hyni për të përditësuar përmbajtjen e faqes Back2Value.</p>
                {!adminConfigured && <div className="admin-alert admin-alert--error" role="alert">Administratori nuk është konfiguruar. Shtoni ADMIN_EMAIL dhe një ADMIN_PASSWORD prej të paktën 12 karakteresh në mjedis, pastaj ekzekutoni php artisan db:seed.</div>}
                <form method="POST" action="/admin/login" className="admin-form admin-login-form">
                    <input type="hidden" name="_token" value={site?.csrfToken ?? ''}/>
                    <label className="admin-field"><span>Email</span><input type="email" name="email" defaultValue={oldInput?.email ?? ''} autoComplete="username" required autoFocus/></label>
                    <label className="admin-field"><span>Fjalëkalimi</span><input type="password" name="password" autoComplete="current-password" required/></label>
                    {emailError && <p className="admin-field-error">{emailError}</p>}
                    {passwordError && <p className="admin-field-error">{passwordError}</p>}
                    <button className="admin-button admin-button--primary inline-flex items-center justify-center gap-2" type="submit">Hyr në panel <span className="admin-inline-icon"><SectionIcon name="arrow" /></span></button>
                </form>
                <Link className="admin-login-back inline-flex items-center gap-1.5" href={homeUrl}>
                    <SectionIcon name="arrow-left" className="w-3.5 h-3.5" /> Kthehu te faqja
                </Link>
            </main>
        </div>
    );
}
