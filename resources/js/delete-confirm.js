// Shows one shared Bootstrap modal for any form that has a data-confirm attribute.
// Works for rows added later by the live search, because it listens on the document.
import { Modal } from 'bootstrap';

const modalElement = document.getElementById('confirmModal');

if (modalElement) {
    const modal = new Modal(modalElement);
    const messageBox = modalElement.querySelector('[data-confirm-message]');
    const confirmButton = modalElement.querySelector('[data-confirm-ok]');
    let pendingForm = null;

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) return;

        event.preventDefault();
        pendingForm = form;
        messageBox.textContent = form.dataset.confirm;
        modal.show();
    });

    confirmButton.addEventListener('click', () => {
        if (!pendingForm) return;
        modal.hide();
        pendingForm.submit(); // form.submit() does not fire the submit event again
        pendingForm = null;
    });
}
