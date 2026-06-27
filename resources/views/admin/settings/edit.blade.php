@extends('admin.layout')

@section('title', 'Editar textos y WhatsApp')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="section-title h2 mb-0">Textos y WhatsApp</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">Volver</a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="card p-4">
        @csrf
        @method('PUT')

        <div class="row g-3">
            @foreach ($settings as $setting)
                <div class="col-md-{{ $setting->type === 'textarea' ? '12' : '6' }}">
                    <label class="form-label" for="setting_{{ $setting->key }}">{{ $setting->label ?? $setting->key }}</label>
                    @if ($setting->type === 'textarea')
                        <textarea class="form-control" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="4">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                    @else
                        <input class="form-control" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" value="{{ old('settings.' . $setting->key, $setting->value) }}" @required($setting->key === 'whatsapp_number')>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4"><button class="btn btn-gold" type="submit">Guardar configuraciones</button></div>
    </form>
@endsection
