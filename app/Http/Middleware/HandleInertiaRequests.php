<?php

namespace App\Http\Middleware;

use App\Content\ContentSchema;
use App\Content\SiteContentRepository;
use App\Support\Locale;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $content = app(SiteContentRepository::class);
        $settings = $content->section('settings');
        $navigation = $content->section('navigation');
        $locale = app(Locale::class)->current();
        $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp'] ?? '355692734476');
        $whatsappMessage = $settings['whatsapp_message'] ?? 'Përshëndetje! Po interesohem për një ofertë për bateri industriale.';
        $logo = site_logo();

        foreach (['header_items', 'footer_items'] as $itemList) {
            $navigation[$itemList] = array_map(
                fn (array $item): array => [
                    ...$item,
                    'href' => site_link($item['target'] ?? 'home'),
                ],
                $navigation[$itemList] ?? [],
            );
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() === null ? null : [
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
            ],
            'site' => [
                'settings' => $settings,
                'navigation' => $navigation,
                'locales' => app(Locale::class)->available(),
                'locale' => $locale,
                'defaultLocale' => app(Locale::class)->default(),
                'adminPages' => ContentSchema::pages(),
                'logo' => $logo === null ? null : site_image($logo),
                'whatsappUrl' => 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($whatsappMessage),
                'secondaryCtaUrl' => site_link($navigation['header_cta_secondary_target'] ?? 'diagnostics'),
                'csrfToken' => csrf_token(),
            ],
            'flash' => [
                'status' => fn (): mixed => $request->session()->get('status'),
            ],
            'oldInput' => fn (): array => $request->session()->get('_old_input', []),
        ];
    }
}
