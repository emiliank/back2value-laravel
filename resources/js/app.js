const ready = (callback) => {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', callback);
        return;
    }

    callback();
};

// ---------------------------------------------------------------
// Public site
// ---------------------------------------------------------------

const initMobileNavigation = () => {
    const toggle = document.querySelector('[data-mobile-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (!(toggle instanceof HTMLElement) || !(menu instanceof HTMLElement)) {
        return;
    }

    const setOpen = (isOpen) => {
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute('aria-label', isOpen ? 'Mbyll menunë' : 'Hap menunë');
        menu.hidden = !isOpen;
    };

    toggle.addEventListener('click', () => {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    menu.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            setOpen(false);
        }
    });
};

const initPartnerMarquees = () => {
    document.querySelectorAll('[data-partner-marquee]').forEach((marquee) => {
        const viewport = marquee.querySelector('[data-partner-marquee-viewport]');
        const track = marquee.querySelector('[data-partner-marquee-track]');
        const group = marquee.querySelector('[data-partner-marquee-group]');

        if (!viewport || !track || !group) return;

        let resizeTimer;

        const build = () => {
            track.querySelectorAll('[data-partner-marquee-group]').forEach((duplicate) => {
                if (duplicate !== group) {
                    duplicate.remove();
                }
            });

            const groupWidth = group.offsetWidth;

            if (!groupWidth) return;

            const copies = Math.max(2, Math.ceil((viewport.clientWidth + groupWidth) / groupWidth));

            for (let index = 1; index < copies; index += 1) {
                const duplicate = group.cloneNode(true);

                duplicate.setAttribute('aria-hidden', 'true');
                duplicate.querySelectorAll('a').forEach((link) => link.setAttribute('tabindex', '-1'));
                track.appendChild(duplicate);
            }

            const duration = Math.min(45, Math.max(10, groupWidth / 30));

            track.style.setProperty('--marquee-shift', `${groupWidth}px`);
            track.style.setProperty('--marquee-duration', `${duration}s`);
        };

        const scheduleBuild = () => {
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(build, 200);
        };

        build();
        window.addEventListener('resize', scheduleBuild, { passive: true });
    });
};

const initPartnerLogoBackgrounds = () => {
    document.querySelectorAll('img[data-logo-background="black"], img[data-logo-background="white"]').forEach((image) => {
        const removeBackground = () => {
            if (image.dataset.backgroundProcessed || !image.naturalWidth || !image.naturalHeight) return;

            const canvas = document.createElement('canvas');
            canvas.width = image.naturalWidth;
            canvas.height = image.naturalHeight;

            const context = canvas.getContext('2d', { willReadFrequently: true });

            if (!context) return;

            try {
                context.drawImage(image, 0, 0);

                const { data } = context.getImageData(0, 0, canvas.width, canvas.height);
                const backgroundValue = image.dataset.logoBackground === 'white' ? 255 : 0;
                const transparentThreshold = 28;
                const featherThreshold = 76;

                for (let index = 0; index < data.length; index += 4) {
                    const distance = Math.max(
                        Math.abs(data[index] - backgroundValue),
                        Math.abs(data[index + 1] - backgroundValue),
                        Math.abs(data[index + 2] - backgroundValue),
                    );

                    if (distance <= transparentThreshold) {
                        data[index + 3] = 0;
                    } else if (distance < featherThreshold) {
                        data[index + 3] = Math.round(data[index + 3] * (distance - transparentThreshold) / (featherThreshold - transparentThreshold));
                    }
                }

                context.putImageData(new ImageData(data, canvas.width, canvas.height), 0, 0);
                image.dataset.backgroundProcessed = 'true';
                image.classList.add('partner-card__logo--processed');
                image.src = canvas.toDataURL('image/png');
            } catch {
                image.dataset.backgroundProcessingFailed = 'true';
            }
        };

        image.addEventListener('load', removeBackground);

        if (image.complete) {
            removeBackground();
        }
    });
};

const initAnchorScrolling = () => {
    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const selector = link.getAttribute('href');

            if (!selector || selector === '#') {
                return;
            }

            const target = document.querySelector(selector);

            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.replaceState(null, '', selector);
        });
    });
};

const initSavingsCalculator = () => {
    document.querySelectorAll('[data-savings-calculator]').forEach((root) => {
        const count = root.querySelector('[data-battery-count]');
        const weight = root.querySelector('[data-battery-weight]');
        const lead = root.querySelector('[data-result-lead]');
        const co2 = root.querySelector('[data-result-co2]');

        if (!count || !weight || !lead || !co2) {
            return;
        }

        const leadShare = Number.parseFloat(root.dataset.leadShare ?? '0.55') || 0.55;
        const co2PerKgLead = Number.parseFloat(root.dataset.co2PerKg ?? '1.6') || 1.6;

        const readNumber = (input) => {
            const value = Number.parseInt(input.value, 10);

            return Number.isFinite(value) && value > 0 ? value : 0;
        };

        const format = (value) =>
            new Intl.NumberFormat('sq-AL', { maximumFractionDigits: 0 }).format(Math.round(value));

        const update = () => {
            const recoveredLead = readNumber(count) * readNumber(weight) * leadShare;

            lead.textContent = format(recoveredLead);
            co2.textContent = format(recoveredLead * co2PerKgLead);
        };

        [count, weight].forEach((input) => input.addEventListener('input', update));

        update();
    });
};

const initMaintenanceInquiry = () => {
    document.querySelectorAll('[data-maintenance-inquiry]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const feedback = form.querySelector('[data-inquiry-feedback]');
            const submit = form.querySelector('[type="submit"]');
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (submit) {
                submit.disabled = true;
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token ?? '',
                    },
                    body: JSON.stringify(Object.fromEntries(new FormData(form))),
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(payload.message ?? 'Kërkesa nuk mund të dërgohet. Provoni përsëri.');
                }

                feedback.hidden = false;
                feedback.classList.add('status-alert');
                feedback.textContent = 'Faleminderit! Kërkesa u dërgua dhe ekipi ynë teknik ju kontakton brenda 24 orëve.';
                form.reset();
            } catch (error) {
                feedback.hidden = false;
                feedback.classList.add('error-summary');
                feedback.textContent = error instanceof Error ? error.message : 'Kërkesa nuk mund të dërgohet. Provoni përsëri.';
            } finally {
                if (submit) {
                    submit.disabled = false;
                }
            }
        });
    });
};

// ---------------------------------------------------------------
// Admin content editor
// ---------------------------------------------------------------

const uniqueKey = (list) => {
    const used = new Set(
        Array.from(list.querySelectorAll('[data-repeat-card] input[name$="[key]"]')).map((input) => input.value),
    );

    let index = list.querySelectorAll('[data-repeat-card]').length + 1;
    let key = `item-${index}`;

    while (used.has(key)) {
        index += 1;
        key = `item-${index}`;
    }

    return key;
};

const hydrate = (node, list) => {
    node.querySelectorAll('input[type="file"]').forEach((input) => {
        input.dataset.repeaterBound = 'true';
    });

    node.querySelectorAll('[data-repeat-card]').forEach((card) => {
        card.dataset.rowKey = card.querySelector('input[name$="[key]"]')?.value ?? '';
    });

    refreshMoveButtons(list);
};

const refreshMoveButtons = (list) => {
    if (!list) {
        return;
    }

    const cards = Array.from(list.querySelectorAll('[data-repeat-card]')).filter((card) => !card.hidden);

    cards.forEach((card, index) => {
        const up = card.querySelector('[data-move-row="up"]');
        const down = card.querySelector('[data-move-row="down"]');

        if (up) up.hidden = index === 0;
        if (down) down.hidden = index === cards.length - 1;
    });
};

const initRepeaters = () => {
    document.querySelectorAll('[data-add-row]').forEach((button) => {
        button.addEventListener('click', () => {
            const template = document.getElementById(button.dataset.addRow);
            const list = document.getElementById(button.dataset.list);

            if (!(template instanceof HTMLTemplateElement) || !list) return;

            const key = uniqueKey(list);

            list.querySelector('.admin-repeater__empty')?.remove();
            list.append(template.content.cloneNode(true).querySelector('[data-repeat-card]'));

            const card = list.querySelector('[data-repeat-card]:last-child');

            card?.querySelectorAll('input, textarea, select').forEach((field) => {
                field.name = field.name.replaceAll('__KEY__', key);
            });

            hydrate(card, list);
            card?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-remove-row]');

        if (!button) return;

        const card = button.closest('[data-repeat-card]');
        const list = button.closest('[data-repeat-list]');
        const repeater = button.closest('[data-repeater]');
        const deletion = card?.querySelector('input[name$="[remove]"]');
        const deletionBin = repeater?.querySelector('[data-repeat-deletions]');

        if (!card || !list || !deletion || !deletionBin) return;

        deletion.value = '1';
        deletionBin.append(deletion);
        card.remove();

        if (list.querySelectorAll('[data-repeat-card]').length === 0) {
            list.insertAdjacentHTML(
                'beforeend',
                '<p class="admin-repeater__empty">Nuk ka ende rreshta. Shtoni rreshtin e parë me butonin sipër.</p>',
            );
        }

        refreshMoveButtons(list);
    });

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-move-row]');

        if (!button) return;

        const card = button.closest('[data-repeat-card]');
        const list = button.closest('[data-repeat-list]');

        if (!card || !list) return;

        const sibling = button.dataset.moveRow === 'up' ? card.previousElementSibling : card.nextElementSibling;

        if (!sibling || !sibling.matches('[data-repeat-card]')) return;

        if (button.dataset.moveRow === 'up') {
            list.insertBefore(card, sibling);
        } else {
            sibling.after(card);
        }

        refreshMoveButtons(list);
    });

    document.querySelectorAll('[data-repeat-list]').forEach((list) => refreshMoveButtons(list));
};

const initImagePreviews = () => {
    document.addEventListener('change', (event) => {
        const input = event.target;

        if (!(input instanceof HTMLInputElement) || input.type !== 'file' || !input.files?.length) return;

        const preview = input.closest('.admin-image-field')?.querySelector('.admin-product-preview');

        if (!preview) return;

        const file = input.files[0];

        if (!file.type.startsWith('image/')) return;

        preview.innerHTML = '';
        preview.append(Object.assign(new Image(), { src: URL.createObjectURL(file), alt: '' }));
    });
};

const initConfirmations = () => {
    document.querySelectorAll('[data-confirm]').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!window.confirm(button.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });
};

const initContentForms = () => {
    document.querySelectorAll('[data-content-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            const submit = form.querySelector('button[type="submit"]');

            if (submit) submit.dataset.busy = 'true';
        });
    });
};

// ---------------------------------------------------------------
// Motion
// ---------------------------------------------------------------

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

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Count a numeric stat up to its final value once it scrolls into view.
 * Non-numeric values (e.g. "24/7") are left exactly as authored.
 */
const countUpStats = (root) => {
    root.querySelectorAll('.stat-card strong').forEach((node) => {
        const text = node.textContent.trim();
        const match = text.match(/^(\D*)(\d[\d\s.,]*)(\D*)$/);

        if (!match) return;

        const [, prefix, digits, suffix] = match;
        const target = Number(digits.replace(/[\s,.]/g, ''));

        if (!Number.isFinite(target) || target === 0) return;

        const duration = 900;
        const start = performance.now();

        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - (1 - progress) ** 3;
            const value = Math.round(target * eased);

            node.textContent = prefix + value.toLocaleString('en-US') + suffix;

            if (progress < 1) {
                requestAnimationFrame(tick);
                return;
            }

            node.textContent = text;
        };

        requestAnimationFrame(tick);
    });
};

const initMotion = () => {
    const header = document.querySelector('.site-header');

    if (header) {
        const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    if (prefersReducedMotion() || typeof IntersectionObserver === 'undefined') {
        return;
    }

    // Stats that are already on screen at load are not reveal targets, so give
    // them their count-up directly.
    document.querySelectorAll('.stat-card').forEach((card) => {
        if (card.getBoundingClientRect().top < window.innerHeight) {
            countUpStats(card);
        }
    });

    // A section heading contains its own eyebrow label, and a card contains its
    // icon. Animating both would compound the effect, so keep only the outermost
    // target in each chain and let the icon fade travel with its card.
    const targets = [...document.querySelectorAll(REVEAL_SELECTOR)].filter(
        (element) => !element.parentElement?.closest(REVEAL_SELECTOR),
    );

    if (!targets.length) {
        document.documentElement.classList.add('has-motion');
        return;
    }

    const groups = new Map();

    targets.forEach((element) => {
        if (element.closest('header, nav, .site-header, .footer-contact')) return;

        // Never animate what is already on screen at load, otherwise the hero
        // visibly assembles itself and above-the-fold content flashes.
        if (element.getBoundingClientRect().top < window.innerHeight) return;

        const parent = element.parentElement;

        if (!groups.has(parent)) groups.set(parent, []);
        groups.get(parent).push(element);

        element.dataset.reveal = '';

        const icon = element.querySelector(REVEAL_ICON_SELECTOR);

        if (icon) {
            icon.dataset.revealIcon = '';
        }
    });

    // Stagger siblings slightly so a row of cards arrives as a wave.
    groups.forEach((siblings) => {
        siblings.forEach((element, index) => {
            element.style.setProperty('--reveal-delay', `${Math.min(index, 5) * 70}ms`);
            element.style.setProperty('--reveal-icon-delay', `${Math.min(index, 5) * 70 + 90}ms`);
        });
    });

    document.documentElement.classList.add('has-motion');

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                entry.target.classList.add('is-revealed');
                countUpStats(entry.target);
                observer.unobserve(entry.target);
            });
        },
        // threshold must stay 0: a ratio-based threshold is unreachable for
        // elements taller than the viewport (a category section holding dozens
        // of products never gets 12% of itself on screen), which would leave
        // them stuck at opacity 0. The negative bottom margin still delays the
        // reveal until the element has travelled a little way into view.
        { rootMargin: '0px 0px -8% 0px', threshold: 0 },
    );

    document.querySelectorAll('[data-reveal]').forEach((element) => observer.observe(element));
};

ready(() => {
    initMobileNavigation();
    initPartnerMarquees();
    initPartnerLogoBackgrounds();
    initAnchorScrolling();
    initSavingsCalculator();
    initMaintenanceInquiry();
    initMotion();
    initRepeaters();
    initImagePreviews();
    initConfirmations();
    initContentForms();
});
