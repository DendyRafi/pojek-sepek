import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    
    // =========================================================================
    // 1. SINKRONISASI CUSTOM BACKGROUND DARI LOCALSTORAGE
    // =========================================================================
    const savedBg = localStorage.getItem('skin_decide_custom_bg');
    if (savedBg) {
        document.body.style.backgroundImage = `linear-gradient(to bottom, rgba(9, 13, 18, 0.85), rgba(9, 13, 18, 0.95)), url('${savedBg}')`;
    }

    // =========================================================================
    // 2. LOGIKA BAWAAN HALAMAN PENGATURAN KRITERIA
    // =========================================================================
    const deleteForm = document.getElementById('delete-form');

    if (!deleteForm) {
        return;
    }

    const toast = document.querySelector('#toast-container .toast');

    if (toast) {
        window.setTimeout(() => {
            toast.classList.add('is-dismissing');
            window.setTimeout(() => toast.remove(), 500);
        }, 3000);
    }

    document.querySelectorAll('.preference-select').forEach((selectElement) => {
        const criteriaId = selectElement.dataset.criteriaId;

        selectElement.addEventListener('change', () => {
            toggleParams(selectElement, criteriaId);
        });

        toggleParams(selectElement, criteriaId);
    });

    document.querySelectorAll('[data-delete-id]').forEach((button) => {
        button.addEventListener('click', () => {
            confirmDelete(button.dataset.deleteId, button.dataset.deleteName ?? '');
        });
    });
});

function toggleParams(selectElement, id) {
    const val = selectElement.value;
    const container = document.getElementById(`params-container-${id}`);
    const pField = document.getElementById(`p-field-${id}`);
    const qField = document.getElementById(`q-field-${id}`);
    const sField = document.getElementById(`s-field-${id}`);

    if (!container || !pField || !qField || !sField) {
        return;
    }

    if (val === 'usual') {
        container.style.display = 'none';
    } else {
        container.style.display = 'block';

        if (val === 'linear') {
            pField.style.display = 'block';
            qField.style.display = 'none';
            sField.style.display = 'none';
        } else if (val === 'quasi') {
            pField.style.display = 'none';
            qField.style.display = 'block';
            sField.style.display = 'none';
        } else if (val === 'linear_quasi' || val === 'level') {
            pField.style.display = 'block';
            qField.style.display = 'block';
            sField.style.display = 'none';
        } else if (val === 'gaussian') {
            pField.style.display = 'none';
            qField.style.display = 'none';
            sField.style.display = 'block';
        }
    }
}

function confirmDelete(id, name) {
    if (!window.confirm(`Apakah Anda yakin ingin menghapus kriteria "${name}"? Tindakan ini tidak dapat dibatalkan.`)) {
        return;
    }

    const form = document.getElementById('delete-form');

    if (!form) {
        return;
    }

    form.action = `/kriteria/${id}`;
    form.submit();
}