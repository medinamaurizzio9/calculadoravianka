<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const SETTINGS = [
        ['key' => 'site_name', 'value' => 'Cooperativa Minera Tierra Bendita', 'type' => 'text', 'group' => 'identidad_publica', 'label' => 'Nombre institucional'],
        ['key' => 'site_logo', 'value' => 'images/tierra-bendita-logo-oficial.png', 'type' => 'image', 'group' => 'identidad_publica', 'label' => 'Logo institucional'],
        ['key' => 'hero_image', 'value' => 'images/tierra-bendita-hero.png', 'type' => 'image', 'group' => 'landing_hero', 'label' => 'Imagen del banner'],
        ['key' => 'hero_eyebrow', 'value' => 'Cooperativa Minera Tierra Bendita', 'type' => 'text', 'group' => 'landing_hero', 'label' => 'Texto introductorio'],
        ['key' => 'hero_title', 'value' => 'Soluciones financieras para construir tu futuro', 'type' => 'text', 'group' => 'landing_hero', 'label' => 'Título principal'],
        ['key' => 'hero_description', 'value' => 'Accede a alternativas de financiamiento diseñadas para nuestros afiliados, con información clara, atención personalizada y herramientas digitales para tomar mejores decisiones.', 'type' => 'textarea', 'group' => 'landing_hero', 'label' => 'Descripción'],
        ['key' => 'hero_primary_text', 'value' => 'Simular mi crédito', 'type' => 'text', 'group' => 'landing_hero', 'label' => 'Texto CTA principal'],
        ['key' => 'hero_primary_url', 'value' => '#simulador', 'type' => 'text', 'group' => 'landing_hero', 'label' => 'URL CTA principal'],
        ['key' => 'hero_secondary_text', 'value' => 'Conocer beneficios', 'type' => 'text', 'group' => 'landing_hero', 'label' => 'Texto CTA secundario'],
        ['key' => 'hero_secondary_url', 'value' => '#beneficios', 'type' => 'text', 'group' => 'landing_hero', 'label' => 'URL CTA secundario'],
    ];

    public function up(): void
    {
        foreach (self::SETTINGS as $setting) {
            SiteSetting::query()->firstOrCreate(['key' => $setting['key']], $setting);
        }
    }

    public function down(): void
    {
        SiteSetting::query()->whereIn('key', collect(self::SETTINGS)->pluck('key'))->delete();
    }
};
