document.addEventListener('DOMContentLoaded', function () {

  const target = document.querySelector('.page-content');
  if (!target) return;

  target.setAttribute('id', 'page-content');
  target.setAttribute('tabindex', '-1');

  const skip = document.createElement('a');
  skip.href = '#page-content';
  skip.textContent = 'Skip to main content';
  skip.className = 'skip-to-content';
  document.body.prepend(skip);

  const style = document.createElement('style');
  style.textContent = `
    .skip-to-content {
    	position: absolute;
		top: -100px;
		left: 10px;
		z-index: 10000;
		background: #ffffff;
		padding: 20px;
		outline-color: {{accent_color}};
		color: #000000;
	}
	
	.skip-to-content:hover {
		color: {{accent_color}};
	}
    
	.skip-to-content:focus {
      top: 10px;
    }
  `;
  document.head.appendChild(style);

  skip.addEventListener('click', function (e) {
    e.preventDefault();
    target.scrollIntoView({ behavior: 'smooth' });
    target.focus({ preventScroll: true });
  });

});