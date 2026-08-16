<?php

namespace Tests\Feature;

use App\Models\CreditLevel;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreditSimulatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_simulator_returns_a_successful_response(): void
    {
        $this->get('/simulador-creditos')->assertOk();
    }

    public function test_simulator_uses_only_active_levels_in_sort_order(): void
    {
        $second = $this->createLevel(['slug' => 'segundo', 'sort_order' => 20]);
        $first = $this->createLevel(['slug' => 'primero', 'sort_order' => 10]);
        $this->createLevel(['slug' => 'inactivo', 'sort_order' => 1, 'is_active' => false]);

        $this->get('/simulador-creditos')
            ->assertOk()
            ->assertViewHas('levels', function (array $levels) use ($first, $second): bool {
                return array_keys($levels) === [$first->slug, $second->slug];
            });
    }

    public function test_simulation_preserves_the_current_financial_formula(): void
    {
        $level = $this->createLevel([
            'slug' => 'controlado',
            'annual_rate' => 12,
            'available_terms' => [12],
        ]);

        $amount = 10000.0;
        $monthlyRate = 0.12 / 12;
        $expectedMonthlyPayment = $amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -12)));
        $expectedTotalPayment = $expectedMonthlyPayment * 12;

        $this->get('/simulador-creditos?tipo_prestamo='.$level->slug.'&monto=10000&plazo=12')
            ->assertOk()
            ->assertViewHas('result', function (?array $result) use ($amount, $expectedMonthlyPayment, $expectedTotalPayment): bool {
                return $result !== null
                    && abs($result['amount'] - $amount) < 0.0001
                    && abs($result['annual_rate'] - 12.0) < 0.0001
                    && $result['term'] === 12
                    && abs($result['monthly_payment'] - $expectedMonthlyPayment) < 0.01
                    && abs($result['total_payment'] - $expectedTotalPayment) < 0.01
                    && abs($result['total_interest'] - ($expectedTotalPayment - $amount)) < 0.01;
            });
    }

    public function test_amount_below_minimum_is_rejected(): void
    {
        $level = $this->createLevel();

        $this->get('/simulador-creditos?tipo_prestamo='.$level->slug.'&monto=999')
            ->assertViewHas('errors', fn (array $errors): bool => str_contains($errors[0] ?? '', 'menor al mínimo'))
            ->assertViewHas('result', null);
    }

    public function test_amount_above_maximum_is_rejected(): void
    {
        $level = $this->createLevel();

        $this->get('/simulador-creditos?tipo_prestamo='.$level->slug.'&monto=20001')
            ->assertViewHas('errors', fn (array $errors): bool => str_contains($errors[0] ?? '', 'supera el máximo'))
            ->assertViewHas('result', null);
    }

    public function test_invalid_credit_type_is_rejected(): void
    {
        $this->get('/simulador-creditos?tipo_prestamo=no-existe&monto=10000')
            ->assertViewHas('errors', fn (array $errors): bool => in_array('Seleccione un tipo de préstamo válido.', $errors, true))
            ->assertViewHas('result', null);
    }

    public function test_unavailable_term_is_rejected(): void
    {
        $level = $this->createLevel(['available_terms' => [12]]);

        $this->get('/simulador-creditos?tipo_prestamo='.$level->slug.'&monto=10000&plazo=24')
            ->assertViewHas('errors', fn (array $errors): bool => in_array('Seleccione un plazo disponible para este tipo de préstamo.', $errors, true))
            ->assertViewHas('result', null);
    }

    public function test_affiliate_url_is_optional(): void
    {
        $this->get('/simulador-creditos')
            ->assertOk()
            ->assertViewHas('affiliateUrl', null);
    }

    public function test_affiliate_url_reaches_the_view_when_configured(): void
    {
        SiteSetting::query()
            ->where('key', SiteSetting::AFFILIATE_URL)
            ->update(['value' => 'https://example.test/afiliacion']);

        $this->get('/simulador-creditos')
            ->assertOk()
            ->assertViewHas('affiliateUrl', 'https://example.test/afiliacion');
    }

    public function test_site_settings_pluck_contract_is_preserved(): void
    {
        SiteSetting::create(['key' => 'hero_title', 'value' => 'Título de prueba']);
        SiteSetting::create(['key' => 'general_warning', 'value' => 'Advertencia de prueba']);

        $settings = SiteSetting::pluck('value', 'key')->all();

        $this->assertSame('Título de prueba', $settings['hero_title']);
        $this->assertSame('Advertencia de prueba', $settings['general_warning']);
    }

    public function test_admin_dashboard_redirects_guests_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
    }

    private function createLevel(array $attributes = []): CreditLevel
    {
        static $sequence = 0;
        $sequence++;

        return CreditLevel::create(array_merge([
            'slug' => 'nivel-'.$sequence,
            'name' => 'Nivel de prueba '.$sequence,
            'level' => $sequence,
            'affiliations' => 1,
            'affiliation_cost' => 250,
            'min_amount' => 1000,
            'max_amount' => 20000,
            'annual_rate' => 12,
            'available_terms' => [12, 24],
            'authorized_use' => 'Prueba',
            'is_housing' => false,
            'evaluation_required' => false,
            'is_active' => true,
            'sort_order' => $sequence,
        ], $attributes));
    }
}
