@extends('admin.layout')

@section('title', 'Editar créditos')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="section-title h2 mb-0">Tipos de crédito</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">Volver</a>
    </div>

    <div class="table-responsive table-wrap bg-white">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nivel</th><th>Nombre</th><th>Rango mínimo</th><th>Rango máximo</th><th>Tasa anual</th><th>Plazos disponibles</th><th>Activo</th><th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($creditLevels as $creditLevel)
                    <tr>
                        <td>{{ $creditLevel->level }}</td>
                        <td>{{ $creditLevel->name }}</td>
                        <td>{{ number_format($creditLevel->min_amount, 2, ',', '.') }} Bs</td>
                        <td>{{ $creditLevel->max_amount ? number_format($creditLevel->max_amount, 2, ',', '.') . ' Bs' : 'Sin límite' }}</td>
                        <td>{{ number_format($creditLevel->annual_rate, 2, ',', '.') }}%</td>
                        <td>{{ implode(',', $creditLevel->available_terms ?? []) }}</td>
                        <td>{{ $creditLevel->is_active ? 'Sí' : 'No' }}</td>
                        <td><a class="btn btn-sm btn-gold" href="{{ route('admin.credit-levels.edit', $creditLevel) }}">Editar</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
