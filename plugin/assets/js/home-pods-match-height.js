(function () {
	var selectors = ['.features-style1__single-content > span', '.features-style1__single-content > p'];
	function equalize() {
		document.querySelectorAll('.e-grid').forEach(function (grid) {
			var items = Array.prototype.filter.call(grid.querySelectorAll('.features-style1__single'), function (item) {
				return item.closest('.e-grid') === grid;
			});
			if (items.length < 2) return;
			selectors.forEach(function (sel) {
				var els = items.map(function (item) { return item.querySelector(sel); }).filter(Boolean);
				els.forEach(function (el) { el.style.display = 'block'; el.style.minHeight = ''; });
				var max = Math.max.apply(null, els.map(function (el) { return el.getBoundingClientRect().height; }));
				els.forEach(function (el) { el.style.minHeight = max + 'px'; });
			});
		});
	}
	var timer;
	window.addEventListener('resize', function () { clearTimeout(timer); timer = setTimeout(equalize, 100); });
	window.addEventListener('load', equalize);
	document.addEventListener('DOMContentLoaded', equalize);
	if (document.fonts) document.fonts.ready.then(equalize);
})();