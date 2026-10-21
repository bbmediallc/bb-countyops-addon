// GovArnex mobile menu (replaces Mobile Menu Tabbing, Injected Aria Labels,
// the "Fix Mobile Menu Button Issues" snippet and the mobile part of Aria
// Labels for Drop Downs). WCAG 2.4.3, 2.4.7, 4.1.2.
//
// Runs on window load, after the theme's custom.js has cloned the desktop
// menu into the drawer and attached its handlers, then takes over: it removes
// the theme's click handlers from the togglers, overlay, close control and
// submenu buttons so only this script controls the menu.
// - The drawer is a modal dialog labeled "Menu". Opening it focuses the
//   visible close X; Tab and Shift+Tab stay inside it; Escape, the X (click,
//   Enter, Space) and the overlay close it and return focus to the hamburger,
//   whose aria-expanded follows the state.
// - Each submenu button is moved out of its parent link to sit beside it,
//   labeled "[Name] submenu" with a live aria-expanded. Parent links lose the
//   theme's aria-haspopup/aria-expanded, which never updated.
// - The drawer's copy of the menu gets "m-" prefixed IDs (no duplicates with
//   the desktop menu) and loses role="menu"/"menuitem", whose arrow-key
//   behavior it doesn't have.
// Styles for the moved submenu buttons are at the end of ada-styles.css.

// Scroll-to-top button labels (were in Injected Aria Labels / Mobile Menu Tabbing).
document.addEventListener('DOMContentLoaded', () => {
  const scrollTop = document.querySelector('.scroll-to-top');
  if (!scrollTop) return;
  scrollTop.setAttribute('aria-label', 'Scroll to top');
  scrollTop.querySelector('i')?.setAttribute('aria-hidden', 'true');
});

(() => {
  const run = () => {
    const $ = window.jQuery;
    const wrapper = document.querySelector('.mobile-nav__wrapper');
    const content = wrapper && wrapper.querySelector('.mobile-nav__content');
    const container = wrapper && wrapper.querySelector('.mobile-nav__container');
    const close = content && content.querySelector('.mobile-nav__close');
    if (!$ || !wrapper || !content || !container || !close) return;
    wrapper.id = 'mobile-nav';
    const openers = [...document.querySelectorAll('a.mobile-nav__toggler')];
    const overlay = wrapper.querySelector('.mobile-nav__overlay');
    let lastOpener = openers[0];

    content.querySelectorAll(':scope > button:not([class])').forEach(b => b.remove());
    content.setAttribute('role', 'dialog');
    content.setAttribute('aria-modal', 'true');
    content.setAttribute('aria-label', 'Menu');

    container.querySelectorAll('[id]').forEach(el => { el.id = `m-${el.id}`; });
    container.querySelectorAll('[role="menu"], [role="menuitem"]').forEach(el => el.removeAttribute('role'));
    container.querySelectorAll('ul.sub-menu').forEach(ul => ul.removeAttribute('aria-labelledby'));

    container.querySelectorAll('.menu-item-has-children > a').forEach((link, i) => {
      const btn = link.querySelector('button');
      const sub = link.parentElement.querySelector(':scope > ul.sub-menu');
      link.removeAttribute('aria-haspopup');
      link.removeAttribute('aria-expanded');
      if (!btn || !sub) return;
      $(btn).off('click');
      const label = link.textContent.trim();
      sub.id = `mobile-submenu-${i}`;
      btn.type = 'button';
      btn.className = 'mobile-nav__sub-toggle';
      btn.setAttribute('aria-label', `${label} submenu`);
      btn.setAttribute('aria-controls', sub.id);
      btn.setAttribute('aria-expanded', 'false');
      btn.querySelector('i')?.setAttribute('aria-hidden', 'true');
      link.after(btn);
      btn.addEventListener('click', () => {
        const open = btn.getAttribute('aria-expanded') !== 'true';
        btn.setAttribute('aria-expanded', String(open));
        btn.classList.toggle('expanded', open);
        link.classList.toggle('expanded', open);
        $(sub).stop(true, true)[open ? 'slideDown' : 'slideUp'](300);
      });
    });

    const isOpen = () => wrapper.classList.contains('expanded');
    const focusables = () => [...content.querySelectorAll('a[href], button, [tabindex="0"]')].filter(el => el.getClientRects().length);
    const focusClose = () => {
      if (!isOpen()) return;
      if (getComputedStyle(content).visibility === 'visible') close.focus();
      else requestAnimationFrame(focusClose);
    };
    const setOpen = open => {
      wrapper.classList.toggle('expanded', open);
      document.body.classList.toggle('locked', open);
      openers.forEach(t => t.setAttribute('aria-expanded', String(open)));
      if (open) focusClose();
      else lastOpener?.focus();
    };

    $(openers).off('click');
    if (overlay) $(overlay).off('click');
    $(close).off('click');

    openers.forEach(t => {
      t.setAttribute('role', 'button');
      t.setAttribute('aria-label', 'Menu');
      t.setAttribute('aria-controls', wrapper.id);
      t.setAttribute('aria-expanded', 'false');
      t.querySelector('i')?.setAttribute('aria-hidden', 'true');
      t.addEventListener('click', e => { e.preventDefault(); lastOpener = t; setOpen(true); });
      t.addEventListener('keydown', e => { if (e.key === ' ') { e.preventDefault(); t.click(); } });
    });

    close.setAttribute('role', 'button');
    close.setAttribute('tabindex', '0');
    close.setAttribute('aria-label', 'Close menu');
    close.querySelector('i')?.setAttribute('aria-hidden', 'true');
    close.addEventListener('click', e => { e.preventDefault(); setOpen(false); });
    close.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); setOpen(false); }
    });

    if (overlay) overlay.addEventListener('click', () => setOpen(false));

    document.addEventListener('keydown', e => {
      if (!isOpen()) return;
      if (e.key === 'Escape') { setOpen(false); return; }
      if (e.key !== 'Tab') return;
      const f = focusables();
      if (!f.length) return;
      const first = f[0];
      const last = f[f.length - 1];
      if (!content.contains(document.activeElement)) { e.preventDefault(); first.focus(); }
      else if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
    });
  };
  if (document.readyState === 'complete') run();
  else window.addEventListener('load', run);
})();
