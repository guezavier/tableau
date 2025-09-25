<fieldset class="card p-3 mb-3" id="remarques-container">
  <legend>Remarques diverses</legend>

  <div class="remarque-item mb-2">
    <label class="form-label">Remarque 1</label>
    <textarea name="remarques[]" class="form-control remarque-field" placeholder="Ajouter une remarque..."></textarea>
  </div>
</fieldset>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('remarques-container');

  function updateLabels() {
    const items = container.querySelectorAll('.remarque-item');
    items.forEach((el, index) => {
      const label = el.querySelector('label');
      if (label) label.textContent = "Remarque " + (index + 1);
    });
  }

  function addNewField() {
    const index = container.querySelectorAll('.remarque-item').length + 1;
    const div = document.createElement('div');
    div.className = "remarque-item mb-2";
    div.innerHTML = `
      <label class="form-label">Remarque ${index}</label>
      <textarea name="remarques[]" class="form-control remarque-field" placeholder="Ajouter une remarque..."></textarea>
    `;
    container.appendChild(div);
    updateLabels();
  }

  container.addEventListener('blur', function (e) {
    if (e.target.classList.contains('remarque-field')) {
      const all = Array.from(container.querySelectorAll('.remarque-field'));
      const last = all[all.length - 1];
      if (e.target === last && last.value.trim() !== "") {
        addNewField();
      }
    }
  }, true);
});
</script>
