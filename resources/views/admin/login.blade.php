<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #F4F6F8; color: #1F2937; }
        .login-card { border: 0; border-radius: 8px; box-shadow: 0 16px 38px rgba(11, 37, 69, .14); }
        .btn-gold { background: #C9A227; border-color: #C9A227; color: #0B2545; font-weight: 800; }
    </style>
</head>
<body>
    <main class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card login-card p-4" style="max-width: 420px; width: 100%;">
            <h1 class="h3 mb-3" style="color:#0B2545;font-weight:800;">Panel administrativo</h1>
            @if ($errors->any())
                <div class="alert alert-warning">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="username">Usuario</label>
                    <input class="form-control form-control-lg" id="username" name="username" value="{{ old('username') }}" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="password">Contraseña</label>
                    <input class="form-control form-control-lg" id="password" name="password" type="password" required>
                </div>
                <button class="btn btn-gold btn-lg w-100" type="submit">Ingresar</button>
            </form>
        </div>
    </main>
</body>
</html>
