document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('.profile-information');
    const card = document.querySelector('.profile-section');
    const button = document.querySelector('.btn-edit-profile');

    if (!form || !card || !button) return;

    const fields = form.querySelectorAll('.form-control');
    let editing = false;

    button.addEventListener('click', () => {
        if (!editing) {
            editing = true;

            card.classList.add('is-editing');
            fields.forEach(field => {
                field.disabled = false;
            });

            button.innerHTML =
                'Save Profile <i class="fa-solid fa-floppy-disk"></i>';

            return;
        }

        let valid = true;

        fields.forEach(field => {
            const value = field.value.trim();
            const fieldContainer = field.closest('.form-field');
            const error = fieldContainer.querySelector('.field-error');

            fieldContainer.classList.remove('is-incomplete');
            field.removeAttribute('aria-invalid');

            if (error) error.remove();

            if (field.required && value === '') {
                valid = false;
                fieldContainer.classList.add('is-incomplete');
                field.setAttribute('aria-invalid', 'true');

                const message = document.createElement('small');
                message.className = 'field-error';
                message.textContent = 'This field is required.';

                fieldContainer.appendChild(message);
            }
        });

        if (!valid) return;

        form.requestSubmit();
    });
});