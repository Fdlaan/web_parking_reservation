document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".profile-form");
  const card = document.querySelector(".profile-section");
  const editButton = document.querySelector(".btn-edit-profile");
  const cancelButton = document.querySelector(".btn-cancel-profile");
  const saveActions = document.querySelector(".profile-edit-actions");

  if (!form || !card || !editButton || !cancelButton || !saveActions) {
    return;
  }

  const fields = form.querySelectorAll('input:not([type="hidden"])');

  function setEditing(editing) {
    card.classList.toggle("is-editing", editing);

    fields.forEach((field) => {
      field.disabled = !editing;
    });

    editButton.hidden = editing;
    saveActions.hidden = !editing;
  }

  editButton.addEventListener("click", () => {
    setEditing(true);
  });

  cancelButton.addEventListener("click", () => {
    form.reset();

    fields.forEach((field) => {
      const container = field.closest(".form-field");
      const error = container?.querySelector(".field-error");

      container?.classList.remove("is-incomplete");
      field.removeAttribute("aria-invalid");

      if (error) {
        error.remove();
      }
    });

    setEditing(false);
  });

  form.addEventListener("submit", (event) => {
    let valid = true;

    fields.forEach((field) => {
      if (field.disabled || field.readOnly) {
        return;
      }

      const container = field.closest(".form-field");
      const oldError = container?.querySelector(".field-error");

      container?.classList.remove("is-incomplete");
      field.removeAttribute("aria-invalid");

      if (oldError) {
        oldError.remove();
      }

      if (!field.checkValidity()) {
        valid = false;

        container?.classList.add("is-incomplete");
        field.setAttribute("aria-invalid", "true");

        const message = document.createElement("small");
        message.className = "field-error";
        message.textContent = field.validity.valueMissing
          ? "This field is required."
          : "Please enter a valid value.";

        container?.appendChild(message);
      }
    });

    if (!valid) {
      event.preventDefault();
    }
  });

  setEditing(false);
});
