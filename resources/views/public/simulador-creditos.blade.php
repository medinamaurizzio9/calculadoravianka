<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulador de Créditos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --navy: #08213f;
            --navy-soft: #123963;
            --gold: #c99a2e;
            --gold-soft: #fff4d8;
            --white: #ffffff;
        }

        body {
            background: #f4f7fb;
            color: #17233a;
            font-family: Arial, Helvetica, sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, var(--navy), var(--navy-soft));
            color: var(--white);
            padding: 72px 0 92px;
        }

        .hero-kicker {
            color: var(--gold);
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .main-card {
            border: 0;
            border-radius: 8px;
            box-shadow: 0 18px 45px rgba(8, 33, 63, .14);
            margin-top: -56px;
        }

        .section-title {
            color: var(--navy);
            font-weight: 800;
        }

        .btn-gold {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--navy);
            font-weight: 800;
        }

        .btn-gold:hover,
        .btn-gold:focus {
            background: #b88920;
            border-color: #b88920;
            color: var(--navy);
        }

        .result-card {
            border: 1px solid rgba(201, 154, 46, .35);
            border-radius: 8px;
            background: var(--white);
        }

        .result-label {
            color: #60708a;
            font-size: .88rem;
        }

        .result-value {
            color: var(--navy);
            font-weight: 800;
        }

        .table thead th {
            background: var(--navy);
            color: var(--white);
        }

        .notice {
            background: var(--gold-soft);
            border-left: 5px solid var(--gold);
            color: #473715;
        }

        .whatsapp-float {
            align-items: center;
            background: #25d366;
            border-radius: 999px;
            bottom: 22px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .2);
            color: var(--white);
            display: inline-flex;
            font-weight: 800;
            gap: 8px;
            padding: 12px 18px;
            position: fixed;
            right: 22px;
            text-decoration: none;
            z-index: 20;
        }

        .whatsapp-float:hover {
            color: var(--white);
            background: #1ebe5d;
        }

        @media (max-width: 575.98px) {
            .hero {
                padding: 48px 0 82px;
            }

            .whatsapp-float {
                left: 16px;
                right: 16px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="hero-kicker mb-3">Cooperativa Tierra Bendita</div>
                    <h1 class="display-5 fw-bold mb-3">Simulador de Créditos</h1>
                    <p class="lead mb-0">Calcula tu cuota aproximada antes de solicitar tu evaluación</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a class="btn btn-gold btn-lg" href="{{ $whatsappEvaluationUrl }}" target="_blank" rel="noopener">
                        Solicitar evaluación
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="container pb-5">
        <section class="card main-card mb-4">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <h2 class="section-title h3 mb-3">Simula tu crédito</h2>

                        @if ($errors !== [])
                            <div class="alert alert-warning" role="alert">
                                @foreach ($errors as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="GET" action="{{ route('simulador-creditos') }}" class="vstack gap-3">
                            <div>
                                <label for="tipo_credito" class="form-label fw-semibold">Tipo de crédito</label>
                                <select id="tipo_credito" name="tipo_credito" class="form-select" required>
                                    @foreach ($levels as $key => $level)
                                        <option value="{{ $key }}" @selected($selectedType === $key)>
                                            {{ $level['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="monto" class="form-label fw-semibold">Monto solicitado</label>
                                <div class="input-group">
                                    <input id="monto" name="monto" type="number" min="1" step="0.01" class="form-control" value="{{ $amount }}" placeholder="Ej. 12000" required>
                                    <span class="input-group-text">Bs</span>
                                </div>
                            </div>

                            <div>
                                <label for="plazo" class="form-label fw-semibold">Plazo en meses</label>
                                <input id="plazo" name="plazo" type="number" min="1" step="1" class="form-control" value="{{ $term }}" placeholder="Ej. 24" required>
                            </div>

                            <button type="submit" class="btn btn-gold btn-lg">Calcular cuota</button>
                        </form>
                    </div>

                    <div class="col-lg-7">
                        <div class="result-card h-100 p-4">
                            <h2 class="section-title h4 mb-4">Resultado</h2>

                            @if ($result)
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="result-label">Tipo de credito</div>
                                        <div class="result-value">{{ $result['type'] }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Nivel requerido</div>
                                        <div class="result-value">Nivel {{ $result['level'] }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Afiliaciones necesarias</div>
                                        <div class="result-value">{{ $result['affiliations'] }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Costo total de afiliacion</div>
                                        <div class="result-value">{{ number_format($result['affiliation_cost'], 2, ',', '.') }} Bs</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Monto solicitado</div>
                                        <div class="result-value">{{ number_format($result['amount'], 2, ',', '.') }} Bs</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Tasa anual</div>
                                        <div class="result-value">{{ number_format($result['annual_rate'], 2, ',', '.') }}%</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Plazo elegido</div>
                                        <div class="result-value">{{ $result['term'] }} meses</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Cuota mensual aproximada</div>
                                        <div class="result-value fs-4">{{ number_format($result['monthly_payment'], 2, ',', '.') }} Bs</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Total a pagar aproximado</div>
                                        <div class="result-value">{{ number_format($result['total_payment'], 2, ',', '.') }} Bs</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="result-label">Interes total aproximado</div>
                                        <div class="result-value">{{ number_format($result['total_interest'], 2, ',', '.') }} Bs</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="result-label">Uso autorizado</div>
                                        <div class="result-value">{{ $result['usage'] }}</div>
                                    </div>
                                    @if ($result['evaluation_note'])
                                        <div class="col-12">
                                            <div class="alert alert-info mb-0">{{ $result['evaluation_note'] }}</div>
                                        </div>
                                    @endif
                                </div>

                                <hr>
                                <h3 class="h6 section-title">Requisitos generales</h3>
                                <ul class="mb-4">
                                    @foreach ($requirements as $requirement)
                                        <li>{{ $requirement }}</li>
                                    @endforeach
                                </ul>
                                <a class="btn btn-gold" href="{{ $whatsappEvaluationUrl }}" target="_blank" rel="noopener">Solicitar evaluacion</a>
                            @else
                                <p class="text-muted mb-4">Completa los datos del formulario para ver la cuota aproximada y el nivel requerido.</p>
                                <div class="notice p-3 rounded">
                                    Este cálculo es referencial y no representa aprobación automática del crédito. La evaluación final será individual.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <h2 class="section-title h3 mb-3">Tabla de niveles</h2>
            <div class="table-responsive bg-white rounded shadow-sm">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Nivel</th>
                            <th>Credito</th>
                            <th>Afiliaciones</th>
                            <th>Costo afiliacion</th>
                            <th>Monto minimo</th>
                            <th>Monto maximo</th>
                            <th>Tasa anual</th>
                            <th>Plazo maximo</th>
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
                                <td>{{ $level['max_amount'] ? number_format($level['max_amount'], 2, ',', '.') . ' Bs' : 'Sin limite fijo' }}</td>
                                <td>{{ number_format($level['annual_rate'], 2, ',', '.') }}%</td>
                                <td>{{ $level['max_term'] ? $level['max_term'] . ' meses' : 'Sujeto a evaluacion' }}</td>
                                <td>{{ $level['usage'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="row g-4 align-items-stretch mb-5">
            <div class="col-lg-7">
                <div class="bg-white rounded shadow-sm p-4 h-100">
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
                    <h2 class="h5 section-title">Advertencia</h2>
                    <p class="mb-3">Este cálculo es referencial y no representa aprobación automática del crédito. La evaluación final será individual.</p>
                    <a class="btn btn-gold" href="{{ $whatsappInfoUrl }}" target="_blank" rel="noopener">Más información por WhatsApp</a>
                </div>
            </div>
        </section>
    </main>

    <a class="whatsapp-float" href="{{ $whatsappInfoUrl }}" target="_blank" rel="noopener">
        WhatsApp
    </a>
</body>
</html>
