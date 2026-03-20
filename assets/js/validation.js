// Simple Form Validation - Beginner Friendly
// Add class='needs-validation' to <form> and data-validate='true'

document.addEventListener('DOMContentLoaded', function() {
  const forms = document.querySelectorAll('form[needs-validation]');
  
  forms.forEach(form => {
    form.addEventListener('submit', function(e) {
      if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });

  // Custom validation rules
  const nisInput = document.querySelector('input[name="nis"]');
  if (nisInput) {
    nisInput.addEventListener('input', function() {
      const nis = this.value;
      if (nis && nis.length !== 4) {
        this.setCustomValidity('NIS harus 4 digit angka');
      } else {
        this.setCustomValidity('');
      }
    });
  }

  const poinInput = document.querySelector('input[name="poin"]');
  if (poinInput) {
    poinInput.addEventListener('input', function() {
      const poin = parseInt(this.value);
      if (this.value && (isNaN(poin) || poin < 1 || poin > 10)) {
        this.setCustomValidity('Poin harus 1-10');
      } else {
        this.setCustomValidity('');
      }
    });
  }

  // NIS search real-time
  // Disabled auto-submit on keyup for search inputs to prevent premature search\n  // const searchInputs = document.querySelectorAll('input[name="nama"], input[name="search"]');\n  // searchInputs.forEach(input => {\n  //   input.addEventListener('keyup', function() {\n  //     this.parentElement.querySelector('button[type="submit"]').click();\n  //   });\n  // });
  
  // Dropdown filter submit
  const dropdownFilters = document.querySelectorAll('.dropdown-toggle-filter');
  dropdownFilters.forEach(dropdown => {
    dropdown.addEventListener('click', function() {
      setTimeout(() => {
        const form = this.closest('form');
        if (form) form.submit();
      }, 100);
    });
  });
});

// Utility for dropdown (existing)
function setDropdown(name, dropdownId, text, value) {
  document.querySelector(`[name="${name}"]`).value = value;
  document.getElementById(dropdownId).textContent = text;
  document.getElementById(dropdownId).dataset.value = value;
}
