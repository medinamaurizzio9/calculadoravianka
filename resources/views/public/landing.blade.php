<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cooperativa Minera Tierra Bendita | Créditos y Servicios para Afiliados</title>
    <meta name="description" content="Conoce los servicios, alternativas de financiamiento y simulador de créditos de Cooperativa Minera Tierra Bendita.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
@php
    $siteName = $settings['site_name'] ?? 'Cooperativa Minera Tierra Bendita';
    $assetUrl = function (?string $path, string $fallback): string {
        $path = filled($path) ? $path : $fallback;
        return str_starts_with($path, 'site/') ? \Illuminate\Support\Facades\Storage::url($path) : asset($path);
    };
    $defaultLogoUrl = asset('images/tierra-bendita-logo-oficial.png');
    $defaultHeroUrl = asset('images/tierra-bendita-hero.png');
    $logoUrl = $assetUrl($settings['site_logo'] ?? null, 'images/tierra-bendita-logo-oficial.png');
    $heroImageUrl = $assetUrl($settings['hero_image'] ?? null, 'images/tierra-bendita-hero.png');
    $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp_number'] ?? '');
    $whatsappMessage = $settings['whatsapp_affiliation_message'] ?? 'Quiero solicitar información.';
    $whatsappUrl = $whatsappNumber ? 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($whatsappMessage) : null;
    $navItems = [
        '#inicio' => 'Inicio', '#nosotros' => 'Nosotros', '#creditos' => 'Créditos',
        '#beneficios' => 'Beneficios', '#simulador' => 'Simulador', '#requisitos' => 'Requisitos',
        '#faq' => 'Preguntas frecuentes', '#contacto' => 'Contacto',
    ];
@endphp
<body class="bg-[#F5F7FA] font-sans text-[#172033] antialiased">
    <a href="#contenido" class="sr-only z-[100] rounded bg-white px-4 py-3 text-[#061A33] focus:not-sr-only focus:fixed focus:left-4 focus:top-4">Saltar al contenido</a>

    <header class="sticky top-0 z-50 border-b border-white/10 bg-[#061A33]/95 text-white shadow-lg backdrop-blur" data-site-header>
        <nav class="mx-auto flex max-w-[1440px] items-center justify-between gap-5 px-4 py-3 sm:px-6 lg:px-8" aria-label="Navegación principal">
            <a href="#inicio" class="flex min-w-0 items-center gap-3 rounded focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#F0C75E]">
                <img src="{{ $logoUrl }}" data-image-fallback="{{ $defaultLogoUrl }}" alt="Logo oficial de {{ $siteName }}" class="h-12 w-12 shrink-0 rounded-full object-contain sm:h-14 sm:w-14">
                <span class="min-w-0 text-[11px] font-semibold uppercase leading-tight tracking-[0.12em] sm:text-sm">
                    <span class="block text-white/75">Cooperativa Minera</span>
                    <span class="block truncate text-white">Tierra Bendita</span>
                </span>
            </a>

            <button type="button" class="rounded-lg border border-white/25 p-2.5 text-white transition hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F0C75E] xl:hidden" aria-expanded="false" aria-controls="mobile-menu" data-menu-button>
                <span class="sr-only">Abrir menú</span>
                <svg class="h-6 w-6" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round"/></svg>
            </button>

            <div class="hidden items-center gap-5 xl:flex">
                <div class="flex items-center gap-4 text-[13px] font-medium">
                    @foreach ($navItems as $url => $label)
                        <a href="{{ $url }}" class="rounded py-2 text-white/78 transition hover:text-[#F0C75E] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F0C75E]">{{ $label }}</a>
                    @endforeach
                </div>
                @if ($affiliateUrl)
                    <a href="{{ $affiliateUrl }}" target="_blank" rel="noopener noreferrer" class="rounded-lg bg-[#D4A72C] px-5 py-2.5 text-sm font-bold text-[#061A33] shadow-sm transition hover:bg-[#F0C75E] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">Afíliate</a>
                @else
                    <button type="button" disabled title="Configure el enlace desde Administración" class="cursor-not-allowed rounded-lg bg-[#D4A72C]/55 px-5 py-2.5 text-sm font-bold text-[#061A33]/70">Afíliate</button>
                @endif
                <a href="{{ route('admin.login') }}" class="text-xs text-white/55 transition hover:text-white">Administración</a>
            </div>
        </nav>

        <div id="mobile-menu" class="hidden border-t border-white/10 bg-[#061A33] px-4 pb-5 pt-3 xl:hidden" data-mobile-menu>
            <div class="mx-auto grid max-w-[1440px] gap-1">
                @foreach ($navItems as $url => $label)
                    <a href="{{ $url }}" class="rounded-lg px-3 py-2.5 text-sm text-white/85 hover:bg-white/10">{{ $label }}</a>
                @endforeach
                <div class="mt-3 flex flex-wrap items-center gap-3 border-t border-white/10 pt-4">
                    @if ($affiliateUrl)<a href="{{ $affiliateUrl }}" target="_blank" rel="noopener noreferrer" class="rounded-lg bg-[#D4A72C] px-5 py-2.5 text-sm font-bold text-[#061A33]">Afíliate</a>@else<button type="button" disabled class="cursor-not-allowed rounded-lg bg-[#D4A72C]/55 px-5 py-2.5 text-sm font-bold text-[#061A33]/70">Afíliate</button>@endif
                    <a href="{{ route('admin.login') }}" class="px-2 py-2 text-sm text-white/65">Administración</a>
                </div>
            </div>
        </div>
    </header>

    <main id="contenido">
        <section id="inicio" class="relative isolate flex min-h-[560px] scroll-mt-20 items-center overflow-hidden bg-[#061A33] text-white">
            <img src="{{ $heroImageUrl }}" data-image-fallback="{{ $defaultHeroUrl }}" alt="Afiliados de Tierra Bendita mirando hacia un futuro de desarrollo" class="absolute inset-0 -z-20 h-full w-full object-cover object-center lg:object-[62%_center]">
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#061A33] via-[#061A33]/90 to-[#061A33]/15"></div>
            <div class="mx-auto w-full max-w-[1440px] px-4 py-20 sm:px-6 lg:px-8 lg:py-28">
                <div class="max-w-2xl">
                    <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-[#F0C75E]/30 bg-[#061A33]/55 px-4 py-2 text-xs font-bold uppercase tracking-[0.16em] text-[#F0C75E] backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-[#D4A72C]"></span>{{ $settings['hero_eyebrow'] ?? 'Cooperativa Minera Tierra Bendita' }}
                    </div>
                    <h1 class="max-w-2xl text-4xl font-bold leading-[1.08] tracking-tight sm:text-5xl lg:text-6xl">{{ $settings['hero_title'] ?? 'Soluciones financieras para construir tu futuro' }}</h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-white/82 sm:text-lg">{{ $settings['hero_description'] ?? 'Accede a alternativas de financiamiento diseñadas para nuestros afiliados, con información clara, atención personalizada y herramientas digitales para tomar mejores decisiones.' }}</p>
                    <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ $settings['hero_primary_url'] ?? '#simulador' }}" class="inline-flex min-h-12 items-center justify-center rounded-lg bg-[#D4A72C] px-6 py-3 font-bold text-[#061A33] shadow-xl transition hover:-translate-y-0.5 hover:bg-[#F0C75E] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-white">{{ $settings['hero_primary_text'] ?? 'Simular mi crédito' }}</a>
                        <a href="{{ $settings['hero_secondary_url'] ?? '#beneficios' }}" class="inline-flex min-h-12 items-center justify-center rounded-lg border border-white/40 bg-white/8 px-6 py-3 font-bold text-white backdrop-blur transition hover:bg-white/15 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#F0C75E]">{{ $settings['hero_secondary_text'] ?? 'Conocer beneficios' }}</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="nosotros" class="scroll-mt-24 bg-white py-20 lg:py-28">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
                    <div>
                        <p class="section-eyebrow">Nuestra institución</p>
                        <h2 class="section-title">Una institución comprometida con el desarrollo de sus afiliados</h2>
                        <p class="mt-6 text-lg leading-8 text-slate-600">Trabajamos para acercar información clara, orientación responsable y alternativas de financiamiento a nuestros afiliados. Nuestra labor se basa en relaciones de confianza y en decisiones que acompañan el crecimiento de las familias y sus actividades productivas.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            ['Confianza', 'Relaciones cercanas construidas con responsabilidad y respeto.'],
                            ['Desarrollo', 'Herramientas que acompañan proyectos personales y productivos.'],
                            ['Transparencia', 'Información comprensible para tomar decisiones con mayor claridad.'],
                            ['Atención personalizada', 'Orientación humana según las necesidades de cada afiliado.'],
                        ] as [$title, $copy])
                            <article class="group rounded-2xl border border-slate-200 bg-[#F5F7FA] p-6 transition hover:-translate-y-1 hover:border-[#D4A72C]/50 hover:shadow-xl">
                                <span class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-[#0B2D55] text-[#F0C75E]" aria-hidden="true">✦</span>
                                <h3 class="text-lg font-bold text-[#061A33]">{{ $title }}</h3>
                                <p class="mt-2 leading-7 text-slate-600">{{ $copy }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="creditos" class="scroll-mt-24 bg-[#F5F7FA] py-20 lg:py-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="h-1 bg-gradient-to-r from-[#0B2D55] via-[#D4A72C] to-[#0B2D55]"></div>
                    <div class="border-b border-slate-200 px-5 py-6 sm:px-7 lg:px-8">
                        <p class="section-eyebrow">Alternativas de financiamiento</p>
                        <h2 class="text-2xl font-bold tracking-tight text-[#061A33] sm:text-3xl">Opciones de financiamiento</h2>
                        <p class="mt-2 leading-7 text-slate-600">Compara montos, tasas y plazos disponibles según el tipo de crédito.</p>
                    </div>

                    <div class="hidden lg:block">
                        <table class="w-full table-fixed text-left" data-credit-table>
                            <caption class="sr-only">Comparación de productos de crédito disponibles</caption>
                            <thead class="bg-[#061A33] text-[11px] font-bold uppercase tracking-[0.1em] text-white">
                                <tr>
                                    <th scope="col" class="w-[8%] px-4 py-3.5">Nivel</th>
                                    <th scope="col" class="w-[17%] px-4 py-3.5">Tipo de crédito</th>
                                    <th scope="col" class="w-[14%] px-4 py-3.5">Rango</th>
                                    <th scope="col" class="w-[10%] px-4 py-3.5">Tasa anual</th>
                                    <th scope="col" class="w-[13%] px-4 py-3.5">Plazos</th>
                                    <th scope="col" class="w-[28%] px-4 py-3.5">Destino / uso</th>
                                    <th scope="col" class="w-[10%] px-4 py-3.5 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($levels as $slug => $level)
                                    <tr class="bg-white transition-colors hover:bg-[#F5F7FA]">
                                        <td class="px-4 py-4 align-top"><span class="inline-flex rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold whitespace-nowrap text-[#174A7E]">Nivel {{ $level['level'] }}</span></td>
                                        <td class="px-4 py-4 align-top text-sm font-bold leading-5 text-[#061A33]">{{ $level['name'] }}</td>
                                        <td class="px-4 py-4 align-top text-sm font-semibold leading-5 text-[#172033]">{{ $level['range_label'] }}</td>
                                        <td class="px-4 py-4 align-top"><span class="inline-flex rounded-full bg-[#D4A72C]/15 px-2.5 py-1 text-xs font-bold whitespace-nowrap text-[#73570A]">{{ rtrim(rtrim(number_format($level['annual_rate'], 2, '.', ''), '0'), '.') }}% anual</span></td>
                                        <td class="px-4 py-4 align-top text-sm leading-5 text-slate-600">{{ $level['terms_label'] }}</td>
                                        <td class="px-4 py-4 align-top text-sm leading-5 text-slate-600">{{ $level['usage'] }}</td>
                                        <td class="px-4 py-4 align-top text-center"><a href="#simulador" data-credit-select="{{ $slug }}" class="inline-flex min-h-9 items-center justify-center rounded-lg bg-[#0B2D55] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#D4A72C] hover:text-[#061A33] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#D4A72C]">Simular</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-6 py-10 text-center text-slate-600">No hay productos de crédito activos disponibles en este momento.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="grid gap-3 bg-[#F5F7FA] p-3 sm:p-5 lg:hidden" data-credit-mobile-list>
                        @forelse ($levels as $slug => $level)
                            <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-[0_1px_2px_rgba(6,26,51,0.04)]">
                                <div class="flex items-start justify-between gap-3">
                                    <div><span class="text-xs font-bold uppercase tracking-[0.12em] text-[#174A7E]">Nivel {{ $level['level'] }}</span><h3 class="mt-1 text-base font-bold leading-5 text-[#061A33] sm:text-lg">{{ $level['name'] }}</h3></div>
                                    <span class="shrink-0 rounded-full bg-[#D4A72C]/15 px-2.5 py-1 text-xs font-bold text-[#73570A]">{{ rtrim(rtrim(number_format($level['annual_rate'], 2, '.', ''), '0'), '.') }}% anual</span>
                                </div>
                                <dl class="mt-4 grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                    <div><dt class="text-xs text-slate-500">Rango</dt><dd class="mt-1 font-semibold text-[#172033]">{{ $level['range_label'] }}</dd></div>
                                    <div><dt class="text-xs text-slate-500">Plazos</dt><dd class="mt-1 font-semibold text-[#172033]">{{ $level['terms_label'] }}</dd></div>
                                    <div class="col-span-2 border-t border-slate-100 pt-3"><dt class="text-xs text-slate-500">Destino / uso</dt><dd class="mt-1 leading-5 text-slate-600">{{ $level['usage'] }}</dd></div>
                                </dl>
                                <a href="#simulador" data-credit-select="{{ $slug }}" class="mt-4 inline-flex min-h-10 w-full items-center justify-center rounded-lg bg-[#0B2D55] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#174A7E] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#D4A72C]">Simular</a>
                            </article>
                        @empty
                            <div class="rounded-xl border border-slate-200 bg-white p-6 text-center text-slate-600">No hay productos de crédito activos disponibles en este momento.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section id="beneficios" class="scroll-mt-24 bg-[#061A33] py-20 text-white lg:py-28">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl"><p class="section-eyebrow text-[#F0C75E]">Beneficios para afiliados</p><h2 class="section-title text-white">Información y acompañamiento para decidir con confianza</h2></div>
                <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach (['Condiciones claras', 'Plazos flexibles', 'Atención personalizada', 'Simulación inmediata', 'Beneficios para afiliados', 'Proceso transparente'] as $benefit)
                        <article class="rounded-2xl border border-white/12 bg-white/[0.06] p-6 backdrop-blur transition hover:border-[#D4A72C]/50 hover:bg-white/[0.09]"><span class="mb-4 block text-2xl text-[#F0C75E]" aria-hidden="true">✓</span><h3 class="text-lg font-bold">{{ $benefit }}</h3><p class="mt-2 leading-7 text-white/65">Accede a orientación comprensible y a herramientas pensadas para acompañar tu proceso como afiliado.</p></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="simulador" class="scroll-mt-24 bg-[#EEF2F7] py-20 lg:py-28" data-focus-simulator="{{ $focusSimulator ? 'true' : 'false' }}">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div class="text-center"><p class="section-eyebrow">Herramienta informativa</p><h2 class="section-title">Simula tu crédito</h2><p class="section-copy mx-auto">{{ $settings['form_intro'] ?? 'Selecciona el tipo de préstamo, ingresa el monto y elige un plazo disponible.' }}</p></div>
                <div class="mt-10 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl shadow-[#061A33]/10">
                    <div class="h-2 bg-gradient-to-r from-[#0B2D55] via-[#174A7E] to-[#D4A72C]"></div>
                    <div class="p-5 sm:p-8 lg:p-10">
                        @if ($errors !== [])
                            <div class="mb-6 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-950" role="alert">@foreach ($errors as $error)<p>{{ $error }}</p>@endforeach</div>
                        @endif
                        @if ($showTerms && ! $result && $errors === [])
                            <div class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-[#0B2D55]" role="status">Monto válido para {{ $selectedLevel['name'] }}. Selecciona un plazo disponible para calcular la cuota.</div>
                        @endif
                        <script type="application/json" data-credit-levels>@json($levels)</script>
                        <form method="GET" action="{{ route('simulador-creditos') }}#simulador" class="grid gap-5 lg:grid-cols-12 lg:items-end" data-simulator-form>
                            <div class="lg:col-span-4">
                                <label for="tipo_prestamo" class="form-label-public">Tipo de préstamo</label>
                                <select id="tipo_prestamo" name="tipo_prestamo" required class="form-control-public" data-credit-select-input>
                                    <option value="">Seleccionar</option>
                                    @foreach ($levels as $key => $level)<option value="{{ $key }}" @selected($selectedType === $key)>{{ $level['option_label'] }}</option>@endforeach
                                </select>
                            </div>
                            <div class="lg:col-span-3">
                                <label for="monto" class="form-label-public">Monto solicitado</label>
                                <div class="relative"><input id="monto" name="monto" type="number" min="1" step="0.01" value="{{ $amount }}" placeholder="Ej. 15000" required class="form-control-public pr-12"><span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-500">Bs</span></div>
                            </div>
                            <div class="lg:col-span-3" data-term-field>
                                <label for="plazo" class="form-label-public">Plazo disponible</label>
                                <select id="plazo" name="plazo" required class="form-control-public" data-term-select @disabled(! $selectedLevel)>
                                    <option value="">Seleccionar plazo</option>
                                    @if ($selectedLevel)
                                        @foreach ($selectedLevel['available_terms'] as $availableTerm)<option value="{{ $availableTerm }}" @selected($term === $availableTerm)>{{ $availableTerm }} meses</option>@endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="lg:col-span-2"><button type="submit" data-modal-return-focus class="min-h-12 w-full rounded-lg bg-[#D4A72C] px-5 py-3 font-bold text-[#061A33] transition hover:bg-[#F0C75E] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#174A7E]">Calcular</button></div>
                        </form>

                        <div class="mt-6 grid gap-3 rounded-xl bg-[#F5F7FA] p-4 text-sm sm:grid-cols-3 {{ $selectedLevel ? '' : 'invisible' }}" data-loan-summary aria-hidden="{{ $selectedLevel ? 'false' : 'true' }}">
                            <div><span class="block text-slate-500">Rango permitido</span><strong data-summary-range>{{ $selectedLevel['range_label'] ?? '' }}</strong></div>
                            <div><span class="block text-slate-500">Tasa anual</span><strong data-summary-rate>{{ isset($selectedLevel['annual_rate']) ? $selectedLevel['annual_rate'].'%' : '' }}</strong></div>
                            <div><span class="block text-slate-500">Plazos</span><strong data-summary-terms>{{ $selectedLevel['terms_label'] ?? '' }}</strong></div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <section id="requisitos" class="scroll-mt-24 bg-white py-20 lg:py-28">
            <div class="mx-auto grid max-w-6xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8 lg:items-center">
                <div><p class="section-eyebrow">Antes de comenzar</p><h2 class="section-title">Requisitos generales</h2><p class="section-copy">Prepara la documentación básica. Según el monto y producto pueden solicitarse antecedentes adicionales durante la evaluación.</p></div>
                <div class="rounded-3xl border border-slate-200 bg-[#F5F7FA] p-6 shadow-lg sm:p-8"><ul class="grid gap-4 sm:grid-cols-2">@forelse ($requirements as $requirement)<li class="flex gap-3"><span class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#D4A72C] text-sm font-bold text-[#061A33]">✓</span><span class="leading-6">{{ $requirement }}</span></li>@empty<li class="text-slate-600">Consulta los requisitos directamente con nuestro equipo.</li>@endforelse</ul></div>
            </div>
        </section>

        <section id="faq" class="scroll-mt-24 bg-[#F5F7FA] py-20 lg:py-28">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8"><div class="text-center"><p class="section-eyebrow">Información útil</p><h2 class="section-title">Preguntas frecuentes</h2></div>
                <div class="mt-10 space-y-3">
                    @foreach ([
                        ['¿Cómo funciona el simulador?', 'Selecciona un tipo de crédito, ingresa un monto dentro del rango disponible y elige un plazo para obtener una cuota referencial.'],
                        ['¿La simulación garantiza la aprobación?', 'No. La simulación es informativa y la aprobación está sujeta a evaluación y condiciones aplicables.'],
                        ['¿Cómo puedo afiliarme?', 'Usa el botón Afíliate para iniciar el proceso mediante el enlace institucional configurado.'],
                        ['¿Qué tipos de crédito existen?', 'Los productos disponibles se muestran en este sitio según la configuración vigente.'],
                        ['¿Dónde puedo solicitar información?', $whatsappNumber ? 'Puedes comunicarte mediante el WhatsApp configurado en la sección de contacto.' : 'Puedes solicitar información mediante los canales institucionales disponibles.'],
                    ] as [$question, $answer])
                        <details class="group rounded-xl border border-slate-200 bg-white p-5 open:shadow-md"><summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-bold text-[#061A33] focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-[#D4A72C]">{{ $question }}<span class="text-2xl font-light text-[#174A7E] transition group-open:rotate-45" aria-hidden="true">+</span></summary><p class="mt-4 pr-8 leading-7 text-slate-600">{{ $answer }}</p></details>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="contacto" class="scroll-mt-24 bg-white py-20 lg:py-28">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8"><div class="overflow-hidden rounded-3xl bg-[#0B2D55] text-white shadow-2xl"><div class="grid gap-8 p-7 sm:p-10 lg:grid-cols-[1fr_auto] lg:items-center lg:p-14"><div><p class="text-sm font-bold uppercase tracking-[0.16em] text-[#F0C75E]">Estamos para orientarte</p><h2 class="mt-3 text-3xl font-bold sm:text-4xl">Conversemos sobre tu próximo paso</h2><p class="mt-4 max-w-2xl leading-7 text-white/70">Solicita información sobre afiliación, requisitos y productos vigentes. Nuestro equipo podrá orientarte según tu consulta.</p>@if ($whatsappNumber)<p class="mt-4 font-semibold">WhatsApp: +{{ $whatsappNumber }}</p>@endif</div>@if ($whatsappUrl)<a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-12 items-center justify-center rounded-lg bg-[#D4A72C] px-6 py-3 font-bold text-[#061A33] transition hover:bg-[#F0C75E]">Consultar por WhatsApp</a>@endif</div></div></div>
        </section>
    </main>

    @if ($result)
        <div
            class="pointer-events-none invisible fixed inset-0 z-[100] flex items-center justify-center bg-[#020B16]/75 p-3 opacity-0 backdrop-blur-[2px] transition-opacity duration-200 sm:p-6"
            data-result-modal
            data-auto-open="true"
            role="dialog"
            aria-modal="true"
            aria-labelledby="result-modal-title"
            aria-hidden="true"
        >
            <div class="max-h-[90vh] w-[94vw] max-w-[920px] scale-95 overflow-y-auto overscroll-contain rounded-2xl bg-white opacity-0 shadow-2xl transition duration-200" data-result-panel>
                <div class="sticky top-0 z-10 flex items-start justify-between gap-5 border-b border-slate-200 bg-white px-5 py-4 sm:px-7">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-[#174A7E]">Resultado referencial</p>
                        <h2 id="result-modal-title" class="mt-1 text-xl font-bold text-[#061A33] sm:text-2xl">{{ $result['type'] }}</h2>
                    </div>
                    <button type="button" data-modal-close class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-slate-200 text-2xl leading-none text-slate-500 transition hover:border-slate-300 hover:bg-slate-100 hover:text-[#061A33] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#D4A72C]" aria-label="Cerrar resultado">×</button>
                </div>

                <div class="p-5 sm:p-7">
                    <div class="rounded-2xl bg-[#061A33] px-5 py-6 text-center text-white sm:px-8 sm:py-8">
                        <span class="block text-xs font-bold uppercase tracking-[0.16em] text-white/65">Cuota mensual aproximada</span>
                        <strong class="mt-2 block text-4xl font-bold tracking-tight text-[#F0C75E] sm:text-5xl">{{ number_format($result['monthly_payment'], 2, ',', '.') }} Bs</strong>
                    </div>

                    <dl class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
                        @foreach ([
                            'Monto solicitado' => number_format($result['amount'], 2, ',', '.').' Bs',
                            'Tasa anual' => number_format($result['annual_rate'], 2, ',', '.').'%',
                            'Plazo' => $result['term'].' meses',
                            'Nivel requerido' => 'Nivel '.$result['level'],
                            'Total aproximado' => number_format($result['total_payment'], 2, ',', '.').' Bs',
                            'Interés aproximado' => number_format($result['total_interest'], 2, ',', '.').' Bs',
                            'Afiliaciones necesarias' => $result['affiliations'],
                            'Costo de afiliación' => number_format($result['affiliation_cost'], 2, ',', '.').' Bs',
                        ] as $label => $value)
                            <div class="min-w-0 rounded-xl border border-slate-200 bg-[#F5F7FA] p-3 sm:p-4">
                                <dt class="text-[10px] font-bold uppercase leading-4 tracking-wider text-slate-500 sm:text-xs">{{ $label }}</dt>
                                <dd class="mt-1 break-words text-sm font-bold text-[#061A33] sm:text-base">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>

                    <div class="mt-5 rounded-xl border-l-4 border-[#D4A72C] bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-950">
                        {{ $result['is_housing'] ? ($settings['housing_warning'] ?? '') : ($settings['general_warning'] ?? '') }}
                    </div>

                    <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
                        <button type="button" data-modal-close class="min-h-11 rounded-lg border border-slate-300 px-6 py-2.5 font-bold text-[#061A33] transition hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#174A7E]">Cerrar</button>
                        @if ($affiliateUrl)
                            <a href="{{ $affiliateUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-[#D4A72C] px-6 py-2.5 font-bold text-[#061A33] transition hover:bg-[#F0C75E] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#174A7E]">Solicitar afiliación</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <footer class="bg-[#061A33] text-white">
        <div class="mx-auto grid max-w-[1440px] gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
            <div class="lg:col-span-2"><div class="flex items-center gap-4"><img src="{{ $logoUrl }}" data-image-fallback="{{ $defaultLogoUrl }}" alt="Logo oficial de {{ $siteName }}" class="h-20 w-20 shrink-0 rounded-full object-contain"><strong class="max-w-xs uppercase tracking-wider">{{ $siteName }}</strong></div><p class="mt-5 max-w-md leading-7 text-white/60">Información clara y herramientas digitales para acompañar las decisiones de nuestros afiliados.</p></div>
            <div><h2 class="text-sm font-bold uppercase tracking-wider text-[#F0C75E]">Accesos</h2><div class="mt-4 grid gap-2 text-sm text-white/70"><a href="#nosotros">Nosotros</a><a href="#creditos">Créditos</a><a href="#beneficios">Beneficios</a><a href="#faq">Preguntas frecuentes</a></div></div>
            <div><h2 class="text-sm font-bold uppercase tracking-wider text-[#F0C75E]">Gestiones</h2><div class="mt-4 grid gap-2 text-sm text-white/70"><a href="#simulador">Simulador</a>@if ($affiliateUrl)<a href="{{ $affiliateUrl }}" target="_blank" rel="noopener noreferrer">Afiliación</a>@endif<a href="{{ route('admin.login') }}">Administración</a></div></div>
        </div>
        <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-white/45">© {{ now()->year }} {{ $siteName }}. Todos los derechos reservados.</div>
    </footer>
</body>
</html>
