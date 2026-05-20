<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkinDecide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --neon: #82cd27;
            --neon-dim: rgba(130, 205, 39, 0.15);
            --neon-glow: rgba(130, 205, 39, 0.4);
            --gold: #f0b429;
            --bg-void: #090d12;
            --bg-deep: #0d1319;
            --bg-card: #111820;
            --bg-input: #0d1319;
            --border: rgba(255,255,255,0.07);
            --border-hover: rgba(130, 205, 39, 0.5);
            --text-primary: #e8edf3;
            --text-muted: #5a6a7a;
            --text-dim: #8898a8;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Syne', sans-serif;
            background-color: var(--bg-void);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
            
            /* ─── ADDED: BACKGROUND HERO IMAGE CONFIGURATION ─── */
            background-image: linear-gradient(to bottom, rgba(9, 13, 18, 0.85), rgba(9, 13, 18, 0.95)), url('/images/hero-bg.jpg');
            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        /* ─── BACKGROUND GRID ─── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(130,205,39,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(130,205,39,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
            pointer-events: none;
            z-index: 1; /* Diubah ke 1 agar berada di atas gambar bg */
        }

        body::after {
            content: '';
            position: fixed;
            top: -30%;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            height: 500px;
            background: radial-gradient(ellipse at center, rgba(130,205,39,0.06) 0%, transparent 70%);
            pointer-events: none;
            z-index: 1; /* Diubah ke 1 agar berada di atas gambar bg */
        }

        /* ─── HEADER ─── */
        header {
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 40px;
            border-bottom: 1px solid var(--border);
            background: rgba(9,13,18,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .logo {
            font-family: 'Orbitron', monospace;
            font-weight: 900;
            font-size: 1.5rem;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .logo-skin {
            color: var(--neon);
            text-shadow: 0 0 20px var(--neon-glow), 0 0 40px rgba(130,205,39,0.2);
        }

        .logo-decide { color: var(--text-primary); }

        .logo-dot {
            width: 6px;
            height: 6px;
            background: var(--neon);
            border-radius: 50%;
            margin-right: 8px;
            box-shadow: 0 0 10px var(--neon-glow);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.7); }
        }

        .header-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            border: 1px solid rgba(255,255,255,0.06);
            padding: 5px 12px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* ─── MAIN ─── */
        main {
            position: relative;
            z-index: 5;
            flex-grow: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 20px;
        }

        .app-container {
            width: 100%;
            max-width: 1000px;
        }

        /* ─── PAGE HEADER ─── */
        .page-header {
            text-align: center;
            margin-bottom: 48px;
            animation: fadeSlideDown 0.6s ease both;
        }

        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .page-header .label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.2em;
            color: var(--neon);
            text-transform: uppercase;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .page-header .label::before,
        .page-header .label::after {
            content: '';
            display: block;
            width: 40px;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--neon));
        }

        .page-header .label::after {
            background: linear-gradient(90deg, var(--neon), transparent);
        }

        .page-header h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(1.6rem, 4vw, 2.6rem);
            font-weight: 900;
            letter-spacing: 0.04em;
            color: var(--text-primary);
            line-height: 1.15;
            margin-bottom: 14px;
        }

        .page-header h1 span { color: var(--neon); }

        .page-header p {
            font-size: 0.85rem;
            color: var(--text-dim);
            max-width: 750px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* ─── SKIN CARDS ─── */
        #container-alternatif { display: flex; flex-direction: column; gap: 20px; }

        .skin-card {
            background: rgba(17, 24, 32, 0.85); /* Ditambahkan sedikit transparan agar menyatu dengan background */
            backdrop-filter: blur(8px); /* Efek kaca tipis di setiap card */
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
            position: relative;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            animation: cardIn 0.4s ease both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .skin-card:hover {
            border-color: rgba(130,205,39,0.2);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4), 0 0 0 1px rgba(130,205,39,0.05);
        }

        .skin-card-number {
            position: absolute;
            top: -12px;
            left: 24px;
            background: var(--neon);
            color: #000;
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            font-size: 0.65rem;
            letter-spacing: 0.1em;
            padding: 4px 12px;
            border-radius: 4px;
        }

        .btn-hapus {
            position: absolute;
            top: 20px;
            right: 20px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.08);
            color: var(--text-muted);
            font-size: 0.75rem;
            font-family: 'Syne', sans-serif;
            cursor: pointer;
            padding: 5px 12px;
            border-radius: 6px;
            transition: all 0.2s ease;
            letter-spacing: 0.05em;
        }

        .btn-hapus:hover {
            border-color: rgba(239, 68, 68, 0.5);
            color: #f87171;
            background: rgba(239,68,68,0.08);
        }

        .skin-name-section { margin-bottom: 24px; margin-top: 8px; }

        .skin-name-section label {
            display: block;
            font-size: 0.7rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-dim);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .input-name {
            width: 100%;
            max-width: 400px;
            background: var(--bg-input);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 11px 16px;
            font-size: 0.95rem;
            font-family: 'Syne', sans-serif;
            color: var(--text-primary);
            font-weight: 600;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }

        .input-name:focus {
            border-color: var(--neon);
            box-shadow: 0 0 0 3px rgba(130,205,39,0.1);
        }

        .input-name::placeholder { color: var(--text-muted); font-weight: 400; }

        /* ─── CRITERIA GRID ─── */
        .criteria-divider {
            border-top: 1px solid var(--border);
            padding-top: 20px;
        }

        .criteria-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        @media (min-width: 640px) {
            .criteria-grid { grid-template-columns: repeat(4, 1fr); }
        }

        .criteria-item label {
            display: block;
            font-size: 0.65rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 7px;
            font-weight: 600;
            line-height: 1.4;
        }

        .criteria-input,
        .criteria-select {
            width: 100%;
            background: var(--bg-input);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 0.82rem;
            font-family: 'Syne', sans-serif;
            color: var(--text-primary);
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            appearance: none;
            -webkit-appearance: none;
        }

        .criteria-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235a6a7a' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
            cursor: pointer;
        }

        .criteria-input:focus,
        .criteria-select:focus {
            border-color: var(--neon);
            box-shadow: 0 0 0 3px rgba(130,205,39,0.1);
        }

        .criteria-input::placeholder { color: var(--text-muted); }

        .hint-text {
            font-size: 0.6rem;
            color: var(--text-muted);
            margin-top: 5px;
            line-height: 1.5;
        }

        /* ─── ACTION BUTTONS ─── */
        .actions-row {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 32px;
            align-items: stretch;
        }

        @media (min-width: 540px) {
            .actions-row {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }
        }

        .btn-add {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            background: rgba(9, 13, 18, 0.6);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: var(--text-dim);
            font-family: 'Syne', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: 0.03em;
        }

        .btn-add:hover {
            border-color: var(--neon);
            color: var(--neon);
            background: var(--neon-dim);
        }

        .btn-add svg { transition: transform 0.2s ease; }
        .btn-add:hover svg { transform: rotate(90deg); }

        .btn-hitung {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px 36px;
            background: var(--neon);
            border: none;
            border-radius: 8px;
            color: #000;
            font-family: 'Orbitron', monospace;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(130,205,39,0.3);
        }

        .btn-hitung::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .btn-hitung:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(130,205,39,0.45);
        }

        .btn-hitung:hover::before { opacity: 1; }

        .btn-hitung:active { transform: translateY(0); }

        .btn-hitung.loading { opacity: 0.7; pointer-events: none; }

        /* ─── SPINNER ─── */
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(0,0,0,0.2);
            border-top-color: #000;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: none;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ─── RESULTS SECTION ─── */
        #section-hasil {
            display: none;
            margin-top: 48px;
            animation: fadeSlideUp 0.5s ease both;
        }

        #section-hasil.visible { display: block; }

        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .hasil-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }

        .hasil-header h2 {
            font-family: 'Orbitron', monospace;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            color: var(--text-primary);
        }

        .hasil-header h2 span { color: var(--neon); }

        .hasil-divider {
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, var(--border-hover), transparent);
        }

        /* ─── TABLE ─── */
        .table-wrap {
            background: rgba(17, 24, 32, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        thead tr {
            background: rgba(130,205,39,0.06);
            border-bottom: 1px solid var(--border);
        }

        thead th {
            padding: 14px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 500;
        }

        thead th.th-score {
            text-align: right;
            color: var(--neon);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.2s ease;
        }

        tbody tr:last-child { border-bottom: none; }

        tbody tr:hover { background: rgba(255,255,255,0.02); }

        tbody tr.rank-1 {
            background: linear-gradient(90deg, rgba(130,205,39,0.08), transparent 60%);
        }

        tbody td {
            padding: 16px 20px;
            font-size: 0.875rem;
        }

        .td-rank {
            font-family: 'JetBrains Mono', monospace;
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .td-rank-1 {
            font-family: 'Orbitron', monospace;
            font-weight: 700;
            color: var(--neon);
            font-size: 0.85rem;
        }

        .td-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .td-name-1 {
            font-weight: 700;
            color: var(--neon);
        }

        .trophy-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, rgba(240,180,41,0.15), rgba(240,180,41,0.05));
            border: 1px solid rgba(240,180,41,0.25);
            color: var(--gold);
            font-size: 0.65rem;
            font-family: 'JetBrains Mono', monospace;
            letter-spacing: 0.1em;
            padding: 3px 10px;
            border-radius: 4px;
            margin-left: 10px;
            vertical-align: middle;
        }

        .td-flow {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .td-score {
            text-align: right;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .score-positive { color: #4ade80; }
        .score-negative { color: #f87171; }

        /* ─── FOOTER ─── */
        footer {
            position: relative;
            z-index: 10;
            border-top: 1px solid var(--border);
            background: rgba(9,13,18,0.8);
            padding: 18px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-brand {
            font-family: 'Orbitron', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: var(--text-muted);
        }

        .footer-brand span { color: var(--neon); }

        .footer-copy {
            font-size: 0.7rem;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        /* ─── CORNER DECORATIONS ─── */
        .corner-deco {
            position: absolute;
            width: 16px;
            height: 16px;
        }

        .corner-deco-tl { top: 0; left: 0; border-top: 1.5px solid var(--neon); border-left: 1.5px solid var(--neon); }
        .corner-deco-tr { top: 0; right: 0; border-top: 1.5px solid var(--neon); border-right: 1.5px solid var(--neon); }
        .corner-deco-bl { bottom: 0; left: 0; border-bottom: 1.5px solid var(--neon); border-left: 1.5px solid var(--neon); }
        .corner-deco-br { bottom: 0; right: 0; border-bottom: 1.5px solid var(--neon); border-right: 1.5px solid var(--neon); }

        /* ─── SELECT FIX ─── */
        select option {
            background: #111820;
            color: #e8edf3;
        }

        /* ─── SCROLL ─── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(130,205,39,0.2); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(130,205,39,0.4); }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <div class="logo-dot"></div>
            <span class="logo-skin">SKIN</span><span class="logo-decide">DECIDE</span>
        </div>
        <div class="header-badge">Promethee Team</div>
    </header>

    <main>
        <div class="app-container">

            <div class="page-header">
                <div class="label">SkinDecide - Asisten Rekomendasi Skin</div>
                <h1>Rekomendasi <span>Skin</span> Terbaik</h1>
                <p>Masukkan nama skin yang ingin dibandingkan beserta penilaian kriteria kamu <br> (Masukkan Skala 1-7, khusus Kategori masukkan skala 1-6, dan untuk Harga masukkan dalam jumlah Diamond)</p>
            </div>

            <form id="spkForm" onsubmit="prosesHitung(event)">
                <div id="container-alternatif"></div>

                <div class="actions-row">
                    <button type="button" class="btn-add" onclick="tambahBarisSkin()">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7.5 1v13M1 7.5h13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                        Tambah Pilihan Skin
                    </button>
                    <button type="submit" class="btn-hitung" id="btn-hitung">
                        <div class="spinner" id="spinner"></div>
                        <svg id="calc-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <rect x="1.5" y="1.5" width="13" height="13" rx="2" stroke="currentColor" stroke-width="1.4"/>
                            <path d="M4 5h8M4 8h4M4 11h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        </svg>
                        Hitung Rekomendasi
                    </button>
                </div>
            </form>

            <div id="section-hasil">
                <div class="hasil-header">
                    <h2>Hasil <span>Peringkat</span></h2>
                    <div class="hasil-divider"></div>
                </div>
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:56px">#</th>
                                <th>Nama Skin</th>
                                <th>Leaving Flow</th>
                                <th>Entering Flow</th>
                                <th class="th-score">Net Flow</th>
                            </tr>
                        </thead>
                        <tbody id="tabel-hasil"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <footer>
        <div class="footer-brand"><span>SKIN</span>DECIDE</div>
        <div class="footer-copy">&copy; 2026 Promethee Team</div>
    </footer>

    <script>
        const daftarKriteria = [
            { id: 1, name: 'Harga (Diamond)', isHarga: true },
            { id: 2, name: 'Kategori Skin', isRarity: true },
            { id: 3, name: 'Model Skin' },
            { id: 4, name: 'Portrait Skin' },
            { id: 5, name: 'Animasi Entrance' },
            { id: 6, name: 'In-Game Effect' },
            { id: 7, name: 'Tingkat Preferensi Hero', isPreferensi: true },
            { id: 8, name: 'Status Ketersediaan Skin', isKetersediaan: true }
        ];

        let urutanSkin = 0;
        let cardCount = 0;

        function tambahBarisSkin() {
            urutanSkin++;
            cardCount++;
            const container = document.getElementById('container-alternatif');

            const card = document.createElement('div');
            card.className = 'skin-card class-skin-item';
            card.id = `skin-row-${urutanSkin}`;
            card.style.animationDelay = '0ms';

            let criteriaHTML = '';
            daftarKriteria.forEach(k => {
                if (k.isHarga) {
                    criteriaHTML += `
                        <div class="criteria-item">
                            <label>${k.name}</label>
                            <input type="number" required name="kriteria_${k.id}" placeholder="Misal: 1089"
                                class="criteria-input">
                            <p class="hint-text">Gacha: estimasi pity (Zodiac ~1500 · Collector ~4000 · Aspirants ~5000 · Legend ~9000)</p>
                        </div>`;
                } else if (k.isRarity) {
                    criteriaHTML += `
                        <div class="criteria-item">
                            <label>${k.name}</label>
                            <select name="kriteria_${k.id}" class="criteria-select">
                                <option value="1" selected>1 — Common (Basic / Elite / Season)</option>
                                <option value="2">2 — Exceptional (Special / Starlight Regular)</option>
                                <option value="3">3 — Deluxe (Epic Shop / Epic Squad Series / Zodiac)</option>
                                <option value="4">4 — Exquisite (Epic Limited / Collector / Lucky Box / Starlight Annual)</option>
                                <option value="5">5 — Grand (Collab Anime/Movie, Aspirants, Exorcists, Mistbenders)</option>
                                <option value="6">6 — Legend (Legend Magic Wheel / Legend Limited Event)</option>
                            </select>
                        </div>`;
                } else if (k.isPreferensi) {
                    criteriaHTML += `
                        <div class="criteria-item">
                            <label>${k.name}</label>
                            <select name="kriteria_${k.id}" class="criteria-select">
                                <option value="1">1 — Tidak Pernah Dipakai</option>
                                <option value="2">2 — Sangat Jarang Dipakai</option>
                                <option value="3">3 — Jarang Dipakai</option>
                                <option value="4" selected>4 — Kadang-kadang</option>
                                <option value="5">5 — Sering Dipakai</option>
                                <option value="6">6 — Sangat Sering Dipakai</option>
                                <option value="7">7 — Hero Andalan Utama (Signature)</option>
                            </select>
                        </div>`;
                } else if (k.isKetersediaan) {
                    criteriaHTML += `
                        <div class="criteria-item">
                            <label>${k.name}</label>
                            <select name="kriteria_${k.id}" class="criteria-select">
                                <option value="1" selected>Dapat Dibeli Kapan Saja di Shop</option>
                                <option value="2">Hanya Bisa Dibeli Saat Event Berlangsung (Limited)</option>
                            </select>
                        </div>`;
                } else {
                    criteriaHTML += `
                        <div class="criteria-item">
                            <label>${k.name}</label>
                            <select name="kriteria_${k.id}" class="criteria-select">
                                <option value="1">1 — Sangat Kurang</option>
                                <option value="2">2 — Kurang</option>
                                <option value="3">3 — Agak Kurang</option>
                                <option value="4" selected>4 — Standar</option>
                                <option value="5">5 — Lumayan Bagus</option>
                                <option value="6">6 — Bagus</option>
                                <option value="7">7 — Sangat Bagus</option>
                            </select>
                        </div>`;
                }
            });

            card.innerHTML = `
                <div class="corner-deco corner-deco-tl"></div>
                <div class="corner-deco corner-deco-tr"></div>
                <div class="corner-deco corner-deco-bl"></div>
                <div class="corner-deco corner-deco-br"></div>
                <div class="skin-card-number">SKIN ${urutanSkin}</div>
                <button type="button" class="btn-hapus" onclick="hapusBarisSkin(${urutanSkin})">✕ Hapus</button>
                <div class="skin-name-section">
                    <label>Nama / Varian Skin</label>
                    <input type="text" required name="nama_skin" placeholder="Misal: Gusion Cosmic Gleam"
                        class="input-name">
                </div>
                <div class="criteria-divider">
                    <div class="criteria-grid">${criteriaHTML}</div>
                </div>`;

            container.appendChild(card);
        }

        function hapusBarisSkin(id) {
            const total = document.querySelectorAll('.class-skin-item').length;
            if (total > 2) {
                const el = document.getElementById(`skin-row-${id}`);
                el.style.animation = 'cardOut 0.3s ease forwards';
                setTimeout(() => el.remove(), 280);
            } else {
                shakeAlert('Minimal 2 skin untuk dibandingkan!');
            }
        }

        function shakeAlert(msg) {
            const existing = document.getElementById('shake-toast');
            if (existing) existing.remove();
            const toast = document.createElement('div');
            toast.id = 'shake-toast';
            toast.style.cssText = `
                position:fixed; bottom:28px; left:50%; transform:translateX(-50%);
                background:#1e2630; border:1px solid rgba(239,68,68,0.4);
                color:#f87171; font-family:'Syne',sans-serif; font-size:0.82rem;
                padding:10px 22px; border-radius:8px; z-index:9999;
                box-shadow:0 4px 20px rgba(0,0,0,0.4);
                animation:toastIn 0.3s ease both;
            `;
            toast.textContent = msg;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2500);
        }

        const toastStyle = document.createElement('style');
        toastStyle.textContent = `
            @keyframes cardOut { to { opacity:0; transform:translateY(10px); } }
            @keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(8px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }
        `;
        document.head.appendChild(toastStyle);

        async function prosesHitung(event) {
            event.preventDefault();

            const btn = document.getElementById('btn-hitung');
            const spinner = document.getElementById('spinner');
            const calcIcon = document.getElementById('calc-icon');
            btn.classList.add('loading');
            spinner.style.display = 'block';
            calcIcon.style.display = 'none';

            const rows = document.querySelectorAll('.class-skin-item');
            let payloadAlternatives = [];

            rows.forEach(row => {
                const nama = row.querySelector('input[name="nama_skin"]').value;
                let scores = {};
                daftarKriteria.forEach(k => {
                    const el = row.querySelector(`[name="kriteria_${k.id}"]`);
                    scores[k.id] = parseFloat(el.value);
                });
                payloadAlternatives.push({ name: nama, scores });
            });

            try {
                const response = await fetch('/api/hitung-rekomendasi', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ alternatives: payloadAlternatives })
                });
                const hasil = await response.json();

                if (hasil.status === 'success') {
                    tampilkanTabelHasil(hasil.rekomendasi);
                } else {
                    shakeAlert(hasil.message || 'Terjadi kesalahan sistem.');
                }
            } catch (err) {
                console.error(err);
                shakeAlert('Gagal menyambung ke server API Laravel.');
            } finally {
                btn.classList.remove('loading');
                spinner.style.display = 'none';
                calcIcon.style.display = 'block';
            }
        }

        function tampilkanTabelHasil(data) {
            const section = document.getElementById('section-hasil');
            const tbody = document.getElementById('tabel-hasil');
            
            tbody.innerHTML = '';
            data.forEach((item, index) => {
                const rank = index + 1;
                const isRank1 = rank === 1;
                
                const tr = document.createElement('tr');
                if (isRank1) tr.className = 'rank-1';
                
                const scoreClass = item.net_flow >= 0 ? 'score-positive' : 'score-negative';
                const formattedScore = (item.net_flow >= 0 ? '+' : '') + item.net_flow.toFixed(4);

                tr.innerHTML = `
                    <td class="${isRank1 ? 'td-rank-1' : 'td-rank'}">${rank}</td>
                    <td class="${isRank1 ? 'td-name-1' : 'td-name'}">
                        ${item.name}
                        ${isRank1 ? '<span class="trophy-badge">🏆 REKOMENDASI</span>' : ''}
                    </td>
                    <td class="td-flow">${item.leaving_flow.toFixed(4)}</td>
                    <td class="td-flow">${item.entering_flow.toFixed(4)}</td>
                    <td class="td-score ${scoreClass}">${formattedScore}</td>
                `;
                tbody.appendChild(tr);
            });
            
            section.className = 'visible';
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        // Pemicu inisialisasi awal agar form tidak kosong saat pertama dibuka
        document.addEventListener('DOMContentLoaded', () => {
            tambahBarisSkin();
            tambahBarisSkin();
        });
    </script>
</body>
</html>