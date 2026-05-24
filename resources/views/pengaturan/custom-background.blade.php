<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Custom Background - SkinDecide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/welcome.css', 'resources/js/custom-bg-page.js'])
</head>
<body style="{{ $customBackgroundStyle }}" data-custom-background-url="{{ $customBackgroundUrl }}" data-custom-background-upload-url="{{ route('custom-background.store', [], false) }}" data-custom-background-reset-url="{{ route('custom-background.destroy', [], false) }}">

    <header>
        <div class="logo">
            <div class="logo-dot"></div>
            <span class="logo-skin">SKIN</span><span class="logo-decide">DECIDE</span>
        </div>
        <div class="header-actions">
            <a href="{{ route('pengaturan.index') }}" class="header-link" style="text-transform: uppercase; font-size: 13px; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 6px;">
                ← HALAMAN PENGATURAN
            </a>
        </div>
    </header>

    <main>
        <div class="app-container" style="max-width: 700px; margin: 0 auto;">
            
            <div class="page-header">
                <div class="label">SKINDECIDE - SETTING BACKGROUND</div>
                <h1>Custom <span>Background</span></h1>
                <p>Sesuaikan background global website SkinDecide. Gambar yang disimpan admin akan tampil untuk semua pengunjung.</p>
            </div>

            @if(session('success'))
                <div id="custom-bg-message" style="margin-bottom: 20px; padding: 14px 16px; border-radius: 6px; border: 1px solid rgba(130, 205, 39, 0.35); background: rgba(130, 205, 39, 0.12); color: #d8ff9d;">{{ session('success') }}</div>
            @elseif($errors->any())
                <div id="custom-bg-message" style="margin-bottom: 20px; padding: 14px 16px; border-radius: 6px; border: 1px solid rgba(239, 68, 68, 0.35); background: rgba(239, 68, 68, 0.12); color: #fecaca;">{{ $errors->first() }}</div>
            @else
                <div id="custom-bg-message" style="display: none; margin-bottom: 20px; padding: 14px 16px; border-radius: 6px;"></div>
            @endif

            <div class="skin-card" style="position: relative; padding: 40px; margin-top: 20px;">
                <div class="corner-deco corner-deco-tl"></div>
                <div class="corner-deco corner-deco-tr"></div>
                <div class="corner-deco corner-deco-bl"></div>
                <div class="corner-deco corner-deco-br"></div>

                <div class="skin-card-number">PREVIEW & SETTING</div>

                <div style="margin-bottom: 25px; text-align: center;">
                    <label style="display: block; margin-bottom: 12px; text-align: left; color: var(--text-primary);">Preview Gambar Saat Ini :</label>
                    <div id="bg-preview-box" style="width: 100%; height: 200px; border-radius: 6px; border: 1px dashed #444; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; color: #666; font-size: 14px;">
                        Belum ada gambar kustom (Menggunakan Default)
                    </div>
                </div>

                <form id="custom-bg-form" action="{{ route('custom-background.store', [], false) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="file-bg-input" name="background" accept="image/*" style="display: none;">
                </form>

                <div class="actions-row custom-bg-actions-row">
                    <button type="button" id="btn-pilih-foto" class="btn-add" style="margin: 0; flex: 1; justify-content: center;">
                        Pilih Gambar Lokal
                    </button>
                    <button type="button" id="btn-reset-bg" class="btn-hapus" style="padding: 12px 20px; font-size: 14px; border-radius: 4px;">
                        Reset Default
                    </button>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <div class="footer-brand"><span>SKIN</span>DECIDE</div>
        <div class="footer-copy">&copy; 2026 Promethee Team</div>
    </footer>

</body>
</html>
