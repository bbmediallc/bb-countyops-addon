document.addEventListener('DOMContentLoaded', function () {

    const toggler = document.querySelector('a.mobile-nav__toggler');
    const closer = document.querySelector('.mobile-nav__close');
    const wrapper = document.querySelector('.mobile-nav__wrapper');
    const content = document.querySelector('.mobile-nav__content');
    const scrollTop = document.querySelector('.scroll-to-top');

    toggler.setAttribute('aria-label', 'Open mobile menu');

    closer.setAttribute('aria-label', 'Close mobile menu');
    closer.setAttribute('role', 'button');
    closer.setAttribute('tabindex', '0');
    closer.querySelector('i').setAttribute('aria-hidden', 'true');

    // Insert an invisible focus anchor right before the close span
    const focusAnchor = document.createElement('button');
    focusAnchor.setAttribute('aria-label', 'Close mobile menu');
    focusAnchor.style.cssText = 'position:absolute;opacity:0;width:1px;height:1px;overflow:hidden;';
    focusAnchor.addEventListener('click', function () {
        closer.click();
    });
    closer.parentNode.insertBefore(focusAnchor, closer);

    if (scrollTop) {
        scrollTop.setAttribute('aria-label', 'Scroll to top');
        scrollTop.querySelector('i').setAttribute('aria-hidden', 'true');
    }

    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.attributeName === 'class') {
                const isExpanded = wrapper.classList.contains('expanded');
                toggler.setAttribute('aria-expanded', String(isExpanded));

                if (isExpanded) {
                    const poll = setInterval(function () {
                        const vis = getComputedStyle(content).visibility;
                        if (vis === 'visible') {
                            clearInterval(poll);
                            focusAnchor.focus();
                        }
                    }, 50);
                } else {
                    toggler.focus();
                }
            }
        });
    });

    observer.observe(wrapper, { attributes: true });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && wrapper.classList.contains('expanded')) {
            closer.click();
        }
    });

});