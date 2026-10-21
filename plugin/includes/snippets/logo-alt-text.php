<?php
// Logo alt text (WCAG 1.1.1, 2.4.4).
//
// GovArnex hard-codes alt="Logo" on its theme logo images (mobile menu logo,
// preloader, default header and sticky logos) in
// framework/classes/govarnex-int.php, so the mobile menu's home link was
// announced as "Logo". The text goes through WordPress translation
// (esc_attr_e( 'Logo', 'govarnex' )), so this swaps it for the site's name,
// e.g. "Wright County Collector of Revenue". Changing the image in the Media
// Library wouldn't help, and the theme file isn't edited.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// gettext_govarnex only runs for the theme's own strings.
function bbcoa_logo_alt_text( $translation, $text ) {
	if ( 'Logo' !== $text ) {
		return $translation;
	}
	$name = get_bloginfo( 'name' );
	return '' !== $name ? $name : $translation;
}
add_filter( 'gettext_govarnex', 'bbcoa_logo_alt_text', 10, 2 );
