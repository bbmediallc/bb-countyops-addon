// Elementor Table of Contents: a real toggle button (WCAG 4.1.2).
// Puts a <button class="elementor-toc__trigger"> inside the existing H2 title,
// so the heading is kept and the control has a name, role and aria-expanded.
// Clicking anywhere on the header bar still toggles it once. The chevron
// buttons become decorative (not focusable, hidden from screen readers).
//
// The click listener runs in the capture phase and stops propagation, so
// Elementor Pro's own handlers (bound to the chevron buttons) never fire and
// can't double-toggle. Enter and Space work natively on the button. The
// MutationObserver keeps aria-expanded right if Elementor collapses the box
// itself, e.g. when the screen crosses the "minimized on" breakpoint.
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.elementor-widget-table-of-contents').forEach(widget => {
    const header = widget.querySelector('.elementor-toc__header');
    const title = widget.querySelector('.elementor-toc__header-title');
    const body = widget.querySelector('.elementor-toc__body');
    if (!header || !title || !body) return;
    if (!body.id) body.id = `elementor-toc__${widget.dataset.id}`;
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'elementor-toc__trigger';
    btn.setAttribute('aria-controls', body.id);
    btn.textContent = title.textContent.trim();
    title.textContent = '';
    title.appendChild(btn);
    header.removeAttribute('tabindex');
    header.style.cursor = 'pointer';
    header.querySelectorAll('.elementor-toc__toggle-button').forEach(t => {
      t.setAttribute('tabindex', '-1');
      t.setAttribute('aria-hidden', 'true');
      t.removeAttribute('role');
      t.removeAttribute('aria-label');
      t.removeAttribute('aria-expanded');
      t.removeAttribute('aria-controls');
    });
    const isOpen = () => !widget.classList.contains('elementor-toc--collapsed');
    const sync = () => btn.setAttribute('aria-expanded', String(isOpen()));
    header.addEventListener('click', e => {
      e.stopPropagation();
      const open = !isOpen();
      widget.classList.toggle('elementor-toc--collapsed', !open);
      body.style.display = open ? 'block' : 'none';
      sync();
    }, true);
    new MutationObserver(sync).observe(widget, { attributes: true, attributeFilter: ['class'] });
    sync();
  });
});
