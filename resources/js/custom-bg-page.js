import './bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    const btnPilih = document.getElementById('btn-pilih-foto');
    const btnReset = document.getElementById('btn-reset-bg');
    const fileInput = document.getElementById('file-bg-input');
    const previewBox = document.getElementById('bg-preview-box');
    const messageBox = document.getElementById('custom-bg-message');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
    const uploadUrl = document.body?.dataset.customBackgroundUploadUrl ?? '/custom-background/upload';
    const resetUrl = document.body?.dataset.customBackgroundResetUrl ?? '/custom-background/reset';

    loadCurrentBackground(document.body?.dataset.customBackgroundUrl);

    if (btnPilih && fileInput) {
        btnPilih.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', async (event) => {
            const file = event.target.files[0];

            if (!file) {
                return;
            }

            try {
                setBusy(true);

                const result = await uploadBackground(file);

                terapkanSistemBackground(result.url);
                showMessage(result.message ?? 'Background global berhasil disimpan.', 'success');
            } catch (error) {
                showMessage(error.message, 'error');
            } finally {
                fileInput.value = '';
                setBusy(false);
            }
        });
    }

    async function uploadBackground(file) {
        let lastErrorMessage = 'Gagal menyimpan background.';

        for (const endpoint of uniqueEndpoints([uploadUrl, '/custom-background'])) {
            const formData = new FormData();
            formData.append('background', file);

            const response = await fetch(endpoint, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            });
            const result = await parseJsonResponse(response);

            if (response.ok) {
                return result;
            }

            lastErrorMessage = result.message ?? `Endpoint upload ${endpoint} tidak tersedia.`;

            if (![404, 405].includes(response.status)) {
                break;
            }
        }

        throw new Error(lastErrorMessage);
    }

    function uniqueEndpoints(endpoints) {
        return [...new Set(endpoints.filter(Boolean))];
    }

    if (btnReset) {
        btnReset.addEventListener('click', async () => {
            try {
                setBusy(true);

                const response = await fetch(resetUrl, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });
                const result = await parseJsonResponse(response);

                if (!response.ok) {
                    throw new Error(result.message ?? 'Gagal reset background.');
                }

                resetBackground();
                showMessage(result.message ?? 'Background global berhasil dikembalikan ke default.', 'success');
            } catch (error) {
                showMessage(error.message, 'error');
            } finally {
                setBusy(false);
            }
        });
    }

    function terapkanSistemBackground(imageUrl) {
        if (!imageUrl) {
            resetBackground();

            return;
        }

        document.body.dataset.customBackgroundUrl = imageUrl;
        document.body.style.backgroundImage = `linear-gradient(to bottom, rgba(9, 13, 18, 0.85), rgba(9, 13, 18, 0.95)), url('${imageUrl}')`;
        
        if (previewBox) {
            previewBox.textContent = '';
            previewBox.style.backgroundImage = `url('${imageUrl}')`;
        }
    }

    function loadCurrentBackground(imageUrl) {
        if (imageUrl) {
            terapkanSistemBackground(imageUrl);
        }
    }

    function resetBackground() {
        document.body.dataset.customBackgroundUrl = '';
        document.body.style.backgroundImage = '';

        if (previewBox) {
            previewBox.style.backgroundImage = '';
            previewBox.textContent = 'Belum ada gambar kustom (Menggunakan Default)';
        }
    }

    async function parseJsonResponse(response) {
        try {
            return await response.json();
        } catch (error) {
            console.error(error);

            return {};
        }
    }

    function setBusy(isBusy) {
        if (btnPilih) {
            btnPilih.disabled = isBusy;
        }

        if (btnReset) {
            btnReset.disabled = isBusy;
        }
    }

    function showMessage(message, type) {
        if (!messageBox) {
            return;
        }

        messageBox.textContent = message;
        messageBox.style.display = 'block';
        messageBox.style.border = type === 'error' ? '1px solid rgba(239, 68, 68, 0.35)' : '1px solid rgba(130, 205, 39, 0.35)';
        messageBox.style.color = type === 'error' ? '#fecaca' : '#d8ff9d';
        messageBox.style.background = type === 'error' ? 'rgba(239, 68, 68, 0.12)' : 'rgba(130, 205, 39, 0.12)';
    }
});
