<?php
// County registry.
//
// Built-in counties live in counties/<id>/county.php and ship with every
// release. Edits made in County Settings are saved on that site only (the
// bbcoa_counties option); for a built-in county only the changed fields are
// stored, so "Reset" just deletes them. A county added in County Settings
// exists only on that site until its exported file is committed as
// counties/<id>/county.php.
//
// Each site uses one county: the one chosen in County Settings, or else the
// one whose Website addresses include this site's address.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Every county setting, in the order County Settings shows them.
function bbcoa_county_fields() {
	return [
		'name'                   => [ 'section' => 'general', 'type' => 'text', 'label' => 'County name', 'help' => 'For your reference, e.g. "Wright County Collector".' ],
		'site_urls'              => [ 'section' => 'general', 'type' => 'urls', 'label' => 'Website addresses', 'help' => 'One per line, e.g. wright.bandbdev.com. List both the staging and live address so the switch to live just works. A site with no county chosen uses the county whose address matches.' ],

		'accent_color'           => [ 'section' => 'colors', 'type' => 'color', 'label' => 'Accent color', 'default' => '#800000', 'help' => 'Table header background, service card hover, FAQ question text, skip-to-content link outline and hover.' ],
		'link_color'             => [ 'section' => 'colors', 'type' => 'color', 'label' => 'Accordion link color', 'default' => '#8c0708', 'help' => 'Links inside accordion (FAQ) answers.' ],
		'footer_link_hover'      => [ 'section' => 'colors', 'type' => 'color', 'label' => 'Footer link hover color', 'default' => '#AF8C5D', 'help' => 'Footer heading links on hover.' ],

		'banner_image'           => [ 'section' => 'banner', 'type' => 'image', 'label' => 'Banner image', 'help' => 'Background of the page header banner and search results header. Leave blank to keep the theme\'s own.' ],
		'banner_position'        => [ 'section' => 'banner', 'type' => 'text', 'label' => 'Banner focal point', 'default' => '50% 50%', 'help' => 'Which part of the image stays in view, as "left top", e.g. 50% 40% shows a little more of the top.' ],

		'header_search'          => [ 'section' => 'header_search', 'type' => 'toggle', 'label' => 'Search in header', 'help' => 'Adds a site search box to the top bar of the header, beside the top menu. Its button uses the Accent color.' ],
	];
}

function bbcoa_county_sections() {
	return [
		'general'       => 'General',
		'colors'        => 'Colors',
		'banner'        => 'Page header banner',
		'header_search' => 'Header search',
	];
}

// Counties bundled with the plugin, keyed by folder name.
function bbcoa_builtin_counties() {
	static $counties = null;
	if ( null !== $counties ) {
		return $counties;
	}
	$counties = [];
	$files    = glob( BBCOA_DIR . 'counties/*/county.php' );
	foreach ( $files ? $files : [] as $file ) {
		$data = include $file;
		if ( is_array( $data ) ) {
			$counties[ basename( dirname( $file ) ) ] = $data;
		}
	}
	return $counties;
}

function bbcoa_saved_counties() {
	$saved = get_option( 'bbcoa_counties', [] );
	return is_array( $saved ) ? $saved : [];
}

// All counties with their final values. Each also carries:
// _id, _builtin (ships with the plugin), _customized (has site edits).
function bbcoa_get_counties() {
	$builtin = bbcoa_builtin_counties();
	$saved   = bbcoa_saved_counties();
	$blank   = array_fill_keys( array_keys( bbcoa_county_fields() ), '' );
	$out     = [];

	foreach ( $builtin as $id => $values ) {
		$edits      = isset( $saved[ $id ] ) ? (array) $saved[ $id ] : [];
		$out[ $id ] = array_merge( $blank, $values, $edits, [
			'_id'         => $id,
			'_builtin'    => true,
			'_customized' => ! empty( $edits ),
		] );
	}
	foreach ( $saved as $id => $values ) {
		if ( ! isset( $out[ $id ] ) ) {
			$out[ $id ] = array_merge( $blank, (array) $values, [
				'_id'         => $id,
				'_builtin'    => false,
				'_customized' => false,
			] );
		}
	}
	uasort( $out, function ( $a, $b ) {
		return strcasecmp( $a['name'], $b['name'] );
	} );
	return $out;
}

function bbcoa_county_exists( $id ) {
	$counties = bbcoa_get_counties();
	return is_string( $id ) && isset( $counties[ $id ] );
}

// "https://www.Example.com/path" => "example.com".
function bbcoa_normalize_host( $url ) {
	$url  = trim( strtolower( $url ) );
	$host = wp_parse_url( false === strpos( $url, '//' ) ? 'https://' . $url : $url, PHP_URL_HOST );
	return $host ? preg_replace( '/^www\./', '', $host ) : '';
}

function bbcoa_county_hosts( $county ) {
	$hosts = [];
	foreach ( preg_split( '/[\s,]+/', (string) $county['site_urls'], -1, PREG_SPLIT_NO_EMPTY ) as $url ) {
		$host = bbcoa_normalize_host( $url );
		if ( '' !== $host ) {
			$hosts[] = $host;
		}
	}
	return $hosts;
}

// The county whose Website addresses include this site, or ''.
function bbcoa_county_for_this_site() {
	$host = bbcoa_normalize_host( home_url() );
	foreach ( bbcoa_get_counties() as $id => $county ) {
		if ( in_array( $host, bbcoa_county_hosts( $county ), true ) ) {
			return $id;
		}
	}
	return '';
}

// The county this site uses: the saved choice, else a Website address match.
function bbcoa_active_county_id() {
	$saved = get_option( 'bbcoa_active_county' );
	if ( bbcoa_county_exists( $saved ) ) {
		return $saved;
	}
	return bbcoa_county_for_this_site();
}

// The active county's values, with defaults filled in for blank fields.
// With no county (a new site not set up yet) every value is its default.
function bbcoa_county( $id = null ) {
	$counties = bbcoa_get_counties();
	if ( null === $id ) {
		$id = bbcoa_active_county_id();
	}
	$county = isset( $counties[ $id ] ) ? $counties[ $id ] : array_fill_keys( array_keys( bbcoa_county_fields() ), '' );
	foreach ( bbcoa_county_fields() as $key => $field ) {
		if ( '' === (string) $county[ $key ] && isset( $field['default'] ) ) {
			$county[ $key ] = $field['default'];
		}
	}
	return $county;
}

// Banner image as a full URL. Stored as a path ("/wp-content/uploads/...")
// when it's on this site, so it keeps working when the site moves from
// staging to its live address.
function bbcoa_banner_url( $county ) {
	$value = trim( (string) $county['banner_image'] );
	if ( '' === $value ) {
		return '';
	}
	return 0 === strpos( $value, '/' ) && 0 !== strpos( $value, '//' ) ? home_url( $value ) : $value;
}

function bbcoa_is_color( $value ) {
	return (bool) preg_match( '/^#([0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/i', (string) $value );
}

// Cleans submitted County Settings values. Unknown keys are dropped.
function bbcoa_sanitize_county( $input ) {
	$clean = [];
	foreach ( bbcoa_county_fields() as $key => $field ) {
		$value = isset( $input[ $key ] ) && is_scalar( $input[ $key ] ) ? trim( wp_unslash( (string) $input[ $key ] ) ) : '';
		switch ( $field['type'] ) {
			case 'urls':
				$hosts = [];
				foreach ( preg_split( '/[\s,]+/', $value, -1, PREG_SPLIT_NO_EMPTY ) as $url ) {
					$host = bbcoa_normalize_host( $url );
					if ( '' !== $host ) {
						$hosts[] = $host;
					}
				}
				$value = implode( "\n", array_unique( $hosts ) );
				break;
			case 'color':
				$value = bbcoa_is_color( $value ) ? $value : '';
				break;
			case 'image':
				$value = esc_url_raw( $value );
				// Same-site images are kept as a path; see bbcoa_banner_url().
				$home = untrailingslashit( home_url() );
				if ( '' !== $value && 0 === strpos( $value, $home . '/' ) ) {
					$value = substr( $value, strlen( $home ) );
				}
				break;
			case 'toggle':
				$value = '' !== $value ? '1' : '';
				break;
			default:
				$value = sanitize_text_field( $value );
				if ( 'banner_position' === $key && ! preg_match( '/^[0-9a-z%.\s-]*$/i', $value ) ) {
					$value = '';
				}
		}
		$clean[ $key ] = $value;
	}
	return $clean;
}

// Saves a county. For built-in counties only the fields that differ from the
// bundled county.php are stored.
function bbcoa_save_county( $id, $values ) {
	$saved   = bbcoa_saved_counties();
	$builtin = bbcoa_builtin_counties();

	if ( isset( $builtin[ $id ] ) ) {
		$diff = [];
		foreach ( $values as $key => $value ) {
			$default = isset( $builtin[ $id ][ $key ] ) ? (string) $builtin[ $id ][ $key ] : '';
			if ( $value !== $default ) {
				$diff[ $key ] = $value;
			}
		}
		if ( ! $diff ) {
			unset( $saved[ $id ] );
			update_option( 'bbcoa_counties', $saved );
			return;
		}
		$values = $diff;
	}
	$saved[ $id ] = $values;
	update_option( 'bbcoa_counties', $saved );
}

// Deletes a county added on this site, or clears a built-in county's edits.
function bbcoa_delete_saved_county( $id ) {
	$saved = bbcoa_saved_counties();
	unset( $saved[ $id ] );
	update_option( 'bbcoa_counties', $saved );
}

// The county as the text of a county.php file, for adding it to the plugin.
function bbcoa_county_file_contents( $county ) {
	$lines = [];
	foreach ( array_keys( bbcoa_county_fields() ) as $key ) {
		$lines[] = "\t" . var_export( $key, true ) . ' => ' . var_export( (string) $county[ $key ], true ) . ',';
	}
	return "<?php\n"
		. '// ' . str_replace( [ "\r", "\n", '?>' ], ' ', $county['name'] ) . ". Values left '' use the defaults in\n"
		. "// bbcoa_county_fields() (includes/counties.php).\n\n"
		. "if ( ! defined( 'ABSPATH' ) ) {\n\texit;\n}\n\n"
		. "return [\n" . implode( "\n", $lines ) . "\n];\n";
}
