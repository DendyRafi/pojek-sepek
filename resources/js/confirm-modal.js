let activeConfirmation = null;

export function confirmAction({
    title,
    message,
    confirmText = 'Ya, lanjutkan',
    cancelText = 'Batal',
    intent = 'danger',
}) {
    if (!document.body) {
        return Promise.resolve(false);
    }

    if (activeConfirmation) {
        activeConfirmation.resolve(false);
    }

    const modal = getConfirmationModal();
    const previousFocus = document.activeElement;

    modal.title.textContent = title;
    modal.message.textContent = message;
    modal.confirmButton.textContent = confirmText;
    modal.cancelButton.textContent = cancelText;
    modal.backdrop.classList.toggle('confirm-modal-danger', intent === 'danger');
    modal.backdrop.hidden = false;
    modal.backdrop.classList.remove('is-hidden');

    document.body.classList.add('confirm-modal-open');
    modal.cancelButton.focus();

    return new Promise((resolve) => {
        const finish = (confirmed) => {
            modal.backdrop.classList.add('is-hidden');
            modal.backdrop.hidden = true;
            document.body.classList.remove('confirm-modal-open');

            modal.confirmButton.removeEventListener('click', confirmHandler);
            modal.cancelButton.removeEventListener('click', cancelHandler);
            document.removeEventListener('keydown', keydownHandler);

            activeConfirmation = null;

            if (previousFocus && typeof previousFocus.focus === 'function') {
                previousFocus.focus();
            }

            resolve(confirmed);
        };

        const confirmHandler = () => finish(true);
        const cancelHandler = () => finish(false);
        const keydownHandler = (event) => {
            if (event.key === 'Escape') {
                finish(false);
            }
        };

        activeConfirmation = { resolve: finish };

        modal.confirmButton.addEventListener('click', confirmHandler);
        modal.cancelButton.addEventListener('click', cancelHandler);
        document.addEventListener('keydown', keydownHandler);
    });
}

function getConfirmationModal() {
    const existing = document.getElementById('confirm-modal');

    if (existing) {
        return getConfirmationParts(existing);
    }

    const backdrop = document.createElement('div');
    backdrop.id = 'confirm-modal';
    backdrop.className = 'confirm-modal-backdrop is-hidden';
    backdrop.hidden = true;
    backdrop.innerHTML = `
        <div class="confirm-modal-panel" role="dialog" aria-modal="true" aria-labelledby="confirm-modal-title" aria-describedby="confirm-modal-message">
            <div class="confirm-modal-corner confirm-modal-corner-tl"></div>
            <div class="confirm-modal-corner confirm-modal-corner-tr"></div>
            <div class="confirm-modal-corner confirm-modal-corner-bl"></div>
            <div class="confirm-modal-corner confirm-modal-corner-br"></div>

            <div class="confirm-modal-kicker">Konfirmasi Aksi</div>
            <h2 id="confirm-modal-title" class="confirm-modal-title"></h2>
            <p id="confirm-modal-message" class="confirm-modal-message"></p>

            <div class="confirm-modal-actions">
                <button type="button" class="confirm-modal-button confirm-modal-button-cancel" data-confirm-cancel></button>
                <button type="button" class="confirm-modal-button confirm-modal-button-confirm" data-confirm-accept></button>
            </div>
        </div>
    `;

    document.body.appendChild(backdrop);

    return getConfirmationParts(backdrop);
}

function getConfirmationParts(backdrop) {
    return {
        backdrop,
        title: backdrop.querySelector('#confirm-modal-title'),
        message: backdrop.querySelector('#confirm-modal-message'),
        confirmButton: backdrop.querySelector('[data-confirm-accept]'),
        cancelButton: backdrop.querySelector('[data-confirm-cancel]'),
    };
}
