<?php
// The County Admin role (formerly set up by hand with User Role Editor).
//
// An Editor who can also manage Gravity Forms forms and entries, TablePress
// tables, Yoast SEO settings, menus/widgets (edit_theme_options), and general
// settings (manage_options). County Settings and BB Media Core's Brand
// Settings still need the support@bandbmedia.com account on top of that.
//
// Kept in sync when the plugin is activated or updated: the role is created
// if missing, gets every capability listed here, and loses only capabilities
// this plugin granted before but no longer lists. Capabilities other plugins
// add to it (e.g. Wordfence's 2FA ones) are left alone. Deleting the plugin
// leaves the role in place so County Admins keep their access.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Copied from the County Admin role on all 7 collector sites (October 2026).
function bbcoa_county_admin_caps() {
	return [
		// WordPress: everything an Editor has.
		'read', 'upload_files', 'unfiltered_html', 'moderate_comments', 'manage_categories', 'manage_links',
		'edit_posts', 'edit_others_posts', 'edit_published_posts', 'edit_private_posts', 'publish_posts',
		'delete_posts', 'delete_others_posts', 'delete_published_posts', 'delete_private_posts', 'read_private_posts',
		'edit_pages', 'edit_others_pages', 'edit_published_pages', 'edit_private_pages', 'publish_pages',
		'delete_pages', 'delete_others_pages', 'delete_published_pages', 'delete_private_pages', 'read_private_pages',
		'level_0', 'level_1', 'level_2', 'level_3', 'level_4', 'level_5', 'level_6', 'level_7',
		// WordPress: beyond Editor.
		'edit_theme_options', // Appearance > Menus and Widgets.
		'manage_options',     // Settings screens, and plugin settings that check it.
		// Yoast Duplicate Post.
		'copy_posts',
		// The Events Calendar: Event Aggregator records.
		'edit_aggregator-records', 'edit_others_aggregator-records', 'edit_published_aggregator-records', 'edit_private_aggregator-records',
		'delete_aggregator-records', 'delete_others_aggregator-records', 'delete_published_aggregator-records', 'delete_private_aggregator-records',
		'publish_aggregator-records', 'read_private_aggregator-records',
		// Gravity Forms.
		'gravityforms_create_form', 'gravityforms_edit_forms', 'gravityforms_delete_forms',
		'gravityforms_view_entries', 'gravityforms_view_entry_notes', 'gravityforms_delete_entries',
		// TablePress.
		'tablepress_list_tables', 'tablepress_add_tables', 'tablepress_edit_tables', 'tablepress_copy_tables', 'tablepress_delete_tables',
		'tablepress_import_tables', 'tablepress_import_tables_url', 'tablepress_export_tables',
		'tablepress_access_options_screen', 'tablepress_access_about_screen',
		// Yoast SEO.
		'wpseo_bulk_edit', 'wpseo_edit_advanced_metadata', 'wpseo_manage_options',
	];
}

function bbcoa_sync_roles() {
	$caps = bbcoa_county_admin_caps();
	$role = get_role( 'county-admin' );
	if ( ! $role ) {
		$role = add_role( 'county-admin', 'County Admin', array_fill_keys( $caps, true ) );
	}
	if ( ! $role ) {
		return;
	}
	foreach ( $caps as $cap ) {
		if ( empty( $role->capabilities[ $cap ] ) ) {
			$role->add_cap( $cap );
		}
	}
	// Remove only what this plugin granted before and no longer lists.
	$previous = get_option( 'bbcoa_county_admin_caps', [] );
	foreach ( array_diff( (array) $previous, $caps ) as $cap ) {
		$role->remove_cap( $cap );
	}
	update_option( 'bbcoa_county_admin_caps', $caps, false );
}

// Activation doesn't run on updates, so also sync once per new version.
function bbcoa_maybe_upgrade() {
	if ( get_option( 'bbcoa_db_version' ) === BBCOA_VERSION ) {
		return;
	}
	bbcoa_sync_roles();
	update_option( 'bbcoa_db_version', BBCOA_VERSION );
}
add_action( 'init', 'bbcoa_maybe_upgrade' );
