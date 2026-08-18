<?php

namespace Tests\Feature;

use App\Models\CreditLevel;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_contains_the_institutional_heading_and_simulator_link(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<h1', false)
            ->assertSee('Soluciones financieras para construir tu futuro')
            ->assertSee('href="#simulador"', false);
    }

    public function test_home_exposes_complete_public_seo_metadata(): void
    {
        $response = $this->get('/')->assertOk();
        $html = $response->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $response
            ->assertSee('<title>Cooperativa Minera Tierra Bendita | Créditos y servicios para afiliados</title>', false)
            ->assertSee('<meta name="description" content="Cooperativa Minera Tierra Bendita ofrece información institucional, beneficios para afiliados y un simulador de créditos para orientar decisiones financieras.">', false)
            ->assertSee('<link rel="canonical" href="https://cooperativatierrabendita.com/">', false)
            ->assertSee('<meta name="robots" content="index, follow, max-image-preview:large">', false)
            ->assertSee('<meta property="og:title" content="Cooperativa Minera Tierra Bendita | Créditos y servicios para afiliados">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false)
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"FinancialService"', false);
    }

    public function test_credit_simulator_exposes_specific_seo_metadata(): void
    {
        $response = $this->get('/simulador-creditos')->assertOk();
        $html = $response->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        $response
            ->assertSee('<title>Simulador de créditos en Bolivia | Cooperativa Tierra Bendita</title>', false)
            ->assertSee('<meta name="description" content="Calcula cuotas referenciales para créditos, préstamos y financiamiento en Bolivia con el simulador de Cooperativa Minera Tierra Bendita.">', false)
            ->assertSee('<link rel="canonical" href="https://cooperativatierrabendita.com/simulador-creditos">', false)
            ->assertSee('Simulador de créditos y préstamos en Bolivia')
            ->assertSee('"@type":"WebApplication"', false)
            ->assertSee('"applicationCategory":"FinanceApplication"', false);
    }

    public function test_robots_and_sitemap_include_only_public_indexable_pages(): void
    {
        $robots = file_get_contents(public_path('robots.txt'));
        $sitemap = file_get_contents(public_path('sitemap.xml'));

        $this->assertStringContainsString('Disallow: /admin/', $robots);
        $this->assertStringContainsString('Disallow: /admin/login', $robots);
        $this->assertStringContainsString('Disallow: /admin/logout', $robots);
        $this->assertStringContainsString('Disallow: /storage/', $robots);
        $this->assertStringContainsString('Disallow: /api/', $robots);
        $this->assertStringContainsString('Disallow: /up', $robots);
        $this->assertStringContainsString('Sitemap: https://cooperativatierrabendita.com/sitemap.xml', $robots);

        $this->assertStringContainsString('<loc>https://cooperativatierrabendita.com/</loc>', $sitemap);
        $this->assertStringContainsString('<loc>https://cooperativatierrabendita.com/simulador-creditos</loc>', $sitemap);
        $this->assertStringNotContainsString('/admin', $sitemap);
        $this->assertStringNotContainsString('/login', $sitemap);
        $this->assertStringNotContainsString('/api/', $sitemap);
        $this->assertStringNotContainsString('/storage/', $sitemap);
    }

    public function test_landing_uses_the_official_logo_without_distorting_it(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('tierra-bendita-logo-oficial.png')
            ->assertSee('object-contain', false)
            ->assertDontSee('tierra-bendita-mark.svg');
    }

    public function test_navigation_contains_all_landing_anchors(): void
    {
        $response = $this->get('/')->assertOk();

        foreach (['#inicio', '#nosotros', '#creditos', '#beneficios', '#simulador', '#requisitos', '#faq', '#contacto'] as $anchor) {
            $response->assertSee('href="'.$anchor.'"', false);
        }
    }

    public function test_hero_uses_the_initial_image_when_setting_is_empty(): void
    {
        SiteSetting::query()->where('key', 'hero_image')->update(['value' => null]);

        $this->get('/')
            ->assertOk()
            ->assertSee('src="'.asset('images/tierra-bendita-hero.png').'"', false);
    }

    public function test_configured_images_expose_runtime_fallbacks(): void
    {
        SiteSetting::query()->where('key', 'hero_image')->update(['value' => 'site/no-existe.jpg']);
        SiteSetting::query()->where('key', 'site_logo')->update(['value' => 'site/no-existe.png']);

        $this->get('/')
            ->assertOk()
            ->assertSee('src="'.Storage::url('site/no-existe.jpg').'"', false)
            ->assertSee('data-image-fallback="'.asset('images/tierra-bendita-hero.png').'"', false)
            ->assertSee('data-image-fallback="'.asset('images/tierra-bendita-logo-oficial.png').'"', false);
    }

    public function test_public_landing_does_not_load_bootstrap(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('bootstrap', false)
            ->assertSee('/build/assets/', false);
    }

    public function test_legacy_simulator_url_continues_working(): void
    {
        $this->get('/simulador-creditos')
            ->assertOk()
            ->assertSee('id="simulador"', false);
    }

    public function test_active_credit_levels_appear_on_the_landing(): void
    {
        CreditLevel::create([
            'slug' => 'landing-productivo',
            'name' => 'Crédito Productivo Landing',
            'level' => 3,
            'affiliations' => 2,
            'affiliation_cost' => 500,
            'min_amount' => 5000,
            'max_amount' => 15000,
            'annual_rate' => 11,
            'available_terms' => [12, 24],
            'authorized_use' => 'Actividad productiva',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Crédito Productivo Landing')
            ->assertSee('Actividad productiva');
    }

    public function test_credit_products_render_as_a_comparison_table_with_compact_mobile_rows(): void
    {
        CreditLevel::create([
            'slug' => 'comparacion',
            'name' => 'Crédito de Comparación',
            'level' => 4,
            'affiliations' => 2,
            'affiliation_cost' => 500,
            'min_amount' => 15000,
            'max_amount' => 25000,
            'annual_rate' => 11,
            'available_terms' => [12, 24, 36],
            'authorized_use' => 'Destino productivo de comparación',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('data-credit-table', false)
            ->assertSee('<table', false)
            ->assertSee('scope="col"', false)
            ->assertSee('data-credit-mobile-list', false)
            ->assertSee('Crédito de Comparación')
            ->assertSee('15.000 a 25.000 Bs')
            ->assertSee('11% anual')
            ->assertSee('12, 24, 36 meses')
            ->assertSee('Destino productivo de comparación')
            ->assertSee('data-credit-select="comparacion"', false)
            ->assertSee('Simular');
    }

    public function test_affiliate_url_remains_available_on_the_landing(): void
    {
        SiteSetting::query()->where('key', SiteSetting::AFFILIATE_URL)->update([
            'value' => 'https://example.test/afiliate',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('https://example.test/afiliate');
    }

    public function test_admin_can_update_hero_text(): void
    {
        $this->ensureWhatsappSetting();

        $this->withSession(['admin_logged_in' => true])
            ->put('/admin/settings', [
                'settings' => [
                    'whatsapp_number' => '59170000000',
                    'hero_title' => 'Un futuro construido en comunidad',
                ],
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->assertDatabaseHas('site_settings', [
            'key' => 'hero_title',
            'value' => 'Un futuro construido en comunidad',
        ]);
    }

    public function test_admin_can_upload_a_logo(): void
    {
        Storage::fake('public');
        $this->ensureWhatsappSetting();

        $this->withSession(['admin_logged_in' => true])
            ->put('/admin/settings', [
                'settings' => ['whatsapp_number' => '59170000000'],
                'site_logo' => UploadedFile::fake()->image('logo.png', 500, 500),
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $path = SiteSetting::query()->where('key', 'site_logo')->value('value');
        $this->assertStringStartsWith('site/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_upload_a_hero_image(): void
    {
        Storage::fake('public');
        $this->ensureWhatsappSetting();

        $this->withSession(['admin_logged_in' => true])
            ->put('/admin/settings', [
                'settings' => ['whatsapp_number' => '59170000000'],
                'hero_image' => UploadedFile::fake()->image('hero.jpg', 1600, 900),
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $path = SiteSetting::query()->where('key', 'hero_image')->value('value');
        $this->assertStringStartsWith('site/', $path);
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_rejects_invalid_site_files(): void
    {
        Storage::fake('public');
        $this->ensureWhatsappSetting();

        $this->withSession(['admin_logged_in' => true])
            ->from('/admin/settings')
            ->put('/admin/settings', [
                'settings' => ['whatsapp_number' => '59170000000'],
                'site_logo' => UploadedFile::fake()->create('payload.php', 20, 'application/x-php'),
                'hero_image' => UploadedFile::fake()->create('banner.svg', 20, 'image/svg+xml'),
            ])
            ->assertRedirect('/admin/settings')
            ->assertSessionHasErrors(['site_logo', 'hero_image']);

        $this->assertSame([], Storage::disk('public')->allFiles('site'));
    }

    public function test_settings_page_remains_protected(): void
    {
        $this->get('/admin/settings')->assertRedirect('/admin/login');
    }

    public function test_valid_simulation_is_rendered_in_the_result_dialog(): void
    {
        $level = $this->createModalTestLevel();
        $warning = 'Este cálculo es referencial y no representa aprobación automática del crédito. La evaluación final será individual.';

        SiteSetting::query()->updateOrCreate(
            ['key' => 'general_warning'],
            ['value' => $warning, 'type' => 'textarea', 'group' => 'textos', 'label' => 'Advertencia'],
        );
        SiteSetting::query()->where('key', SiteSetting::AFFILIATE_URL)->update([
            'value' => 'https://example.test/afiliacion-modal',
        ]);

        $this->get('/simulador-creditos?tipo_prestamo='.$level->slug.'&monto=10000&plazo=12')
            ->assertOk()
            ->assertSee('data-result-modal', false)
            ->assertSee('role="dialog"', false)
            ->assertSee('aria-modal="true"', false)
            ->assertSee('Resultado referencial')
            ->assertSee('Cuota mensual aproximada')
            ->assertSee('10.000,00 Bs')
            ->assertSee('12,00%')
            ->assertSee('12 meses')
            ->assertSee($warning)
            ->assertSee('https://example.test/afiliacion-modal');
    }

    public function test_validation_errors_do_not_render_the_result_dialog(): void
    {
        $level = $this->createModalTestLevel();

        $this->get('/simulador-creditos?tipo_prestamo='.$level->slug.'&monto=10&plazo=12')
            ->assertOk()
            ->assertSee('menor al mínimo')
            ->assertDontSee('data-result-modal', false);
    }

    public function test_simulator_form_keeps_all_fields_and_submits_only_to_calculate(): void
    {
        $level = $this->createModalTestLevel();

        $this->get('/simulador-creditos?tipo_prestamo='.$level->slug.'&monto=5000')
            ->assertOk()
            ->assertSee('method="GET"', false)
            ->assertSee('action="'.route('simulador-creditos').'#simulador"', false)
            ->assertSee('name="tipo_prestamo"', false)
            ->assertSee('name="monto"', false)
            ->assertSee('name="plazo"', false)
            ->assertSee('type="submit"', false)
            ->assertSee('data-modal-return-focus', false)
            ->assertDontSee('onchange=', false);
    }

    private function ensureWhatsappSetting(): void
    {
        SiteSetting::query()->updateOrCreate(
            ['key' => 'whatsapp_number'],
            ['value' => '59170000000', 'type' => 'text', 'group' => 'contacto', 'label' => 'WhatsApp'],
        );
    }

    private function createModalTestLevel(): CreditLevel
    {
        return CreditLevel::create([
            'slug' => 'modal-controlado',
            'name' => 'Crédito Modal Controlado',
            'level' => 2,
            'affiliations' => 2,
            'affiliation_cost' => 500,
            'min_amount' => 1000,
            'max_amount' => 20000,
            'annual_rate' => 12,
            'available_terms' => [12],
            'authorized_use' => 'Prueba de modal',
            'is_housing' => false,
            'evaluation_required' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}
