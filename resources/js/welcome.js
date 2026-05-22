import './bootstrap';
import { confirmAction } from './confirm-modal';

window.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 0. GLITCH COLOR RANDOMIZER
    // ==========================================
    const glitchPalettes = [
        { c1: '#00ffff', s1: 'rgba(0,255,255,0.8)',   c2: '#ff0000', s2: 'rgba(255,0,0,0.8)'   },
        { c1: '#ff0000', s1: 'rgba(255,0,0,0.8)',     c2: '#aaaaaa', s2: 'rgba(170,170,170,0.8)' },
        { c1: '#aaaaaa', s1: 'rgba(170,170,170,0.8)', c2: '#00ffff', s2: 'rgba(0,255,255,0.8)' },
        { c1: '#00ffff', s1: 'rgba(0,255,255,0.8)',   c2: '#ff0000', s2: 'rgba(255,0,0,0.8)'   },
        { c1: '#ff0000', s1: 'rgba(255,0,0,0.8)',     c2: '#aaaaaa', s2: 'rgba(170,170,170,0.8)' },
        { c1: '#aaaaaa', s1: 'rgba(170,170,170,0.8)', c2: '#00ffff', s2: 'rgba(0,255,255,0.8)' },
    ];

    function randomizeGlitchColors() {
        const palette = glitchPalettes[Math.floor(Math.random() * glitchPalettes.length)];
        const root = document.documentElement;
        root.style.setProperty('--glitch-color-1',  palette.c1);
        root.style.setProperty('--glitch-shadow-1', palette.s1);
        root.style.setProperty('--glitch-color-2',  palette.c2);
        root.style.setProperty('--glitch-shadow-2', palette.s2);
    }

    randomizeGlitchColors();
    setInterval(randomizeGlitchColors, 6000);

    // ==========================================
    // 1. LOGIKA UTAMA CUSTOM BACKGROUND (DIATAS AGAR ANTI-MOGOK)
    // ==========================================
    const btnTriggerBg = document.getElementById('btn-trigger-bg');
    const inputCustomBg = document.getElementById('input-custom-bg');

    // Ambil dan pasang background jika ada di local storage browser
    const savedBg = localStorage.getItem('skin_decide_custom_bg');
    if (savedBg) {
        terapkanBackground(savedBg);
    }

    if (btnTriggerBg) {
        btnTriggerBg.addEventListener('click', (e) => {
            e.preventDefault(); 
            e.stopPropagation();
            
            // Mengambil element langsung saat diklik untuk memastikan DOM siap
            const inputLokal = document.getElementById('input-custom-bg');
            if (inputLokal) {
                inputLokal.click();
            }
        });
    }

    if (inputCustomBg) {
        inputCustomBg.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const base64Image = e.target.result;
                    localStorage.setItem('skin_decide_custom_bg', base64Image);
                    terapkanBackground(base64Image);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    function terapkanBackground(urlGambar) {
        document.body.style.backgroundImage = `linear-gradient(to bottom, rgba(9, 13, 18, 0.85), rgba(9, 13, 18, 0.95)), url('${urlGambar}')`;
    }

    // ==========================================
    // 2. LOGIKA HALAMAN UTAMA & PERHITUNGAN SPK
    // ==========================================
    const form = document.getElementById('spkForm');
    const addButton = document.getElementById('btn-add-skin');
    const clearSavedButton = document.getElementById('btn-clear-saved');
    const container = document.getElementById('container-alternatif');
    const sectionHasil = document.getElementById('section-hasil');

    // Jika element SPK di bawah ini tidak lengkap, abaikan sisa kode SPK tanpa merusak background
    if (!form || !addButton || !container || !sectionHasil) {
        return;
    }

    const daftarKriteria = getCriteriaDefinitions();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    let urutanSkin = 0;
    let saveTimeout = null;

    addButton.addEventListener('click', () => {
        tambahBarisSkin();
        schedulePersistWelcomeInputs();
    });

    clearSavedButton?.addEventListener('click', clearSavedInputs);
    form.addEventListener('submit', prosesHitung);
    container.addEventListener('click', handleContainerClick);
    container.addEventListener('input', schedulePersistWelcomeInputs);
    container.addEventListener('change', schedulePersistWelcomeInputs);

    hydrateSavedInputs();

    function getCriteriaDefinitions() {
        return parseDataset('criterias', []).map((criteria) => ({
            id: criteria.id,
            name: criteria.name,
            isHarga: criteria.name.toLowerCase().includes('harga'),
            isRarity: criteria.name.toLowerCase().includes('rarity') || criteria.name.toLowerCase().includes('kategori'),
            isPreferensi: criteria.name.toLowerCase().includes('preferensi'),
            isKetersediaan: criteria.name.toLowerCase().includes('ketersediaan'),
        }));
    }

    function getSavedInputs() {
        return parseDataset('savedInputs', { alternatives: [] });
    }

    function parseDataset(key, defaultValue) {
        const rawData = document.body?.dataset[key];

        if (!rawData) {
            return defaultValue;
        }

        try {
            return JSON.parse(rawData);
        } catch (error) {
            console.error(error);

            return defaultValue;
        }
    }

    function hydrateSavedInputs() {
        const savedAlternatives = getSavedInputs().alternatives ?? [];

        if (savedAlternatives.length > 0) {
            savedAlternatives.forEach((alternative) => tambahBarisSkin(alternative));

            return;
        }

        tambahBarisSkin();
        tambahBarisSkin();
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderOption(value, label, selectedValue) {
        const selected = String(selectedValue ?? '') === String(value) ? ' selected' : '';

        return `<option value="${value}"${selected}>${label}</option>`;
    }

    function valueAttribute(value) {
        if (value === null || value === undefined || value === '') {
            return '';
        }

        return ` value="${escapeHtml(value)}"`;
    }

    function tambahBarisSkin(savedAlternative = null) {
        urutanSkin += 1;

        const scores = savedAlternative?.scores ?? {};
        const card = document.createElement('div');
        card.className = 'skin-card class-skin-item';
        card.id = `skin-row-${urutanSkin}`;
        card.style.animationDelay = '0ms';

        let criteriaHTML = '';
        daftarKriteria.forEach((kriteria) => {
            const savedScore = scores[kriteria.id] ?? scores[String(kriteria.id)] ?? '';

            if (kriteria.isHarga) {
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <input type="number" min="0" required name="kriteria_${kriteria.id}" placeholder="Misal: 1089"
                                class="criteria-input"${valueAttribute(savedScore)} step="any">
                            <p class="hint-text">Gacha: estimasi pity (Zodiac ~1500 · Collector ~4000 · Aspirants ~5000 · Legend ~9000)</p>
                        </div>`;
            } else if (kriteria.isRarity) {
                const selectedValue = savedScore || 1;
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
                                ${renderOption(1, 'Common (Basic / Elite / Season)', selectedValue)}
                                ${renderOption(2, 'Exceptional (Special / Starlight Regular)', selectedValue)}
                                ${renderOption(3, 'Deluxe (Epic Shop / Epic Squad Series / Zodiac)', selectedValue)}
                                ${renderOption(4, 'Exquisite (Epic Limited / Collector / Lucky Box / Starlight Annual)', selectedValue)}
                                ${renderOption(5, 'Grand (Collab Anime/Movie, Aspirants, Exorcists, Mistbenders)', selectedValue)}
                                ${renderOption(6, 'Legend (Legend Magic Wheel / Legend Limited Event)', selectedValue)}
                            </select>
                        </div>`;
            } else if (kriteria.isPreferensi) {
                const selectedValue = savedScore || 4;
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
                                ${renderOption(1, 'Tidak Pernah Dipakai', selectedValue)}
                                ${renderOption(2, 'Sangat Jarang Dipakai', selectedValue)}
                                ${renderOption(3, 'Jarang Dipakai', selectedValue)}
                                ${renderOption(4, 'Kadang-kadang', selectedValue)}
                                ${renderOption(5, 'Sering Dipakai', selectedValue)}
                                ${renderOption(6, 'Sangat Sering Dipakai', selectedValue)}
                                ${renderOption(7, 'Hero Andalan Utama (Signature)', selectedValue)}
                            </select>
                        </div>`;
            } else if (kriteria.isKetersediaan) {
                const selectedValue = savedScore || 1;
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
                                ${renderOption(1, 'Dapat Dibeli Kapan Saja di Shop', selectedValue)}
                                ${renderOption(2, 'Hanya Bisa Dibeli Saat Event Berlangsung (Limited)', selectedValue)}
                            </select>
                        </div>`;
            } else {
                const selectedValue = savedScore || 4;
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
                                ${renderOption(1, 'Sangat Kurang', selectedValue)}
                                ${renderOption(2, 'Kurang', selectedValue)}
                                ${renderOption(3, 'Agak Kurang', selectedValue)}
                                ${renderOption(4, 'Standar', selectedValue)}
                                ${renderOption(5, 'Lumayan Bagus', selectedValue)}
                                ${renderOption(6, 'Bagus', selectedValue)}
                                ${renderOption(7, 'Sangat Bagus', selectedValue)}
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
                <button type="button" class="btn-hapus" data-action="remove-skin" data-skin-id="${urutanSkin}">✕ Hapus</button>
                <div class="skin-name-section">
                    <label>Nama / Varian Skin</label>
                    <input type="text" required name="nama_skin" placeholder="Misal: Gusion Cosmic Gleam"
                        class="input-name"${valueAttribute(savedAlternative?.name)}>
                </div>
                <div class="criteria-divider">
                    <div class="criteria-grid">${criteriaHTML}</div>
                </div>`;

        container.appendChild(card);
    }

    function handleContainerClick(event) {
        const removeButton = event.target.closest('[data-action="remove-skin"]');
        if (!removeButton) return;
        hapusBarisSkin(removeButton.dataset.skinId);
    }

    function hapusBarisSkin(id) {
        const total = document.querySelectorAll('.class-skin-item').length;
        if (total <= 2) {
            shakeAlert('Minimal 2 skin untuk dibandingkan!');
            return;
        }

        const element = document.getElementById(`skin-row-${id}`);
        if (!element) return;

        element.classList.add('is-removing');
        window.setTimeout(() => {
            element.remove();
            schedulePersistWelcomeInputs();
        }, 280);
    }

    function collectWelcomeInputs() {
        return Array.from(document.querySelectorAll('.class-skin-item')).map((row) => {
            const nama = row.querySelector('input[name="nama_skin"]')?.value ?? '';
            const scores = {};

            daftarKriteria.forEach((kriteria) => {
                const input = row.querySelector(`[name="kriteria_${kriteria.id}"]`);
                scores[kriteria.id] = input?.value ?? '';
            });

            return { name: nama, scores };
        });
    }

    function schedulePersistWelcomeInputs() {
        window.clearTimeout(saveTimeout);
        saveTimeout = window.setTimeout(() => {
            persistWelcomeInputs();
        }, 350);
    }

    async function persistWelcomeInputs(showError = false) {
        try {
            await fetch('/skin-inputs', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ alternatives: collectWelcomeInputs() }),
            });
        } catch (error) {
            console.error(error);

            if (showError) {
                shakeAlert('Input belum bisa disimpan ke sesi.');
            }
        }
    }

    async function clearSavedInputs() {
        const confirmed = await confirmAction({
            title: 'Hapus Input Tersimpan?',
            message: 'Semua input skin yang tersimpan di sesi browser ini akan dihapus dan form akan dibuat ulang dari awal.',
            confirmText: 'Ya, hapus input',
            cancelText: 'Batal',
        });

        if (!confirmed) {
            return;
        }

        try {
            await fetch('/skin-inputs', {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            container.innerHTML = '';
            sectionHasil.classList.remove('visible');
            urutanSkin = 0;
            tambahBarisSkin();
            tambahBarisSkin();
            shakeAlert('Input tersimpan sudah dihapus.');
        } catch (error) {
            console.error(error);
            shakeAlert('Gagal menghapus input tersimpan.');
        }
    }

    function shakeAlert(message) {
        const existing = document.getElementById('shake-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'shake-toast';
        toast.className = 'shake-toast';
        toast.textContent = message;
        document.body.appendChild(toast);

        window.setTimeout(() => toast.remove(), 2500);
    }

    async function prosesHitung(event) {
        event.preventDefault();

        const btn = document.getElementById('btn-hitung');
        const spinner = document.getElementById('spinner');
        const calcIcon = document.getElementById('calc-icon');

        if (!btn || !spinner || !calcIcon) return;

        btn.classList.add('loading');
        spinner.style.display = 'block';
        calcIcon.style.display = 'none';

        const rows = document.querySelectorAll('.class-skin-item');
        const payloadAlternatives = [];

        rows.forEach((row) => {
            const nama = row.querySelector('input[name="nama_skin"]')?.value ?? '';
            const scores = {};

            daftarKriteria.forEach((kriteria) => {
                const input = row.querySelector(`[name="kriteria_${kriteria.id}"]`);
                scores[kriteria.id] = parseFloat(input?.value ?? '0');
            });

            payloadAlternatives.push({ name: nama, scores });
        });

        try {
            await persistWelcomeInputs(true);

            const response = await fetch('/api/hitung-rekomendasi', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ alternatives: payloadAlternatives }),
            });
            const hasil = await response.json();

            if (hasil.status === 'success') {
                tampilkanTabelHasil(hasil.rekomendasi);
            } else {
                shakeAlert(hasil.message || 'Terjadi kesalahan sistem.');
            }
        } catch (error) {
            console.error(error);
            shakeAlert('Gagal menyambung ke server API Laravel.');
        } finally {
            btn.classList.remove('loading');
            spinner.style.display = 'none';
            calcIcon.style.display = 'block';
        }
    }

    function tampilkanTabelHasil(data) {
        const tbody = document.getElementById('tabel-hasil');
        if (!tbody) return;

        tbody.innerHTML = '';
        if (data.length === 0) return;

        const maxNetFlow = data[0].net_flow;
        const isTie = data.filter((item) => Math.abs(item.net_flow - maxNetFlow) < 0.0001).length > 1;

        data.forEach((item, index) => {
            const isTop = Math.abs(item.net_flow - maxNetFlow) < 0.0001;
            const rank = isTop ? 1 : index + 1;
            const scoreClass = item.net_flow >= 0 ? 'score-positive' : 'score-negative';
            const formattedScore = `${item.net_flow >= 0 ? '+' : ''}${item.net_flow.toFixed(4)}`;

            let badgeHtml = '';
            if (isTop) {
                badgeHtml = isTie
                    ? '<span class="tie-badge">🤝 SERI (REKOMENDASI)</span>'
                    : '<span class="trophy-badge">🏆 REKOMENDASI</span>';
            }

            const row = document.createElement('tr');
            row.className = isTop ? 'rank-1' : '';
            row.innerHTML = `
                    <td class="${isTop ? 'td-rank-1' : 'td-rank'}">${rank}</td>
                    <td class="${isTop ? 'td-name-1' : 'td-name'}">
                        ${escapeHtml(item.name)}
                        ${badgeHtml}
                    </td>
                    <td class="td-flow">${item.leaving_flow.toFixed(4)}</td>
                    <td class="td-flow">${item.entering_flow.toFixed(4)}</td>
                    <td class="td-score ${scoreClass}">${formattedScore}</td>
                `;
            tbody.appendChild(row);
        });

        sectionHasil.classList.add('visible');
        sectionHasil.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});