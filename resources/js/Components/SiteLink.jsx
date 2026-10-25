import { Link } from '@inertiajs/react';

const isExternal = (href) =>
    !href
    || href.startsWith('http://')
    || href.startsWith('https://')
    || href.startsWith('mailto:')
    || href.startsWith('tel:')
    || href.startsWith('#')
    || href.startsWith('javascript:');

export default function SiteLink({ href, className, children, onClick, ...props }) {
    if (isExternal(href)) {
        return <a href={href || '#'} className={className} onClick={onClick} {...props}>{children}</a>;
    }

    const handleClick = (e) => {
        if (onClick) {
            onClick(e);
        }

        if (e.defaultPrevented) {
            return;
        }

        if (typeof window !== 'undefined' && href && href.includes('#')) {
            const [path, hash] = href.split('#');
            const currentPath = window.location.pathname;
            const normalize = (p) => (p || '/').replace(/\/+$/, '') || '/';

            if (hash && normalize(path) === normalize(currentPath)) {
                const target = document.getElementById(hash);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                    window.history.pushState(null, '', `#${hash}`);
                }
            }
        }
    };

    return <Link href={href} className={className} onClick={handleClick} {...props}>{children}</Link>;
}
