document.addEventListener("DOMContentLoaded", () => {
  const overlay = document.createElement("div");
  overlay.className = "action-confirmation";
  overlay.hidden = true;
  overlay.innerHTML = `
    <div class="action-confirmation-backdrop" data-confirm-cancel></div>
    <section class="action-confirmation-card" role="dialog" aria-modal="true" aria-labelledby="action-confirmation-title">
      <span class="action-confirmation-title" id="action-confirmation-title">Confirm action</span>
      <span class="action-confirmation-message" data-confirm-message></span>
      <div class="action-confirmation-actions">
        <button type="button" class="action-confirmation-cancel" data-confirm-cancel>Cancel</button>
        <button type="button" class="action-confirmation-accept" data-confirm-accept>Continue</button>
      </div>
    </section>`;
  document.body.append(overlay);

  const acceptButton = overlay.querySelector("[data-confirm-accept]");
  const cancelButtons = overlay.querySelectorAll("[data-confirm-cancel]");
  let pendingForm = null;
  let submitter = null;
  let previousFocus = null;

  function close() {
    overlay.hidden = true;
    document.body.classList.remove("confirmation-open");
    previousFocus?.focus();
    pendingForm = null;
    submitter = null;
  }

  document.addEventListener("submit", (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.dataset.confirmMessage) {
      return;
    }

    if (form.dataset.confirmed === "true") {
      delete form.dataset.confirmed;
      return;
    }

    event.preventDefault();
    pendingForm = form;
    submitter = event.submitter;
    previousFocus = document.activeElement;
    overlay.querySelector("[data-confirm-message]").textContent = form.dataset.confirmMessage;
    overlay.hidden = false;
    document.body.classList.add("confirmation-open");
    acceptButton.focus();
  }, true);

  cancelButtons.forEach((button) => button.addEventListener("click", close));

  acceptButton.addEventListener("click", () => {
    if (!pendingForm) {
      return;
    }

    const form = pendingForm;
    const button = submitter;
    close();
    form.dataset.confirmed = "true";
    if (button) {
      form.requestSubmit(button);
    } else {
      form.requestSubmit();
    }
  });

  overlay.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      cancelButtons[0].click();
    } else if (event.key === "Tab") {
      const focusable = [cancelButtons[1], acceptButton];
      if (event.shiftKey && document.activeElement === focusable[0]) {
        event.preventDefault();
        focusable[1].focus();
      } else if (!event.shiftKey && document.activeElement === focusable[1]) {
        event.preventDefault();
        focusable[0].focus();
      }
    }
  });
});
