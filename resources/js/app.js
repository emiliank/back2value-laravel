document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-mobile-toggle]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!isOpen));
            toggle.setAttribute('aria-label', isOpen ? 'Hap menunë' : 'Mbyll menunë');
            menu.hidden = isOpen;
        });
    }

    document.querySelectorAll('a[href^="#"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const targetId = link.getAttribute('href');
            if (!targetId || targetId === '#') {
                return;
            }

            const target = document.querySelector(targetId);
            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });

            if (menu && !menu.hidden) {
                menu.hidden = true;
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-label', 'Hap menunë');
            }
        });
    });

    let rowSequence = 0;

    document.querySelectorAll('[data-add-row]').forEach((button) => {
        button.addEventListener('click', () => {
            const template = document.getElementById(button.dataset.addRow);
            const list = document.getElementById(button.dataset.list);

            if (!(template instanceof HTMLTemplateElement) || !list) {
                return;
            }

            const rowKey = `new-${Date.now()}-${rowSequence++}`;
            const row = template.content.cloneNode(true);

            row.querySelectorAll('[name], [value]').forEach((element) => {
                ['name', 'value'].forEach((attribute) => {
                    const value = element.getAttribute(attribute);

                    if (value?.includes('__KEY__')) {
                        element.setAttribute(attribute, value.replaceAll('__KEY__', rowKey));
                    }
                });
            });

            list.append(row);
        });
    });

    document.querySelectorAll('[data-savings-calculator]').forEach((root) => {
        const count = root.querySelector('[data-battery-count]');
        const weight = root.querySelector('[data-battery-weight]');
        const lead = root.querySelector('[data-result-lead]');
        const co2 = root.querySelector('[data-result-co2]');

        if (!count || !weight || !lead || !co2) {
            return;
        }

        const leadShare = 0.55;
        const co2PerKgLead = 1.6;

        const readNumber = (input) => {
            const value = Number.parseInt(input.value, 10);

            return Number.isFinite(value) && value > 0 ? value : 0;
        };

        const format = (value) => new Intl.NumberFormat('sq-AL', { maximumFractionDigits: 0 }).format(Math.round(value));

        const update = () => {
            const totalWeight = readNumber(count) * readNumber(weight);
            const recoveredLead = totalWeight * leadShare;

            lead.textContent = format(recoveredLead);
            co2.textContent = format(recoveredLead * co2PerKgLead);
        };

        [count, weight].forEach((input) => input.addEventListener('input', update));

        update();
    });

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
                        'Accept': 'application/json',
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

    document.querySelectorAll('[data-repeat-list]').forEach((list) => {
        list.addEventListener('click', (event) => {
            if (!(event.target instanceof Element)) {
                return;
            }

            const removeButton = event.target.closest('[data-remove-row]');
            removeButton?.closest('[data-repeat-card]')?.remove();
        });

        list.addEventListener('change', (event) => {
            if (!(event.target instanceof HTMLInputElement) || event.target.type !== 'file') {
                return;
            }

            const file = event.target.files?.[0];
            const card = event.target.closest('[data-repeat-card]');

            if (!file || !card) {
                return;
            }

            let preview = card.querySelector('[data-image-preview]');

            if (!preview) {
                preview = document.createElement('div');
                preview.className = 'admin-product-preview';
                preview.dataset.imagePreview = '';
                const image = document.createElement('img');
                image.alt = 'Parapamje e imazhit të ri';
                preview.append(image);
                card.querySelector('.admin-product-edit')?.prepend(preview);

                if (!preview.parentElement) {
                    card.querySelector('.admin-form-grid')?.before(preview);
                }
            }

            const image = preview.querySelector('img');

            if (image) {
                image.src = URL.createObjectURL(file);
            }
        });
    });
});
