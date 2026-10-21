window.addEventListener('load', function() {
    document.querySelectorAll('div.e-n-accordion[aria-label]').forEach(function(el) {
        el.setAttribute('role', 'region');
    });
});

const observer = new MutationObserver(function() {
    document.querySelectorAll('div.e-n-accordion[aria-label]:not([role])').forEach(function(el) {
        el.setAttribute('role', 'region');
    });
});

observer.observe(document.body, { childList: true, subtree: true, attributes: true });