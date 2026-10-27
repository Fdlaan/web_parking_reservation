document.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll("[data-auth-form]");
  const emailPattern = /^[^\s@]+@(?:[A-Za-z0-9](?:[A-Za-z0-9-]*[A-Za-z0-9])?\.)+[A-Za-z]{2,}$/;

  forms.forEach((form) => {
    const fields = [...form.querySelectorAll("[data-auth-field]")];
    const password = form.querySelector('[name="password"]');
    const confirmation = form.querySelector('[name="confirm_password"]');
    const strength = form.querySelector(".password-strength");
    const strengthLabel = strength?.querySelector(".password-strength-label");

    form.querySelectorAll("[data-password-toggle]").forEach((toggle) => {
      const field = form.querySelector(`#${CSS.escape(toggle.dataset.passwordToggle)}`);
      const icon = toggle.querySelector("i");

      if (!field || !icon) {
        return;
      }

      toggle.addEventListener("click", () => {
        const visible = field.type === "password";
        field.type = visible ? "text" : "password";
        toggle.setAttribute("aria-label", `${visible ? "Hide" : "Show"} ${field.labels?.[0]?.textContent.trim() || "password"}`);
        icon.classList.toggle("fa-eye", !visible);
        icon.classList.toggle("fa-eye-slash", visible);
      });
    });

    function messageFor(field) {
      const value = field.value.trim();

      if (field.required && value === "") {
        return `${field.labels?.[0]?.textContent.trim() || "This field"} is required.`;
      }

      if (field.type === "email" && value !== "" && !emailPattern.test(value)) {
        return "Enter a complete email address, for example name@example.com.";
      }

      if (field.name === "phone" && value !== "" && !/^\+?[0-9]{8,15}$/.test(value)) {
        return "Phone number must contain 8-15 digits, with an optional leading +.";
      }

      if (field.name === "otp" && value !== "" && !/^[0-9]{6}$/.test(value)) {
        return "Enter the 6-digit OTP code.";
      }

      if (field.name === "password" && value.length < 8) {
        return "Password must be at least 8 characters.";
      }

      if (field.name === "confirm_password" && value !== (password?.value ?? "")) {
        return "Confirm Password must match Create Password.";
      }

      return "";
    }

    function showMessage(field, message) {
      const id = `${field.id}-feedback`;
      let feedback = form.querySelector(`#${CSS.escape(id)}`);

      field.setAttribute("aria-invalid", message ? "true" : "false");

      if (!message) {
        feedback?.remove();
        field.removeAttribute("aria-describedby");
        return;
      }

      if (!feedback) {
        feedback = document.createElement("span");
        feedback.id = id;
        feedback.className = "auth-field-feedback";
        feedback.setAttribute("role", "alert");
        feedback.setAttribute("aria-live", "polite");
        field.insertAdjacentElement("afterend", feedback);
      }

      feedback.textContent = message;
      field.setAttribute("aria-describedby", id);
    }

    function updateStrength() {
      if (!password || !strength || !strengthLabel) {
        return;
      }

      const value = password.value;
      const score = [
        value.length >= 8,
        /[A-Za-z]/.test(value) && /[0-9]/.test(value),
        value.length >= 12 && /[^A-Za-z0-9]/.test(value),
      ].filter(Boolean).length;
      const level = value.length < 8 ? 0 : score;

      strength.dataset.level = String(level);
      strengthLabel.textContent = value.length === 0
        ? "Use at least 8 characters."
        : value.length < 8
          ? `Too short — ${8 - value.length} more character${8 - value.length === 1 ? "" : "s"} needed.`
          : ["", "Weak", "Good", "Strong"][level];
    }

    fields.forEach((field) => {
      field.addEventListener("input", () => {
        showMessage(field, messageFor(field));
        if (field === password) {
          updateStrength();
          if (confirmation?.value) {
            showMessage(confirmation, messageFor(confirmation));
          }
        }
      });
    });

    form.addEventListener("submit", (event) => {
      let firstInvalid = null;

      fields.forEach((field) => {
        const message = messageFor(field);
        showMessage(field, message);
        if (message && firstInvalid === null) {
          firstInvalid = field;
        }
      });

      if (firstInvalid) {
        event.preventDefault();
        firstInvalid.focus();
      }
    });

    updateStrength();
  });
});
