<?php

namespace App\Models;

use Database\Factories\DiagnosticBookingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiagnosticBooking extends Model
{
    /** @use HasFactory<DiagnosticBookingFactory> */
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'company_name',
        'sector',
        'battery_type',
        'preferred_date',
        'service_preference',
        'notes',
        'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'status' => 'string',
    ];
}
