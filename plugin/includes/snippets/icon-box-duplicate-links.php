<?php
// Icon Box duplicate links (formerly Code Snippets: "Fix Duplicate Elmentor
// Icon Links").
//
// A linked Elementor Icon Box outputs the same link twice: once on the icon
// and once on the title. Screen readers announce both and keyboard users tab
// through both (WCAG 2.4.4 / redundant links). This hides the icon's copy
// from assistive tech and takes it out of the tab order, leaving the title
// link as the one real link.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_icon_box_duplicate_links( $content, $widget ) {
	if ( 'icon-box' !== $widget->get_name() ) {
		return $content;
	}
	return preg_replace_callback(
		'/<a([^>]*class="elementor-icon"[^>]*)>/i',
		function ( $m ) {
			$attrs = preg_replace( '/\s(aria-label|aria-hidden|tabindex)="[^"]*"/i', '', $m[1] );
			return '<a' . $attrs . ' tabindex="-1" aria-hidden="true">';
		},
		$content
	);
}
add_filter( 'elementor/widget/render_content', 'bbcoa_icon_box_duplicate_links', 10, 2 );
