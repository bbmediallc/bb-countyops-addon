document.addEventListener('DOMContentLoaded', function () {

    // Desktop menu - add role="menuitem" to all sub-menu links
    document.querySelectorAll('.main-menu__list ul.sub-menu a').forEach(function (link) {
        link.setAttribute('role', 'menuitem');
    });

    // Desktop menu - sync aria-expanded on parent links when submenu opens/closes
    document.querySelectorAll('.main-menu__list .menu-item-has-children > a').forEach(function (link) {
        link.addEventListener('mouseenter', function () {
            link.setAttribute('aria-expanded', 'true');
        });
        link.addEventListener('mouseleave', function () {
            link.setAttribute('aria-expanded', 'false');
        });
        link.addEventListener('focus', function () {
            link.setAttribute('aria-expanded', 'true');
        });
        link.addEventListener('blur', function () {
            link.setAttribute('aria-expanded', 'false');
        });
    });

    // Mobile menu - dropdown buttons + menuitem roles
    setTimeout(function () {
        const mobileNav = document.querySelector('.mobile-nav__container');
        if (!mobileNav) return;

        const dropdownBtns = mobileNav.querySelectorAll('.menu-item-has-children > a > button');
        dropdownBtns.forEach(function (btn, i) {
            const parentLink = btn.closest('a');
            const parentLi = btn.closest('li');
            const subMenu = parentLi.querySelector(':scope > ul.sub-menu');
            if (!subMenu) return;

            const subMenuId = 'mobile-submenu-' + i;
            subMenu.setAttribute('id', subMenuId);

            const linkText = parentLink.childNodes[0].textContent.trim();
            btn.setAttribute('aria-expanded', 'false');
            btn.setAttribute('aria-controls', subMenuId);
            btn.setAttribute('aria-label', 'Expand ' + linkText + ' submenu');
            btn.querySelector('i').setAttribute('aria-hidden', 'true');

            btn.addEventListener('click', function () {
                const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', String(!isExpanded));
            });
        });

        // Mobile menu - add role="menuitem" to all sub-menu links
        mobileNav.querySelectorAll('ul.sub-menu a').forEach(function (link) {
            link.setAttribute('role', 'menuitem');
        });

    }, 300);

});