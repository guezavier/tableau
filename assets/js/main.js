// Format du code de dossier (ex: PR12-345)
function formatCode(input) {
  const raw = input.value.replace(/\D/g, '');
  let formatted = "PR";

  if (raw.length >= 2) {
    formatted += raw.slice(0, 2) + "-";
    formatted += raw.slice(2, 7);
  } else {
    formatted += raw;
  }

  input.value = formatted;
}

// Format intelligent des numéros de téléphone belges
function formatPhoneNumber(input) {
  let raw = input.value.trim();
  let valid = false;
  let formatted = "";

  if (raw.startsWith('+') || raw.startsWith('00')) {
    raw = raw.replace(/[^+\d]/g, '');
    raw = raw.replace(/^00/, '+');
    valid = /^\+32\d{8,9}$/.test(raw);
    formatted = raw;
  } else {
    const digits = raw.replace(/\D/g, '');

    if (digits.length === 9) {
      formatted = digits.replace(/^(\d{2})(\d{2})(\d{2})(\d{2})$/, "0$1/$2.$3.$4");
      valid = true;
    } else if (digits.length === 10 && digits.startsWith("0")) {
      formatted = digits.replace(/^(\d{3})(\d{2})(\d{2})(\d{2})$/, "$1/$2.$3.$4");
      valid = true;
    } else if (digits.length === 8) {
      formatted = digits.replace(/^(\d{2})(\d{2})(\d{2})(\d{2})$/, "0$1/$2.$3.$4");
      valid = true;
    }
  }

  input.value = formatted;

  if (valid || formatted === "") {
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
  } else {
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
    input.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
}

// Code principal, exécuté une fois le DOM chargé
document.addEventListener("DOMContentLoaded", () => {

  // Format des téléphones
  document.querySelectorAll('.phone-format').forEach(input => {
    input.addEventListener('blur', function () {
      formatPhoneNumber(this);
    });
  });

  // Vérifie qu'au moins un numéro est rempli
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function (e) {
      const telInput = document.querySelector('[name="tel"]');
      const gsmInput = document.querySelector('[name="gsm"]');
      const tel = telInput.value.trim();
      const gsm = gsmInput.value.trim();

      telInput.classList.remove('is-invalid');
      gsmInput.classList.remove('is-invalid');

      if (tel === '' && gsm === '') {
        e.preventDefault();
        alert("Veuillez remplir au moins un des deux champs : Téléphone ou GSM.");
        telInput.classList.add('is-invalid');
        gsmInput.classList.add('is-invalid');
      }
    });
  }

  // Remarques dynamiques
  const container = document.getElementById('remarques-container');
  if (container) {
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
  }

  // Boutons haut / bas
  const btnTop = document.getElementById("toTop");
  if (btnTop) {
    btnTop.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  const btnBottom = document.getElementById("toBottom");
  if (btnBottom) {
    btnBottom.onclick = () => window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
  }

});
