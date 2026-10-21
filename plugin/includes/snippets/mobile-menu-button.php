<?php
// Mobile menu button (formerly Code Snippets: "Fix Mobile Menu Button Issues").
//
// GovArnex's mobile menu opener is a plain link with no name or state. This
// makes it a proper button for assistive tech (role="button", a label,
// aria-controls, and aria-expanded kept in sync as the menu opens and
// closes), hides the dark overlay from screen readers, and lets the Space
// key open and close the menu like a real button (WCAG 4.1.2, 2.1.1).

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_mobile_menu_button_script() {
	?>
	<script>
	document.addEventListener('DOMContentLoaded', () => {
		const wrapper = document.querySelector('.mobile-nav__wrapper');
		if (!wrapper) return;
		if (!wrapper.id) wrapper.id = 'mobile-nav';

		const overlay = wrapper.querySelector('.mobile-nav__overlay');
		if (overlay) overlay.setAttribute('aria-hidden', 'true');

		const togglers = document.querySelectorAll('a.mobile-nav__toggler');
		const sync = () => {
			const open = wrapper.classList.contains('expanded');
			togglers.forEach(t => {
				t.setAttribute('role', 'button');
				t.setAttribute('aria-label', 'Open mobile menu');
				t.setAttribute('aria-controls', wrapper.id);
				t.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
		};
		sync();
		new MutationObserver(sync).observe(wrapper, { attributes: true, attributeFilter: ['class'] });

		document.querySelectorAll('a.mobile-nav__toggler, .mobile-nav__close').forEach(el => {
			el.addEventListener('keydown', e => {
				if (e.key === ' ' || e.key === 'Spacebar') {
					e.preventDefault();
					el.click();
				}
			});
		});
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'bbcoa_mobile_menu_button_script', 100 );
