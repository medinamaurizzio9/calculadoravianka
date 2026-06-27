@extends('admin.layout')

@section('title', 'Dashboard admin')

@section('content')
    <h1 class="section-title h2 mb-4">Dashboard</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="card p-4"><div class="text-muted">Total tipos de crédito</div><div class="display-6 fw-bold">{{ $totalCreditLevels }}</div></div></div>
        <div class="col-md-4"><div class="card p-4"><div class="text-muted">Tipos activos</div><div class="display-6 fw-bold">{{ $activeCreditLevels }}</div></div></div>
        <div class="col-md-4"><div class="card p-4"><div class="text-muted">Total configuraciones</div><div class="display-6 fw-bold">{{ $totalSettings }}</div></div></div>
    </div>

    <div class="card p-4">
        <div class="d-grid gap-2 d-md-flex">
            <a class="btn btn-gold" href="{{ route('admin.credit-levels.index') }}">Editar créditos</a>
            <a class="btn btn-gold" href="{{ route('admin.settings.edit') }}">Editar textos y WhatsApp</a>
            <a class="btn btn-outline-secondary" href="{{ route('simulador-creditos') }}" target="_blank">Ver simulador público</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn btn-outline-danger" type="submit">Cerrar sesión</button>
            </form>
        </div>
    </div>
@endsection
