document.addEventListener('DOMContentLoaded', () => {
  const convert = root => {
    root.querySelectorAll('[aria-label="Tax bill search results"] h4').forEach(h4 => {
      const h3 = document.createElement('h3');
      [...h4.attributes].forEach(a => h3.setAttribute(a.name, a.value));
      h3.innerHTML = h4.innerHTML;
      h4.replaceWith(h3);
    });
  };
  convert(document);
  new MutationObserver(() => convert(document)).observe(document.body, { childList: true, subtree: true });
});