document.addEventListener('DOMContentLoaded', function () {

  document.querySelectorAll('.accordion').forEach((item, i) => {
    const btn = item.querySelector('.acc-btn');
    const panel = item.querySelector('.acc-content');
    if (!btn || !panel) return;

    const panelId = `accordion-panel-${i}`;
    panel.id = panelId;

    btn.setAttribute('tabindex', '0');
    btn.setAttribute('role', 'button');
    btn.setAttribute('aria-expanded', 'false');
    btn.setAttribute('aria-controls', panelId);

    panel.setAttribute('aria-hidden', 'true');

    btn.addEventListener('click', function () {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', String(!expanded));
      panel.setAttribute('aria-hidden', String(expanded));
    });

    btn.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        btn.click();
      }
    });
  });

});