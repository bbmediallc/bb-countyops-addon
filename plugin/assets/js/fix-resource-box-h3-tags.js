document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.elementor-widget-govarnex_features h3').forEach(function (h3) {
        const span = document.createElement('span');
        span.innerHTML = h3.innerHTML;
        Array.from(h3.attributes).forEach(function (attr) {
            span.setAttribute(attr.name, attr.value);
        });
        h3.parentNode.replaceChild(span, h3);
    });

});