<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPreparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_configured_credentials(): void
    {
        config()->set('admin.username', 'admin-test');
        config()->set('admin.password', 'secret-test');

        $this->post('/admin/login', [
            'username' => 'admin-test',
            'password' => 'secret-test',
        ])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('admin_logged_in', true);
    }

    public function test_admin_logout_invalidates_the_authenticated_session(): void
    {
        $this->withSession(['admin_logged_in' => true])
            ->post('/admin/logout')
            ->assertRedirect(route('admin.login'))
            ->assertSessionMissing('admin_logged_in');
    }

    public function test_admin_login_is_rate_limited(): void
    {
        config()->set('admin.username', 'admin-test');
        config()->set('admin.password', 'secret-test');

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/admin/login', [
                'username' => 'incorrecto',
                'password' => 'incorrecta',
            ])->assertRedirect();
        }

        $this->post('/admin/login', [
            'username' => 'incorrecto',
            'password' => 'incorrecta',
        ])->assertTooManyRequests();
    }

    public function test_credit_level_maximum_cannot_be_lower_than_minimum(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->from('/admin/credit-levels/create')
            ->post('/admin/credit-levels', $this->validCreditLevelData([
                'min_amount' => 5000,
                'max_amount' => 4999,
            ]));

        $response
            ->assertRedirect('/admin/credit-levels/create')
            ->assertSessionHasErrors('max_amount');
        $this->assertDatabaseCount('credit_levels', 0);
    }

    public function test_credit_level_terms_must_be_positive_comma_separated_months(): void
    {
        $response = $this->withSession(['admin_logged_in' => true])
            ->from('/admin/credit-levels/create')
            ->post('/admin/credit-levels', $this->validCreditLevelData([
                'available_terms' => '12, cero, -24',
            ]));

        $response
            ->assertRedirect('/admin/credit-levels/create')
            ->assertSessionHasErrors('available_terms');
        $this->assertDatabaseCount('credit_levels', 0);
    }

    private function validCreditLevelData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Crédito de prueba',
            'slug' => 'credito-de-prueba',
            'level' => 1,
            'affiliations' => 1,
            'affiliation_cost' => 250,
            'min_amount' => 1000,
            'max_amount' => 10000,
            'annual_rate' => 12,
            'available_terms' => '12,24',
            'authorized_use' => 'Prueba',
            'sort_order' => 1,
            'is_housing' => 0,
            'evaluation_required' => 0,
            'is_active' => 1,
        ], $overrides);
    }
}
