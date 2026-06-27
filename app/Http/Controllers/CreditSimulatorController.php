<?php

namespace App\Http\Controllers;

use App\Models\CreditLevel;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class CreditSimulatorController extends Controller
{
    public function __invoke(Request $request)
    {
        $levels = $this->creditLevels();
        $settings = $this->siteSettings();
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
            'requirements' => $this->requirements($settings),
            'settings' => $settings,
            'whatsappAffiliationUrl' => $this->whatsappUrl(
                $settings['whatsapp_number'] ?? '59162553853',
                $settings['whatsapp_affiliation_message'] ?? 'Me interesa, vi su anuncio, quiero afiliarme. ¿Me envía requisitos?'
            ),
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
            'is_housing' => $level['is_housing'],
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
        return CreditLevel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->mapWithKeys(function (CreditLevel $creditLevel) {
                $level = [
                    'level' => $creditLevel->level,
                    'name' => $creditLevel->name,
                    'affiliations' => $creditLevel->affiliations,
                    'affiliation_cost' => (float) $creditLevel->affiliation_cost,
                    'min_amount' => (float) $creditLevel->min_amount,
                    'max_amount' => $creditLevel->max_amount !== null ? (float) $creditLevel->max_amount : null,
                    'annual_rate' => (float) $creditLevel->annual_rate,
                    'available_terms' => $creditLevel->available_terms ?? [],
                    'usage' => $creditLevel->authorized_use,
                    'is_housing' => $creditLevel->is_housing,
                    'evaluation_required' => $creditLevel->evaluation_required,
                ];

                return [$creditLevel->slug => $level];
            })
            ->map(function (array $level) {
            $level['range_label'] = $this->rangeLabel($level);
            $level['option_label'] = $this->optionLabel($level);
            $level['terms_label'] = implode(', ', $level['available_terms']) . ' meses';
            $level['terms_sentence'] = $this->termsSentence($level['available_terms']);

            return $level;
            })
            ->all();
    }

    private function termsSentence(array $terms): string
    {
        if (count($terms) === 1) {
            return $terms[0] . ' meses';
        }

        $last = array_pop($terms);

        return implode(', ', $terms) . ' y ' . $last . ' meses';
    }

    private function siteSettings(): array
    {
        return SiteSetting::query()
            ->pluck('value', 'key')
            ->all();
    }

    private function requirements(array $settings): array
    {
        return preg_split('/\r\n|\r|\n/', $settings['general_requirements'] ?? '', -1, PREG_SPLIT_NO_EMPTY);
    }

    private function whatsappUrl(string $number, string $message): string
    {
        return 'https://wa.me/' . preg_replace('/\D+/', '', $number) . '?text=' . rawurlencode($message);
    }
}
