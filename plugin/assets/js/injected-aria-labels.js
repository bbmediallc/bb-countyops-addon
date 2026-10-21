document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu open toggle
    const toggler = document.querySelector('a.mobile-nav__toggler');
    toggler.setAttribute('aria-label', 'Open mobile menu');
    toggler.setAttribute('aria-expanded', 'false');

    toggler.addEventListener('click', function() {
        const expanded = this.getAttribute('aria-expanded') === 'true';
        this.setAttribute('aria-expanded', String(!expanded));
    });

    // Mobile menu close button
    const closer = document.querySelector('.mobile-nav__close');
    closer.setAttribute('aria-label', 'Close mobile menu');
    closer.setAttribute('role', 'button');
    closer.querySelector('i').setAttribute('aria-hidden', 'true');

    // Scroll to top
    document.querySelector('.scroll-to-top').setAttribute('aria-label', 'Scroll to top');
    document.querySelector('.scroll-to-top i').setAttribute('aria-hidden', 'true');
});