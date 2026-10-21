<?php
// First-run setup.
//
// On activation the site's county is picked automatically when its address
// matches a county's Website addresses. Otherwise support@bandbmedia.com is
// sent to County Settings to choose or add one; until then the shared code
// runs with the default colors and no banner or header search.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_activate() {
	if ( ! bbcoa_county_exists( get_option( 'bbcoa_active_county' ) ) ) {
		$match = bbcoa_county_for_this_site();
		if ( '' !== $match ) {
			update_option( 'bbcoa_active_county', $match );
		} else {
			update_option( 'bbcoa_needs_setup', 1 );
			set_transient( 'bbcoa_setup_redirect', 1, 5 * MINUTE_IN_SECONDS );
		}
	}
	bbcoa_sync_roles();
	update_option( 'bbcoa_db_version', BBCOA_VERSION );
}

function bbcoa_needs_setup() {
	return (bool) get_option( 'bbcoa_needs_setup' ) && '' === bbcoa_active_county_id();
}

// County Settings goes under BB Media Core's custom Dashboard menu when that
// plugin is active (2.x or the older 1.6.x), otherwise under Settings.
function bbcoa_menu_parent() {
	return function_exists( 'bbcore_render_dashboard' ) || function_exists( 'custom_dashboard_content' ) ? 'custom_dashboard' : 'options-general.php';
}

function bbcoa_settings_url() {
	$base = 'custom_dashboard' === bbcoa_menu_parent() ? 'admin.php' : 'options-general.php';
	return admin_url( $base . '?page=bbcoa-county-settings' );
}

// Right after activation, take the settings admin straight to setup.
function bbcoa_setup_redirect() {
	if ( ! get_transient( 'bbcoa_setup_redirect' ) || ! bbcoa_is_settings_admin() ) {
		return;
	}
	if ( wp_doing_ajax() || isset( $_GET['activate-multi'] ) ) {
		return;
	}
	delete_transient( 'bbcoa_setup_redirect' );
	wp_safe_redirect( bbcoa_settings_url() );
	exit;
}
add_action( 'admin_init', 'bbcoa_setup_redirect', 1 );

// Reminder on other admin screens until a county is chosen.
function bbcoa_setup_notice() {
	if ( ! bbcoa_needs_setup() || ! bbcoa_is_settings_admin() ) {
		return;
	}
	$screen = get_current_screen();
	if ( $screen && false !== strpos( $screen->id, 'bbcoa-county-settings' ) ) {
		return;
	}
	printf(
		'<div class="notice notice-warning"><p><strong>CountyOps Addon:</strong> this site isn\'t matched to a county yet. <a href="%s">Choose or add its county</a></p></div>',
		esc_url( bbcoa_settings_url() )
	);
}
add_action( 'admin_notices', 'bbcoa_setup_notice' );
