<?php

namespace App\Models;

use Database\Factories\DiagnosticReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosticReport extends Model
{
    use HasFactory;

    protected static function newFactory(): DiagnosticReportFactory
    {
        return DiagnosticReportFactory::new();
    }

    protected $fillable = [
        'battery_id',
        'user_id',
        'initial_voltage',
        'internal_resistance',
        'expected_capacity',
        'actual_capacity',
        'is_reactivation_eligible',
        'notes',
        'tested_at',
    ];

    protected $casts = [
        'initial_voltage' => 'float',
        'internal_resistance' => 'float',
        'expected_capacity' => 'integer',
        'actual_capacity' => 'integer',
        'is_reactivation_eligible' => 'boolean',
        'tested_at' => 'datetime',
    ];

    public function battery(): BelongsTo
    {
        return $this->belongsTo(Battery::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
