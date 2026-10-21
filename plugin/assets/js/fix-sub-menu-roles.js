document.querySelectorAll('ul.sub-menu[role="menu"] > li > a').forEach(function(a) {
    a.setAttribute('role', 'menuitem');
});