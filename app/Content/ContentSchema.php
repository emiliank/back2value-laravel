<?php

namespace App\Content;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Declarative description of everything an administrator can edit.
 *
 * Every admin page owns one or more content sections. A section is either a
 * "group" (an associative payload described by groups of fields) or a "list"
 * (the payload is the list of rows itself). Each field declares its input
 * type, label and validation rules, so the admin UI, the validator and the
 * payload normaliser are all generated from this single source of truth.
 */
final class ContentSchema
{
    /**
     * Icon keys supported by the x-section-icon component.
     *
     * @return array<string, string>
     */
    public static function icons(): array
    {
        return [
            'shield' => 'Shiriti mbrojtës',
            'battery' => 'Bateri',
            'coins' => 'Kosto / monetë',
            'leaf' => 'Gjelbër / mjedis',
            'recycle' => 'Riciklim',
            'document' => 'Dokument',
            'academic' => 'Edukim',
            'factory' => 'Industri',
            'sun' => 'Energji diellore',
            'car' => 'Automjet',
            'landmark' => 'Institucion',
            'download' => 'Dorëzim',
            'truck' => 'Transport',
            'pulse' => 'Matje',
            'layers' => 'Shtresa',
            'zap' => 'Energji',
            'clipboard' => 'Kontroll',
            'refresh' => 'Rigjenerim',
            'calendar' => 'Plan / afat',
            'home' => 'Shtëpi',
            'chart' => 'Grafik',
            'image' => 'Imazh',
            'sparkles' => 'Cilësi',
            'box' => 'Paketë',
            'services' => 'Shërbim',
            'settings' => 'Cilësime',
            'whatsapp' => 'WhatsApp',
            'mail' => 'Email',
            'phone' => 'Telefon',
        ];
    }

    /**
     * Internal link targets that content links may point at.
     *
     * @return array<string, string>
     */
    public static function linkTargets(): array
    {
        return [
            'home' => 'Kryefaqja',
            'products' => 'Bateritë',
            'services' => 'Shërbimet',
            'regeneration' => 'Rigjenerimi',
            'solutions' => 'Zgjidhjet',
            'sustainability' => 'Qëndrueshmëria',
            'resources' => 'Burimet',
            'diagnostics' => 'Rezervo diagnostikim',
            'calculator' => 'Kalkulatori i kursimit',
            'contact' => 'Kontakt (kyefa kryesore)',
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public static function sections(): array
    {
        return array_merge(
            self::globalSections(),
            self::catalogSections(),
            self::pageSections(),
        );
    }

    /**
     * Every admin page, keyed by its URL slug.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function pages(): array
    {
        return [
            'settings' => [
                'label' => 'Cilësimet & hero',
                'icon' => 'settings',
                'group' => 'Të përgjithshme',
                'description' => 'Hero, titujt e seksioneve, kontaktet, ngjyrat dhe SEO.',
                'sections' => ['settings'],
            ],
            'navigation' => [
                'label' => 'Menjë & footer',
                'icon' => 'box',
                'group' => 'Të përgjithshme',
                'description' => 'Lidhjet e menusë, butonat e kryenjtë dhe fundi i faqes.',
                'sections' => ['navigation'],
            ],
            'homepage' => [
                'label' => 'Faqja kryesore',
                'icon' => 'home',
                'group' => 'Faqja kryesore',
                'description' => 'Shiriti rregullativ, vlerat, hapat e diagnostikimit, procesi qarkullimit, partnerët dhe kartat e kontaktit.',
                'sections' => ['compliance_banner', 'value_props', 'diagnostics_teaser', 'circular_process', 'partners', 'contact_cards'],
            ],
            'products' => [
                'label' => 'Produktet & imazhet',
                'icon' => 'image',
                'group' => 'Katalogu',
                'description' => 'Kartat e produkteve që shfaqen në faqen kryesore.',
                'sections' => ['products'],
            ],
            'catalog' => [
                'label' => 'Faqja e katalogut',
                'icon' => 'services',
                'group' => 'Katalogu',
                'description' => 'Tekstet e faqes /products, filtrat e aplikimit dhe gjetësi i mjetit.',
                'sections' => ['catalog_page', 'application_filters'],
            ],
            'services' => [
                'label' => 'Shërbimet',
                'icon' => 'services',
                'group' => 'Faqet e brendshme',
                'description' => 'Kartat e shërbimeve që shfaqen në faqen kryesore.',
                'sections' => ['services'],
            ],
            'services-page' => [
                'label' => 'Faqja e shërbimeve',
                'icon' => 'document',
                'group' => 'Faqet e brendshme',
                'description' => 'Hero, shërbimet e detajuara, kontratat SLA dhe pikat e grumbullimit.',
                'sections' => ['services_page', 'calculator', 'drop_off_points'],
            ],
            'about' => [
                'label' => 'Për Back2Value',
                'icon' => 'shield',
                'group' => 'Faqet e brendshme',
                'description' => 'Pikat e forta, statistikat dhe shiriti i besimit.',
                'sections' => ['about', 'stats', 'trust'],
            ],
            'solutions' => [
                'label' => 'Faqja e zgjidhjeve',
                'icon' => 'factory',
                'group' => 'Faqet e brendshme',
                'description' => 'Zgjidhjet sipas sektorit, hapat e punës dhe banda e thirrjes.',
                'sections' => ['solutions'],
            ],
            'sustainability' => [
                'label' => 'Faqja e qëndrueshmërisë',
                'icon' => 'leaf',
                'group' => 'Faqet e brendshme',
                'description' => 'Angazhimet, treguesit dhe paneli i përputhshmërisë.',
                'sections' => ['sustainability'],
            ],
            'regeneration' => [
                'label' => 'Faqja e rigjenerimit',
                'icon' => 'refresh',
                'group' => 'Faqet e brendshme',
                'description' => 'Hero, pajisjet, laboratori i baterive, procesi dhe oferta.',
                'sections' => ['regeneration_page'],
            ],
            'resources' => [
                'label' => 'Faqja e burimeve',
                'icon' => 'academic',
                'group' => 'Faqet e brendshme',
                'description' => 'Hero, artikujt edukativë, paneli i planifikimit dhe banda e thirrjes.',
                'sections' => ['resources'],
            ],
            'diagnostics' => [
                'label' => 'Faqja e diagnostikimit',
                'icon' => 'clipboard',
                'group' => 'Faqet e brendshme',
                'description' => 'Hero, opsionet e formularit, etiketat e fushave dhe banda e thirrjes.',
                'sections' => ['diagnostics_page'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function section(string $section): ?array
    {
        return self::sections()[$section] ?? null;
    }

    /**
     * Treat a list-shaped section as a repeater field so the admin editor
     * can render it with the same markup as nested repeaters.
     *
     * @param  array<string, mixed>  $section
     * @return array<string, mixed>
     */
    public static function asRepeater(array $section): array
    {
        return [
            'key' => $section['key'] ?? null,
            'label' => $section['title'],
            'type' => 'repeater',
            'required' => true,
            'optional' => false,
            'max' => $section['max_items'],
            'rows' => 3,
            'wide' => true,
            'hint' => $section['description'] ?? null,
            'input' => null,
            'pair' => false,
            'paragraphs' => false,
            'fields' => $section['fields'],
            'title_field' => $section['title_field'] ?? 'title',
            'add_label' => $section['add_label'] ?? 'Shto rresht',
            'min' => $section['min'] ?? 0,
            'max_items' => $section['max_items'] ?? 20,
            'sortable' => true,
            'keyed' => false,
        ];
    }

    /**
     * Section keys that store a list of rows instead of an object.
     *
     * @return list<string>
     */
    public static function listSections(): array
    {
        return array_keys(array_filter(self::sections(), fn (array $section): bool => ($section['shape'] ?? 'group') === 'list'));
    }

    // -----------------------------------------------------------------
    // Sections
    // -----------------------------------------------------------------

    /**
     * @return array<string, mixed>
     */
    private static function globalSections(): array
    {
        return [
            'settings' => [
                'title' => 'Cilësimet e faqes',
                'kicker' => 'TË PËRGJITHSHME',
                'description' => 'Hero, titujt e seksioneve, kontaktet dhe SEO.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'Prezantimi dhe ofertat',
                        'fields' => [
                            self::t('hero_badge', 'Titulli i vogël mbi kryetitull', max: 120),
                            self::t('hero_title', 'Rreshti i parë i titullit', max: 100),
                            self::t('hero_highlight', 'Rreshti i theksuar me ngjyrë', max: 100),
                            self::t('hero_subtitle', 'Rreshti i tretë', max: 100),
                            self::area('hero_description', 'Përshkrimi kryesor', max: 500, rows: 3, wide: true),
                            self::t('hero_cta', 'Butoni i WhatsApp', max: 100),
                            self::t('hero_products_cta', 'Butoni i produkteve', max: 100),
                            self::image('hero_image', 'Ilustrimi i hero', hint: 'PNG, JPG, WebP ose SVG deri në 5 MB.'),
                            self::t('hero_image_alt', 'Teksti alternativ i ilustrimit', max: 180, wide: true),
                            self::num('hero_image_width', 'Gjerësia e ilustrimit (px)', max: 2000, optional: true),
                            self::num('hero_image_height', 'Lartësia e ilustrimit (px)', max: 2000, optional: true),
                            self::t('whatsapp_message', 'Mesazhi i parazgjedhur në WhatsApp', max: 200, wide: true, hint: 'Shfaqet kur dikush hap butonin e WhatsApp.'),
                        ],
                    ],
                    [
                        'label' => 'LOGOJA E FAQES',
                        'fields' => [
                            self::image('logo_image', 'Logoja e faqes', hint: 'PNG ose SVG me sfond transparent. Pa logo, shfaqet wordmark-i Back2Value.'),
                        ],
                    ],
                    [
                        'label' => 'SEKSIONET',
                        'fields' => [
                            self::t('products_eyebrow', 'Etiketa e produkteve', max: 80),
                            self::t('products_title', 'Titulli i produkteve', max: 120),
                            self::area('products_description', 'Përshkrimi i produkteve', max: 350, rows: 2, wide: true),
                            self::t('services_eyebrow', 'Etiketa e shërbimeve', max: 80),
                            self::t('services_title', 'Titulli i shërbimeve', max: 120),
                            self::area('services_description', 'Përshkrimi i shërbimeve', max: 350, rows: 2, wide: true),
                            self::t('contact_eyebrow', 'Etiketa e kontaktit', max: 80),
                            self::t('contact_title', 'Titulli i kontaktit', max: 120),
                            self::area('contact_description', 'Përshkrimi i kontaktit', max: 350, rows: 2, wide: true),
                            self::area('footer_description', 'Përshkrimi në fund të faqes', max: 500, rows: 2, wide: true),
                        ],
                    ],
                    [
                        'label' => 'TË DHËNAT E BIZNESIT',
                        'fields' => [
                            self::t('phone', 'Numri i telefonit', max: 30, input: 'tel'),
                            self::t('whatsapp', 'Numri i WhatsApp (me kodin e shtetit)', max: 30, input: 'tel'),
                            self::t('email', 'Email', max: 255, input: 'email'),
                            self::t('hours', 'Orari i punës', max: 120),
                            self::t('address', 'Adresa', max: 180, wide: true),
                            self::t('map_query', 'Vendndodhja e kërkimit në Google Maps', max: 180, wide: true),
                            self::color('accent_color', 'Ngjyra kryesore', hint: 'Ngjyra e butonave, ikonave dhe theksimeve.'),
                        ],
                    ],
                    [
                        'label' => 'GOOGLE DHE SEO',
                        'fields' => [
                            self::t('meta_title', 'Titulli i faqes', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi i faqes', max: 300, rows: 3, wide: true),
                        ],
                    ],
                ],
            ],

            'navigation' => [
                'title' => 'Menjë, butona dhe fundi i faqes',
                'kicker' => 'TË PËRGJITHSHME',
                'description' => 'Lidhjet e menusë kryesore, butonat dhe përmbajtja e footerit.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'MENJA KRYESORE',
                        'fields' => [
                            self::repeater('header_items', 'Lidhjet e menusë', [self::t('label', 'Teksti i lidhjes', max: 60),
                                self::select('target', 'Ku dërgon', self::linkTargets()), ], min: 1, maxItems: 10, titleField: 'label', addLabel: 'Shto lidhje', wide: true),
                            self::t('header_cta_secondary', 'Butoni i dytë', max: 60),
                            self::select('header_cta_secondary_target', 'Ku dërgon butoni i dytë', self::linkTargets()),
                            self::t('header_cta_primary', 'Butoni kryesor (WhatsApp)', max: 60),
                            self::t('floating_whatsapp_label', 'Teksti i butonit float', max: 80, wide: true),
                            self::t('brand_alt', 'Teksti alternativ i logos', max: 80),
                            self::t('breadcrumb_home', 'Emri i kryefaqesë në breadcrumb', max: 40),
                        ],
                    ],
                    [
                        'label' => 'FOOTER',
                        'fields' => [
                            self::repeater('footer_items', 'Lidhjet e footerit', [self::t('label', 'Teksti i lidhjes', max: 60),
                                self::select('target', 'Ku dërgon', self::linkTargets()), ], min: 1, maxItems: 12, titleField: 'label', addLabel: 'Shto lidhje', wide: true),
                            self::t('footer_whatsapp_label', 'Etiketa e WhatsApp', max: 40),
                            self::t('footer_copyright', 'Teksti i copyright', max: 160, wide: true, hint: 'Viti shtohet automatikisht përpara tekstit.'),
                            self::t('footer_partner_line', 'Niveli i partneritetit', max: 160, wide: true),
                        ],
                    ],
                ],
            ],

            'compliance_banner' => [
                'title' => 'Shiriti rregullativ',
                'kicker' => 'FAQJA KRYESORE',
                'description' => 'Njoftimi mbi legjislacionin në krye të faqes.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'NJOFTIMI MBI LEGJISLACIONIN',
                    'fields' => [
                        self::check('enabled', 'Shfaq këtë shirit në krye të faqes'),
                        self::t('prefix', 'Teksti para lidhjes së parë', max: 200, wide: true),
                        self::t('first_label', 'Teksti i lidhjes së parë', max: 160),
                        self::url('first_url', 'Adresa e lidhjes së parë', max: 400),
                        self::t('middle', 'Teksti ndërmjet lidhjeve', max: 80, wide: true),
                        self::t('second_label', 'Teksti i lidhjes së dytë', max: 160),
                        self::url('second_url', 'Adresa e lidhjes së dytë', max: 400),
                        self::t('suffix', 'Teksti pas lidhjes së dytë', max: 300, wide: true),
                    ],
                ]],
            ],

            'value_props' => [
                'title' => 'Vlerat e Back2Value',
                'kicker' => 'FAQJA KRYESORE',
                'description' => 'Tre kartat e vlerave kryesore.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'VLERAT',
                    'fields' => [
                        self::t('eyebrow', 'Etiketa e seksionit', max: 80),
                        self::t('title', 'Titulli', max: 120),
                        self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                        self::repeater('items', 'Kartat', [self::t('title', 'Titulli', max: 120),
                            self::t('value', 'Shifra', max: 20),
                            self::select('icon', 'Ikona', self::icons()),
                            self::area('description', 'Përshkrimi', max: 400, rows: 3), ], min: 1, maxItems: 6, titleField: 'title', addLabel: 'Shto kartë', wide: true),
                    ],
                ]],
            ],

            'diagnostics_teaser' => [
                'title' => 'Hapat e diagnostikimit',
                'kicker' => 'FAQJA KRYESORE',
                'description' => 'Tre hapat dhe butonat e seksionit.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'HAPAT',
                    'fields' => [
                        self::t('eyebrow', 'Etiketa e seksionit', max: 80),
                        self::t('title', 'Titulli', max: 120),
                        self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                        self::repeater('steps', 'Hapat', [self::t('title', 'Titulli i hapit', max: 80),
                            self::area('description', 'Përshkrimi', max: 400, rows: 3), ], min: 1, maxItems: 6, titleField: 'title', addLabel: 'Shto hap', wide: true),
                        self::t('cta', 'Butoni kryesor', max: 60),
                        self::select('cta_target', 'Ku dërgon butoni kryesor', self::linkTargets()),
                        self::t('secondary_cta', 'Butoni dytë', max: 60),
                        self::select('secondary_cta_target', 'Ku dërgon butoni dytë', self::linkTargets()),
                    ],
                ]],
            ],

            'circular_process' => [
                'title' => 'Procesi qarkullimit',
                'kicker' => 'FAQJA KRYESORE',
                'description' => 'Mbledhja, testimi dhe riciklimi i baterive.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'PROCESI',
                    'fields' => [
                        self::t('eyebrow', 'Etiketa e seksionit', max: 80),
                        self::t('title', 'Titulli', max: 120),
                        self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                        self::repeater('steps', 'Hapat e procesit', [self::t('title', 'Titulli', max: 80),
                            self::select('icon', 'Ikona', self::icons()),
                            self::area('description', 'Përshkrimi', max: 400, rows: 3), ], min: 1, maxItems: 8, titleField: 'title', addLabel: 'Shto hap', wide: true),
                        self::repeater('outcomes', 'Rezultatet', [self::t('title', 'Titulli', max: 80),
                            self::select('icon', 'Ikona', self::icons()),
                            self::area('description', 'Përshkrimi', max: 400, rows: 3), ], min: 1, maxItems: 6, titleField: 'title', addLabel: 'Shto rezultat', wide: true),
                        self::t('note_eyebrow', 'Etiketa e shënimit', max: 80),
                        self::t('note_title', 'Titulli i shënimit', max: 120),
                        self::area('note_description', 'Përshkrimi i shënimit', max: 350, rows: 2, wide: true),
                        self::t('note_cta', 'Butoni i shënimit', max: 60),
                        self::select('note_cta_target', 'Ku dërgon butoni i shënimit', self::linkTargets()),
                    ],
                ]],
            ],

            'partners' => [
                'title' => 'Partnerët & certifikimet',
                'kicker' => 'FAQJA KRYESORE',
                'description' => 'Kartat e partnerëve dhe certikimeve.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'PARTNERËT',
                    'fields' => [
                        self::t('eyebrow', 'Etiketa e seksionit', max: 80),
                        self::t('title', 'Titulli', max: 120),
                        self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                        self::repeater('items', 'Partnerët', [self::t('name', 'Emri', max: 120),
                            self::t('country', 'Vendi', max: 60),
                            self::area('description', 'Përshkrimi', max: 300, rows: 3), ], min: 1, maxItems: 12, titleField: 'name', addLabel: 'Shto partner', wide: true),
                    ],
                ]],
            ],

            'contact_cards' => [
                'title' => 'Kartat e kontaktit',
                'kicker' => 'FAQJA KRYESORE',
                'description' => 'Etiketat e kartave WhatsApp, email, telefon dhe hartë.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'ETIKETAT',
                    'fields' => [
                        self::t('whatsapp_label', 'Kartela WhatsApp', max: 40),
                        self::t('email_label', 'Kartela Email', max: 40),
                        self::t('phone_label', 'Kartela Telefon', max: 40),
                        self::t('map_label', 'Butoni i hartës', max: 40),
                    ],
                ]],
            ],

            'products' => [
                'title' => 'Produktet dhe imazhet',
                'kicker' => 'KATALOGU',
                'description' => 'Kartat e produkteve të faqes kryesore.',
                'shape' => 'list',
                'fields' => [
                    self::t('key', 'Identifikuesi', max: 80, hint: 'Vetëm shkronja, numra dhe vijë dash.', optional: true),
                    self::t('title', 'Emri i produktit', max: 120),
                    self::area('description', 'Përshkrimi', max: 1000, rows: 3, wide: true),
                    self::lines('features', 'Veçoritë (një për rresht)', max: 1500, rows: 3, wide: true),
                    self::image('image', 'Fotografia', hint: 'PNG, JPG ose WebP deri në 5 MB.'),
                    self::t('image_alt', 'Teksti alternativ i fotografisë', max: 180, wide: true),
                    self::num('sort_order', 'Renditja në faqe', max: 1000),
                ],
                'title_field' => 'title',
                'add_label' => 'Shto produkt',
                'min' => 1,
                'max_items' => 24,
            ],

            'services' => [
                'title' => 'Shërbimet',
                'kicker' => 'FAQJA KRYESORE',
                'description' => 'Kartat e shërbimeve teknike.',
                'shape' => 'list',
                'fields' => [
                    self::t('key', 'Identifikuesi', max: 80, optional: true),
                    self::t('title', 'Emri i shërbimit', max: 120),
                    self::select('icon', 'Ikona', self::icons()),
                    self::area('description', 'Përshkrimi', max: 1000, rows: 3, wide: true),
                    self::num('sort_order', 'Renditja në faqe', max: 1000),
                ],
                'title_field' => 'title',
                'add_label' => 'Shto shërbim',
                'min' => 1,
                'max_items' => 24,
            ],

            'about' => [
                'title' => 'Seksioni «Pse Back2Value?»',
                'kicker' => 'PËR BACK2VALUE',
                'description' => 'Pikat e forta dhe përshkrimi i tyre.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'AVANTAZHI YNË',
                    'fields' => [
                        self::t('eyebrow', 'Etiketa e seksionit', max: 80),
                        self::t('title', 'Titulli', max: 120),
                        self::area('description', 'Përshkrimi i seksionit', max: 350, rows: 3, wide: true),
                        self::repeater('points', 'Pikat e forta', [self::t('key', 'Identifikuesi', max: 80, optional: true),
                            self::t('title', 'Titulli', max: 120),
                            self::select('icon', 'Ikona', self::icons()),
                            self::area('description', 'Përshkrimi', max: 800, rows: 4, wide: true),
                            self::t('label', 'Emri poshtë kartës', max: 120, optional: true), ], min: 1, maxItems: 6, titleField: 'title', addLabel: 'Shto pikë', wide: true),
                    ],
                ]],
            ],

            'stats' => [
                'title' => 'Statistikat dhe garancitë',
                'kicker' => 'PËR BACK2VALUE',
                'description' => 'Shifrat që shfaqen në faqen kryesore.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'SHIFRAT NË FAQE',
                    'fields' => [
                        self::repeater('items', 'Statistikat', [self::t('key', 'Identifikuesi', max: 80, optional: true),
                            self::t('value', 'Vlera', max: 30),
                            self::t('label', 'Etiketa', max: 80),
                            self::num('sort_order', 'Renditja', max: 1000), ], min: 1, maxItems: 8, titleField: 'label', addLabel: 'Shto statistikë', wide: true),
                    ],
                ]],
            ],

            'trust' => [
                'title' => 'Shiriti i besimit',
                'kicker' => 'PËR BACK2VALUE',
                'description' => 'Përfitimet që theksohen pas hero.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'PËRFITIMET KRYESORE',
                    'fields' => [
                        self::repeater('items', 'Përfitimet', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                            self::t('text', 'Teksti', max: 120),
                            self::select('icon', 'Ikona', self::icons()), ], min: 1, maxItems: 6, titleField: 'text', addLabel: 'Shto përfitim', wide: true),
                    ],
                ]],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function catalogSections(): array
    {
        return [
            'catalog_page' => [
                'title' => 'Faqja /products',
                'kicker' => 'KATALOGU',
                'description' => 'Tekstet, filtrat dhe gjetësi i mjetit.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'KRYETITULLI',
                        'fields' => [
                            self::t('meta_title', 'Titulli SEO', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi SEO', max: 300, rows: 2, wide: true),
                            self::t('badge', 'Etiketa e vogël', max: 120, wide: true),
                            self::t('hero_title', 'Titulli', max: 120),
                            self::area('hero_description', 'Përshkrimi', max: 400, rows: 3, wide: true),
                            self::area('price_note', 'Shënimi i çmimit', max: 300, rows: 2, wide: true),
                            self::t('portfolio_label', 'Etiketa e portofolit', max: 120, wide: true),
                            self::t('whatsapp_message', 'Mesazhi i WhatsApp nga katalogu', max: 200, wide: true),
                        ],
                    ],
                    [
                        'label' => 'FILRAT DHE KËRKIMI',
                        'fields' => [
                            self::t('search_placeholder', 'Kërkimi: teksti ndihmës', max: 160, wide: true),
                            self::t('search_button', 'Butoni i kërkimit', max: 30),
                            self::t('clear_button', 'Butoni i pastrimit', max: 30),
                            self::t('categories_label', 'Etiketa e kategorive', max: 60),
                            self::t('applications_label', 'Etiketa e aplikimit', max: 60),
                            self::t('all_label', 'Etiketa «Të gjitha»', max: 40),
                            self::t('model_unit_one', 'Emërtimi i modelit (njësi)', max: 20),
                            self::t('model_unit_many', 'Emërtimi i modelit (shumësi)', max: 20),
                        ],
                    ],
                    [
                        'label' => 'GJETËSI I MJETIT',
                        'fields' => [
                            self::t('finder_title', 'Titulli', max: 80),
                            self::area('finder_description', 'Përshkrimi', max: 400, rows: 3, wide: true),
                            self::t('finder_type_label', 'Fusha: tipi i mjetit', max: 60),
                            self::t('finder_type_placeholder', 'Mjedisi: tipi', max: 60),
                            self::t('finder_make_label', 'Fusha: marka', max: 60),
                            self::t('finder_make_placeholder', 'Mjedisi: marka', max: 60),
                            self::t('finder_model_label', 'Fusha: modeli', max: 60),
                            self::t('finder_model_placeholder', 'Shembull: Sprinter, Golf, A4…', max: 60),
                            self::t('finder_year_label', 'Fusha: viti', max: 60),
                            self::t('finder_fuel_label', 'Fusha: karburanti', max: 60),
                            self::t('finder_startstop_label', 'Fusha: start-stop', max: 60),
                            self::t('finder_capacity_label', 'Fusha: kapaciteti', max: 60),
                            self::t('finder_capacity_placeholder', 'Shembull: 74', max: 40),
                            self::t('finder_vin_label', 'Fusha: VIN', max: 60),
                            self::t('finder_vin_placeholder', 'Shembull: WVWZZZ1KZAW000001', max: 40),
                            self::t('finder_notes_label', 'Fusha: detaje të tjera', max: 80),
                            self::area('finder_notes_placeholder', 'Shembull teksti', max: 200, rows: 2, wide: true),
                            self::t('finder_submit', 'Butoni i kërkimit', max: 60),
                            self::t('finder_reset', 'Butoni i pastrimit', max: 60),
                            self::t('finder_hint', 'Shënimi nën formular', max: 200, wide: true),
                            self::t('vehicle_badge', 'Etiketa e rezultatit të mjetit', max: 40),
                            self::t('vin_confirm_label', 'Butoni i konfirmimit të VIN', max: 60),
                        ],
                    ],
                    [
                        'label' => 'PËRSHKRIMET E KATEGORIVEVE',
                        'fields' => [
                            self::lines('category_descriptions', 'Përshkrime (format «kategori = përshkrim»)', rows: 6, wide: true, pair: true),
                            self::t('category_fallback_description', 'Përshkrimi i parazgjedhur i kategorisë', max: 300, wide: true),
                        ],
                    ],
                    [
                        'label' => 'PASI KENI ZBROTHUR',
                        'fields' => [
                            self::t('empty_title', 'Titulli', max: 80),
                            self::t('empty_description', 'Përshkrimi', max: 200),
                            self::t('empty_action', 'Butoni', max: 60),
                            self::t('quote_category', 'Butoni i ofertës së kategorisë', max: 60),
                            self::t('quote_whatsapp_message', 'Mesazhi i WhatsApp për kategorinë', max: 200, wide: true),
                            self::t('contact_title', 'Titulli i butonit të kontaktit', max: 80),
                            self::t('price_request_label', 'Etiketa «Çmimi me kërkesë»', max: 40),
                            self::t('quote_button', 'Butoni i ofertës', max: 40),
                            self::t('warranty_prefix', 'Para e garancisë', max: 40),
                            self::t('preorder_notice', 'Njoftimi i porosisë paraprake', max: 200, wide: true),
                            self::t('stock_label_in_stock', 'Etiketa «Në stok»', max: 40),
                            self::t('stock_label_low_stock', 'Etiketa «Stok i kufizuar»', max: 40),
                            self::t('stock_label_on_preorder', 'Etiketa «Porosi paraprake»', max: 40),
                            self::t('stock_label_out_of_stock', 'Etiketa «Jashtë stokut»', max: 40),
                            self::t('vin_valid_text', 'Teksti i VIN-it të vlefshëm', max: 80),
                            self::t('vin_region_label', 'Etiketa e rajonit', max: 40),
                            self::t('vin_year_label', 'Etiketa e vitit', max: 40),
                            self::t('vin_match_text', 'Teksti i përputhjes', max: 80),
                            self::t('vin_invalid_text', 'Teksti i VIN-it të pavlefshëm', max: 80),
                            self::t('recommendation_label', 'Etiketa e rekomandimit', max: 60),
                            self::t('bottom_kicker', 'Etiketa e fundit', max: 60),
                            self::t('bottom_title', 'Titulli i fundit', max: 120),
                            self::area('bottom_description', 'Përshkrimi i fundit', max: 400, rows: 3, wide: true),
                            self::t('bottom_primary', 'Butoni kryesor', max: 60),
                            self::t('bottom_secondary', 'Butoni dytë', max: 60),
                        ],
                    ],
                ],
            ],

            'application_filters' => [
                'title' => 'Filtrat e aplikimit',
                'kicker' => 'KATALOGU',
                'description' => 'Filtrat që lidhin katalogun dhe zgjidhjet.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'FILTRAT SIPAS APLIKIMIT',
                    'fields' => [
                        self::repeater('items', 'Filtrat', [self::t('key', 'Identifikuesi', max: 60, hint: 'P.sh. industrial, solar, auto, backup_power.', optional: true),
                            self::t('label', 'Etiketa që shfaqet', max: 80),
                            self::lines('categories', 'Kategoritë (një për rresht)', rows: 3),
                            self::lines('application_types', 'Tipet e aplikimit (një për rresht)', rows: 3), ], min: 1, maxItems: 12, titleField: 'label', addLabel: 'Shto filtr', sortable: false, wide: true, keyed: true),
                    ],
                ]],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function pageSections(): array
    {
        return [
            'services_page' => [
                'title' => 'Faqja /services',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Hero, shërbimet e detajuara dhe formata e kontratave SLA.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'KRYETITULLI',
                        'fields' => [
                            self::t('meta_title', 'Titulli SEO', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi SEO', max: 300, rows: 2, wide: true),
                            self::t('eyebrow', 'Etiketa', max: 80),
                            self::t('title', 'Titulli', max: 120),
                            self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::t('hero_cta_primary', 'Butoni kryesor', max: 60),
                            self::t('hero_cta_secondary', 'Butoni dytë', max: 60),
                            self::select('hero_cta_secondary_target', 'Ku dërgon butoni dytë', self::linkTargets()),
                            self::t('detail_link', 'Lidhja poshtë shërbimeve', max: 80, wide: true),
                            self::select('detail_link_target', 'Ku dërgon lidhja', self::linkTargets()),
                        ],
                    ],
                    [
                        'label' => 'SHËRBIMET E DETAJUARA',
                        'fields' => [
                            self::repeater('items', 'Shërbimet', [self::t('key', 'Identifikuesi', max: 80, optional: true),
                                self::select('icon', 'Ikona', self::icons()),
                                self::t('title', 'Titulli', max: 120),
                                self::area('description', 'Përshkrimi', max: 800, rows: 3, wide: true),
                                self::lines('features', 'Pikat e forta (një për rresht)', max: 1000, rows: 4, wide: true), ], min: 1, maxItems: 12, titleField: 'title', addLabel: 'Shto shërbim', wide: true),
                        ],
                    ],
                    [
                        'label' => 'KONTRATA MIRËMBAJTJEJE',
                        'fields' => [
                            self::t('maintenance_eyebrow', 'Etiketa', max: 80),
                            self::t('maintenance_title', 'Titulli', max: 120),
                            self::area('maintenance_description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::t('form_company_label', 'Fusha: emri i kompanisë', max: 60),
                            self::t('form_contact_label', 'Fusha: kontakti', max: 60),
                            self::t('form_email_label', 'Fusha: email', max: 60),
                            self::t('form_phone_label', 'Fusha: telefoni', max: 60),
                            self::t('form_sector_label', 'Fusha: sektori', max: 60),
                            self::pairs('form_sectors', 'Opsionet e sektorit', hint: 'Çdo rresht: vlera = etiketa.'),
                            self::t('form_fleet_label', 'Fusha: numri i baterive', max: 60),
                            self::t('form_fleet_default', 'Vlera e parazgjedhur', max: 10),
                            self::t('form_requirements_label', 'Fusha: kërkesat teknike', max: 60),
                            self::area('form_requirements_hint', 'Ndihma e fushës', max: 250, rows: 2, wide: true),
                            self::t('form_message_label', 'Fusha: shënimet', max: 60),
                            self::t('form_submit', 'Butoni i dërgimit', max: 40),
                            self::t('form_hint', 'Ndihma pas butonit', max: 120, wide: true),
                        ],
                    ],
                    self::ctaGroup(),
                ],
            ],

            'calculator' => [
                'title' => 'Kalkulatori i kursimit',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Tekstet dhe faktorët e kalkulatorit.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'TEKSTET DHE FAKTORËT',
                    'fields' => [
                        self::t('eyebrow', 'Etiketa', max: 80),
                        self::t('title', 'Titulli', max: 120),
                        self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                        self::t('count_label', 'Fusha: numri i baterive', max: 60),
                        self::t('weight_label', 'Fusha: pesha mesatare', max: 80),
                        self::num('count_default', 'Numri i parazgjedhur', max: 5000),
                        self::num('weight_default', 'Pesha e parazgjedhur (kg)', max: 3000),
                        self::t('capacity_value', 'Rezultati: kapaciteti', max: 30),
                        self::t('capacity_label', 'Etiketa e kapacitetit', max: 80),
                        self::t('cost_value', 'Rezultati: kostoja', max: 30),
                        self::t('cost_label', 'Etiketa e kostos', max: 80),
                        self::t('lead_label', 'Rezultati: plumbi', max: 80),
                        self::t('lead_unit', 'Njësia e plumbit', max: 10),
                        self::t('co2_label', 'Rezultati: CO2', max: 80),
                        self::t('co2_unit', 'Njësia e CO2', max: 10),
                        self::area('note', 'Shënimi nën rezultate', max: 600, rows: 3, wide: true),
                        self::t('primary_label', 'Butoni kryesor', max: 60),
                        self::select('primary_target', 'Ku dërgon', self::linkTargets()),
                        self::t('secondary_label', 'Butoni dytë', max: 60),
                        self::select('secondary_target', 'Ku dërgon', self::linkTargets()),
                        self::num('lead_share_percent', 'Përqindja e plumbit në peshë (%)', max: 100, hint: 'Pjesa e peshës që përbëhet nga plumbi.'),
                        self::num('co2_per_kg', 'kg CO2 për kilogram plumbi', max: 100),
                    ],
                ]],
            ],

            'drop_off_points' => [
                'title' => 'Pikat e grumbullimit',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Rrjeti i pikave të grumbullimit dhe servisit.',
                'shape' => 'group',
                'groups' => [[
                    'label' => 'RRJETI I GRUMBULLIMIT',
                    'fields' => [
                        self::t('eyebrow', 'Etiketa', max: 80),
                        self::t('title', 'Titulli', max: 120),
                        self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                        self::repeater('items', 'Pikat', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                            self::t('title', 'Emri i pikës', max: 120),
                            self::t('type', 'Tipi', max: 80),
                            self::select('category', 'Ngjyra e etiketës', [
                                'licensed' => 'E autorizuar',
                                'collection' => 'Grumbullim',
                                'workshop' => 'Partner',
                            ]),
                            self::t('address', 'Adresa', max: 180),
                            self::t('phone', 'Telefoni', max: 30),
                            self::t('hours', 'Orari', max: 80), ], min: 1, titleField: 'title', addLabel: 'Shto pikë', wide: true),
                        self::t('address_label', 'Etiketa: adresa', max: 30),
                        self::t('phone_label', 'Etiketa: telefoni', max: 30),
                        self::t('hours_label', 'Etiketa: orari', max: 30),
                    ],
                ]],
            ],

            'solutions' => [
                'title' => 'Faqja /solutions',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Zgjidhjet sipas sektorit dhe hapat e punës.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'KRYETITULLI',
                        'fields' => [
                            self::t('meta_title', 'Titulli SEO', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi SEO', max: 300, rows: 2, wide: true),
                            self::t('eyebrow', 'Etiketa', max: 80),
                            self::t('title', 'Titulli', max: 120),
                            self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::t('hero_cta_primary', 'Butoni kryesor', max: 60),
                            self::t('hero_cta_secondary', 'Butoni dytë', max: 60),
                            self::select('hero_cta_secondary_target', 'Ku dërgon butoni dytë', self::linkTargets()),
                        ],
                    ],
                    [
                        'label' => 'ZGJIDHJET SIPAS SEKTORIT',
                        'fields' => [
                            self::repeater('items', 'Sektorët', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                                self::select('icon', 'Ikona', self::icons()),
                                self::t('title', 'Titulli', max: 120),
                                self::t('audience', 'Kush është', max: 160),
                                self::area('description', 'Përshkrimi', max: 600, rows: 3, wide: true),
                                self::lines('benefits', 'Përfitimet (një për rresht)', max: 1000, rows: 4, wide: true),
                                self::lines('applications', 'Aplikimet e lidhura (një identifikues për rresht)', max: 300, rows: 3, wide: true, hint: 'P.sh. industrial, solar, backup_power.'), ], min: 1, maxItems: 12, titleField: 'title', addLabel: 'Shto sektor', wide: true),
                        ],
                    ],
                    [
                        'label' => 'SI PUNOJMË',
                        'fields' => [
                            self::t('process_eyebrow', 'Etiketa', max: 80),
                            self::t('process_title', 'Titulli', max: 120),
                            self::area('process_description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::repeater('process_steps', 'Hapat', [self::t('title', 'Titulli i hapit', max: 80),
                                self::area('description', 'Përshkrimi', max: 400, rows: 3), ], min: 1, maxItems: 6, titleField: 'title', addLabel: 'Shto hap', wide: true),
                        ],
                    ],
                    self::ctaGroup(),
                ],
            ],

            'sustainability' => [
                'title' => 'Faqja /sustainability',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Angazhimet, treguesit dhe përputhshmëria.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'KRYETITULLI',
                        'fields' => [
                            self::t('meta_title', 'Titulli SEO', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi SEO', max: 300, rows: 2, wide: true),
                            self::t('eyebrow', 'Etiketa', max: 80),
                            self::t('title', 'Titulli', max: 120),
                            self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::t('hero_cta_primary', 'Butoni kryesor', max: 60),
                            self::t('hero_cta_secondary', 'Butoni dytë', max: 60),
                            self::select('hero_cta_secondary_target', 'Ku dërgon butoni dytë', self::linkTargets()),
                        ],
                    ],
                    [
                        'label' => 'ANGAZHIMET TONA',
                        'fields' => [
                            self::t('commitments_eyebrow', 'Etiketa', max: 80),
                            self::t('commitments_title', 'Titulli', max: 120),
                            self::area('commitments_description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::repeater('commitments', 'Angazhimet', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                                self::select('icon', 'Ikona', self::icons()),
                                self::t('title', 'Titulli', max: 120),
                                self::area('description', 'Përshkrimi', max: 500, rows: 3, wide: true), ], min: 1, maxItems: 12, titleField: 'title', addLabel: 'Shto angazhim', wide: true),
                            self::repeater('metrics', 'Treguesit', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                                self::t('value', 'Vlera', max: 30),
                                self::t('label', 'Etiketa', max: 80), ], min: 1, maxItems: 8, titleField: 'label', addLabel: 'Shto tregues', wide: true),
                        ],
                    ],
                    [
                        'label' => 'PËRPUTHSHMËRIA',
                        'fields' => [
                            self::t('compliance_eyebrow', 'Etiketa', max: 80),
                            self::t('compliance_title', 'Titulli', max: 120),
                            self::area('compliance_description', 'Përshkrimi', max: 400, rows: 2, wide: true),
                            self::lines('compliance_points', 'Pikat (një për rresht)', max: 1000, rows: 4, wide: true),
                        ],
                    ],
                    self::ctaGroup(),
                ],
            ],

            'regeneration_page' => [
                'title' => 'Faqja /regeneration',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Hero, pajisjet, laboratori i baterive dhe oferta.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'KRYETITULLI',
                        'fields' => [
                            self::t('meta_title', 'Titulli SEO', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi SEO', max: 300, rows: 2, wide: true),
                            self::t('eyebrow', 'Etiketa', max: 80),
                            self::t('title', 'Titulli', max: 120),
                            self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::t('hero_cta_primary', 'Butoni kryesor', max: 60),
                            self::t('hero_cta_secondary', 'Butoni dytë', max: 60),
                            self::select('hero_cta_secondary_target', 'Ku dërgon butoni dytë', self::linkTargets()),
                            self::t('teaser_eyebrow', 'Etiketa e teaser-it', max: 80),
                            self::t('teaser_title', 'Titulli i teaser-it', max: 120),
                            self::area('teaser_description', 'Përshkrimi i teaser-it', max: 400, rows: 3, wide: true),
                            self::t('teaser_cta', 'Butoni i teaser-it', max: 60),
                            self::select('teaser_cta_target', 'Ku dërgon butoni i teaser-it', self::linkTargets()),
                            self::t('teaser_secondary_cta', 'Butoni dytë i teaser-it', max: 60),
                            self::select('teaser_secondary_cta_target', 'Ku dërgon butoni dytë', self::linkTargets()),
                        ],
                    ],
                    [
                        'label' => 'PAJISJET',
                        'fields' => [
                            self::t('tools_eyebrow', 'Etiketa', max: 80),
                            self::t('tools_title', 'Titulli', max: 120),
                            self::area('tools_description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::repeater('tools', 'Pajisjet', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                                self::select('icon', 'Ikona', self::icons()),
                                self::t('model', 'Modeli', max: 120),
                                self::t('title', 'Titulli', max: 120),
                                self::area('description', 'Përshkrimi', max: 600, rows: 3, wide: true),
                                self::lines('features', 'Karakteristikat (një për rresht)', max: 1000, rows: 4, wide: true), ], min: 1, maxItems: 8, titleField: 'title', addLabel: 'Shto pajisje', wide: true),
                        ],
                    ],
                    [
                        'label' => 'LABORATORI I BATERIVE',
                        'fields' => [
                            self::t('lab_eyebrow', 'Etiketa', max: 80),
                            self::t('lab_title', 'Titulli', max: 120),
                            self::area('lab_description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::t('lab_panel_title', 'Titulli i panelit', max: 120, wide: true),
                            self::image('lab_brand_logo', 'Logoja e partnerit', hint: 'Shfaqet krah logos tuaj.'),
                            self::t('lab_brand_alt', 'Teksti alternativ i logos', max: 80),
                            self::repeater('lab_steps', 'Hapat e laboratorit', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                                self::t('title', 'Titulli', max: 120),
                                self::area('description', 'Përshkrimi', max: 400, rows: 3, wide: true),
                                self::image('image', 'Fotografia'),
                                self::t('alt', 'Teksti alternativ', max: 180),
                                self::num('width', 'Gjerësia (px)', max: 2000, optional: true),
                                self::num('height', 'Lartësia (px)', max: 2000, optional: true), ], min: 1, maxItems: 8, titleField: 'title', addLabel: 'Shto hap', wide: true),
                            self::area('lab_note', 'Shënimi nën fund', max: 600, rows: 3, wide: true),
                            self::image('lab_badge_image', 'Sellia e cilësisë'),
                            self::t('lab_badge_alt', 'Teksti alternativ i sellës', max: 120, optional: true),
                            self::num('lab_badge_width', 'Gjerësia e sellës (px)', max: 2000, optional: true),
                            self::num('lab_badge_height', 'Lartësia e sellës (px)', max: 2000, optional: true),
                        ],
                    ],
                    [
                        'label' => 'PROCESI DHE OFERTA',
                        'fields' => [
                            self::t('process_eyebrow', 'Etiketa', max: 80),
                            self::t('process_title', 'Titulli', max: 120),
                            self::area('process_description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::repeater('process_steps', 'Hapat', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                                self::t('title', 'Titulli', max: 120),
                                self::area('description', 'Përshkrimi', max: 400, rows: 3), ], min: 1, maxItems: 6, titleField: 'title', addLabel: 'Shto hap', wide: true),
                            self::t('offer_eyebrow', 'Etiketa e ofertës', max: 80),
                            self::t('offer_title', 'Titulli i ofertës', max: 120),
                            self::area('offer_description', 'Përshkrimi i ofertës', max: 600, rows: 3, wide: true),
                        ],
                    ],
                    self::ctaGroup(),
                ],
            ],

            'resources' => [
                'title' => 'Faqja /resources',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Artikujt edukativë dhe paneli i planifikimit.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'KRYETITULLI',
                        'fields' => [
                            self::t('meta_title', 'Titulli SEO', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi SEO', max: 300, rows: 2, wide: true),
                            self::t('eyebrow', 'Etiketa', max: 80),
                            self::t('title', 'Titulli', max: 120),
                            self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                            self::t('hero_cta_primary', 'Butoni kryesor', max: 60),
                            self::t('hero_cta_secondary', 'Butoni dytë', max: 60),
                            self::select('hero_cta_secondary_target', 'Ku dërgon butoni dytë', self::linkTargets()),
                            self::t('article_more_label', 'Teksti i hapjes së artikullit', max: 60),
                        ],
                    ],
                    [
                        'label' => 'ARTIKUJT',
                        'fields' => [
                            self::repeater('articles', 'Artikujt', [self::t('key', 'Identifikuesi', max: 60, optional: true),
                                self::t('category', 'Kategoria', max: 60),
                                self::t('title', 'Titulli', max: 160),
                                self::area('excerpt', 'Përmbledhja', max: 400, rows: 3, wide: true),
                                self::t('reading_time', 'Koha e leximit', max: 30),
                                self::lines('paragraphs', 'Paragrafët (linjë bosh mes tyre)', max: 3000, rows: 8, wide: true, paragraphs: true), ], min: 1, maxItems: 24, titleField: 'title', addLabel: 'Shto artikull', wide: true),
                        ],
                    ],
                    [
                        'label' => 'PANELI I PLANIFIKIMIT',
                        'fields' => [
                            self::t('planning_eyebrow', 'Etiketa', max: 80),
                            self::t('planning_title', 'Titulli', max: 120),
                            self::area('planning_description', 'Përshkrimi', max: 500, rows: 3, wide: true),
                            self::lines('planning_points', 'Pikat (një për rresht)', max: 800, rows: 4, wide: true),
                        ],
                    ],
                    self::ctaGroup(),
                ],
            ],

            'diagnostics_page' => [
                'title' => 'Faqja /diagnostics',
                'kicker' => 'FAQT E BRENDSHME',
                'description' => 'Hero, opsionet dhe etiketat e formularit.',
                'shape' => 'group',
                'groups' => [
                    [
                        'label' => 'KRYETITULLI',
                        'fields' => [
                            self::t('meta_title', 'Titulli SEO', max: 120, wide: true),
                            self::area('meta_description', 'Përshkrimi SEO', max: 300, rows: 2, wide: true),
                            self::t('eyebrow', 'Etiketa', max: 80),
                            self::t('title', 'Titulli', max: 120),
                            self::area('description', 'Përshkrimi', max: 350, rows: 2, wide: true),
                        ],
                    ],
                    [
                        'label' => 'OPSIONET E FORMULARIT',
                        'fields' => [
                            self::pairs('sectors', 'Sektorët', hint: 'Çdo rresht: vlera = etiketa.'),
                            self::pairs('battery_types', 'Llojet e baterive', hint: 'Çdo rresht: vlera = etiketa.'),
                            self::pairs('service_preferences', 'Mënyrat e shërbimit', hint: 'Çdo rresht: vlera = etiketa.'),
                        ],
                    ],
                    [
                        'label' => 'ETIKETAT E FUSHAVE',
                        'fields' => [
                            self::t('label_name', 'Emri dhe mbiemri', max: 60),
                            self::t('label_company', 'Kompania (opsionale)', max: 60),
                            self::t('label_email', 'Email', max: 60),
                            self::t('label_phone', 'Telefoni', max: 60),
                            self::t('label_sector', 'Sektori', max: 60),
                            self::t('label_battery_type', 'Lloji i baterive', max: 60),
                            self::t('label_date', 'Data e preferuar', max: 60),
                            self::t('label_service_preference', 'Mënyra e shërbimit', max: 60),
                            self::area('label_notes', 'Shënimet', max: 200, rows: 2),
                            self::t('placeholder_select', 'Teksti i opsionit bosh', max: 60),
                            self::t('error_summary', 'Titulli i gabimeve', max: 120, wide: true),
                            self::t('submit_label', 'Butoni i dërgimit', max: 40),
                            self::t('success_message', 'Mesazhi pas dërgimit', max: 250, wide: true),
                            self::t('submit_hint', 'Ndihma pas butonit', max: 160, wide: true),
                        ],
                    ],
                    self::ctaGroup(),
                ],
            ],
        ];
    }

    /**
     * Reusable call-to-action band fields.
     *
     * @return array<string, mixed>
     */
    private static function ctaGroup(): array
    {
        return [
            'label' => 'BANDA E THIRRJES',
            'fields' => [
                self::t('cta_title', 'Titulli', max: 160, wide: true),
                self::area('cta_description', 'Përshkrimi', max: 400, rows: 2, wide: true),
                self::t('cta_primary', 'Butoni kryesor', max: 60),
                self::select('cta_primary_target', 'Ku dërgon', self::linkTargets()),
                self::t('cta_secondary', 'Butoni dytë', max: 60),
                self::select('cta_secondary_target', 'Ku dërgon', self::linkTargets()),
            ],
        ];
    }

    // -----------------------------------------------------------------
    // Field helpers
    // -----------------------------------------------------------------

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    private static function base(string $key, string $label, string $type, int $max, int $rows, bool $wide, ?string $hint, bool $optional, ?string $input, bool $pair, bool $paragraphs): array
    {
        return [
            'key' => $key,
            'label' => $label,
            'type' => $type,
            'required' => ! $optional,
            'optional' => $optional,
            'max' => $max,
            'rows' => $rows,
            'wide' => $wide,
            'hint' => $hint,
            'input' => $input,
            'pair' => $pair,
            'paragraphs' => $paragraphs,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function t(string $key, string $label, int $max = 255, int $rows = 3, bool $wide = false, ?string $hint = null, bool $optional = false, ?string $input = null): array
    {
        return self::base($key, $label, 'text', $max, $rows, $wide, $hint, $optional, $input, false, false);
    }

    /**
     * @return array<string, mixed>
     */
    private static function area(string $key, string $label, int $max = 1000, int $rows = 3, bool $wide = false, ?string $hint = null, bool $optional = false): array
    {
        return self::base($key, $label, 'textarea', $max, $rows, $wide, $hint, $optional, null, false, false);
    }

    /**
     * @return array<string, mixed>
     */
    private static function lines(string $key, string $label, int $max = 1000, int $rows = 4, bool $wide = false, ?string $hint = null, bool $optional = false, bool $pair = false, bool $paragraphs = false): array
    {
        return self::base($key, $label, 'list', $max, $rows, $wide, $hint, $optional, null, $pair, $paragraphs);
    }

    /**
     * @return array<string, mixed>
     */
    private static function pairs(string $key, string $label, int $max = 2000, int $rows = 6, bool $wide = false, ?string $hint = null): array
    {
        return self::base($key, $label, 'pairs', $max, $rows, $wide, $hint, false, null, true, false);
    }

    /**
     * @return array<string, mixed>
     */
    private static function num(string $key, string $label, int $max = 1000, int $rows = 3, bool $wide = false, ?string $hint = null, bool $optional = true): array
    {
        return self::base($key, $label, 'number', $max, $rows, $wide, $hint, $optional, 'number', false, false);
    }

    /**
     * @return array<string, mixed>
     */
    private static function url(string $key, string $label, int $max = 400, int $rows = 3, bool $wide = false, ?string $hint = null, bool $optional = false): array
    {
        return self::base($key, $label, 'url', $max, $rows, $wide, $hint, $optional, 'url', false, false);
    }

    /**
     * @return array<string, mixed>
     */
    private static function color(string $key, string $label, int $max = 7, int $rows = 3, bool $wide = false, ?string $hint = null): array
    {
        return self::base($key, $label, 'color', $max, $rows, $wide, $hint, false, 'color', false, false);
    }

    /**
     * @return array<string, mixed>
     */
    private static function check(string $key, string $label, int $max = 1, int $rows = 3, bool $wide = false, ?string $hint = null): array
    {
        return self::base($key, $label, 'checkbox', $max, $rows, $wide, $hint, false, null, false, false);
    }

    /**
     * @return array<string, mixed>
     */
    private static function image(string $key, string $label, int $max = 255, int $rows = 3, bool $wide = false, ?string $hint = null, bool $optional = true): array
    {
        return self::base($key, $label, 'image', $max, $rows, $wide, $hint, $optional, null, false, false);
    }

    /**
     * @param  array<string, string>  $choices
     * @return array<string, mixed>
     */
    private static function select(string $key, string $label, array $choices, int $max = 255, int $rows = 3, bool $wide = false, ?string $hint = null, bool $optional = true): array
    {
        return array_merge(
            self::base($key, $label, 'select', $max, $rows, $wide, $hint, $optional, null, false, false),
            ['options' => $choices],
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @return array<string, mixed>
     */
    private static function repeater(string $key, string $label, array $fields, int $min = 0, int $maxItems = 20, string $titleField = 'title', string $addLabel = 'Shto rresht', bool $sortable = true, bool $wide = false, ?string $hint = null, bool $keyed = false): array
    {
        return array_merge(
            self::base($key, $label, 'repeater', $maxItems, 3, $wide, $hint, false, null, false, false),
            [
                'fields' => $fields,
                'title_field' => $titleField,
                'add_label' => $addLabel,
                'min' => $min,
                'max_items' => $maxItems,
                'sortable' => $sortable,
                'keyed' => $keyed,
            ],
        );
    }

    /**
     * Validation rule for a single field.
     *
     * @param  array<string, mixed>  $field
     * @return array<int, mixed>
     */
    public static function rules(array $field): array
    {
        $presence = $field['required'] ?? false ? 'required' : 'nullable';

        return match ($field['type']) {
            'number' => array_filter([$presence, 'integer', 'min:0', 'max:'.max($field['max'], 1)]),
            'checkbox' => ['nullable', 'boolean'],
            'select' => array_filter([$presence, 'string', 'max:'.$field['max'], Rule::in(array_keys($field['options'] ?? []))]),
            'color' => array_filter([$presence, 'string', 'regex:/\A#[0-9a-fA-F]{6}\z/']),
            'url' => array_filter([$presence, 'string', 'max:'.$field['max'], 'url']),
            'list', 'pairs' => [$presence, 'string', 'max:'.$field['max']],
            'image' => array_filter([$presence, 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120']),
            default => array_filter([$presence, 'string', 'max:'.$field['max']]),
        };
    }

    /**
     * Sanitise a submitted scalar value.
     */
    public static function sanitise(string $type, mixed $value): mixed
    {
        if ($type === 'checkbox') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        if ($type === 'number') {
            return $value === null || $value === '' ? null : (int) $value;
        }

        if (! is_string($value)) {
            return $value === null ? null : (string) $value;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    /**
     * Convert a textarea value into the stored array shape.
     *
     * @param  array<string, mixed>  $field
     * @return array<int, string>
     */
    public static function toList(array $field, mixed $value): array
    {
        if (! is_string($value)) {
            return [];
        }

        if ($field['pair'] ?? false) {
            return self::toPairs($value);
        }

        if ($field['paragraphs'] ?? false) {
            return array_values(array_filter(array_map(
                'trim',
                preg_split('/\R\s*\R/', trim($value)) ?: [],
            ), fn (string $block): bool => $block !== ''));
        }

        return array_values(array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', $value) ?: [],
        ), fn (string $line): bool => $line !== ''));
    }

    /**
     * Convert `key = label` lines into an associative array.
     *
     * @return array<string, string>
     */
    public static function toPairs(mixed $value): array
    {
        if (is_array($value)) {
            $value = implode("\n", array_map(
                fn (mixed $row): string => is_array($row) ? ($row['value'] ?? '').'='.($row['label'] ?? '') : (string) $row,
                $value,
            ));
        }

        if (! is_string($value)) {
            return [];
        }

        $pairs = [];

        foreach (preg_split('/\r\n|\r|\n/', $value) ?: [] as $line) {
            if (! str_contains($line, '=')) {
                continue;
            }

            [$rawKey, $label] = explode('=', $line, 2);
            $key = Str::slug(trim($rawKey), '_');

            if ($key !== '') {
                $pairs[$key] = trim($label);
            }
        }

        return $pairs;
    }

    /**
     * Render stored pairs/lists back into their textarea representation.
     */
    public static function fromList(array $field, mixed $value): string
    {
        if ($field['type'] === 'pairs') {
            $lines = [];

            foreach ((array) $value as $key => $label) {
                $lines[] = $key.'='.$label;
            }

            return implode("\n", $lines);
        }

        return implode("\n", is_array($value) ? $value : []);
    }
}
