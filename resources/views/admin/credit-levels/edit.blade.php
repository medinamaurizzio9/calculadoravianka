@extends('admin.layout')

@section('title', 'Editar crédito')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="section-title h2 mb-0">Editar {{ $creditLevel->name }}</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.credit-levels.index') }}">Volver</a>
    </div>

    <form method="POST" action="{{ route('admin.credit-levels.update', $creditLevel) }}" class="card p-4">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6"><label class="form-label" for="name">Nombre</label><input class="form-control" id="name" name="name" value="{{ old('name', $creditLevel->name) }}" required></div>
            <div class="col-md-2"><label class="form-label" for="level">Nivel</label><input class="form-control" id="level" name="level" type="number" value="{{ old('level', $creditLevel->level) }}" required></div>
            <div class="col-md-2"><label class="form-label" for="affiliations">Afiliaciones</label><input class="form-control" id="affiliations" name="affiliations" type="number" value="{{ old('affiliations', $creditLevel->affiliations) }}" required></div>
            <div class="col-md-2"><label class="form-label" for="sort_order">Orden</label><input class="form-control" id="sort_order" name="sort_order" type="number" value="{{ old('sort_order', $creditLevel->sort_order) }}" required></div>
            <div class="col-md-3"><label class="form-label" for="affiliation_cost">Costo afiliación</label><input class="form-control" id="affiliation_cost" name="affiliation_cost" type="number" step="0.01" value="{{ old('affiliation_cost', $creditLevel->affiliation_cost) }}" required></div>
            <div class="col-md-3"><label class="form-label" for="min_amount">Monto mínimo</label><input class="form-control" id="min_amount" name="min_amount" type="number" step="0.01" value="{{ old('min_amount', $creditLevel->min_amount) }}" required></div>
            <div class="col-md-3"><label class="form-label" for="max_amount">Monto máximo</label><input class="form-control" id="max_amount" name="max_amount" type="number" step="0.01" value="{{ old('max_amount', $creditLevel->max_amount) }}"></div>
            <div class="col-md-3"><label class="form-label" for="annual_rate">Tasa anual</label><input class="form-control" id="annual_rate" name="annual_rate" type="number" step="0.01" value="{{ old('annual_rate', $creditLevel->annual_rate) }}" required></div>
            <div class="col-md-6"><label class="form-label" for="available_terms">Plazos disponibles</label><input class="form-control" id="available_terms" name="available_terms" value="{{ old('available_terms', $termsText) }}" placeholder="6,12"></div>
            <div class="col-md-6"><label class="form-label" for="authorized_use">Uso autorizado</label><textarea class="form-control" id="authorized_use" name="authorized_use" rows="3">{{ old('authorized_use', $creditLevel->authorized_use) }}</textarea></div>
            <div class="col-12 d-flex gap-4 flex-wrap">
                <input type="hidden" name="is_housing" value="0"><input type="hidden" name="evaluation_required" value="0"><input type="hidden" name="is_active" value="0">
                <label class="form-check"><input class="form-check-input" type="checkbox" name="is_housing" value="1" @checked(old('is_housing', $creditLevel->is_housing))> <span class="form-check-label">Es vivienda</span></label>
                <label class="form-check"><input class="form-check-input" type="checkbox" name="evaluation_required" value="1" @checked(old('evaluation_required', $creditLevel->evaluation_required))> <span class="form-check-label">Requiere evaluación</span></label>
                <label class="form-check"><input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $creditLevel->is_active))> <span class="form-check-label">Activo</span></label>
            </div>
        </div>

        <div class="mt-4"><button class="btn btn-gold" type="submit">Guardar cambios</button></div>
    </form>
@endsection
