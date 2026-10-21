<?php
// Allow pinch-to-zoom (formerly Code Snippets: "Re-Enable Theme Pinch/Zoom").
//
// GovArnex's viewport tag blocks zooming (user-scalable=no / maximum-scale),
// which fails WCAG 1.4.4 (Resize Text). This rewrites any viewport tag on the
// page to "width=device-width, initial-scale=1.0", which lets people zoom.
//
// Carried over as-is, including two side effects worth revisiting someday:
// it removes WordPress's resource hints (dns-prefetch/preconnect tags), and
// it unhooks wp_no_robots (deprecated in WordPress 5.7, so a no-op today).

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wp_resource_hints', '__return_empty_array' );

remove_action( 'wp_head', 'wp_no_robots' );

// Buffer from get_header (before <head> is printed) to the end of the footer,
// and swap the viewport tag in that output.
function bbcoa_viewport_buffer_start() {
	ob_start( function ( $buffer ) {
		return preg_replace(
			'/<meta[^>]+name=["\']viewport["\'][^>]*>/i',
			'<meta name="viewport" content="width=device-width, initial-scale=1.0">',
			$buffer
		);
	} );
}
add_action( 'get_header', 'bbcoa_viewport_buffer_start' );

function bbcoa_viewport_buffer_end() {
	ob_end_flush();
}
add_action( 'wp_footer', 'bbcoa_viewport_buffer_end', 999 );
