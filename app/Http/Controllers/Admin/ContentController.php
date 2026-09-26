<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function settings(SiteContentService $siteContent): View
    {
        return view('admin.settings', [
            'activeSection' => 'settings',
            'settings' => $siteContent->all()['settings'],
        ]);
    }

    public function updateSettings(Request $request, SiteContentService $siteContent): RedirectResponse
    {
        $validated = $request->validate([
            'meta_title' => ['required', 'string', 'max:120'],
            'meta_description' => ['required', 'string', 'max:300'],
            'accent_color' => ['required', 'regex:/\A#[0-9a-fA-F]{6}\z/'],
            'hero_badge' => ['required', 'string', 'max:120'],
            'hero_title' => ['required', 'string', 'max:100'],
            'hero_highlight' => ['required', 'string', 'max:100'],
            'hero_subtitle' => ['required', 'string', 'max:100'],
            'hero_description' => ['required', 'string', 'max:500'],
            'hero_cta' => ['required', 'string', 'max:100'],
            'hero_products_cta' => ['required', 'string', 'max:100'],
            'promo_title' => ['required', 'string', 'max:150'],
            'promo_description' => ['required', 'string', 'max:250'],
            'promo_link' => ['required', 'string', 'max:150'],
            'products_eyebrow' => ['required', 'string', 'max:80'],
            'products_title' => ['required', 'string', 'max:120'],
            'products_description' => ['required', 'string', 'max:350'],
            'services_eyebrow' => ['required', 'string', 'max:80'],
            'services_title' => ['required', 'string', 'max:120'],
            'services_description' => ['required', 'string', 'max:350'],
            'contact_eyebrow' => ['required', 'string', 'max:80'],
            'contact_title' => ['required', 'string', 'max:120'],
            'contact_description' => ['required', 'string', 'max:350'],
            'footer_description' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:30'],
            'whatsapp' => ['required', 'regex:/\A\+?[0-9 ]{8,20}\z/'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:180'],
            'hours' => ['required', 'string', 'max:120'],
            'map_query' => ['required', 'string', 'max:180'],
        ]);

        $siteContent->save('settings', $validated);

        return back()->with('status', 'Cilësimet e faqes u ruajtën me sukses.');
    }

    public function products(SiteContentService $siteContent): View
    {
        $products = collect($siteContent->all()['products'])
            ->sortBy('sort_order')
            ->values()
            ->all();

        return view('admin.products', [
            'activeSection' => 'products',
            'products' => $products,
        ]);
    }

    public function updateProducts(Request $request, SiteContentService $siteContent): RedirectResponse
    {
        $validated = $request->validate([
            'products' => ['required', 'array', 'min:1', 'max:20'],
            'products.*.key' => ['required', 'alpha_dash:ascii', 'max:80'],
            'products.*.title' => ['required', 'string', 'max:120'],
            'products.*.description' => ['required', 'string', 'max:1000'],
            'products.*.features' => ['nullable', 'string', 'max:1500'],
            'products.*.image_alt' => ['required', 'string', 'max:180'],
            'products.*.sort_order' => ['required', 'integer', 'min:0', 'max:1000'],
            'products.*.remove_image' => ['nullable', 'boolean'],
            'products.*.image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $currentProducts = collect($siteContent->all()['products'])->keyBy('key');
        $products = [];
        $seenKeys = [];

        foreach ($validated['products'] as $index => $product) {
            $key = $product['key'];

            if (in_array($key, $seenKeys, true)) {
                return back()->withErrors(["products.$index.key" => 'Çdo produkt duhet të ketë një identifikues unik.'])->withInput();
            }

            $seenKeys[] = $key;
            $currentProduct = $currentProducts->get($key, []);

            if (($product['remove'] ?? false) === true) {
                $this->deleteStoredImage($currentProduct['image'] ?? null);

                continue;
            }

            $image = $currentProduct['image'] ?? null;
            $uploadedImage = $request->file("products.$index.image");

            if (filter_var($product['remove_image'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                $this->deleteStoredImage($image);
                $image = null;
            }

            if ($uploadedImage !== null) {
                $this->deleteStoredImage($image);
                $image = $uploadedImage->store('products', 'public');
            }

            $products[] = [
                'key' => $key,
                'title' => $product['title'],
                'description' => $product['description'],
                'features' => collect(preg_split('/\r\n|\r|\n/', $product['features'] ?? '') ?: [])
                    ->map(fn (string $feature): string => trim($feature))
                    ->filter()
                    ->values()
                    ->all(),
                'image' => $image,
                'image_alt' => $product['image_alt'],
                'sort_order' => (int) $product['sort_order'],
            ];
        }

        if ($products === []) {
            return back()->withErrors(['products' => 'Faqja duhet të ketë të paktën një produkt.'])->withInput();
        }

        $this->deleteRemovedImages($currentProducts->all(), collect($products)->pluck('key')->all());

        $siteContent->save('products', collect($products)->sortBy('sort_order')->values()->all());

        return back()->with('status', 'Produktet dhe imazhet u përditësuan me sukses.');
    }

    public function services(SiteContentService $siteContent): View
    {
        return view('admin.services', [
            'activeSection' => 'services',
            'services' => collect($siteContent->all()['services'])->sortBy('sort_order')->values()->all(),
        ]);
    }

    public function updateServices(Request $request, SiteContentService $siteContent): RedirectResponse
    {
        $validated = $request->validate([
            'services' => ['required', 'array', 'min:1', 'max:20'],
            'services.*.key' => ['required', 'alpha_dash:ascii', 'max:80'],
            'services.*.title' => ['required', 'string', 'max:120'],
            'services.*.description' => ['required', 'string', 'max:1000'],
            'services.*.icon' => ['required', Rule::in(['clipboard', 'refresh', 'calendar', 'download'])],
            'services.*.sort_order' => ['required', 'integer', 'min:0', 'max:1000'],
            'services.*.remove' => ['nullable', 'boolean'],
        ]);

        $services = [];
        $seenKeys = [];

        foreach ($validated['services'] as $index => $service) {
            if (in_array($service['key'], $seenKeys, true)) {
                return back()->withErrors(["services.$index.key" => 'Çdo shërbim duhet të ketë një identifikues unik.'])->withInput();
            }

            $seenKeys[] = $service['key'];

            if (filter_var($service['remove'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            $services[] = [
                'key' => $service['key'],
                'title' => $service['title'],
                'description' => $service['description'],
                'icon' => $service['icon'],
                'sort_order' => (int) $service['sort_order'],
            ];
        }

        if ($services === []) {
            return back()->withErrors(['services' => 'Faqja duhet të ketë të paktën një shërbim.'])->withInput();
        }

        $siteContent->save('services', collect($services)->sortBy('sort_order')->values()->all());

        return back()->with('status', 'Shërbimet u përditësuan me sukses.');
    }

    public function about(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('admin.about', [
            'activeSection' => 'about',
            'about' => $content['about'],
            'stats' => collect($content['stats'])->sortBy('sort_order')->values()->all(),
            'trust' => $content['trust'],
        ]);
    }

    public function updateAbout(Request $request, SiteContentService $siteContent): RedirectResponse
    {
        $validated = $request->validate([
            'about.eyebrow' => ['required', 'string', 'max:80'],
            'about.title' => ['required', 'string', 'max:120'],
            'about.description' => ['required', 'string', 'max:350'],
            'about.points' => ['required', 'array', 'min:1', 'max:6'],
            'about.points.*.key' => ['required', 'alpha_dash:ascii', 'max:80'],
            'about.points.*.title' => ['required', 'string', 'max:120'],
            'about.points.*.description' => ['required', 'string', 'max:800'],
            'about.points.*.label' => ['nullable', 'string', 'max:120'],
            'stats' => ['required', 'array', 'min:1', 'max:8'],
            'stats.*.key' => ['required', 'alpha_dash:ascii', 'max:80'],
            'stats.*.value' => ['required', 'string', 'max:30'],
            'stats.*.label' => ['required', 'string', 'max:80'],
            'stats.*.sort_order' => ['required', 'integer', 'min:0', 'max:1000'],
            'stats.*.remove' => ['nullable', 'boolean'],
            'trust.title' => ['required', 'string', 'max:100'],
            'trust.subtitle' => ['required', 'string', 'max:100'],
            'trust.guarantee' => ['required', 'string', 'max:100'],
            'trust.service' => ['required', 'string', 'max:100'],
        ]);

        $stats = [];
        $seenKeys = [];

        foreach ($validated['stats'] as $index => $stat) {
            if (in_array($stat['key'], $seenKeys, true)) {
                return back()->withErrors(["stats.$index.key" => 'Çdo statistikë duhet të ketë një identifikues unik.'])->withInput();
            }

            $seenKeys[] = $stat['key'];

            if (filter_var($stat['remove'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            $stats[] = [
                'key' => $stat['key'],
                'value' => $stat['value'],
                'label' => $stat['label'],
                'sort_order' => (int) $stat['sort_order'],
            ];
        }

        if ($stats === []) {
            return back()->withErrors(['stats' => 'Shtoni të paktën një statistikë.'])->withInput();
        }

        $siteContent->save('about', $validated['about']);
        $siteContent->save('stats', collect($stats)->sortBy('sort_order')->values()->all());
        $siteContent->save('trust', $validated['trust']);

        return back()->with('status', 'Përmbajtja dhe statistikat u ruajtën me sukses.');
    }

    private function deleteStoredImage(?string $image): void
    {
        if (filled($image) && ! Str::startsWith($image, 'images/')) {
            Storage::disk('public')->delete($image);
        }
    }

    /**
     * @param  array<string, array<string, mixed>>  $currentProducts
     * @param  array<int, string>  $retainedKeys
     */
    private function deleteRemovedImages(array $currentProducts, array $retainedKeys): void
    {
        foreach ($currentProducts as $key => $product) {
            if (! in_array($key, $retainedKeys, true)) {
                $this->deleteStoredImage($product['image'] ?? null);
            }
        }
    }
}
