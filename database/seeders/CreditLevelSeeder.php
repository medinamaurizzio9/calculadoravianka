<?php

namespace Database\Seeders;

use App\Models\CreditLevel;
use Illuminate\Database\Seeder;

class CreditLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'slug' => 'bajo',
                'name' => 'Crédito Bajo',
                'level' => 1,
                'affiliations' => 1,
                'affiliation_cost' => 250,
                'min_amount' => 3000,
                'max_amount' => 5000,
                'annual_rate' => 12,
                'available_terms' => [6, 12],
                'authorized_use' => 'Gastos menores, emergencias, consumo',
                'is_housing' => false,
                'evaluation_required' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'consumo',
                'name' => 'Crédito General / Consumo',
                'level' => 2,
                'affiliations' => 2,
                'affiliation_cost' => 500,
                'min_amount' => 8000,
                'max_amount' => 12000,
                'annual_rate' => 12,
                'available_terms' => [12, 18, 24],
                'authorized_use' => 'Bienes del hogar, compras generales',
                'is_housing' => false,
                'evaluation_required' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'slug' => 'productivo',
                'name' => 'Crédito Productivo / Emprendimiento',
                'level' => 3,
                'affiliations' => 3,
                'affiliation_cost' => 750,
                'min_amount' => 15000,
                'max_amount' => 25000,
                'annual_rate' => 11,
                'available_terms' => [12, 24, 36],
                'authorized_use' => 'Negocios, agricultura, comercio, taller',
                'is_housing' => false,
                'evaluation_required' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'slug' => 'vehiculos',
                'name' => 'Crédito Vehículos',
                'level' => 4,
                'affiliations' => 4,
                'affiliation_cost' => 1250,
                'min_amount' => 70000,
                'max_amount' => 140000,
                'annual_rate' => 12,
                'available_terms' => [24, 36, 48, 60, 72],
                'authorized_use' => 'Compra de auto, moto, vehículo de trabajo',
                'is_housing' => false,
                'evaluation_required' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'slug' => 'vivienda',
                'name' => 'Crédito Vivienda',
                'level' => 5,
                'affiliations' => 5,
                'affiliation_cost' => 2000,
                'min_amount' => 70000,
                'max_amount' => null,
                'annual_rate' => 6.5,
                'available_terms' => [60, 84, 120],
                'authorized_use' => 'Construcción, mejora, compra de casa o terreno',
                'is_housing' => true,
                'evaluation_required' => true,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($levels as $level) {
            CreditLevel::updateOrCreate(
                ['slug' => $level['slug']],
                $level
            );
        }
    }
}
