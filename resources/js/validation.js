// Client-side validation. Gives instant feedback only.
// The server (Laravel Form Requests, Module 7) always validates again,
// because anyone can bypass JavaScript.
//
// Usage in Blade:
//   <form data-validate-form> ... <x-form.input data-validate="required|email" />
//   File inputs: data-validate="file" data-types="jpg,jpeg,png" data-max-kb="2048"

const rules = {
    required: (value, el) =>
        (el.type === 'file' ? el.files.length > 0 : value.trim() !== '') || 'This field is required.',

    email: (value) =>
        value === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value) || 'Enter a valid email address.',

    phone: (value) =>
        value === '' || /^[0-9]{10}$/.test(value) || 'Enter a 10-digit phone number.',

    number: (value) =>
        value === '' || (!isNaN(value) && Number(value) >= 0) || 'Enter a valid positive number.',

    file: (value, el) => {
        if (el.files.length === 0) return true; // optional unless "required" is also set
        const file = el.files[0];
        const types = (el.dataset.types || 'jpg,jpeg,png').split(',');
        const extension = file.name.split('.').pop().toLowerCase();
        if (!types.includes(extension)) return `Allowed file types: ${types.join(', ')}.`;
        const maxKb = Number(el.dataset.maxKb || 2048);
        if (file.size > maxKb * 1024) return `File must be smaller than ${maxKb} KB.`;
        return true;
    },
};

function clearError(el) {
    el.classList.remove('is-invalid');
    el.parentElement.querySelectorAll('.js-feedback').forEach((node) => node.remove());
}

function showError(el, message) {
    clearError(el);
    // Hide any server-rendered message for this field
    el.parentElement.querySelectorAll('.invalid-feedback').forEach((node) => node.remove());
    el.classList.add('is-invalid');
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback js-feedback';
    feedback.textContent = message;
    el.after(feedback);
}

function validateField(el) {
    const names = (el.dataset.validate || '').split('|').filter(Boolean);
    for (const name of names) {
        const rule = rules[name];
        if (!rule) continue;
        const result = rule(el.value, el);
        if (result !== true) {
            showError(el, result);
            return false;
        }
    }
    clearError(el);
    return true;
}

document.querySelectorAll('form[data-validate-form]').forEach((form) => {
    form.noValidate = true; // show our messages instead of the browser's tooltips

    form.addEventListener('submit', (event) => {
        let firstInvalid = null;
        form.querySelectorAll('[data-validate]').forEach((el) => {
            if (!validateField(el) && !firstInvalid) firstInvalid = el;
        });
        if (firstInvalid) {
            event.preventDefault();
            firstInvalid.focus();
        }
    });

    // Re-check a field when the user leaves it or changes it
    ['focusout', 'change'].forEach((type) => {
        form.addEventListener(type, (event) => {
            if (event.target.matches('[data-validate]')) validateField(event.target);
        });
    });
});
