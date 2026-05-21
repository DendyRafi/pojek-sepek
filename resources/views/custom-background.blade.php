<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Background - SkinDecide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/welcome.css', 'resources/js/custom-bg-page.js'])
</head>
<body>

    <header>
        <div class="logo">
            <div class="logo-dot"></div>
            <span class="logo-skin">SKIN</span><span class="logo-decide">DECIDE</span>
        </div>
      <div class="header-actions">
    <a href="/" class="header-link" style="text-transform: uppercase; font-size: 13px; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 6px;">
        ← HALAMAN UTAMA
    </a>
</div>
    </header>

    <main>
        <div class="app-container" style="max-width: 700px; margin: 0 auto;">
            
            <div class="page-header">
                <div class="label">SKINDECIDE - SETTING BACKGROUND</div>
                <h1>Custom <span>Background</span></h1>
                <p>Sesuaikan tampilan background website SkinDecide dengan gambar pilihanmu sendiri langsung dari file lokal.</p>
            </div>

            <div class="skin-card" style="position: relative; padding: 30px; margin-top: 20px;">
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

                <div class="actions-row" style="display: flex; gap: 15px; margin-top: 20px;">
                    <button type="button" id="btn-pilih-foto" class="btn-add" style="margin: 0; flex: 1; justify-content: center;">
                        Pilih Gambar Lokal
                    </button>
                    <button type="button" id="btn-reset-bg" class="btn-hapus" style="padding: 12px 20px; font-size: 14px; border-radius: 4px;">
                        Reset Default
                    </button>
                </div>

                <input type="file" id="file-bg-input" accept="image/*" style="display: none;">
            </div>

        </div>
    </main>

    <footer>
        <div class="footer-brand"><span>SKIN</span>DECIDE</div>
        <div class="footer-copy">&copy; 2026 Promethee Team</div>
    </footer>

</body>
</html>