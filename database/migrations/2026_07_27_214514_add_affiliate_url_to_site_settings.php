<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        SiteSetting::query()->firstOrCreate(
            ['key' => SiteSetting::AFFILIATE_URL],
            [
                'value' => null,
                'type' => 'url',
                'group' => 'enlace_afiliacion',
                'label' => 'URL de afiliación',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteSetting::query()
            ->where('key', SiteSetting::AFFILIATE_URL)
            ->delete();
    }
};
