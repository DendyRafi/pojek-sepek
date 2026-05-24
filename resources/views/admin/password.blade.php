<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Admin - SkinDecide</title>
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
        <div class="nav-actions">
            <a href="{{ route('pengaturan.index') }}" class="nav-link">← Pengaturan Kriteria</a>
            <form action="{{ route('logout') }}" method="POST" class="nav-form">
                @csrf
                <button type="submit" class="nav-link nav-button">Logout</button>
            </form>
        </div>
    </header>

    <main>
        <div class="app-container auth-container">
            <div class="page-header">
                <div class="label">SkinDecide - Admin</div>
                <h1>Reset <span>Password</span></h1>
                <p>Ubah password admin yang sedang login.</p>
            </div>

            @if(session('success'))
                <div class="auth-alert auth-alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="auth-alert auth-alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="new-criteria-card">
                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="form-input">
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" required class="form-input">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Simpan Password Baru</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
