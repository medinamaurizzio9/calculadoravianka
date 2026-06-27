<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditLevel extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'level',
        'affiliations',
        'affiliation_cost',
        'min_amount',
        'max_amount',
        'annual_rate',
        'available_terms',
        'authorized_use',
        'is_housing',
        'evaluation_required',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'available_terms' => 'array',
            'is_housing' => 'boolean',
            'evaluation_required' => 'boolean',
            'is_active' => 'boolean',
        ];
    }
}
