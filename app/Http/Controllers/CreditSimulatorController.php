<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreditSimulatorController extends Controller
{
    public function __invoke(Request $request)
    {
        $levels = $this->creditLevels();
        $selectedType = $request->query('tipo_prestamo');
        $amount = $request->filled('monto') ? (float) $request->query('monto') : null;
        $term = $request->filled('plazo') ? (int) $request->query('plazo') : null;
        $selectedLevel = $selectedType && isset($levels[$selectedType]) ? $levels[$selectedType] : null;
        $errors = [];
        $showTerms = false;
        $result = null;

        if ($request->hasAny(['tipo_prestamo', 'monto', 'plazo'])) {
            if ($selectedLevel === null) {
                $errors[] = 'Seleccione un tipo de préstamo válido.';
            }

            if ($amount === null || $amount <= 0) {
                $errors[] = 'Ingrese un monto solicitado válido.';
            } elseif ($selectedLevel !== null && $amount < $selectedLevel['min_amount']) {
                $errors[] = 'El monto solicitado es menor al mínimo permitido. Rango permitido: ' . $this->rangeLabel($selectedLevel) . '.';
            } elseif (
                $selectedLevel !== null
                && $selectedLevel['max_amount'] !== null
                && $amount > $selectedLevel['max_amount']
            ) {
                $errors[] = 'El monto solicitado supera el máximo permitido. Rango permitido: ' . $this->rangeLabel($selectedLevel) . '.';
            }

            $showTerms = $errors === [] && $selectedLevel !== null && $amount !== null;

            if ($showTerms && $term !== null && ! in_array($term, $selectedLevel['available_terms'], true)) {
                $errors[] = 'Seleccione un plazo disponible para este tipo de préstamo.';
                $result = null;
            } elseif ($showTerms && $term !== null) {
                $result = $this->buildResult($selectedLevel, $amount, $term);
            }
        }

        return view('public.simulador-creditos', [
            'levels' => $levels,
            'selectedType' => $selectedType,
            'selectedLevel' => $selectedLevel,
            'amount' => $amount,
            'term' => $term,
            'showTerms' => $showTerms,
            'errors' => $errors,
            'result' => $result,
            'requirements' => $this->requirements(),
            'whatsappAffiliationUrl' => $this->whatsappUrl('Me interesa, vi su anuncio, quiero afiliarme. ¿Me envía requisitos?'),
        ]);
    }

    private function buildResult(array $level, float $amount, int $term): array
    {
        $monthlyRate = $level['annual_rate'] / 12 / 100;
        $monthlyPayment = $amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$term)));
        $totalPayment = $monthlyPayment * $term;
        $totalInterest = $totalPayment - $amount;

        return [
            'type' => $level['name'],
            'level' => $level['level'],
            'affiliations' => $level['affiliations'],
            'affiliation_cost' => $level['affiliation_cost'],
            'amount' => $amount,
            'range' => $this->rangeLabel($level),
            'annual_rate' => $level['annual_rate'],
            'term' => $term,
            'monthly_payment' => $monthlyPayment,
            'total_payment' => $totalPayment,
            'total_interest' => $totalInterest,
            'usage' => $level['usage'],
            'warning' => $level['is_housing']
                ? 'Este cálculo es referencial. El crédito de vivienda está sujeto a evaluación individual, capacidad de pago y garantías.'
                : 'Este cálculo es referencial y no representa aprobación automática del crédito. El monto debe estar dentro del rango permitido y la evaluación final será individual.',
        ];
    }

    private function rangeLabel(array $level): string
    {
        $minimum = number_format($level['min_amount'], 0, ',', '.');

        if ($level['max_amount'] === null) {
            return 'desde ' . $minimum . ' Bs';
        }

        return $minimum . ' a ' . number_format($level['max_amount'], 0, ',', '.') . ' Bs';
    }

    private function optionLabel(array $level): string
    {
        return $level['name'] . ' — ' . $this->rangeLabel($level);
    }

    private function creditLevels(): array
    {
        $levels = [
            'bajo' => [
                'level' => 1,
                'name' => 'Crédito Bajo',
                'affiliations' => 1,
                'affiliation_cost' => 250,
                'min_amount' => 3000,
                'max_amount' => 5000,
                'annual_rate' => 12,
                'available_terms' => [6, 12],
                'usage' => 'Gastos menores, emergencias, consumo',
                'is_housing' => false,
            ],
            'general' => [
                'level' => 2,
                'name' => 'Crédito General / Consumo',
                'affiliations' => 2,
                'affiliation_cost' => 500,
                'min_amount' => 8000,
                'max_amount' => 12000,
                'annual_rate' => 12,
                'available_terms' => [12, 18, 24],
                'usage' => 'Bienes del hogar, compras generales',
                'is_housing' => false,
            ],
            'productivo' => [
                'level' => 3,
                'name' => 'Crédito Productivo / Emprendimiento',
                'affiliations' => 3,
                'affiliation_cost' => 750,
                'min_amount' => 15000,
                'max_amount' => 25000,
                'annual_rate' => 11,
                'available_terms' => [12, 24, 36],
                'usage' => 'Negocios, agricultura, comercio, taller',
                'is_housing' => false,
            ],
            'vehiculos' => [
                'level' => 4,
                'name' => 'Crédito Vehículos',
                'affiliations' => 4,
                'affiliation_cost' => 1250,
                'min_amount' => 70000,
                'max_amount' => 140000,
                'annual_rate' => 12,
                'available_terms' => [24, 36, 48, 60, 72],
                'usage' => 'Compra de auto, moto, vehículo de trabajo',
                'is_housing' => false,
            ],
            'vivienda' => [
                'level' => 5,
                'name' => 'Crédito Vivienda',
                'affiliations' => 5,
                'affiliation_cost' => 2000,
                'min_amount' => 70000,
                'max_amount' => null,
                'annual_rate' => 6.5,
                'available_terms' => [60, 84, 120],
                'usage' => 'Construcción, mejora, compra de casa o terreno',
                'is_housing' => true,
            ],
        ];

        return array_map(function (array $level) {
            $level['range_label'] = $this->rangeLabel($level);
            $level['option_label'] = $this->optionLabel($level);
            $level['terms_label'] = implode(', ', $level['available_terms']) . ' meses';
            $level['terms_sentence'] = $this->termsSentence($level['available_terms']);

            return $level;
        }, $levels);
    }

    private function termsSentence(array $terms): string
    {
        if (count($terms) === 1) {
            return $terms[0] . ' meses';
        }

        $last = array_pop($terms);

        return implode(', ', $terms) . ' y ' . $last . ' meses';
    }

    private function requirements(): array
    {
        return [
            'Ser afiliado activo de la cooperativa.',
            'Presentar documento de identidad vigente.',
            'Respaldar ingresos y capacidad de pago.',
            'Cumplir con el nivel de afiliaciones requerido.',
            'Aceptar la evaluación crediticia individual.',
        ];
    }

    private function whatsappUrl(string $message): string
    {
        return 'https://wa.me/59162553853?text=' . rawurlencode($message);
    }
}
