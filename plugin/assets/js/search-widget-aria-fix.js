// Search Widget ARIA Fix

window.addEventListener('load', () => {
  // Every search landmark needs its own name (the header search form is
  // "Header search"), so unnamed Elementor Search widgets become "Page
  // search", numbered if a page has more than one.
  const landmarks = [...document.querySelectorAll('.elementor-widget-search search, .elementor-widget-search [role="search"]')]
    .filter(el => !el.hasAttribute('aria-label') && !el.hasAttribute('aria-labelledby'));
  landmarks.forEach((el, i) => el.setAttribute('aria-label', landmarks.length > 1 ? 'Page search ' + (i + 1) : 'Page search'));

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
