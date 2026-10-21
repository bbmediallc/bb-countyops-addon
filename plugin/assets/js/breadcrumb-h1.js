document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.breadcrumb-style1__inner h2').forEach(function (h2) {
        const h1 = document.createElement('h1');
        h1.innerHTML = h2.innerHTML;
        Array.from(h2.attributes).forEach(function (attr) {
            h1.setAttribute(attr.name, attr.value);
        });
        h2.parentNode.replaceChild(h1, h2);
    });

});