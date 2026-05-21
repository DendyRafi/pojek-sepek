import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    const btnPilih = document.getElementById('btn-pilih-foto');
    const btnReset = document.getElementById('btn-reset-bg');
    const fileInput = document.getElementById('file-bg-input');
    const previewBox = document.getElementById('bg-preview-box');

    // Load background yang ada saat halaman dimuat
    loadCurrentBackground();

    if (btnPilih && fileInput) {
        btnPilih.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const base64Data = event.target.result;
                    
                    // Simpan ke localStorage global
                    localStorage.setItem('skin_decide_custom_bg', base64Data);
                    
                    // Perbarui tampilan halaman instan
                    terapkanSistemBackground(base64Data);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', () => {
            localStorage.removeItem('skin_decide_custom_bg');
            document.body.style.backgroundImage = '';
            if (previewBox) {
                previewBox.style.backgroundImage = '';
                previewBox.textContent = 'Belum ada gambar kustom (Menggunakan Default)';
            }
        });
    }

    function terapkanSistemBackground(imgData) {
        // Terapkan ke background body halaman saat ini
        document.body.style.backgroundImage = `linear-gradient(to bottom, rgba(9, 13, 18, 0.85), rgba(9, 13, 18, 0.95)), url('${imgData}')`;
        
        // Terapkan ke kotak preview di tengah card tanpa lapisan hitam pekat agar gambarnya terlihat jelas
        if (previewBox) {
            previewBox.textContent = '';
            previewBox.style.backgroundImage = `url('${imgData}')`;
        }
    }

    function loadCurrentBackground() {
        const saved = localStorage.getItem('skin_decide_custom_bg');
        if (saved) {
            terapkanSistemBackground(saved);
        }
    }
});