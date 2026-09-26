<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['section', 'payload'])]
class SiteContent extends Model
{
    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
