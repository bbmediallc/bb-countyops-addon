<?php
// Header search (formerly Laclede's Code Snippets: "Add Search to Header").
//
// Turned on per county in County Settings ("Search in header"). Inserts an
// Elementor saved template (the county's search form) into the GovArnex top
// bar, right after the top-left menu. Styles: assets/css/header-search.css.
//
// The template is rendered during wp_footer so Elementor loads its CSS/JS
// normally, then an output buffer drops it into the header markup.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_header_search_template_id() {
	$county = bbcoa_county();
	return '1' === (string) $county['header_search'] ? absint( $county['header_search_template'] ) : 0;
}

function bbcoa_header_search_enabled() {
	return bbcoa_header_search_template_id() > 0;
}

function bbcoa_header_search_skip() {
	return is_admin() || wp_doing_ajax() || is_feed() || isset( $_GET['elementor-preview'] );
}

function bbcoa_header_search_render() {
	if ( bbcoa_header_search_skip() || ! bbcoa_header_search_enabled() ) {
		return;
	}
	$GLOBALS['bbcoa_header_search_html'] = do_shortcode( '[elementor-template id="' . bbcoa_header_search_template_id() . '"]' );
}
add_action( 'wp_footer', 'bbcoa_header_search_render', 1 );

function bbcoa_header_search_buffer() {
	if ( bbcoa_header_search_skip() || ! bbcoa_header_search_enabled() ) {
		return;
	}
	ob_start( function ( $html ) {
		if ( empty( $GLOBALS['bbcoa_header_search_html'] ) ) {
			return $html;
		}
		$insert = '<div class="bb-header-template">' . $GLOBALS['bbcoa_header_search_html'] . '</div>';
		$result = preg_replace_callback(
			'#(<ul class="main-header-one__top-left-menu">.*?</ul>)#s',
			function ( $m ) use ( $insert ) {
				return $m[1] . $insert;
			},
			$html,
			1
		);
		return null === $result ? $html : $result;
	} );
}
add_action( 'template_redirect', 'bbcoa_header_search_buffer' );
