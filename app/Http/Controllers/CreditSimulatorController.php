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
        $isSimulatorPage = $request->routeIs('simulador-creditos');
        $seo = $this->seoMetadata($isSimulatorPage, $settings);
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

        return view('public.landing', [
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
            'seo' => $seo,
            'structuredData' => $this->structuredData($seo, $settings, $levels, $isSimulatorPage),
            'affiliateUrl' => filled($settings[SiteSetting::AFFILIATE_URL] ?? null)
                ? $settings[SiteSetting::AFFILIATE_URL]
                : null,
            'focusSimulator' => $isSimulatorPage,
        ]);
    }

    private function seoMetadata(bool $isSimulatorPage, array $settings): array
    {
        $baseUrl = 'https://cooperativatierrabendita.com';
        $siteName = $settings['site_name'] ?? 'Cooperativa Minera Tierra Bendita';

        if ($isSimulatorPage) {
            return [
                'title' => 'Simulador de créditos en Bolivia | Cooperativa Tierra Bendita',
                'description' => 'Calcula cuotas referenciales para créditos, préstamos y financiamiento en Bolivia con el simulador de Cooperativa Minera Tierra Bendita.',
                'canonical' => $baseUrl.'/simulador-creditos',
                'robots' => 'index, follow, max-image-preview:large',
                'og_type' => 'website',
                'og_image' => $baseUrl.'/images/tierra-bendita-hero.png',
                'h1' => 'Simulador de créditos y préstamos en Bolivia',
                'site_name' => $siteName,
            ];
        }

        return [
            'title' => 'Cooperativa Minera Tierra Bendita | Créditos y servicios para afiliados',
            'description' => 'Cooperativa Minera Tierra Bendita ofrece información institucional, beneficios para afiliados y un simulador de créditos para orientar decisiones financieras.',
            'canonical' => $baseUrl.'/',
            'robots' => 'index, follow, max-image-preview:large',
            'og_type' => 'website',
            'og_image' => $baseUrl.'/images/tierra-bendita-hero.png',
            'h1' => $settings['hero_title'] ?? 'Soluciones financieras para construir tu futuro',
            'site_name' => $siteName,
        ];
    }

    private function structuredData(array $seo, array $settings, array $levels, bool $isSimulatorPage): array
    {
        $baseUrl = 'https://cooperativatierrabendita.com';
        $siteName = $seo['site_name'];
        $organizationId = $baseUrl.'/#organization';

        $graph = [
            [
                '@type' => 'Organization',
                '@id' => $organizationId,
                'name' => $siteName,
                'url' => $baseUrl.'/',
                'logo' => $baseUrl.'/images/tierra-bendita-logo-oficial.png',
                'description' => 'Cooperativa Minera Tierra Bendita brinda información, beneficios y orientación financiera para sus afiliados.',
            ],
            [
                '@type' => 'WebSite',
                '@id' => $baseUrl.'/#website',
                'url' => $baseUrl.'/',
                'name' => $siteName,
                'publisher' => ['@id' => $organizationId],
                'inLanguage' => 'es-BO',
            ],
            [
                '@type' => 'WebPage',
                '@id' => $seo['canonical'].'#webpage',
                'url' => $seo['canonical'],
                'name' => $seo['title'],
                'description' => $seo['description'],
                'isPartOf' => ['@id' => $baseUrl.'/#website'],
                'about' => ['@id' => $organizationId],
                'inLanguage' => 'es-BO',
            ],
        ];

        if ($isSimulatorPage) {
            $graph[] = [
                '@type' => 'WebApplication',
                '@id' => $baseUrl.'/simulador-creditos#simulator',
                'name' => 'Simulador de créditos Cooperativa Tierra Bendita',
                'url' => $baseUrl.'/simulador-creditos',
                'applicationCategory' => 'FinanceApplication',
                'operatingSystem' => 'Web',
                'description' => 'Herramienta informativa para estimar cuotas referenciales de créditos y préstamos en Bolivia.',
                'provider' => ['@id' => $organizationId],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => '0',
                    'priceCurrency' => 'BOB',
                ],
            ];
        } else {
            $graph[] = [
                '@type' => 'FinancialService',
                '@id' => $baseUrl.'/#financial-service',
                'name' => $siteName,
                'url' => $baseUrl.'/',
                'description' => 'Servicios de orientación financiera, beneficios y alternativas de crédito para afiliados.',
                'areaServed' => [
                    '@type' => 'Country',
                    'name' => 'Bolivia',
                ],
                'provider' => ['@id' => $organizationId],
                'hasOfferCatalog' => [
                    '@type' => 'OfferCatalog',
                    'name' => 'Opciones de financiamiento',
                    'itemListElement' => collect($levels)->map(fn (array $level) => [
                        '@type' => 'Offer',
                        'name' => $level['name'],
                        'description' => $level['usage'],
                    ])->values()->all(),
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
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

}
