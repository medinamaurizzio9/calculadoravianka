@extends('admin.layout')

@section('title', 'Editar textos y WhatsApp')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="section-title h2 mb-0">Textos y WhatsApp</h1>
        <a class="btn btn-outline-secondary" href="{{ route('admin.dashboard') }}">Volver</a>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" class="card p-4" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            $groupTitles = [
                'enlace_afiliacion' => 'Enlace de afiliación',
                'contacto' => 'Contacto',
                'textos' => 'Textos',
                'requisitos' => 'Requisitos',
                'identidad_publica' => 'Identidad y página pública — Identidad',
                'landing_hero' => 'Identidad y página pública — Banner principal',
            ];
        @endphp

        @foreach ($settings->groupBy('group') as $group => $groupSettings)
            <section class="mb-4">
                <h2 class="section-title h5 mb-3">{{ $groupTitles[$group] ?? ucfirst((string) $group) }}</h2>

                <div class="row g-3">
                    @foreach ($groupSettings as $setting)
                        <div class="col-md-{{ in_array($setting->type, ['textarea', 'image'], true) ? '12' : '6' }}">
                            <label class="form-label" for="setting_{{ $setting->key }}">{{ $setting->label ?? $setting->key }}</label>
                            @if ($setting->type === 'image')
                                @php
                                    $previewUrl = str_starts_with((string) $setting->value, 'site/')
                                        ? \Illuminate\Support\Facades\Storage::url($setting->value)
                                        : asset($setting->value);
                                @endphp
                                @if ($setting->value)
                                    <div class="mb-3 rounded border bg-light p-3">
                                        <img
                                            src="{{ $previewUrl }}"
                                            alt="Vista previa de {{ $setting->label }}"
                                            style="max-height: {{ $setting->key === 'site_logo' ? '100px' : '220px' }}; max-width: 100%; object-fit: contain;"
                                        >
                                    </div>
                                @endif
                                <input
                                    class="form-control"
                                    id="setting_{{ $setting->key }}"
                                    name="{{ $setting->key }}"
                                    type="file"
                                    accept="image/jpeg,image/png,image/webp"
                                >
                                <div class="form-text">JPG, JPEG, PNG o WEBP. {{ $setting->key === 'site_logo' ? 'Máximo 3 MB.' : 'Máximo 5 MB.' }}</div>
                            @elseif ($setting->type === 'textarea')
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
