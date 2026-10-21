document.addEventListener('DOMContentLoaded', function () {

  document.querySelectorAll('.gform_wrapper form').forEach(form => {

    /* Hide honeypot fields from screen readers */
    form.querySelectorAll('.gfield--type-honeypot').forEach(field => {
      field.setAttribute('aria-hidden', 'true');
      const input = field.querySelector('input');
      if (input) input.setAttribute('tabindex', '-1');
    });

    /* Convert aria-required to native required */
    form.querySelectorAll('[aria-required="true"]').forEach(field => {
      field.setAttribute('required', '');
    });

    /* Fix phone inputs */
    form.querySelectorAll('input[id*="phone"], input[name*="phone"]').forEach(input => {
      input.setAttribute('type', 'tel');
    });

    /* Associate error messages when present */
    form.querySelectorAll('.gfield').forEach(field => {
      const error = field.querySelector('.validation_message');
      const input = field.querySelector('input, textarea, select');
      if (error && input) {
        const errorId = error.id || `error-${Math.random().toString(36).slice(2)}`;
        error.id = errorId;
        input.setAttribute('aria-describedby', errorId);
        input.setAttribute('aria-invalid', 'true');
      }
    });

  });

});