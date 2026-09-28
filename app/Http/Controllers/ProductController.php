<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Services\SiteContentService;
use App\Services\VehicleFitmentService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request, SiteContentService $siteContent, VehicleFitmentService $vehicleFitment): View
    {
        $content = $siteContent->all();
        $settings = $content['settings'] ?? config('site.settings');
        $applicationFilters = $content['application_filters'] ?? config('site.application_filters');
        $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp'] ?? '355692734476');

        $vehicleActive = $vehicleFitment->isActive($request->query());
        $vehicleQuery = $vehicleFitment->queryState($request->query());
        $vehicleProfile = $vehicleActive
            ? $vehicleFitment->profile($request->query(), $whatsappNumber)
            : null;

        $query = Battery::query()->where('is_available', true);

        $activeApplication = (string) $request->query('application', '');
        $applicationFilter = $applicationFilters[$activeApplication] ?? null;

        if ($applicationFilter !== null && ! $vehicleActive) {
            $query->where(function ($sub) use ($applicationFilter): void {
                if (! empty($applicationFilter['categories'])) {
                    $sub->orWhereIn('category', $applicationFilter['categories']);
                }

                if (! empty($applicationFilter['application_types'])) {
                    $sub->orWhereIn('application_type', $applicationFilter['application_types']);
                }
            });
        }

        // The vehicle finder owns the scope (starter batteries for vehicles),
        // so category/application facets are ignored while it is active.
        if ($request->filled('category') && ! $vehicleActive) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('application_type') && ! $vehicleActive) {
            $query->where('application_type', $request->query('application_type'));
        }

        if ($request->filled('brand') && ! $vehicleActive) {
            $query->where('brand', $request->query('brand'));
        }

        if ($request->filled('status') && ! $vehicleActive) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('q')) {
            $search = '%'.trim((string) $request->query('q')).'%';
            $query->where(function ($sub) use ($search): void {
                $sub->where('model', 'like', $search)
                    ->orWhere('brand', 'like', $search)
                    ->orWhere('category', 'like', $search)
                    ->orWhere('technology', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        $allCategories = Battery::query()
            ->where('is_available', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->values()
            ->all();

        if ($vehicleActive) {
            $query->where('category', VehicleFitmentService::STARTER_CATEGORY);
        }

        $products = $query
            ->orderBy('category')
            ->orderBy('brand')
            ->orderBy('capacity_ah')
            ->get();

        if ($vehicleActive && $vehicleProfile !== null) {
            $products = $vehicleFitment->narrow($products, $vehicleProfile);
        }

        $products = $products
            ->map(function (Battery $battery) use ($whatsappNumber): array {
                $title = filled($battery->model)
                    ? $battery->model
                    : ($battery->brand.' '.$battery->capacity_ah.'Ah');

                $category = filled($battery->category)
                    ? $battery->category
                    : match ($battery->application_type) {
                        'solar' => 'Solar',
                        'auto' => 'Auto',
                        'backup_power' => 'Backup Power',
                        default => 'Other',
                    };

                $inquiryText = rawurlencode("Përshëndetje! Jam i interesuar për ofertë çmimi për baterinë: {$title} ({$battery->capacity_ah}Ah).");
                $quoteUrl = "https://wa.me/{$whatsappNumber}?text={$inquiryText}";

                return [
                    'id' => $battery->id,
                    'serial_number' => $battery->serial_number,
                    'title' => $title,
                    'model' => $battery->model,
                    'brand' => $battery->brand,
                    'category' => $category,
                    'capacity_ah' => $battery->capacity_ah,
                    'voltage' => $battery->voltage ?? ($battery->capacity_ah >= 200 && str_contains((string) $battery->model, 'OPzS') ? '2V' : null),
                    'technology' => $battery->technology,
                    'application_type' => $battery->application_type,
                    'status' => $battery->status,
                    'warranty_months' => $battery->warranty_months,
                    'specs' => is_array($battery->specs) ? $battery->specs : [],
                    'quote_url' => $quoteUrl,
                    'description' => filled($battery->description)
                        ? $battery->description
                        : match ($battery->status) {
                            'reactivated' => 'Bateri e rigjeneruar dhe e testuar me standarde laboratorike RID.',
                            'end_of_life' => 'Bateri e përfunduar; për përdorim të vazhdueshëm rekomandohet zëvendësim.',
                            default => 'Bateri e re me performancë të besueshme dhe garanci fabrike.',
                        },
                ];
            });

        $groupedProducts = $products->groupBy('category');

        $categoryImages = $groupedProducts->mapWithKeys(
            fn ($items, string $category): array => [$category => $this->categoryImage($category)]
        );

        return view('products.index', [
            'groupedProducts' => $groupedProducts,
            'categoryImages' => $categoryImages,
            'allCategories' => $allCategories,
            'applicationFilters' => $applicationFilters,
            'settings' => $settings,
            'activeCategory' => $request->query('category'),
            'activeApplication' => $activeApplication,
            'searchQuery' => $request->query('q'),
            'vehicleProfile' => $vehicleProfile,
            'vehicleQuery' => $vehicleQuery,
            'vehicleOptions' => $vehicleFitment->options(),
        ]);
    }

    /**
     * Map each catalog category to a representative image so the catalog
     * stays visual even without per-product photography. Rule-based so
     * newly added categories still resolve to a sensible image.
     *
     * @return array{image: string, alt: string}
     */
    private function categoryImage(string $category): array
    {
        $ups = [
            'image' => 'images/battery-ups.jpg',
            'alt' => 'Bateri të gjelbra RID për sisteme UPS dhe përdorim industrial',
        ];

        $rules = [
            ['Diagnostics', ['image' => 'images/battery-generic.svg', 'alt' => 'Pajisje diagnostike për teste baterish']],
            ['ST Series', ['image' => 'images/battery-start.jpg', 'alt' => 'Bateri startimi RID-Batterie për automjete dhe kamionë']],
            ['Xtreme', $ups],
            ['OPzV', $ups],
            ['Socomec', $ups],
            ['UPS', $ups],
        ];

        foreach ($rules as [$needle, $image]) {
            if (str_contains($category, $needle)) {
                return $image;
            }
        }

        return [
            'image' => 'images/battery-pzs.jpg',
            'alt' => 'Bateri traksionare RID me qeliza industriale dhe kapakë portokalli',
        ];
    }
}
