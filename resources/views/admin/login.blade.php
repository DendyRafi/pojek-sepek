<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SkinDecide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/pengaturan.css'])
</head>
<body style="{{ $customBackgroundStyle }}" data-custom-background-url="{{ $customBackgroundUrl }}">
    <header>
        <a href="/" class="logo">
            <div class="logo-dot"></div>
            <span class="logo-skin">SKIN</span><span class="logo-decide">DECIDE</span>
        </a>
        <a href="/" class="nav-link">← Halaman Utama</a>
    </header>

    <main>
        <div class="app-container auth-container">
            <div class="page-header">
                <div class="label">SkinDecide - Admin</div>
                <h1>Login <span>Admin</span></h1>
                <p>Masuk untuk mengatur kriteria rekomendasi dan reset password admin.</p>
            </div>

            @if(session('success'))
                <div class="auth-alert auth-alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="auth-alert auth-alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="new-criteria-card">
                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Username Admin</label>
                        <input type="text" name="username" value="{{ old('username') }}" required autofocus class="form-input" placeholder="admin">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" required class="form-input" placeholder="Password admin">
                    </div>

                    <label class="remember-row">
                        <input type="checkbox" name="remember" value="1">
                        Ingat sesi admin
                    </label>

                    <button type="submit" class="btn-submit">Login Admin</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
