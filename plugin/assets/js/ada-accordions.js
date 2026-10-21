// GovArnex FAQ accordions. Each question is a real <button class="acc-trigger">
// inside its <h3> (added by includes/snippets/faq-toggle-buttons.php), so
// headings survive for screen readers and Enter/Space work natively.
//
// This script is the only thing that opens and closes the panels:
// stopPropagation() keeps GovArnex's own handler (custom.js, delegated on
// .accordion-box) from running, because that handler won't close an open
// panel and doesn't update aria-expanded. Only one panel is open at a time,
// and aria-expanded always matches what's on screen. Closed panels are
// display:none, so they don't need aria-hidden.
document.addEventListener('DOMContentLoaded', () => {
  if (typeof jQuery === 'undefined') return;
  let n = 0;
  document.querySelectorAll('.accordion-box').forEach(box => {
    // Only FAQ items that have the button; anything else is left to the theme.
    const items = [...box.querySelectorAll('.accordion')].filter(item =>
      item.querySelector('.acc-trigger') && item.querySelector('.acc-btn') && item.querySelector('.acc-content'));
    const setState = (item, open) => {
      item.querySelector('.acc-trigger').setAttribute('aria-expanded', String(open));
      item.querySelector('.acc-btn').classList.toggle('active', open);
      item.classList.toggle('active-block', open);
      jQuery(item.querySelector('.acc-content'))[open ? 'slideDown' : 'slideUp'](300);
    };
    items.forEach(item => {
      const bar = item.querySelector('.acc-btn');
      const trigger = item.querySelector('.acc-trigger');
      const panel = item.querySelector('.acc-content');
      const id = `accordion-panel-${n++}`;
      panel.id = id;
      trigger.setAttribute('aria-controls', id);
      trigger.setAttribute('aria-expanded', String(jQuery(panel).is(':visible')));
      bar.addEventListener('click', e => {
        e.stopPropagation();
        const open = trigger.getAttribute('aria-expanded') !== 'true';
        items.forEach(other => {
          if (other !== item && other.querySelector('.acc-trigger').getAttribute('aria-expanded') === 'true') setState(other, false);
        });
        setState(item, open);
      });
    });
  });
});
