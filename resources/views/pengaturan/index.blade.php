<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Kriteria - SkinDecide</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Syne:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/pengaturan.css', 'resources/js/pengaturan.js'])
</head>

<body style="{{ $customBackgroundStyle }}" data-custom-background-url="{{ $customBackgroundUrl }}">

    <header>
        <a href="/" class="logo">
            <div class="logo-dot"></div>
            <span class="logo-skin">SKIN</span><span class="logo-decide">DECIDE</span>
        </a>
        <div class="nav-actions">
            <span class="admin-chip">Admin: {{ auth()->user()->name }}</span>
            <a href="{{ route('admin.password.edit') }}" class="nav-link">Reset Password</a>
            <a href="{{ route('custom-background') }}" class="nav-link">
                Custom Background
            </a>
            <a href="/" class="nav-link">← Halaman Utama</a>
            <form action="{{ route('logout') }}" method="POST" class="nav-form">
                @csrf
                <button type="submit" class="nav-link nav-button">Logout</button>
            </form>
        </div>
    </header>

    <main>
        <div class="app-container">

            <div class="page-header">
                <div class="label">SkinDecide - Konfigurasi Sistem</div>
                <h1>Pengaturan <span>Kriteria</span></h1>
                <p>Sesuaikan tipe kriteria, bobot kepentingan, serta tipe fungsi preferensi PROMETHEE beserta batas threshold ($p, q, s$).</p>
            </div>

            @if(session('success'))
            <div id="toast-container">
                <div class="toast toast-success">
                    {{ session('success') }}
                </div>
            </div>
            @endif

            @if($errors->any())
            <div id="toast-container">
                <div class="toast toast-error">
                    {{ $errors->first() }}
                </div>
            </div>
            @endif

            <form id="delete-form" class="delete-form" method="POST">
                @csrf
                @method('DELETE')
            </form>

            <div class="admin-panel-card">
                <div>
                    <h2>Kontrol Admin</h2>
                    <p>Gunakan reset untuk mengembalikan daftar kriteria ke nilai awal sistem.</p>
                </div>
                <form action="{{ route('pengaturan.reset') }}" method="POST" data-confirm-reset>
                    @csrf
                    <button type="submit" class="btn-action btn-danger p-1" style="min-width: 120px;">Reset Kriteria Semula</button>
                </form>
            </div>

            <div class="section-separator">
                <h2>Daftar <span>Kriteria</span> Aktif</h2>
                <div class="section-separator-line"></div>
            </div>

            <div class="criteria-list-grid">
                @foreach($criterias as $criteria)
                <div class="criteria-card" id="card-{{ $criteria->id }}">
                    <div class="corner-deco corner-deco-tl"></div>
                    <div class="corner-deco corner-deco-tr"></div>
                    <div class="corner-deco corner-deco-bl"></div>
                    <div class="corner-deco corner-deco-br"></div>

                    <form action="{{ route('pengaturan.update', $criteria->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="criteria-card-title">
                            <span>{{ $criteria->name }}</span>
                            <span class="badge {{ $criteria->type }}">
                                {{ $criteria->type === 'maximize' ? 'Maximize (▲)' : 'Minimize (▼)' }}
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nama Kriteria</label>
                            <input type="text" name="name" value="{{ old('name', $criteria->name) }}" required class="form-input">
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label class="form-label">Tipe Optimasi</label>
                                <select name="type" class="form-select">
                                    <option value="maximize" {{ $criteria->type === 'maximize' ? 'selected' : '' }}>Maximize</option>
                                    <option value="minimize" {{ $criteria->type === 'minimize' ? 'selected' : '' }}>Minimize</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Bobot (Weight)</label>
                                <input type="number" step="0.01" name="weight" value="{{ old('weight', $criteria->weight) }}" required class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Fungsi Preferensi</label>
                            <select name="preference_function" class="form-select preference-select" data-criteria-id="{{ $criteria->id }}">
                                <option value="usual" {{ $criteria->preference_function === 'usual' ? 'selected' : '' }}>Tipe I — Usual (Biasa)</option>
                                <option value="linear" {{ $criteria->preference_function === 'linear' ? 'selected' : '' }}>Tipe II — Linear (V-Shape)</option>
                                <option value="quasi" {{ $criteria->preference_function === 'quasi' ? 'selected' : '' }}>Tipe III — Quasi (U-Shape)</option>
                                <option value="linear_quasi" {{ $criteria->preference_function === 'linear_quasi' ? 'selected' : '' }}>Tipe IV — Linear Quasi (V-Shape Indifference)</option>
                                <option value="level" {{ $criteria->preference_function === 'level' ? 'selected' : '' }}>Tipe V — Level (Tingkat)</option>
                                <option value="gaussian" {{ $criteria->preference_function === 'gaussian' ? 'selected' : '' }}>Tipe VI — Gaussian</option>
                            </select>
                        </div>

                        <!-- Parameters dynamic block -->
                        <div class="parameters-container" id="params-container-{{ $criteria->id }}">
                            <div class="parameters-title">Parameter Threshold</div>

                            <div class="form-row-2">
                                <div class="form-group param-field-p" id="p-field-{{ $criteria->id }}">
                                    <label class="form-label">Preference (p)</label>
                                    <input type="number" step="any" name="p" value="{{ old('p', $criteria->p) }}" class="form-input">
                                    <p class="parameter-desc">Selisih minimum untuk preferensi mutlak.</p>
                                </div>
                                <div class="form-group param-field-q" id="q-field-{{ $criteria->id }}">
                                    <label class="form-label">Indifference (q)</label>
                                    <input type="number" step="any" name="q" value="{{ old('q', $criteria->q) }}" class="form-input">
                                    <p class="parameter-desc">Selisih maksimum di mana tidak ada perbedaan.</p>
                                </div>
                            </div>

                            <div class="form-group param-field-s" id="s-field-{{ $criteria->id }}">
                                <label class="form-label">Gaussian (s)</label>
                                <input type="number" step="any" name="s" value="{{ old('s', $criteria->s) }}" class="form-input">
                                <p class="parameter-desc">Parameter s (standar deviasi deviasi gaussian).</p>
                            </div>
                        </div>

                        <div class="btn-container">
                            <button type="submit" class="btn-action btn-update">Simpan</button>
                            <button type="button" class="btn-action btn-danger" data-delete-id="{{ $criteria->id }}" data-delete-name="{{ $criteria->name }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>

                    </form>
                </div>
                @endforeach
            </div>

            <div class="section-separator">
                <h2>Tambah <span>Kriteria Baru</span></h2>
                <div class="section-separator-line"></div>
            </div>

            <div class="new-criteria-card">
                <div class="corner-deco corner-deco-tl"></div>
                <div class="corner-deco corner-deco-tr"></div>
                <div class="corner-deco corner-deco-bl"></div>
                <div class="corner-deco corner-deco-br"></div>

                <form action="{{ route('pengaturan.store') }}" method="POST">
                    @csrf

                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Nama Kriteria Baru</label>
                            <input type="text" name="name" placeholder="Misal: Efek Suara Skin" required value="{{ old('name') }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe Optimasi</label>
                            <select name="type" class="form-select">
                                <option value="maximize">Maximize</option>
                                <option value="minimize">Minimize</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label class="form-label">Bobot (Weight)</label>
                            <input type="number" step="0.01" name="weight" placeholder="1.0" required value="{{ old('weight') }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Fungsi Preferensi</label>
                            <select name="preference_function" class="form-select preference-select" data-criteria-id="new">
                                <option value="usual" selected>Tipe I — Usual (Biasa)</option>
                                <option value="linear">Tipe II — Linear (V-Shape)</option>
                                <option value="quasi">Tipe III — Quasi (U-Shape)</option>
                                <option value="linear_quasi">Tipe IV — Linear Quasi (V-Shape Indifference)</option>
                                <option value="level">Tipe V — Level (Tingkat)</option>
                                <option value="gaussian">Tipe VI — Gaussian</option>
                            </select>
                        </div>
                    </div>

                    <!-- Parameters dynamic block for new criteria -->
                    <div class="parameters-container" id="params-container-new">
                        <div class="parameters-title">Parameter Threshold</div>

                        <div class="form-row-2">
                            <div class="form-group param-field-p" id="p-field-new">
                                <label class="form-label">Preference (p)</label>
                                <input type="number" step="any" name="p" placeholder="0" class="form-input">
                                <p class="parameter-desc">Selisih minimum untuk preferensi mutlak.</p>
                            </div>
                            <div class="form-group param-field-q" id="q-field-new">
                                <label class="form-label">Indifference (q)</label>
                                <input type="number" step="any" name="q" placeholder="0" class="form-input">
                                <p class="parameter-desc">Selisih maksimum di mana tidak ada perbedaan.</p>
                            </div>
                        </div>

                        <div class="form-group param-field-s" id="s-field-new">
                            <label class="form-label">Gaussian (s)</label>
                            <input type="number" step="any" name="s" placeholder="0" class="form-input">
                            <p class="parameter-desc">Parameter s (standar deviasi deviasi gaussian).</p>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                        </svg>
                        Tambahkan Kriteria Baru
                    </button>
                </form>
            </div>

        </div>
    </main>

    <footer>
        <div class="footer-brand"><span>SKIN</span>DECIDE</div>
        <div class="footer-copy">&copy; 2026 Promethee Team</div>
    </footer>
</body>

</html>
