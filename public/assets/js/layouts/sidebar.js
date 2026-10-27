document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.querySelector("[data-sidebar]");
  const toggle = document.querySelector("[data-sidebar-toggle]");
  const layout = document.querySelector(".layout");
  const backdrop = layout?.querySelector("[data-sidebar-backdrop]");

  if (!sidebar || !toggle || !layout) {
    return;
  }

  const mobileQuery = window.matchMedia("(max-width: 900px)");

  function setOpen(open) {
    const isOpen = mobileQuery.matches && open;
    layout.classList.toggle("mobile-sidebar-expanded", isOpen);
    sidebar.inert = mobileQuery.matches && !isOpen;
    sidebar.setAttribute("aria-hidden", String(mobileQuery.matches && !isOpen));
    toggle.setAttribute("aria-expanded", String(isOpen));
    toggle.setAttribute("aria-label", isOpen ? "Close navigation" : "Open navigation");
  }

  function applyResponsiveState() {
    setOpen(false);
  }

  toggle.addEventListener("click", () => {
    setOpen(!layout.classList.contains("mobile-sidebar-expanded"));
  });

  sidebar.querySelectorAll(".nav-item[href]").forEach((item) => {
    item.addEventListener("click", () => {
      if (mobileQuery.matches) {
        setOpen(false);
      }
    });
  });

  backdrop?.addEventListener("click", () => setOpen(false));

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && layout.classList.contains("mobile-sidebar-expanded")) {
      setOpen(false);
      toggle.focus();
    }
  });

  mobileQuery.addEventListener("change", applyResponsiveState);
  applyResponsiveState();
});
