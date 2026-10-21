document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.elementor-toc__header').forEach(function (header) {
        const widget = header.closest('.elementor-widget-table-of-contents');
        if (!widget) return;

        header.style.cursor = 'pointer';
        header.setAttribute('tabindex', '0');

        header.querySelectorAll('.elementor-toc__toggle-button').forEach(function (btn) {
            btn.setAttribute('tabindex', '-1');
        });

        function triggerToggle() {
            const isCollapsed = widget.classList.contains('elementor-toc--collapsed');
            const body = widget.querySelector('.elementor-toc__body');
            const expandBtn = header.querySelector('.elementor-toc__toggle-button--expand');
            const collapseBtn = header.querySelector('.elementor-toc__toggle-button--collapse');

            if (isCollapsed) {
                widget.classList.remove('elementor-toc--collapsed');
                if (body) body.style.display = '';
                if (expandBtn) expandBtn.setAttribute('aria-expanded', 'true');
                if (collapseBtn) collapseBtn.setAttribute('aria-expanded', 'true');
            } else {
                widget.classList.add('elementor-toc--collapsed');
                if (body) body.style.display = 'none';
                if (expandBtn) expandBtn.setAttribute('aria-expanded', 'false');
                if (collapseBtn) collapseBtn.setAttribute('aria-expanded', 'false');
            }
        }

        header.addEventListener('click', function (e) {
            if (e.target.closest('.elementor-toc__toggle-button')) return;
            triggerToggle();
        });

        header.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                triggerToggle();
            }
        });
    });

});