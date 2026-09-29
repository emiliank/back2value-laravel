<?php

namespace App\Http\Controllers\Admin;

use App\Content\SiteContentRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\BatteryRequest;
use App\Models\Battery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class BatteryController extends Controller
{
    public function index(Request $request, SiteContentRepository $content): View
    {
        $query = Battery::query();

        if ($request->filled('q')) {
            $search = '%'.trim((string) $request->query('q')).'%';
            $query->where(function ($sub) use ($search): void {
                $sub->where('model', 'like', $search)
                    ->orWhere('brand', 'like', $search)
                    ->orWhere('serial_number', 'like', $search)
                    ->orWhere('category', 'like', $search)
                    ->orWhere('technology', 'like', $search);
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->query('brand'));
        }

        if ($request->filled('application_type')) {
            $query->where('application_type', $request->query('application_type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->query('available') !== null && $request->query('available') !== '') {
            $query->where('is_available', $request->query('available') === '1');
        }

        if ($request->filled('stock_status')) {
            $query->where('stock_status', $request->query('stock_status'));
        }

        $batteries = $query
            ->orderBy('brand')
            ->orderByDesc('capacity_ah')
            ->paginate(30)
            ->withQueryString();

        return view('admin.catalog.index', [
            'batteries' => $batteries,
            'filters' => [
                'q' => $request->query('q'),
                'category' => $request->query('category'),
                'brand' => $request->query('brand'),
                'application_type' => $request->query('application_type'),
                'status' => $request->query('status'),
                'available' => $request->query('available'),
                'stock_status' => $request->query('stock_status'),
            ],
            'total' => Battery::query()->count(),
            'availableCount' => Battery::query()->where('is_available', true)->count(),
            'categories' => $this->options('category'),
            'brands' => $this->options('brand'),
            'applicationTypes' => $this->options('application_type'),
            'statuses' => $this->options('status'),
            'technologies' => $this->options('technology'),
            'statusLabels' => $this->statusLabels(),
            'stockStatuses' => Battery::stockStatusLabels(),
            'preorderCount' => Battery::query()->needingPreorder()->count(),
            'applicationLabels' => array_map(
                fn (string $key): string => $content->section('application_filters')[$key]['label'] ?? $key,
                $this->options('application_type')->all(),
            ),
        ]);
    }

    public function create(): View
    {
        return view('admin.catalog.form', [
            'battery' => new Battery(['status' => 'new', 'warranty_months' => 24, 'is_available' => true, 'stock_status' => 'in_stock']),
            'action' => route('admin.catalog.store'),
            'method' => 'POST',
            'title' => 'Bateri i ri',
            'categories' => $this->options('category'),
            'brands' => $this->options('brand'),
            'technologies' => $this->options('technology'),
            'applicationTypes' => $this->options('application_type'),
            'statusLabels' => $this->statusLabels(),
            'stockStatuses' => Battery::stockStatusLabels(),
        ]);
    }

    public function store(BatteryRequest $request): RedirectResponse
    {
        $battery = Battery::query()->create($request->validated());

        return redirect()
            ->route('admin.catalog.index')
            ->with('status', 'Bateria "'.$battery->model.'" u shtua në katalog.');
    }

    public function edit(Battery $battery): View
    {
        return view('admin.catalog.form', [
            'battery' => $battery,
            'action' => route('admin.catalog.update', ['battery' => $battery]),
            'method' => 'PUT',
            'title' => $battery->model ?: ('Bateria #'.$battery->id),
            'categories' => $this->options('category'),
            'brands' => $this->options('brand'),
            'technologies' => $this->options('technology'),
            'applicationTypes' => $this->options('application_type'),
            'statusLabels' => $this->statusLabels(),
            'stockStatuses' => Battery::stockStatusLabels(),
        ]);
    }

    public function update(BatteryRequest $request, Battery $battery): RedirectResponse
    {
        $battery->update($request->validated());

        return redirect()
            ->route('admin.catalog.index')
            ->with('status', 'Bateria u përditësua me sukses.');
    }

    public function destroy(Battery $battery): RedirectResponse
    {
        $battery->delete();

        return redirect()
            ->route('admin.catalog.index')
            ->with('status', 'Bateria u fshi nga katalogu.');
    }

    /**
     * Distinct, non-empty values of a column for the filter and select inputs.
     *
     * @return Collection<int, string>
     */
    private function options(string $column): Collection
    {
        return Battery::query()
            ->whereNotNull($column)
            ->where($column, '!=', '')
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }

    /**
     * @return array<string, string>
     */
    private function statusLabels(): array
    {
        return [
            'new' => 'E re',
            'reactivated' => 'E rigjeneruar',
            'end_of_life' => 'Jashtë jetës',
        ];
    }
}
