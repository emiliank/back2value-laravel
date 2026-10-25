const REVEAL_SELECTOR = [
    '.section-intro',
    '.eyebrow',
    '.value-prop',
    '.solution-card',
    '.contact-card',
    '.step-card',
    '.partner-card',
    '.stat-card',
    '.service-card',
    '.service-detail',
    '.about-card',
    '.commitment-card',
    '.drop-off-card',
    '.article-card',
    '.product-card',
    '.product-group__head',
].join(', ');

const REVEAL_ICON_SELECTOR = '.icon-badge, .contact-ico, .section-icon';

const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const countUpStats = (root) => {
    root.querySelectorAll('.stat-card strong').forEach((node) => {
        const text = node.textContent.trim();
        const match = text.match(/^(\D*)(\d[\d\s.,]*)(\D*)$/);

        if (!match) {
            return;
        }

        const [, prefix, digits, suffix] = match;
        const target = Number(digits.replace(/[\s,.]/g, ''));

        if (!Number.isFinite(target) || target === 0) {
            return;
        }

        const duration = 900;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - (1 - progress) ** 3;
            node.textContent = prefix + Math.round(target * eased).toLocaleString('en-US') + suffix;

            if (progress < 1) {
                requestAnimationFrame(tick);
                return;
            }

            node.textContent = text;
        };

        requestAnimationFrame(tick);
    });
};

export const initMotion = (root = document) => {
    if (prefersReducedMotion() || typeof IntersectionObserver === 'undefined') {
        return () => {};
    }

    root.querySelectorAll('.stat-card').forEach((card) => {
        if (card.getBoundingClientRect().top < window.innerHeight) {
            countUpStats(card);
        }
    });

    const targets = [...root.querySelectorAll(REVEAL_SELECTOR)].filter(
        (element) => !element.parentElement?.closest(REVEAL_SELECTOR),
    );

    document.documentElement.classList.add('has-motion');

    if (!targets.length) {
        return () => {};
    }

    const groups = new Map();

    targets.forEach((element) => {
        if (element.closest('header, nav, .site-header, .footer-contact')) {
            return;
        }

        if (element.getBoundingClientRect().top < window.innerHeight) {
            return;
        }

        const parent = element.parentElement;

        if (!groups.has(parent)) {
            groups.set(parent, []);
        }

        groups.get(parent).push(element);
        element.dataset.reveal = '';

        const icon = element.querySelector(REVEAL_ICON_SELECTOR);

        if (icon) {
            icon.dataset.revealIcon = '';
        }
    });

    groups.forEach((siblings) => {
        siblings.forEach((element, index) => {
            element.style.setProperty('--reveal-delay', `${Math.min(index, 5) * 70}ms`);
            element.style.setProperty('--reveal-icon-delay', `${Math.min(index, 5) * 70 + 90}ms`);
        });
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-revealed');
                countUpStats(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0 },
    );

    root.querySelectorAll('[data-reveal]').forEach((element) => observer.observe(element));

    return () => observer.disconnect();
};
