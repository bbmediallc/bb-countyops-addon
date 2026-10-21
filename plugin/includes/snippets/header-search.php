<?php
// Header search (started as Laclede's Code Snippets: "Add Search to Header").
//
// Turned on per county in County Settings ("Search in header"). Puts a site
// search form in the GovArnex top bar, right after the top-left menu. The
// form is built in here (no Elementor template needed) and submits to the
// normal search results page. Styles: assets/css/header-search.css; the
// button uses the county's Accent color.
//
// GovArnex has no hook in its top bar, so an output buffer inserts the form
// after the top-left menu's closing </ul>.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_header_search_enabled() {
	$county = bbcoa_county();
	return '1' === (string) $county['header_search'];
}

function bbcoa_header_search_skip() {
	return is_admin() || wp_doing_ajax() || is_feed() || isset( $_GET['elementor-preview'] );
}

function bbcoa_header_search_form() {
	ob_start();
	?>
	<div class="bb-header-template bbcoa-header-search">
		<form class="bbcoa-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="bbcoa-search__label" for="bbcoa-header-search-input">Search this site</label>
			<input id="bbcoa-header-search-input" class="bbcoa-search__input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Type to start searching...">
			<button class="bbcoa-search__submit" type="submit" aria-label="Search"><i class="icon-search" aria-hidden="true"></i></button>
		</form>
	</div>
	<?php
	return ob_get_clean();
}

function bbcoa_header_search_buffer() {
	if ( bbcoa_header_search_skip() || ! bbcoa_header_search_enabled() ) {
		return;
	}
	// Built now: PHP doesn't allow ob_start() inside a buffer's callback.
	$insert = bbcoa_header_search_form();
	ob_start( function ( $html ) use ( $insert ) {
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
