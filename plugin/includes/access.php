<?php
// Who can see County Settings.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_admin_emails() {
	$emails = [];
	foreach ( explode( ',', BBCOA_ADMIN_EMAILS ) as $email ) {
		$email = strtolower( trim( $email ) );
		if ( '' !== $email ) {
			$emails[] = $email;
		}
	}
	return $emails;
}

// True only for an administrator whose account email is in BBCOA_ADMIN_EMAILS.
function bbcoa_is_settings_admin() {
	if ( ! function_exists( 'wp_get_current_user' ) || ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		return false;
	}
	return in_array( strtolower( wp_get_current_user()->user_email ), bbcoa_admin_emails(), true );
}
