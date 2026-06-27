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
                'key' => 'hero_title',
                'value' => 'Simulador de Créditos',
                'type' => 'text',
                'group' => 'textos',
                'label' => 'Título principal',
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
