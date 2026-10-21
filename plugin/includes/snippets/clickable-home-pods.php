<?php
// Clickable home pods (formerly Code Snippets: "Clickable Home Pods").
//
// Makes the whole GovArnex Features box ("home pod") clickable, not just its
// link text: the link's ::after is stretched over the box. Keyboard focus is
// shown as one solid maroon outline around the whole box, with nothing on the
// title link, and no outline on mouse clicks (WCAG 2.4.7 Focus Visible, and a
// larger target for 2.5.8). ADA Styles must not give the title link its own
// focus outline, or keyboard users see two.
//
// Pairs with the "Home Pods Match Height" script (assets/js).

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_clickable_home_pods_styles() {
	?>
	<style>
		.elementor-widget-govarnex_features .features-style1__single-content {
			position: static;
		}
		.elementor-widget-govarnex_features .features-style1__single-content a::after {
			content: "";
			position: absolute;
			inset: 0;
			z-index: 5;
		}
		.elementor-widget-govarnex_features .features-style1__single-content a:focus-visible {
			outline: none;
		}
		.elementor-widget-govarnex_features .features-style1__single-content span a:focus,
		.elementor-widget-govarnex_features .features-style1__single-content span a:focus-visible {
			outline: none !important;
			box-shadow: none !important;
		}
		.elementor-widget-govarnex_features .features-style1__single:has(a:focus-visible) {
			outline: 3px solid #7f0003;
			outline-offset: 3px;
			border-radius: 15px;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'bbcoa_clickable_home_pods_styles' );
