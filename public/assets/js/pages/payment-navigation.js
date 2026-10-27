document.addEventListener("DOMContentLoaded", () => {
  const dialog = document.querySelector("[data-payment-leave-dialog]");
  if (!dialog) {
    return;
  }

  const confirmButton = dialog.querySelector("[data-payment-confirm]");
  const cancelButtons = dialog.querySelectorAll("[data-payment-cancel]");
  const originalFocus = { element: null };
  let pendingBackNavigation = false;
  let destination = "";

  function openDialog({ back = false, href = "" } = {}) {
    pendingBackNavigation = back;
    destination = href;
    originalFocus.element = document.activeElement;
    dialog.hidden = false;
    confirmButton.focus();
    document.body.classList.add("confirmation-open");
  }

  function closeDialog() {
    dialog.hidden = true;
    document.body.classList.remove("confirmation-open");
    originalFocus.element?.focus();
  }

  document.addEventListener("click", (event) => {
    const link = event.target.closest("a[href]");
    if (!link || !link.matches(".sidebar a, .back-button, .topbar a")) {
      return;
    }

    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
      return;
    }

    const url = new URL(link.href, window.location.href);
    if (url.origin !== window.location.origin || url.href === window.location.href) {
      return;
    }

    event.preventDefault();
    openDialog({ href: url.href });
  });

  cancelButtons.forEach((button) => {
    button.addEventListener("click", closeDialog);
  });

  confirmButton.addEventListener("click", () => {
    if (pendingBackNavigation) {
      if (history.length > 2) {
        history.go(-2);
      } else {
        window.location.assign(document.referrer || "?page=history");
      }
      return;
    }

    if (destination) {
      window.location.assign(destination);
    }
  });

  dialog.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      event.preventDefault();
      cancelButtons[0].click();
    }

    if (event.key === "Tab") {
      const focusable = [...dialog.querySelectorAll("button:not([disabled])")];
      if (event.shiftKey && document.activeElement === focusable[0]) {
        event.preventDefault();
        focusable.at(-1).focus();
      } else if (!event.shiftKey && document.activeElement === focusable.at(-1)) {
        event.preventDefault();
        focusable[0].focus();
      }
    }
  });

  history.pushState({ paymentLeaveGuard: true }, "", window.location.href);
  window.addEventListener("popstate", () => {
    openDialog({ back: true });
    history.pushState({ paymentLeaveGuard: true }, "", window.location.href);
  });
});
