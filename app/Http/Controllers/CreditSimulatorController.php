<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreditSimulatorController extends Controller
{
    public function __invoke(Request $request)
    {
        $levels = $this->creditLevels();
        $selectedType = $request->query('tipo_credito', array_key_first($levels));
        $amount = $request->filled('monto') ? (float) $request->query('monto') : null;
        $term = $request->filled('plazo') ? (int) $request->query('plazo') : null;
        $selectedLevel = $levels[$selectedType] ?? $levels[array_key_first($levels)];
        $errors = [];
        $result = null;

        if ($request->hasAny(['tipo_credito', 'monto', 'plazo'])) {
            if (! isset($levels[$selectedType])) {
                $errors[] = 'Seleccione un tipo de credito valido.';
            }

            if ($amount === null || $amount <= 0) {
                $errors[] = 'Ingrese un monto solicitado valido.';
            } elseif ($amount < $selectedLevel['min_amount']) {
                $errors[] = 'El monto esta por debajo del minimo permitido para este credito.';
            } elseif ($selectedLevel['max_amount'] !== null && $amount > $selectedLevel['max_amount']) {
                $errors[] = 'El monto supera el maximo permitido para este credito.';
            }

            if ($term === null || $term <= 0) {
                $errors[] = 'Ingrese un plazo en meses valido.';
            } elseif ($selectedLevel['max_term'] !== null && $term > $selectedLevel['max_term']) {
                $errors[] = 'El plazo supera el maximo permitido para este credito.';
            }

            if ($errors === []) {
                $monthlyRate = $selectedLevel['annual_rate'] / 12 / 100;
                $monthlyPayment = $amount * ($monthlyRate / (1 - pow(1 + $monthlyRate, -$term)));
                $totalPayment = $monthlyPayment * $term;
                $totalInterest = $totalPayment - $amount;

                $result = [
                    'type' => $selectedLevel['name'],
                    'level' => $selectedLevel['level'],
                    'affiliations' => $selectedLevel['affiliations'],
                    'affiliation_cost' => $selectedLevel['affiliation_cost'],
                    'amount' => $amount,
                    'annual_rate' => $selectedLevel['annual_rate'],
                    'term' => $term,
                    'monthly_payment' => $monthlyPayment,
                    'total_payment' => $totalPayment,
                    'total_interest' => $totalInterest,
                    'usage' => $selectedLevel['usage'],
                    'evaluation_note' => $selectedLevel['evaluation_note'],
                ];
            }
        }

        return view('public.simulador-creditos', [
            'levels' => $levels,
            'selectedType' => $selectedType,
            'amount' => $amount,
            'term' => $term,
            'errors' => $errors,
            'result' => $result,
            'requirements' => $this->requirements(),
            'whatsappInfoUrl' => $this->whatsappUrl('Hola, quiero más información sobre el simulador de créditos de la cooperativa.'),
            'whatsappEvaluationUrl' => $this->whatsappUrl('Hola, quiero solicitar una evaluación para acceder a un crédito.'),
        ]);
    }

    private function creditLevels(): array
    {
        return [
            'bajo' => [
                'level' => 1,
                'name' => 'Credito Bajo',
                'affiliations' => 1,
                'affiliation_cost' => 250,
                'min_amount' => 3000,
                'max_amount' => 5000,
                'annual_rate' => 12,
                'max_term' => 12,
                'usage' => 'Gastos menores, emergencias, consumo',
                'evaluation_note' => null,
            ],
            'general' => [
                'level' => 2,
                'name' => 'Credito General / Consumo',
                'affiliations' => 2,
                'affiliation_cost' => 500,
                'min_amount' => 8000,
                'max_amount' => 12000,
                'annual_rate' => 12,
                'max_term' => 24,
                'usage' => 'Bienes del hogar, compras generales',
                'evaluation_note' => null,
            ],
            'productivo' => [
                'level' => 3,
                'name' => 'Credito Productivo / Emprendimiento',
                'affiliations' => 3,
                'affiliation_cost' => 750,
                'min_amount' => 15000,
                'max_amount' => 25000,
                'annual_rate' => 11,
                'max_term' => 36,
                'usage' => 'Negocios, agricultura, comercio, taller',
                'evaluation_note' => null,
            ],
            'vehiculos' => [
                'level' => 4,
                'name' => 'Credito Vehiculos',
                'affiliations' => 4,
                'affiliation_cost' => 1250,
                'min_amount' => 70000,
                'max_amount' => 140000,
                'annual_rate' => 12,
                'max_term' => 72,
                'usage' => 'Compra de auto, moto, vehiculo de trabajo',
                'evaluation_note' => null,
            ],
            'vivienda' => [
                'level' => 5,
                'name' => 'Credito Vivienda',
                'affiliations' => 5,
                'affiliation_cost' => 2000,
                'min_amount' => 70000,
                'max_amount' => null,
                'annual_rate' => 6.5,
                'max_term' => null,
                'usage' => 'Construccion, mejora, compra de casa o terreno',
                'evaluation_note' => 'Sujeto a evaluacion individual.',
            ],
        ];
    }

    private function requirements(): array
    {
        return [
            'Ser afiliado activo de la cooperativa.',
            'Presentar documento de identidad vigente.',
            'Respaldar ingresos y capacidad de pago.',
            'Cumplir con el nivel de afiliaciones requerido.',
            'Aceptar la evaluacion crediticia individual.',
        ];
    }

    private function whatsappUrl(string $message): string
    {
        return 'https://wa.me/59162553853?text=' . rawurlencode($message);
    }
}
