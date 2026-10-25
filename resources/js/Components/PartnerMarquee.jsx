import { useEffect, useRef } from 'react';
import { imageUrl } from '../lib/site';

export default function PartnerMarquee({ partners }) {
    const viewportRef = useRef(null);
    const trackRef = useRef(null);
    const groupRef = useRef(null);
    const items = (partners.items ?? []).filter((partner) => imageUrl(partner.logo));

    useEffect(() => {
        const viewport = viewportRef.current;
        const track = trackRef.current;
        const group = groupRef.current;

        if (!viewport || !track || !group || items.length === 0) {
            return undefined;
        }

        const motionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');
        let readyFrame = 0;

        const build = () => {
            const groupWidth = group.getBoundingClientRect().width;

            if (!groupWidth) {
                return;
            }

            const copies = motionPreference.matches
                ? 1
                : Math.max(2, Math.ceil((viewport.clientWidth + groupWidth) / groupWidth));
            const groups = track.querySelectorAll('[data-partner-marquee-group]');

            if (Number(track.dataset.groupWidth) === groupWidth && groups.length === copies) {
                return;
            }

            track.dataset.ready = 'false';
            groups.forEach((existingGroup) => {
                if (existingGroup !== group) {
                    existingGroup.remove();
                }
            });

            for (let index = 1; index < copies; index += 1) {
                const duplicate = group.cloneNode(true);
                duplicate.setAttribute('aria-hidden', 'true');
                duplicate.classList.add('partner-marquee__group--duplicate');
                duplicate.querySelectorAll('a').forEach((link) => link.setAttribute('tabindex', '-1'));
                track.appendChild(duplicate);
            }

            track.dataset.groupWidth = String(groupWidth);
            const duration = Math.min(45, Math.max(10, groupWidth / 30));
            track.style.setProperty('--marquee-shift', `${groupWidth}px`);
            track.style.setProperty('--marquee-duration', `${duration}s`);
            window.cancelAnimationFrame(readyFrame);

            if (copies > 1) {
                readyFrame = window.requestAnimationFrame(() => {
                    track.dataset.ready = 'true';
                });
            } else {
                delete track.dataset.ready;
            }
        };

        build();

        const observer = 'ResizeObserver' in window ? new ResizeObserver(build) : null;
        observer?.observe(viewport);
        observer?.observe(group);
        motionPreference.addEventListener('change', build);

        return () => {
            window.cancelAnimationFrame(readyFrame);
            observer?.disconnect();
            motionPreference.removeEventListener('change', build);
        };
    }, [items.length]);

    if (items.length === 0) {
        return null;
    }

    return (
        <div className="partner-marquee" data-partner-marquee>
            <div className="partner-marquee__viewport" ref={viewportRef} role="region" aria-label={partners.title}>
                <div className="partner-marquee__track" ref={trackRef}>
                    <div className="partner-marquee__group" ref={groupRef} data-partner-marquee-group>
                        {items.map((partner, index) => {
                            const src = imageUrl(partner.logo);
                            const logo = (
                                <img
                                    className={`partner-card__logo partner-card__logo--${partner.logo_background ?? 'transparent'}`}
                                    src={src}
                                    alt={partner.logo_alt || partner.name}
                                    loading="lazy"
                                />
                            );

                            return (
                                <article className="partner-card partner-card--logo" key={`${partner.name}-${index}`}>
                                    {partner.url
                                        ? <a className="partner-card__logo-link" href={partner.url} target="_blank" rel="noopener noreferrer" aria-label={partner.name}>{logo}</a>
                                        : logo}
                                </article>
                            );
                        })}
                    </div>
                </div>
            </div>
        </div>
    );
}
