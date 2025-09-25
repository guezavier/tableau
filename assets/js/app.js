document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.phone-format').forEach(input => {
    input.addEventListener('blur', function () {
      let raw = this.value.trim();
      if (raw.startsWith('+') || raw.startsWith('00')) {
        raw = raw.replace(/[^\d+]/g, '');
        raw = raw.replace(/^00/, '+');
        this.value = raw;
        return;
      }
      const digits = raw.replace(/\D/g, '');
      if (digits.length === 9) {
        this.value = digits.replace(/^(\d{3})(\d{2})(\d{2})(\d{2})$/, "$1/$2.$3.$4");
      } else if (digits.length === 10) {
        this.value = digits.replace(/^(\d{4})(\d{2})(\d{2})(\d{2})$/, "$1/$2.$3.$4");
      }
    });
  });
});
