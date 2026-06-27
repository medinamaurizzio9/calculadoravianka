<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel administrativo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --navy: #0B2545; --gold: #C9A227; --light: #F4F6F8; }
        body { background: var(--light); color: #1F2937; }
        .admin-nav { background: var(--navy); }
        .brand { color: #fff; font-weight: 800; text-decoration: none; }
        .btn-gold { background: var(--gold); border-color: var(--gold); color: var(--navy); font-weight: 800; }
        .btn-gold:hover { background: #b89220; border-color: #b89220; color: var(--navy); }
        .card, .table-wrap { border: 0; border-radius: 8px; box-shadow: 0 12px 30px rgba(11, 37, 69, .1); }
        .section-title { color: var(--navy); font-weight: 800; }
    </style>
</head>
<body>
    <nav class="admin-nav py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="brand" href="{{ route('admin.dashboard') }}">Panel Cooperativa</a>
            @if (session('admin_logged_in'))
                <form method="POST" action="{{ route('admin.logout') }}" class="mb-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-light" type="submit">Cerrar sesión</button>
                </form>
            @endif
        </div>
    </nav>

    <main class="container py-4">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-warning">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
