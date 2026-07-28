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

        @php
            $groupTitles = [
                'enlace_afiliacion' => 'Enlace de afiliación',
                'contacto' => 'Contacto',
                'textos' => 'Textos',
                'requisitos' => 'Requisitos',
            ];
        @endphp

        @foreach ($settings->groupBy('group') as $group => $groupSettings)
            <section class="mb-4">
                <h2 class="section-title h5 mb-3">{{ $groupTitles[$group] ?? ucfirst((string) $group) }}</h2>

                <div class="row g-3">
                    @foreach ($groupSettings as $setting)
                        <div class="col-md-{{ $setting->type === 'textarea' ? '12' : '6' }}">
                            <label class="form-label" for="setting_{{ $setting->key }}">{{ $setting->label ?? $setting->key }}</label>
                            @if ($setting->type === 'textarea')
                                <textarea class="form-control" id="setting_{{ $setting->key }}" name="settings[{{ $setting->key }}]" rows="4">{{ old('settings.' . $setting->key, $setting->value) }}</textarea>
                            @else
                                <input
                                    class="form-control"
                                    id="setting_{{ $setting->key }}"
                                    name="settings[{{ $setting->key }}]"
                                    type="{{ $setting->type === 'url' ? 'url' : 'text' }}"
                                    value="{{ old('settings.' . $setting->key, $setting->value) }}"
                                    @if ($setting->key === 'affiliate_url') placeholder="https://..." @endif
                                    @required($setting->key === 'whatsapp_number')
                                >
                                @if ($setting->key === 'affiliate_url')
                                    <div class="form-text">Todos los botones 'Solicitar afiliación' abrirán esta dirección.</div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
            @endforeach

        <div class="mt-4"><button class="btn btn-gold" type="submit">Guardar configuraciones</button></div>
    </form>
@endsection
