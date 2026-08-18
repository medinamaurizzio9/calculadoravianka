<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Cooperativa Minera Tierra Bendita',
                'type' => 'text',
                'group' => 'identidad_publica',
                'label' => 'Nombre institucional',
            ],
            [
                'key' => 'site_logo',
                'value' => 'images/tierra-bendita-logo-oficial.png',
                'type' => 'image',
                'group' => 'identidad_publica',
                'label' => 'Logo institucional',
            ],
            [
                'key' => 'whatsapp_number',
                'value' => '59164211914',
                'type' => 'text',
                'group' => 'contacto',
                'label' => 'Número de WhatsApp',
            ],
            [
                'key' => 'whatsapp_affiliation_message',
                'value' => 'Me interesa, vi su anuncio, quiero afiliarme. ¿Me envía requisitos?',
                'type' => 'textarea',
                'group' => 'contacto',
                'label' => 'Mensaje para solicitar afiliación',
            ],
            [
                'key' => SiteSetting::AFFILIATE_URL,
                'value' => null,
                'type' => 'url',
                'group' => 'enlace_afiliacion',
                'label' => 'URL de afiliación',
            ],
            [
                'key' => 'hero_title',
                'value' => 'Soluciones financieras para construir tu futuro',
                'type' => 'text',
                'group' => 'landing_hero',
                'label' => 'Título principal',
            ],
            [
                'key' => 'hero_image',
                'value' => 'images/tierra-bendita-hero.png',
                'type' => 'image',
                'group' => 'landing_hero',
                'label' => 'Imagen del banner',
            ],
            [
                'key' => 'hero_eyebrow',
                'value' => 'Cooperativa Minera Tierra Bendita',
                'type' => 'text',
                'group' => 'landing_hero',
                'label' => 'Texto introductorio',
            ],
            [
                'key' => 'hero_description',
                'value' => 'Accede a alternativas de financiamiento diseñadas para nuestros afiliados, con información clara, atención personalizada y herramientas digitales para tomar mejores decisiones.',
                'type' => 'textarea',
                'group' => 'landing_hero',
                'label' => 'Descripción',
            ],
            [
                'key' => 'hero_primary_text',
                'value' => 'Simular mi crédito',
                'type' => 'text',
                'group' => 'landing_hero',
                'label' => 'Texto CTA principal',
            ],
            [
                'key' => 'hero_primary_url',
                'value' => '#simulador',
                'type' => 'text',
                'group' => 'landing_hero',
                'label' => 'URL CTA principal',
            ],
            [
                'key' => 'hero_secondary_text',
                'value' => 'Conocer beneficios',
                'type' => 'text',
                'group' => 'landing_hero',
                'label' => 'Texto CTA secundario',
            ],
            [
                'key' => 'hero_secondary_url',
                'value' => '#beneficios',
                'type' => 'text',
                'group' => 'landing_hero',
                'label' => 'URL CTA secundario',
            ],
            [
                'key' => 'hero_subtitle',
                'value' => 'Calcula tu cuota aproximada antes de solicitar tu afiliación',
                'type' => 'text',
                'group' => 'textos',
                'label' => 'Subtítulo principal',
            ],
            [
                'key' => 'form_intro',
                'value' => 'Selecciona el tipo de préstamo, ingresa el monto y elige un plazo disponible.',
                'type' => 'textarea',
                'group' => 'textos',
                'label' => 'Texto introductorio del formulario',
            ],
            [
                'key' => 'general_warning',
                'value' => 'Este cálculo es referencial y no representa aprobación automática del crédito. La evaluación final será individual.',
                'type' => 'textarea',
                'group' => 'textos',
                'label' => 'Advertencia general',
            ],
            [
                'key' => 'housing_warning',
                'value' => 'Este cálculo es referencial. El crédito de vivienda está sujeto a evaluación individual, capacidad de pago y garantías.',
                'type' => 'textarea',
                'group' => 'textos',
                'label' => 'Advertencia vivienda',
            ],
            [
                'key' => 'general_requirements',
                'value' => "Mayor de 18 años\nCédula de identidad vigente\nRecibo de servicio básico\nGarantía o referencias según monto\nSin deudas vencidas",
                'type' => 'textarea',
                'group' => 'requisitos',
                'label' => 'Requisitos generales',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
