<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public const AFFILIATE_URL = 'affiliate_url';

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
    ];
}
