// ADA Tables

document.addEventListener('DOMContentLoaded', function () {

  document.querySelectorAll('.elementor-shortcode table').forEach(table => {

    const headingId = table.getAttribute('aria-labelledby');
    if (headingId) {
      const heading = document.getElementById(headingId);
      if (heading) {
        const titleText = heading.textContent.trim();
        if (titleText) {
          heading.style.display = 'none';

          let caption = table.querySelector('caption');
          if (!caption) {
            caption = document.createElement('caption');
            table.prepend(caption);
          }

          caption.textContent = titleText;

          caption.style.position = 'absolute';
          caption.style.width = '1px';
          caption.style.height = '1px';
          caption.style.margin = '-1px';
          caption.style.overflow = 'hidden';
          caption.style.clip = 'rect(0,0,0,0)';
          caption.style.whiteSpace = 'nowrap';

          table.removeAttribute('aria-labelledby');
        }
      }
    }

    // Ensure column headers have scope
    table.querySelectorAll('thead th').forEach(th => {
      if (!th.hasAttribute('scope')) {
        th.setAttribute('scope', 'col');
      }
    });

    // Convert first column cells to row headers
    table.querySelectorAll('tbody tr').forEach(row => {
      const firstCell = row.children[0];
      if (!firstCell) return;

      if (firstCell.tagName.toLowerCase() === 'td') {
        const th = document.createElement('th');
        th.innerHTML = firstCell.innerHTML;
        th.className = firstCell.className;
        th.setAttribute('scope', 'row');
        row.replaceChild(th, firstCell);
      } else if (firstCell.tagName.toLowerCase() === 'th' && !firstCell.hasAttribute('scope')) {
        firstCell.setAttribute('scope', 'row');
      }
    });

  });

});