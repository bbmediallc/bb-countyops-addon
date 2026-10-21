<?php
// Prints the shared CSS and JS (formerly Simple Custom CSS and JS items).
//
// Placement and order match what Simple Custom CSS and JS did, so the cascade
// is unchanged: inline in <head> at wp_head priority 10, all JS first, then
// all CSS, each in the order below (newest item first, as it printed them).
// Each file gets its own tag so an error in one script can't stop the others.
//
// This plugin loads before custom-css-js alphabetically, so anything a county
// still keeps in Simple Custom CSS and JS prints after this and wins ties.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// assets/js/<name>.js, in output order. See README.md for what each does.
function bbcoa_js_files() {
	return [
		'home-pods-match-height',
		'fix-duplicate-header-issues',
		'fix-tax-search-results-headings',
		'fix-duplicate-nav-label',
		'fix-accordion-generic-role',
		'fix-sub-menu-roles',
		'breadcrumb-h1',
		'fix-resource-box-h3-tags',
		'elementor-toc-header-link-fix',
		'move-button-box-links-to-container',
		'aria-labels-for-drop-downs',
		'mobile-menu',
		'anchor-scroll-offset',
		'ada-gravity-forms',
		'ada-accordions',
		'ada-tables',
		'skip-to-content',
	];
}

// assets/css/<name>.css, in output order. Theme Overrides comes last so it
// wins ties, as before. header-search only prints when the county uses it.
function bbcoa_css_files() {
	$files = [ 'county-ops-overrides', 'backend-overrides', 'ada-styles', 'theme-overrides' ];
	if ( bbcoa_header_search_enabled() ) {
		$files[] = 'header-search';
	}
	return $files;
}

// {{placeholder}} => value, for the county-specific parts of the files.
function bbcoa_replacements() {
	$county = bbcoa_county();
	$fields = bbcoa_county_fields();
	$out    = [];
	foreach ( [ 'accent_color', 'link_color', 'footer_link_hover' ] as $key ) {
		// Saved values are checked when saved; check again since they're printed into CSS.
		$out[ '{{' . $key . '}}' ] = bbcoa_is_color( $county[ $key ] ) ? $county[ $key ] : $fields[ $key ]['default'];
	}
	$out['{{banner}}'] = bbcoa_banner_css( $county );
	return $out;
}

// The page header banner rule, or nothing when the county has no banner image.
function bbcoa_banner_css( $county ) {
	$url = bbcoa_banner_url( $county );
	if ( '' === $url ) {
		return '';
	}
	$position = preg_match( '/^[0-9a-z%.\s-]+$/i', $county['banner_position'] ) ? $county['banner_position'] : '50% 50%';
	// esc_url_raw strips quotes, so the URL can't break out of url('...').
	return ".page-breadcrumb,\nbody.search .breadcrumb-style1__bg,\nbody.search-results .breadcrumb-style1__bg{\n"
		. "    background: url('" . esc_url_raw( $url ) . "');\n"
		. "    background-size: cover;\n"
		. '    background-position: ' . $position . ";\n}\n";
}

function bbcoa_print_file( $path, $tag, $replacements ) {
	if ( ! is_readable( $path ) ) {
		return;
	}
	$code = strtr( file_get_contents( $path ), $replacements );
	echo '<' . $tag . ' id="bbcoa-' . esc_attr( basename( $path ) ) . '">' . "\n" . $code . "\n</" . $tag . ">\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- our own files.
}

function bbcoa_print_frontend_code() {
	$replacements = bbcoa_replacements();
	foreach ( bbcoa_js_files() as $name ) {
		bbcoa_print_file( BBCOA_DIR . 'assets/js/' . $name . '.js', 'script', $replacements );
	}
	foreach ( bbcoa_css_files() as $name ) {
		bbcoa_print_file( BBCOA_DIR . 'assets/css/' . $name . '.css', 'style', $replacements );
	}
}
add_action( 'wp_head', 'bbcoa_print_frontend_code', 10 );

// Backend Overrides also applied in wp-admin.
function bbcoa_print_admin_code() {
	bbcoa_print_file( BBCOA_DIR . 'assets/css/backend-overrides.css', 'style', [] );
}
add_action( 'admin_head', 'bbcoa_print_admin_code', 10 );

// Formerly Appearance > Customize > Additional CSS on every site. Printed at
// the same point WordPress prints Additional CSS (wp_head priority 101).
function bbcoa_print_former_customizer_css() {
	echo "<style id=\"bbcoa-page-content-overflow\">\n.page-content {\n    overflow: unset;\n}\n</style>\n";
}
add_action( 'wp_head', 'bbcoa_print_former_customizer_css', 101 );
