<?php
/**
 * Updates from GitHub releases.
 *
 * Each release on github.com/bbmediallc/bb-countyops-addon must have a
 * bb-countyops-addon.zip asset (scripts/build.sh makes it, and the GitHub
 * Action attaches it). WordPress shows the update like any other plugin update.
 *
 * Sites can only see releases while the repository is public, so results are
 * cached briefly, and Dashboard > Updates > "Check again" always asks GitHub
 * fresh.
 *
 * Test versions (tags like v1.0.1-beta.1, published as GitHub pre-releases)
 * are offered only to sites with this line in wp-config.php:
 *   define( 'BBCOA_UPDATE_CHANNEL', 'beta' );
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BBCOA_GITHUB_REPO', 'bbmediallc/bb-countyops-addon' );
define( 'BBCOA_PACKAGE_NAME', 'bb-countyops-addon.zip' );

function bbcoa_update_channel() {
	return defined( 'BBCOA_UPDATE_CHANNEL' ) && 'beta' === BBCOA_UPDATE_CHANNEL ? 'beta' : 'stable';
}

function bbcoa_release_cache_key() {
	return 'bbcoa_release_' . bbcoa_update_channel();
}

// A GitHub release as the fields we need, or null if it has no plugin zip.
function bbcoa_parse_release( $body ) {
	if ( ! is_array( $body ) || empty( $body['tag_name'] ) || ! empty( $body['draft'] ) ) {
		return null;
	}
	$package = '';
	foreach ( isset( $body['assets'] ) ? (array) $body['assets'] : [] as $asset ) {
		if ( isset( $asset['name'] ) && BBCOA_PACKAGE_NAME === $asset['name'] ) {
			$package = $asset['browser_download_url'];
		}
	}
	if ( '' === $package ) {
		return null;
	}
	return [
		'version'   => ltrim( $body['tag_name'], 'vV' ),
		'package'   => $package,
		'url'       => isset( $body['html_url'] ) ? $body['html_url'] : '',
		'notes'     => isset( $body['body'] ) ? $body['body'] : '',
		'published' => isset( $body['published_at'] ) ? $body['published_at'] : '',
	];
}

function bbcoa_github_get( $path ) {
	$response = wp_remote_get( 'https://api.github.com/repos/' . BBCOA_GITHUB_REPO . $path, [
		'timeout' => 10,
		'headers' => [
			'Accept'     => 'application/vnd.github+json',
			'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . home_url(),
		],
	] );
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return null;
	}
	return json_decode( wp_remote_retrieve_body( $response ), true );
}

// Newest release for this site's channel. Cached for 1 hour, or 10 minutes
// after a failure (e.g. while the repository is private).
function bbcoa_latest_release( $force = false ) {
	$cache_key = bbcoa_release_cache_key();
	if ( ! $force ) {
		$cached = get_site_transient( $cache_key );
		if ( false !== $cached ) {
			return $cached ? $cached : null;
		}
	}

	$release = null;
	if ( 'beta' === bbcoa_update_channel() ) {
		// Newest of the recent releases, pre-releases included.
		foreach ( (array) bbcoa_github_get( '/releases?per_page=20' ) as $body ) {
			$candidate = bbcoa_parse_release( $body );
			if ( $candidate && ( ! $release || version_compare( $candidate['version'], $release['version'], '>' ) ) ) {
				$release = $candidate;
			}
		}
	} else {
		// GitHub's "latest" never includes pre-releases.
		$release = bbcoa_parse_release( bbcoa_github_get( '/releases/latest' ) );
	}

	// Failures are cached as 0 so they aren't retried on every page load.
	set_site_transient( $cache_key, $release ? $release : 0, $release ? HOUR_IN_SECONDS : 10 * MINUTE_IN_SECONDS );
	return $release;
}

// True once per request when someone clicked "Check again" on Dashboard > Updates.
function bbcoa_is_forced_update_check() {
	static $used = false;
	if ( $used || ! is_admin() || empty( $_GET['force-check'] ) ) {
		return false;
	}
	$used = true;
	return true;
}

function bbcoa_plugin_slug() {
	return dirname( BBCOA_BASENAME );
}

// "Requires at least", "Tested up to" and "Requires PHP" from this file's
// header. Without "tested", the Updates screen says "Not tested" for every
// WordPress version.
function bbcoa_plugin_requirements() {
	$data = get_file_data( BBCOA_FILE, [
		'requires'     => 'Requires at least',
		'tested'       => 'Tested up to',
		'requires_php' => 'Requires PHP',
	], 'plugin' );
	return array_filter( $data );
}

// The update entry WordPress lists for this plugin.
function bbcoa_update_item( $version, $package, $url ) {
	return (object) array_merge( [
		'id'          => 'github.com/' . BBCOA_GITHUB_REPO,
		'slug'        => bbcoa_plugin_slug(),
		'plugin'      => BBCOA_BASENAME,
		'new_version' => $version,
		'url'         => $url,
		'package'     => $package,
		'icons'       => [ 'svg' => BBCOA_URL . 'assets/icon.svg' ],
	], bbcoa_plugin_requirements() );
}

// Runs when WordPress refreshes its list of plugin updates (about twice a day).
function bbcoa_check_for_update( $transient ) {
	if ( ! is_object( $transient ) || empty( $transient->checked ) ) {
		return $transient;
	}

	$release = bbcoa_latest_release( bbcoa_is_forced_update_check() );
	if ( ! $release ) {
		// GitHub can't be reached (e.g. the repository is private). Still list
		// the plugin as up to date: WordPress only shows the Plugins page
		// "Enable auto-updates" link for plugins it has update info for.
		if ( ! isset( $transient->response[ BBCOA_BASENAME ] ) && ! isset( $transient->no_update[ BBCOA_BASENAME ] ) ) {
			$transient->no_update[ BBCOA_BASENAME ] = bbcoa_update_item( BBCOA_VERSION, '', 'https://bandbmedia.com/' );
		}
		return $transient;
	}

	$item = bbcoa_update_item( $release['version'], $release['package'], $release['url'] );
	if ( version_compare( BBCOA_VERSION, $release['version'], '<' ) ) {
		$transient->response[ BBCOA_BASENAME ] = $item;
	} else {
		unset( $transient->response[ BBCOA_BASENAME ] );
		$transient->no_update[ BBCOA_BASENAME ] = $item;
	}
	return $transient;
}
add_filter( 'pre_set_site_transient_update_plugins', 'bbcoa_check_for_update' );

// The "View details" popup.
function bbcoa_plugin_info( $result, $action, $args ) {
	if ( 'plugin_information' !== $action || ! isset( $args->slug ) || bbcoa_plugin_slug() !== $args->slug ) {
		return $result;
	}
	$release = bbcoa_latest_release();
	$notes   = $release && '' !== $release['notes'] ? wpautop( esc_html( $release['notes'] ) ) : '';
	$plugin  = get_file_data( BBCOA_FILE, [ 'name' => 'Plugin Name', 'description' => 'Description' ], 'plugin' );

	return (object) array_merge( [
		'name'          => $plugin['name'],
		'slug'          => bbcoa_plugin_slug(),
		'version'       => $release ? $release['version'] : BBCOA_VERSION,
		'author'        => 'B&amp;B Media, LLC',
		'homepage'      => 'https://bandbmedia.com/',
		'download_link' => $release ? $release['package'] : '',
		'last_updated'  => $release ? $release['published'] : '',
		'sections'      => [
			'description' => esc_html( $plugin['description'] ),
			'changelog'   => $notes,
		],
	], bbcoa_plugin_requirements() );
}
add_filter( 'plugins_api', 'bbcoa_plugin_info', 20, 3 );

// After this plugin updates, forget the cached release so the next check is fresh.
function bbcoa_after_update( $upgrader, $options ) {
	if ( ! isset( $options['action'], $options['type'] ) || 'update' !== $options['action'] || 'plugin' !== $options['type'] ) {
		return;
	}
	$plugins = isset( $options['plugins'] ) ? (array) $options['plugins'] : [];
	if ( isset( $options['plugin'] ) ) {
		$plugins[] = $options['plugin'];
	}
	if ( in_array( BBCOA_BASENAME, $plugins, true ) ) {
		delete_site_transient( bbcoa_release_cache_key() );
	}
}
add_action( 'upgrader_process_complete', 'bbcoa_after_update', 10, 2 );
