document.addEventListener('DOMContentLoaded', function () {
  var label = function () {
    document.querySelectorAll('nav.main-menu:not([aria-label])').forEach(function (nav, i) {
      nav.setAttribute('aria-label', i === 0 ? 'Main Menu' : 'Sticky Main Menu');
    });
  };
  label();
  new MutationObserver(label).observe(document.body, { childList: true, subtree: true });
});