<div class="delete-confirm-overlay" id="deleteConfirmModal" aria-hidden="true">
    <section
        class="delete-confirm-dialog"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="deleteConfirmTitle"
        aria-describedby="deleteConfirmMessage deleteConfirmNote"
    >
        <div class="delete-confirm-icon" aria-hidden="true">
            <i class="fa-solid fa-trash"></i>
        </div>
        <h2 class="delete-confirm-title" id="deleteConfirmTitle">Hapus data?</h2>
        <p class="delete-confirm-message" id="deleteConfirmMessage"></p>
        <p class="delete-confirm-note" id="deleteConfirmNote">
            Tindakan ini tidak dapat dibatalkan.
        </p>
        <div class="delete-confirm-actions">
            <button type="button" class="delete-confirm-button delete-confirm-cancel" id="deleteConfirmCancel">
                Batal
            </button>
            <button type="button" class="delete-confirm-button delete-confirm-submit" id="deleteConfirmSubmit">
                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                Ya, hapus
            </button>
        </div>
    </section>
</div>

<script>
    (() => {
        const modal = document.getElementById('deleteConfirmModal');
        const message = document.getElementById('deleteConfirmMessage');
        const cancelButton = document.getElementById('deleteConfirmCancel');
        const confirmButton = document.getElementById('deleteConfirmSubmit');
        let pendingForm = null;
        let returnFocus = null;

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            pendingForm = null;

            if (returnFocus instanceof HTMLElement && returnFocus.isConnected) {
                returnFocus.focus();
            }
        }

        document.addEventListener('submit', (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm-delete')) {
                return;
            }

            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return;
            }

            event.preventDefault();
            pendingForm = form;
            returnFocus = document.activeElement;
            message.textContent = form.dataset.confirmMessage;
            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            cancelButton.focus();
        }, true);

        cancelButton.addEventListener('click', closeModal);

        confirmButton.addEventListener('click', () => {
            if (!pendingForm) {
                return;
            }

            const form = pendingForm;
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            pendingForm = null;
            form.dataset.confirmed = 'true';
            form.requestSubmit();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });

        modal.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                event.preventDefault();
                closeModal();
                return;
            }

            if (event.key === 'Tab') {
                const nextButton = event.shiftKey ? cancelButton : confirmButton;
                const previousButton = event.shiftKey ? confirmButton : cancelButton;

                if (document.activeElement === nextButton) {
                    event.preventDefault();
                    previousButton.focus();
                }
            }
        });
    })();
</script>
