<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings['hero_title'] ?? 'Simulador de Créditos' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #0B2545;
            --petrol: #133B5C;
            --gold: #C9A227;
            --white: #FFFFFF;
            --light: #F4F6F8;
            --text: #1F2937;
        }

        body {
            background: var(--light);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
        }

        .hero {
            background: var(--navy);
            color: var(--white);
            padding: 72px 0 98px;
        }

        .hero-kicker {
            color: var(--gold);
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .hero .lead {
            color: rgba(255, 255, 255, .86);
        }

        .calculator-card,
        .content-card,
        .visual-card {
            background: var(--white);
            border: 1px solid rgba(11, 37, 69, .08);
            border-radius: 8px;
            box-shadow: 0 14px 34px rgba(11, 37, 69, .1);
        }

        .calculator-card {
            margin-top: -58px;
        }

        .section-title {
            color: var(--navy);
            font-weight: 800;
        }

        .form-control,
        .form-select,
        .input-group-text {
            border-color: #d7dde5;
            font-size: 1.05rem;
            min-height: 50px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 .2rem rgba(201, 162, 39, .18);
        }

        .btn-gold {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
            font-weight: 800;
        }

        .btn-gold:hover,
        .btn-gold:focus {
            background: #b89220;
            border-color: #b89220;
            color: var(--navy);
        }

        .visual-card {
            height: 100%;
            overflow: hidden;
        }

        .visual-card img {
            aspect-ratio: 16 / 10;
            object-fit: cover;
            width: 100%;
        }

        .visual-card-body {
            border-top: 4px solid var(--gold);
            padding: 22px;
        }

        .result-label {
            color: #667085;
            font-size: .88rem;
        }

        .result-value {
            color: var(--navy);
            font-weight: 800;
        }

        .table thead th {
            background: var(--petrol);
            color: var(--white);
        }

        .notice {
            background: #fbf7e8;
            border-left: 5px solid var(--gold);
            color: #41391f;
        }

        .loan-help {
            background: #f8fafc;
            border: 1px solid #d7dde5;
            border-left: 5px solid var(--gold);
            border-radius: 8px;
            color: var(--text);
            margin-top: 12px;
            padding: 14px 16px;
        }

        .action-band {
            background: linear-gradient(135deg, var(--navy), var(--petrol));
            border-radius: 8px;
            color: var(--white);
        }

        .result-modal .modal-content {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 24px 70px rgba(11, 37, 69, .28);
        }

        .result-modal .modal-header {
            background: var(--navy);
            border-bottom: 4px solid var(--gold);
            color: var(--white);
        }

        .result-modal .btn-close {
            filter: invert(1);
        }

        @media (max-width: 575.98px) {
            .hero {
                padding: 48px 0 86px;
            }
        }
    </style>
</head>
<body>
    @php
        $creditCards = [
            [
                'title' => 'Crédito Productivo / Emprendimiento',
                'text' => 'Capital para inventario, herramientas, agricultura, comercio o crecimiento del negocio.',
                'image' => 'https://images.unsplash.com/photo-1556745757-8d76bdb6984b?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Crédito Vehículos',
                'text' => 'Financiamiento referencial para auto, moto o vehículo destinado al trabajo diario.',
                'image' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&w=900&q=80',
            ],
            [
                'title' => 'Crédito Vivienda',
                'text' => 'Opciones sujetas a evaluación para construir, mejorar, comprar casa o adquirir terreno.',
                'image' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=900&q=80',
            ],
        ];
    @endphp

    <header class="hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="hero-kicker mb-3">Cooperativa Tierra Bendita</div>
                    <h1 class="display-5 fw-bold mb-3">{{ $settings['hero_title'] ?? 'Simulador de Créditos' }}</h1>
                    <p class="lead mb-0">{{ $settings['hero_subtitle'] ?? 'Calcula tu cuota aproximada antes de solicitar tu afiliación' }}</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a class="btn btn-gold btn-lg" href="{{ $whatsappAffiliationUrl }}" target="_blank" rel="noopener">
                        Solicitar afiliación
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container pb-5">
        <section class="calculator-card mb-4">
            <div class="p-4 p-lg-5">
                <h2 class="section-title h3 mb-3">Calculadora</h2>
                <p class="text-muted mb-4">{{ $settings['form_intro'] ?? 'Selecciona el tipo de préstamo, ingresa el monto y elige un plazo disponible.' }}</p>

                @if ($errors !== [])
                    <div class="alert alert-warning simulator-alert" role="alert">
                        @foreach ($errors as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if ($showTerms && ! $result && $errors === [])
                    <div class="alert alert-info simulator-alert" role="alert">
                        Monto válido para {{ $selectedLevel['name'] }}. Selecciona un plazo disponible para calcular la cuota.
                    </div>
                @endif

                <form id="simulatorForm" method="GET" action="{{ route('simulador-creditos') }}" class="row g-3 align-items-end">
                    <div class="col-lg-4">
                        <label for="tipo_prestamo" class="form-label fw-semibold">Tipo de préstamo</label>
                        <select id="tipo_prestamo" name="tipo_prestamo" class="form-select form-select-lg" required>
                            <option value="">Seleccionar</option>
                            @foreach ($levels as $key => $level)
                                <option value="{{ $key }}" @selected($selectedType === $key)>{{ $level['option_label'] }}</option>
                            @endforeach
                        </select>

                        <div id="loanHelpHost">
                            @if ($selectedLevel)
                                <div class="loan-help" id="loanHelp">
                                    <div><strong>Rango permitido:</strong> {{ $selectedLevel['range_label'] }}</div>
                                    <div><strong>Tasa anual:</strong> {{ rtrim(rtrim(number_format($selectedLevel['annual_rate'], 1, '.', ''), '0'), '.') }}%</div>
                                    @if ($selectedLevel['is_housing'])
                                        <div><strong>Plazos referenciales:</strong> {{ $selectedLevel['terms_sentence'] }}</div>
                                        <div>Sujeto a evaluación individual.</div>
                                    @else
                                        <div><strong>Plazos disponibles:</strong> {{ $selectedLevel['terms_label'] }}</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <label for="monto" class="form-label fw-semibold">Monto solicitado</label>
                        <div class="input-group input-group-lg">
                            <input id="monto" name="monto" type="number" min="1" step="0.01" class="form-control" value="{{ $amount }}" placeholder="Ej. 15000" required>
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>

                    @if ($showTerms)
                        <div class="col-lg-3" id="termColumn">
                            <label for="plazo" class="form-label fw-semibold">Plazo disponible</label>
                            <select id="plazo" name="plazo" class="form-select form-select-lg" required>
                                <option value="">Seleccionar plazo</option>
                                @foreach ($selectedLevel['available_terms'] as $availableTerm)
                                    <option value="{{ $availableTerm }}" @selected($term === $availableTerm)>
                                        {{ $availableTerm }} meses
                                    </option>
                                @endforeach
                            </select>
                            @if ($selectedLevel['is_housing'])
                                <div class="form-text">Sujeto a evaluación individual.</div>
                            @endif
                        </div>
                    @endif

                    <div class="col-lg-2" id="submitColumn">
                        <button type="submit" class="btn btn-gold btn-lg w-100">Calcular cuota</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="mb-4">
            <div class="row g-4">
                @foreach ($creditCards as $card)
                    <div class="col-md-4">
                        <article class="visual-card">
                            <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}">
                            <div class="visual-card-body">
                                <h2 class="section-title h5 mb-2">{{ $card['title'] }}</h2>
                                <p class="text-muted">{{ $card['text'] }}</p>
                                <a class="btn btn-gold" href="{{ $whatsappAffiliationUrl }}" target="_blank" rel="noopener">Solicitar información</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="mb-4">
            <h2 class="section-title h3 mb-3">Tabla de niveles</h2>
            <div class="table-responsive bg-white rounded shadow-sm">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nivel</th>
                            <th>Crédito</th>
                            <th>Afiliaciones</th>
                            <th>Costo afiliación</th>
                            <th>Monto mínimo</th>
                            <th>Monto máximo</th>
                            <th>Tasa anual</th>
                            <th>Plazos disponibles</th>
                            <th>Uso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($levels as $level)
                            <tr>
                                <td>{{ $level['level'] }}</td>
                                <td>{{ $level['name'] }}</td>
                                <td>{{ $level['affiliations'] }}</td>
                                <td>{{ number_format($level['affiliation_cost'], 2, ',', '.') }} Bs</td>
                                <td>{{ number_format($level['min_amount'], 2, ',', '.') }} Bs</td>
                                <td>{{ $level['max_amount'] ? number_format($level['max_amount'], 2, ',', '.') . ' Bs' : 'Sin límite fijo' }}</td>
                                <td>{{ number_format($level['annual_rate'], 2, ',', '.') }}%</td>
                                <td>{{ implode(', ', $level['available_terms']) }} meses</td>
                                <td>{{ $level['usage'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="row g-4 align-items-stretch mb-4">
            <div class="col-lg-7">
                <div class="content-card p-4 h-100">
                    <h2 class="section-title h3 mb-3">Requisitos generales</h2>
                    <ul class="mb-0">
                        @foreach ($requirements as $requirement)
                            <li>{{ $requirement }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="notice rounded p-4 h-100">
                    <h2 class="h5 section-title">Advertencia legal</h2>
                    <p class="mb-0">{{ $settings['general_warning'] ?? 'Este cálculo es referencial y no representa aprobación automática del crédito. La evaluación final será individual.' }}</p>
                </div>
            </div>
        </section>

        <section class="action-band p-4 p-lg-5 mb-5">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <h2 class="h3 fw-bold mb-2">Solicitar afiliación</h2>
                    <p class="mb-0">Da el siguiente paso y solicita los requisitos por mensaje.</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <a class="btn btn-gold btn-lg" href="{{ $whatsappAffiliationUrl }}" target="_blank" rel="noopener">
                        Solicitar afiliación
                    </a>
                </div>
            </div>
        </section>
    </main>

    @if ($result)
        <div class="modal fade result-modal" id="resultadoCreditoModal" tabindex="-1" aria-labelledby="resultadoCreditoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h2 class="modal-title h4" id="resultadoCreditoModalLabel">Resultado de la simulación</h2>
                            <div class="small">Cálculo referencial según el préstamo, monto y plazo seleccionados</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Tipo de préstamo</div>
                                <div class="result-value">{{ $result['type'] }}</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Monto solicitado</div>
                                <div class="result-value">{{ number_format($result['amount'], 2, ',', '.') }} Bs</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Rango permitido</div>
                                <div class="result-value">{{ $result['range'] }}</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Nivel requerido</div>
                                <div class="result-value">Nivel {{ $result['level'] }}</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Afiliaciones necesarias</div>
                                <div class="result-value">{{ $result['affiliations'] }}</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Costo total de afiliación</div>
                                <div class="result-value">{{ number_format($result['affiliation_cost'], 2, ',', '.') }} Bs</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Tasa anual</div>
                                <div class="result-value">{{ number_format($result['annual_rate'], 2, ',', '.') }}%</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Plazo seleccionado</div>
                                <div class="result-value">{{ $result['term'] }} meses</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Cuota mensual aproximada</div>
                                <div class="result-value fs-4">{{ number_format($result['monthly_payment'], 2, ',', '.') }} Bs</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Total a pagar aproximado</div>
                                <div class="result-value">{{ number_format($result['total_payment'], 2, ',', '.') }} Bs</div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="result-label">Interés total aproximado</div>
                                <div class="result-value">{{ number_format($result['total_interest'], 2, ',', '.') }} Bs</div>
                            </div>
                            <div class="col-lg-8">
                                <div class="result-label">Uso autorizado</div>
                                <div class="result-value">{{ $result['usage'] }}</div>
                            </div>
                        </div>

                        <div class="notice p-3 rounded mt-4">
                            {{ $result['is_housing'] ? ($settings['housing_warning'] ?? 'Este cálculo es referencial. El crédito de vivienda está sujeto a evaluación individual, capacidad de pago y garantías.') : ($settings['general_warning'] ?? 'Este cálculo es referencial y no representa aprobación automática del crédito. La evaluación final será individual.') }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-gold" href="{{ $whatsappAffiliationUrl }}" target="_blank" rel="noopener">Solicitar afiliación</a>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const loanLevels = @json($levels);

        function formatRate(rate) {
            return Number(rate).toLocaleString('en-US', {
                maximumFractionDigits: 1,
                minimumFractionDigits: 0
            });
        }

        function removeSimulatorAlerts() {
            document.querySelectorAll('.simulator-alert').forEach(function (alert) {
                alert.remove();
            });
        }

        function isAmountValidForLoan(amount, loan) {
            if (!loan || !amount || amount <= 0) {
                return false;
            }

            if (amount < Number(loan.min_amount)) {
                return false;
            }

            return loan.max_amount === null || amount <= Number(loan.max_amount);
        }

        function renderLoanHelp(loan) {
            var loanHelpHost = document.getElementById('loanHelpHost');

            if (!loanHelpHost) {
                return;
            }

            if (!loan) {
                loanHelpHost.innerHTML = '';
                return;
            }

            var termLabel = loan.is_housing
                ? '<div><strong>Plazos referenciales:</strong> ' + loan.terms_sentence + '</div><div>Sujeto a evaluación individual.</div>'
                : '<div><strong>Plazos disponibles:</strong> ' + loan.terms_label + '</div>';

            loanHelpHost.innerHTML = '<div class="loan-help" id="loanHelp">'
                + '<div><strong>Rango permitido:</strong> ' + loan.range_label + '</div>'
                + '<div><strong>Tasa anual:</strong> ' + formatRate(loan.annual_rate) + '%</div>'
                + termLabel
                + '</div>';
        }

        function removeTermSelector() {
            var termColumn = document.getElementById('termColumn');

            if (termColumn) {
                termColumn.remove();
            }
        }

        function renderTermSelector(loan, selectedTerm) {
            var submitColumn = document.getElementById('submitColumn');

            removeTermSelector();

            if (!loan || !submitColumn) {
                return;
            }

            var termColumn = document.createElement('div');
            termColumn.className = 'col-lg-3';
            termColumn.id = 'termColumn';

            var options = '<option value="">Seleccionar plazo</option>';
            loan.available_terms.forEach(function (term) {
                var selected = String(term) === String(selectedTerm || '') ? ' selected' : '';
                options += '<option value="' + term + '"' + selected + '>' + term + ' meses</option>';
            });

            var housingNote = loan.is_housing ? '<div class="form-text">Sujeto a evaluación individual.</div>' : '';

            termColumn.innerHTML = '<label for="plazo" class="form-label fw-semibold">Plazo disponible</label>'
                + '<select id="plazo" name="plazo" class="form-select form-select-lg" required>'
                + options
                + '</select>'
                + housingNote;

            submitColumn.before(termColumn);
        }

        function syncSelectedLoan(options) {
            var shouldClearTerm = options && options.clearTerm;
            var loanType = document.getElementById('tipo_prestamo');
            var amount = document.getElementById('monto');
            var selectedKey = loanType ? loanType.value : '';
            var loan = selectedKey ? loanLevels[selectedKey] : null;
            var amountValue = amount && amount.value !== '' ? Number(amount.value) : null;

            renderLoanHelp(loan);

            if (amount) {
                if (loan) {
                    amount.min = loan.min_amount;
                    if (loan.max_amount === null) {
                        amount.removeAttribute('max');
                    } else {
                        amount.max = loan.max_amount;
                    }
                } else {
                    amount.min = '1';
                    amount.removeAttribute('max');
                }
            }

            removeSimulatorAlerts();

            if (shouldClearTerm) {
                removeTermSelector();
                return;
            }

            if (isAmountValidForLoan(amountValue, loan)) {
                var currentTerm = document.getElementById('plazo') ? document.getElementById('plazo').value : '';
                renderTermSelector(loan, currentTerm);
            } else {
                removeTermSelector();
            }
        }

        function resetSimulatorForm() {
            var form = document.getElementById('simulatorForm');
            var loanType = document.getElementById('tipo_prestamo');
            var amount = document.getElementById('monto');
            var term = document.getElementById('plazo');
            var termColumn = document.getElementById('termColumn');
            var loanHelp = document.getElementById('loanHelp');
            var loanHelpHost = document.getElementById('loanHelpHost');
            var resultModal = document.getElementById('resultadoCreditoModal');

            if (form) {
                form.reset();
                form.querySelectorAll('.is-valid, .is-invalid').forEach(function (field) {
                    field.classList.remove('is-valid', 'is-invalid');
                });
            }

            if (loanType) {
                loanType.value = '';
            }

            if (amount) {
                amount.value = '';
            }

            if (term) {
                term.value = '';
            }

            if (termColumn) {
                termColumn.remove();
            }

            if (loanHelp) {
                loanHelp.remove();
            }

            if (loanHelpHost) {
                loanHelpHost.innerHTML = '';
            }

            removeSimulatorAlerts();

            if (resultModal) {
                resultModal.querySelectorAll('.modal-body, .modal-footer').forEach(function (section) {
                    section.innerHTML = '';
                });
                resultModal.remove();
            }

            document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
                backdrop.remove();
            });

            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');

            window.history.replaceState({}, document.title, '{{ route('simulador-creditos') }}');
        }

        document.addEventListener('DOMContentLoaded', function () {
            var loanType = document.getElementById('tipo_prestamo');
            var amount = document.getElementById('monto');
            var resultModalElement = document.getElementById('resultadoCreditoModal');

            if (loanType) {
                loanType.addEventListener('change', function () {
                    syncSelectedLoan({ clearTerm: true });
                });
            }

            if (amount) {
                amount.addEventListener('input', function () {
                    syncSelectedLoan({ clearTerm: false });
                });
            }

            syncSelectedLoan({ clearTerm: false });

            if (resultModalElement) {
                resultModalElement.addEventListener('hidden.bs.modal', function () {
                    resetSimulatorForm();
                });

                new bootstrap.Modal(resultModalElement).show();
            }
        });
    </script>
</body>
</html>
