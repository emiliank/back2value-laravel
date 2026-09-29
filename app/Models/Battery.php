<?php

namespace App\Models;

use Database\Factories\BatteryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    use HasFactory;

    /**
     * Stock states a product can be in, in the order they should be offered.
     *
     * @var list<string>
     */
    public const STOCK_STATUSES = ['in_stock', 'low_stock', 'on_preorder', 'out_of_stock'];

    /**
     * Products that cannot be supplied immediately must be pre-ordered.
     *
     * @var list<string>
     */
    public const PREORDER_STATUSES = ['on_preorder', 'out_of_stock'];

    protected static function newFactory(): BatteryFactory
    {
        return BatteryFactory::new();
    }

    protected $fillable = [
        'serial_number',
        'brand',
        'category',
        'model',
        'capacity_ah',
        'voltage',
        'technology',
        'description',
        'specs',
        'application_type',
        'status',
        'purchase_price',
        'sale_price',
        'warranty_months',
        'is_available',
        'stock_status',
    ];

    protected $attributes = [
        'stock_status' => 'in_stock',
    ];

    protected $casts = [
        'capacity_ah' => 'integer',
        'application_type' => 'string',
        'specs' => 'array',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'warranty_months' => 'integer',
        'is_available' => 'boolean',
        'stock_status' => 'string',
    ];

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function scopeReactivated(Builder $query): Builder
    {
        return $query->where('status', 'reactivated');
    }

    public function scopeAvailableReactivated(Builder $query): Builder
    {
        return $query
            ->where('is_available', true)
            ->where('status', 'reactivated');
    }

    /**
     * Products that must be ordered in rather than bought off the shelf.
     */
    public function scopeNeedingPreorder(Builder $query): Builder
    {
        return $query->whereIn('stock_status', self::PREORDER_STATUSES);
    }

    public function needsPreorder(): bool
    {
        return in_array($this->stock_status, self::PREORDER_STATUSES, true);
    }

    /**
     * Admin-facing stock labels, kept in sync with the public CMS copy.
     *
     * @return array<string, string>
     */
    public static function stockStatusLabels(): array
    {
        return [
            'in_stock' => 'Në stok',
            'low_stock' => 'Stok i kufizuar',
            'on_preorder' => 'Porosi paraprake',
            'out_of_stock' => 'Jashtë stokut',
        ];
    }
}
