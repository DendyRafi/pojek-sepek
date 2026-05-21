import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    
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
    const container = document.getElementById('container-alternatif');
    const sectionHasil = document.getElementById('section-hasil');

    // Jika element SPK di bawah ini tidak lengkap, abaikan sisa kode SPK tanpa merusak background
    if (!form || !addButton || !container || !sectionHasil) {
        return;
    }

    const daftarKriteria = getCriteriaDefinitions();
    let urutanSkin = 0;

    addButton.addEventListener('click', () => {
        tambahBarisSkin();
    });

    form.addEventListener('submit', prosesHitung);
    container.addEventListener('click', handleContainerClick);

    tambahBarisSkin();
    tambahBarisSkin();

    function getCriteriaDefinitions() {
        const rawData = document.body?.dataset.criterias;

        if (!rawData) {
            return [];
        }

        try {
            return JSON.parse(rawData).map((criteria) => ({
                id: criteria.id,
                name: criteria.name,
                isHarga: criteria.name.toLowerCase().includes('harga'),
                isRarity: criteria.name.toLowerCase().includes('rarity') || criteria.name.toLowerCase().includes('kategori'),
                isPreferensi: criteria.name.toLowerCase().includes('preferensi'),
                isKetersediaan: criteria.name.toLowerCase().includes('ketersediaan'),
            }));
        } catch (error) {
            console.error(error);
            return [];
        }
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function tambahBarisSkin() {
        urutanSkin += 1;

        const card = document.createElement('div');
        card.className = 'skin-card class-skin-item';
        card.id = `skin-row-${urutanSkin}`;
        card.style.animationDelay = '0ms';

        let criteriaHTML = '';
        daftarKriteria.forEach((kriteria) => {
            if (kriteria.isHarga) {
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <input type="number" required name="kriteria_${kriteria.id}" placeholder="Misal: 1089"
                                class="criteria-input">
                            <p class="hint-text">Gacha: estimasi pity (Zodiac ~1500 · Collector ~4000 · Aspirants ~5000 · Legend ~9000)</p>
                        </div>`;
            } else if (kriteria.isRarity) {
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
                                <option value="1" selected>1 — Common (Basic / Elite / Season)</option>
                                <option value="2">2 — Exceptional (Special / Starlight Regular)</option>
                                <option value="3">3 — Deluxe (Epic Shop / Epic Squad Series / Zodiac)</option>
                                <option value="4">4 — Exquisite (Epic Limited / Collector / Lucky Box / Starlight Annual)</option>
                                <option value="5">5 — Grand (Collab Anime/Movie, Aspirants, Exorcists, Mistbenders)</option>
                                <option value="6">6 — Legend (Legend Magic Wheel / Legend Limited Event)</option>
                            </select>
                        </div>`;
            } else if (kriteria.isPreferensi) {
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
                                <option value="1">1 — Tidak Pernah Dipakai</option>
                                <option value="2">2 — Sangat Jarang Dipakai</option>
                                <option value="3">3 — Jarang Dipakai</option>
                                <option value="4" selected>4 — Kadang-kadang</option>
                                <option value="5">5 — Sering Dipakai</option>
                                <option value="6">6 — Sangat Sering Dipakai</option>
                                <option value="7">7 — Hero Andalan Utama (Signature)</option>
                            </select>
                        </div>`;
            } else if (kriteria.isKetersediaan) {
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
                                <option value="1" selected>Dapat Dibeli Kapan Saja di Shop</option>
                                <option value="2">Hanya Bisa Dibeli Saat Event Berlangsung (Limited)</option>
                            </select>
                        </div>`;
            } else {
                criteriaHTML += `
                        <div class="criteria-item">
                            <label>${kriteria.name}</label>
                            <select name="kriteria_${kriteria.id}" class="criteria-select">
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
                <button type="button" class="btn-hapus" data-action="remove-skin" data-skin-id="${urutanSkin}">✕ Hapus</button>
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
        window.setTimeout(() => element.remove(), 280);
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