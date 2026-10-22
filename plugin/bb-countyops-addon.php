<?php
/**
 * Plugin Name: B&B Media CountyOps Addon
 * Plugin URI: https://github.com/bbmediallc/bb-countyops-addon
 * Description: Shared accessibility fixes, theme styling and features for B&B Media's county office websites (GovArnex theme + CountyOps). Each site picks its county under Dashboard > County Settings.
 * Version: 1.5.4
 * Author: B&B Media, LLC
 * Author URI: https://bandbmedia.com/
 * Requires at least: 6.5
 * Tested up to: 7.1
 * Requires PHP: 7.4
 * Requires Plugins: elementor
 * Update URI: https://github.com/bbmediallc/bb-countyops-addon
 */

// Never rename this file or the bb-countyops-addon folder: WordPress
// identifies the plugin as "bb-countyops-addon/bb-countyops-addon.php", and
// renaming either one would deactivate it on every site that updates.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Must match the Version header above. scripts/build.sh refuses to build
// if they differ.
define( 'BBCOA_VERSION', '1.5.4' );
define( 'BBCOA_FILE', __FILE__ );
define( 'BBCOA_BASENAME', plugin_basename( __FILE__ ) );
define( 'BBCOA_DIR', plugin_dir_path( __FILE__ ) );
define( 'BBCOA_URL', plugin_dir_url( __FILE__ ) );

// Only these WordPress accounts can see County Settings. To change it on a
// single site, add this to wp-config.php:
// define( 'BBCOA_ADMIN_EMAILS', 'support@bandbmedia.com, someone@example.com' );
if ( ! defined( 'BBCOA_ADMIN_EMAILS' ) ) {
	define( 'BBCOA_ADMIN_EMAILS', 'support@bandbmedia.com' );
}

require_once BBCOA_DIR . 'includes/access.php';
require_once BBCOA_DIR . 'includes/counties.php';
require_once BBCOA_DIR . 'includes/setup.php';
require_once BBCOA_DIR . 'includes/roles.php';
require_once BBCOA_DIR . 'includes/admin-menu.php';
require_once BBCOA_DIR . 'includes/settings-page.php';
require_once BBCOA_DIR . 'includes/output.php';
require_once BBCOA_DIR . 'includes/updater.php';

// Former Code Snippets snippets, one file each. See README.md for what each
// one does and why.
require_once BBCOA_DIR . 'includes/snippets/ada-sitemap.php';
require_once BBCOA_DIR . 'includes/snippets/viewport-zoom.php';
require_once BBCOA_DIR . 'includes/snippets/elementor-link-options-open.php';
require_once BBCOA_DIR . 'includes/snippets/icon-box-duplicate-links.php';
require_once BBCOA_DIR . 'includes/snippets/clickable-home-pods.php';
require_once BBCOA_DIR . 'includes/snippets/faq-answer-editor.php';
require_once BBCOA_DIR . 'includes/snippets/faq-toggle-buttons.php';
require_once BBCOA_DIR . 'includes/snippets/header-search.php';
require_once BBCOA_DIR . 'includes/snippets/logo-alt-text.php';

register_activation_hook( __FILE__, 'bbcoa_activate' );
