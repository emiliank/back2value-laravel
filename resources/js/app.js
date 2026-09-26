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
