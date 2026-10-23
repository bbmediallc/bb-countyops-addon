// Search Widget ARIA Fix

window.addEventListener('load', () => {
  document.querySelectorAll('.elementor-widget-search').forEach(widget => {
    const settings = JSON.parse(widget.getAttribute('data-settings') || '{}');
    if (settings.live_results === 'yes') return;
    const input = widget.querySelector('.e-search-input');
    if (input) {
      ['role', 'aria-autocomplete', 'aria-expanded', 'aria-controls', 'aria-haspopup'].forEach(attr => input.removeAttribute(attr));
    }
    const results = widget.querySelector('.e-search-results-container');
    if (results) results.removeAttribute('tabindex');
  });
});
