<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['section', 'locale', 'payload'])]
class SiteContent extends Model
{
    protected function casts(): array
    {
        return [
            'locale' => 'string',
            'payload' => 'array',
        ];
    }

    public function scopeForLocale($query, string $locale)
    {
        return $query->where('locale', $locale);
    }
}
