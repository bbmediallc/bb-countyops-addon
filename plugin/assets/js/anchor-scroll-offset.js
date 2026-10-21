document.addEventListener('DOMContentLoaded', function () {
  const offset = 160;

  function scrollWithOffset(id) {
    const target = document.getElementById(id);
    if (!target) return;
    const y = target.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top: y, behavior: 'smooth' });
  }

  const initialHash = window.location.hash.replace('#', '');

  if (initialHash) {
    history.replaceState(null, null, ' ');
    window.scrollTo(0, 0);
  }

  document.body.addEventListener('click', function (e) {
    const link = e.target.closest('a[href*="#"]');
    if (!link) return;
    const id = link.hash.replace('#', '');
    if (!id) return;
    const target = document.getElementById(id);
    if (!target) return;
    if (link.pathname !== window.location.pathname) return;
    e.preventDefault();
    scrollWithOffset(id);
    history.pushState(null, null, '#' + id);
  });

  if (initialHash) {
    setTimeout(function () {
      scrollWithOffset(initialHash);
    }, 400);
  }
});