document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.services-style1__single-text').forEach(function (block) {
        const h3Link = block.querySelector('h3 a');
        if (!h3Link) return;

        const url = h3Link.getAttribute('href');
        const wpelAttr = h3Link.getAttribute('data-wpel-link');
        const linkText = h3Link.textContent;

        const outer = block.closest('.services-style1__single');
        if (outer) {
            // Wrap the entire card in an <a> tag
            const wrapper = document.createElement('a');
            wrapper.setAttribute('href', url);
            wrapper.setAttribute('aria-label', linkText);
            if (wpelAttr) wrapper.setAttribute('data-wpel-link', wpelAttr);

            outer.parentNode.insertBefore(wrapper, outer);
            wrapper.appendChild(outer);
        }

        // Replace h3 link with plain text
        h3Link.outerHTML = `<span>${linkText}</span>`;

        // Remove the btn-box link
        const btnLink = block.querySelector('.btn-box a');
        if (btnLink) btnLink.outerHTML = btnLink.innerHTML;
        const btnIcon = block.querySelector('.btn-box span');
        if (btnIcon) btnIcon.setAttribute('aria-hidden', 'true');
    });

});