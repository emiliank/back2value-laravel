<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TradeInRequest extends Model
{
    protected $fillable = [
        'customer_company_name',
        'sector',
        'battery_condition',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
