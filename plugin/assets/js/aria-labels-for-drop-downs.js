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

});