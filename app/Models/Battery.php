<?php

namespace App\Models;

use Database\Factories\BatteryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    use HasFactory;

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
    ];

    protected $casts = [
        'capacity_ah' => 'integer',
        'application_type' => 'string',
        'specs' => 'array',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'warranty_months' => 'integer',
        'is_available' => 'boolean',
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
}
