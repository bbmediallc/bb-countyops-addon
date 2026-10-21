<?php
// Runs when the plugin is deleted from the Plugins page (not on deactivate
// or update). Removes this plugin's saved settings.

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// The County Admin role is left in place so County Admins keep their access.
foreach ( [ 'bbcoa_active_county', 'bbcoa_counties', 'bbcoa_needs_setup', 'bbcoa_db_version', 'bbcoa_county_admin_caps' ] as $option ) {
	delete_option( $option );
}
delete_transient( 'bbcoa_setup_redirect' );
delete_site_transient( 'bbcoa_release_stable' );
delete_site_transient( 'bbcoa_release_beta' );
