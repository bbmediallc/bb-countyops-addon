document.addEventListener('DOMContentLoaded', () => {
  const sticky = document.querySelector('.stricky-header');
  if (!sticky) return;
  const hide = () => {
    sticky.setAttribute('aria-hidden', 'true');
    sticky.querySelectorAll('a, button, input, select, textarea, [tabindex]').forEach(el => {
      if (el.getAttribute('tabindex') !== '-1') el.setAttribute('tabindex', '-1');
    });
  };
  hide();
  new MutationObserver(hide).observe(sticky, { childList: true, subtree: true });
});