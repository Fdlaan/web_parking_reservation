// Opens the reservation modal for the clicked slot: only the slot id and name change.
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('reserveModal');

  if (!modal) {
    return;
  }

  modal.addEventListener('show.bs.modal', (event) => {
    const slot = event.relatedTarget;

    modal.querySelector('[name="slot_id"]').value = slot.dataset.slotId;
    modal.querySelector('[data-slot-title]').textContent = slot.dataset.slotCode;
  });
});
