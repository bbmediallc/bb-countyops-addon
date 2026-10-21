<?php
// Keep Elementor link options open (formerly Code Snippets: "Keep Elementor
// Button Settings Open").
//
// In the Elementor editor, a link field's options (open in new window, add
// nofollow, custom attributes) are hidden behind a gear icon. This keeps them
// always visible, so it's harder to miss setting a link's attributes.
// Editor only; nothing changes on the live site.
//
// B&B Media Elementor Add-Ons does the same thing; having both is harmless.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_elementor_link_options_open() {
	wp_add_inline_style(
		'elementor-editor',
		'.elementor-control-url-more-options {
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			height: auto !important;
		}'
	);
}
add_action( 'elementor/editor/after_enqueue_styles', 'bbcoa_elementor_link_options_open' );
