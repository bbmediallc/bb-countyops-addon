<?php
// What County Admins see in wp-admin (formerly the Hide Admin Menu plugin).
//
// The same 22 menus and 107 submenus Hide Admin Menu hid for the County
// Admin role on Stoddard and Pulaski (identical on both), removed the same
// way: remove_menu_page() / remove_submenu_page() on admin_menu (now at
// PHP_INT_MAX, after every plugin has added its items), for any user who has the county-admin role. Administrators are
// unaffected. Slugs are exactly as Hide Admin Menu stored them (including
// its &#038; and &amp; encodings), since that's what it matched against.
//
// The hidden screens are also blocked: opening one by its address gets
// WordPress's usual "Sorry, you are not allowed to access this page." with a
// 403. (Hide Admin Menu only removed the links.)
//
// Added after testing on Wright (1.5.0): Link Checker, Dashboard > Updates,
// TablePress's Options/Modules/About/Upgrade pages, every Customizer link
// under Appearance (Customize, Background), Customize/Updates in the top
// admin bar, and a few plugin notices County Admins shouldn't see.
//
// What County Admins keep: Dashboard (main screen), Pages, Media, Forms,
// TablePress (All Tables, Add New Table, Import, Export), Appearance > Menus
// and Widgets, and Profile.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_county_admin_hidden_menus() {
	return [
		'edit-tags.php?taxonomy=link_category',
		'edit-comments.php',
		'edit.php',
		'edit.php?post_type=elementor_library',
		'edit.php?post_type=custom-css-js',
		'plugins.php',
		'tools.php',
		'options-general.php',
		'elementor-home',
		'wpseo_dashboard',
		'wdan-notices-wbcr_dan',
		'wdanp-edit-redirects-wbcr_dan',
		'wdanp-edit-admin-bar-wbcr_dan',
		'hide-admin-menu',
		'sg-cachepress',
		'Wordfence',
		'kirki',
		'wpel-settings-page',
		'envato-market',
		'wp-mail-smtp',
		'snippets',
		'elementor',
		// Added in 1.5.0.
		'blc_dash', // Broken Link Checker.
	];
}

// Parent menu slug => submenu slugs to hide under it.
function bbcoa_county_admin_hidden_submenus() {
	return [
		// Added in 1.5.0.
		'index.php' => [
			'update-core.php',
		],
		// BB Media Core replaces the Dashboard menu with its own "custom_dashboard"
		// menu, and Updates sits under that. index.php stays in case it's off.
		'custom_dashboard' => [
			'update-core.php',
		],
		'tablepress' => [
			'tablepress_options',
			'tablepress_modules',
			'tablepress_about',
			'tablepress-pricing',
		],
		// From Hide Admin Menu (plus a few marked 1.5.0).
		'edit-tags.php?taxonomy=link_category' => [
			'edit-tags.php?taxonomy=link_category',
		],
		'edit-comments.php' => [
			'edit-comments.php',
		],
		'edit.php' => [
			'edit.php',
			'post-new.php',
			'edit-tags.php?taxonomy=category',
			'edit-tags.php?taxonomy=post_tag',
		],
		'edit.php?post_type=elementor_library' => [
			'edit.php?post_type=elementor_library',
			'post-new.php?post_type=elementor_library',
			'edit-tags.php?taxonomy=elementor_library_category&amp;post_type=elementor_library',
		],
		'edit.php?post_type=custom-css-js' => [
			'edit.php?post_type=custom-css-js',
			'post-new.php?post_type=custom-css-js&#038;language=css',
			'post-new.php?post_type=custom-css-js&#038;language=js',
			'post-new.php?post_type=custom-css-js&#038;language=html',
			'custom-css-js-config',
		],
		'themes.php' => [
			'themes.php',
			'site-editor.php?p=/pattern',
			'font-library.php',
			'customize.php?return=%2Fwp-admin%2Fadmin.php%3Fpage%3Dhide-admin-menu&#038;autofocus%5Bcontrol%5D=background_image',
			'multiple_sidebars',
			'install-required-plugins',
			'custom-background',
			'theme-editor.php',
		],
		'plugins.php' => [
			'plugins.php',
			'plugin-install.php',
			'plugin-editor.php',
			'meta-box',
		],
		'tools.php' => [
			'tools.php',
			'import.php',
			'export.php',
			'site-health.php',
			'export-personal-data.php',
			'erase-personal-data.php',
			'action-scheduler',
			'redirection.php',
			'wpseo_redirects_tools',
			'disable_comments_tools',
		],
		'options-general.php' => [
			'options-general.php',
			'options-connectors.php',
			'options-writing.php',
			'options-reading.php',
			'options-discussion.php',
			'options-media.php',
			'options-permalink.php',
			'options-privacy.php',
			'wdan_settings-wbcr_dan',
			'duplicatepost',
			'ga-google-analytics',
			'settings-user-role-editor.php',
			'whl_settings',
			'breadcrumb-navxt',
			'disable_comments_settings',
		],
		'elementor-home' => [
			'admin.php?page=elementor-home',
			'elementor',
			'accessibility-settings',
		],
		'wpseo_dashboard' => [
			'wpseo_dashboard',
			'wpseo_page_settings',
			'wpseo_integrations',
			'wpseo_tools',
			'wpseo_page_academy',
			'wpseo_licenses',
			'wpseo_workouts',
			'wpseo_redirects',
			'wpseo_page_bulk_edit',
			'wpseo_page_support',
			'wpseo_upgrade_sidebar',
			'wpseo_brand_insights',
		],
		'blc_dash' => [
			'blc_dash',
			'plugins_cross_sell',
			'blc_local', // 1.5.0: Link Checker's "Local [old]" page.
		],
		'sg-cachepress' => [
			'sg-cachepress',
			'sgo_caching',
			'sgo_environment',
			'sgo_frontend',
			'sgo_media',
			'sgo_analysis',
		],
		'Wordfence' => [
			'Wordfence',
			'WordfenceWAF',
			'WordfenceScan',
			'WordfenceTools',
			'WordfenceAuditLog',
			'WFLS',
			'WordfenceOptions',
			'WordfenceSupport',
			'WordfenceUpgradeToPremium',
		],
		'kirki' => [
			'kirki',
			'kirki-home',
			'kirki-submissions',
			'kirki-settings',
		],
		'gf_edit_forms' => [
			'gf_export',
			'gf_addons',
			'gf_system_status',
			'gf_help',
		],
		'wp-mail-smtp' => [
			'wp-mail-smtp-dashboard',
			'wp-mail-smtp',
			'wp-mail-smtp-logs',
			'wp-mail-smtp-reports',
			'wp-mail-smtp-tools',
			'wp-mail-smtp-recommended-wpconsent',
			'wp-mail-smtp-about',
			'https://wpmailsmtp.com/lite-upgrade/?utm_source=WordPress&#038;utm_medium=admin-menu&#038;utm_campaign=liteplugin&#038;utm_locale=en_us&#038;utm_content=Upgrade%20to%20Pro',
			'wp-mail-smtp-setup-checklist',
		],
		'snippets' => [
			'snippets',
			'add-snippet',
			'code-snippets-stats',
			'import-code-snippets',
			'snippets-settings',
			'code-snippets-welcome',
			'code_snippets_upgrade',
		],
	];
}

function bbcoa_hide_county_admin_menus() {
	global $submenu;
	if ( ! bbcoa_is_county_admin() ) {
		return;
	}
	foreach ( bbcoa_county_admin_hidden_menus() as $slug ) {
		remove_menu_page( $slug );
	}
	foreach ( bbcoa_county_admin_hidden_submenus() as $parent => $children ) {
		foreach ( $children as $child ) {
			remove_submenu_page( $parent, $child );
		}
	}
	// Every Customizer link under Appearance (Customize, and Background on
	// themes that send it to the Customizer), whatever its position.
	if ( ! empty( $submenu['themes.php'] ) ) {
		foreach ( $submenu['themes.php'] as $i => $item ) {
			if ( isset( $item[2] ) && 0 === strpos( $item[2], 'customize.php' ) ) {
				unset( $submenu['themes.php'][ $i ] );
			}
		}
	}
}
// PHP_INT_MAX: TablePress Premium's "Upgrade" item comes from its Freemius SDK,
// which adds menu items at priority 999999999.
add_action( 'admin_menu', 'bbcoa_hide_county_admin_menus', PHP_INT_MAX );

function bbcoa_is_county_admin() {
	return in_array( 'county-admin', (array) wp_get_current_user()->roles, true );
}

// Top admin bar: no Customize or Updates.
function bbcoa_county_admin_bar( $bar ) {
	if ( bbcoa_is_county_admin() ) {
		$bar->remove_node( 'customize' );
		$bar->remove_node( 'updates' );
	}
}
add_action( 'admin_bar_menu', 'bbcoa_county_admin_bar', 999 );

// Plugin notices County Admins shouldn't see. (The Media Library's "Remember
// to add ALT text" reminder is CSS in backend-overrides.css and stays.)

// The theme's TGMPA "required or recommended plugins" notice: require a
// capability County Admins don't have.
function bbcoa_county_admin_tgmpa_capability( $capability ) {
	return bbcoa_is_county_admin() ? 'install_plugins' : $capability;
}
add_filter( 'tgmpa_show_admin_notice_capability', 'bbcoa_county_admin_tgmpa_capability' );

// WP Mail SMTP's dashboard widget.
function bbcoa_county_admin_dashboard_widgets() {
	if ( bbcoa_is_county_admin() ) {
		remove_meta_box( 'wp_mail_smtp_reports_widget_lite', 'dashboard', 'normal' );
	}
}
add_action( 'wp_dashboard_setup', 'bbcoa_county_admin_dashboard_widgets', 999 );

// Enable Media Replace's "New Beta Feature" box and Disable Comments' "help
// make Disable Comments even better" survey. Neither plugin has a setting or
// hook for these alone (their notice systems also carry messages County
// Admins need, like "file replaced"), so these two are removed by their
// wording, for County Admins only.
function bbcoa_county_admin_hide_notices() {
	if ( ! bbcoa_is_county_admin() ) {
		return;
	}
	?>
	<script>
	(function () {
		var phrases = ['New Beta Feature!', 'help make Disable Comments even better'];
		document.querySelectorAll('.notice, .updated, .error, .update-nag, [class*="notice"]').forEach(function (el) {
			var text = el.textContent || '';
			if (phrases.some(function (p) { return text.indexOf(p) !== -1; })) el.remove();
		});
	})();
	</script>
	<?php
}
add_action( 'admin_footer', 'bbcoa_county_admin_hide_notices' );

// Every hidden menu/submenu as [ path, [ query args ] ], e.g. "edit.php"
// with no args (Posts) or "admin.php" with [ 'page' => 'wpseo_dashboard' ].
function bbcoa_county_admin_blocked_screens() {
	$slugs = bbcoa_county_admin_hidden_menus();
	foreach ( bbcoa_county_admin_hidden_submenus() as $children ) {
		$slugs = array_merge( $slugs, $children );
	}
	$screens = [];
	foreach ( $slugs as $slug ) {
		$slug  = html_entity_decode( $slug, ENT_QUOTES );
		$path  = (string) wp_parse_url( $slug, PHP_URL_PATH );
		$query = [];
		wp_parse_str( (string) wp_parse_url( $slug, PHP_URL_QUERY ), $query );
		if ( false === strpos( $path, '.php' ) ) {
			// A plugin page slug, e.g. "wpseo_dashboard" => admin.php?page=wpseo_dashboard.
			$query = [ 'page' => $path ];
			$path  = '';
		}
		// Customizer links only differ by where to return to; block the Customizer
		// itself, which Hide Admin Menu's quirk also hid.
		if ( 'customize.php' === $path ) {
			$query = [];
		}
		$screens[] = [ $path, $query ];
	}
	return $screens;
}

function bbcoa_is_blocked_screen( $pagenow, $request ) {
	foreach ( bbcoa_county_admin_blocked_screens() as $screen ) {
		list( $path, $query ) = $screen;
		if ( '' !== $path && $path !== $pagenow ) {
			continue;
		}
		$match = true;
		foreach ( $query as $key => $value ) {
			if ( ! isset( $request[ $key ] ) || (string) $request[ $key ] !== (string) $value ) {
				$match = false;
				break;
			}
		}
		// Bare edit.php / post-new.php means Posts; Pages etc. use ?post_type=.
		if ( $match && in_array( $path, [ 'edit.php', 'post-new.php' ], true ) && ! isset( $query['post_type'] )
			&& ! empty( $request['post_type'] ) && 'post' !== $request['post_type'] ) {
			$match = false;
		}
		// Bare edit-tags.php would otherwise match every taxonomy.
		if ( $match && 'edit-tags.php' === $path && ! isset( $query['taxonomy'] ) && ! empty( $request['taxonomy'] ) ) {
			$match = false;
		}
		if ( $match ) {
			return true;
		}
	}
	return false;
}

function bbcoa_block_county_admin_screens() {
	global $pagenow;
	if ( wp_doing_ajax() || ! bbcoa_is_county_admin() ) {
		return;
	}
	if ( bbcoa_is_blocked_screen( $pagenow, $_GET ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to access this page.' ), '', [ 'response' => 403, 'back_link' => true ] );
	}
}
add_action( 'admin_init', 'bbcoa_block_county_admin_screens', 1 );
