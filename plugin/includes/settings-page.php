<?php
// County Settings: Dashboard > County Settings (under BB Media Core's custom
// Dashboard menu), or Settings > County Settings on a site without it.
// Visible only to bbcoa_is_settings_admin().

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function bbcoa_add_settings_page() {
	if ( ! bbcoa_is_settings_admin() ) {
		return;
	}
	$GLOBALS['bbcoa_settings_hook'] = add_submenu_page( bbcoa_menu_parent(), 'County Settings', 'County Settings', 'manage_options', 'bbcoa-county-settings', 'bbcoa_render_settings' );
}
// After BB Media Core adds its Dashboard menu and its own pages.
add_action( 'admin_menu', 'bbcoa_add_settings_page', 20 );

function bbcoa_settings_assets( $hook ) {
	if ( empty( $GLOBALS['bbcoa_settings_hook'] ) || $hook !== $GLOBALS['bbcoa_settings_hook'] ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'bbcoa-settings', BBCOA_URL . 'assets/admin/settings.css', [], BBCOA_VERSION );
	wp_enqueue_script( 'bbcoa-settings', BBCOA_URL . 'assets/admin/settings.js', [ 'jquery' ], BBCOA_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'bbcoa_settings_assets' );

// ---------------------------------------------------------------------------
// Form handlers (admin-post.php)
// ---------------------------------------------------------------------------

function bbcoa_verify_request( $action ) {
	if ( ! bbcoa_is_settings_admin() ) {
		wp_die( 'You do not have permission to change county settings.', 403 );
	}
	check_admin_referer( $action );
}

function bbcoa_settings_redirect( $args = [] ) {
	wp_safe_redirect( add_query_arg( $args, bbcoa_settings_url() ) );
	exit;
}

function bbcoa_handle_set_active() {
	bbcoa_verify_request( 'bbcoa_set_active' );
	$id = isset( $_POST['county'] ) ? sanitize_key( wp_unslash( $_POST['county'] ) ) : '';
	if ( '' === $id ) {
		delete_option( 'bbcoa_active_county' );
	} elseif ( bbcoa_county_exists( $id ) ) {
		update_option( 'bbcoa_active_county', $id );
		delete_option( 'bbcoa_needs_setup' );
	} else {
		bbcoa_settings_redirect( [ 'bbcoa_msg' => 'unknown' ] );
	}
	bbcoa_settings_redirect( [ 'bbcoa_msg' => 'activated' ] );
}
add_action( 'admin_post_bbcoa_set_active', 'bbcoa_handle_set_active' );

function bbcoa_handle_save_county() {
	bbcoa_verify_request( 'bbcoa_save_county' );
	$is_new = ! empty( $_POST['is_new'] );
	$values = bbcoa_sanitize_county( isset( $_POST['county_fields'] ) ? (array) $_POST['county_fields'] : [] );

	if ( '' === $values['name'] ) {
		bbcoa_settings_redirect( [ 'bbcoa_msg' => 'name_required', 'edit' => $is_new ? 'new' : sanitize_key( wp_unslash( $_POST['id'] ?? '' ) ) ] );
	}

	if ( $is_new ) {
		$requested = isset( $_POST['id'] ) ? wp_unslash( $_POST['id'] ) : '';
		$id        = sanitize_key( '' !== $requested ? $requested : preg_replace( '/\s+county.*$/i', '', $values['name'] ) );
		if ( '' === $id || bbcoa_county_exists( $id ) ) {
			bbcoa_settings_redirect( [ 'bbcoa_msg' => 'id_taken', 'edit' => 'new' ] );
		}
	} else {
		$id = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : '';
		if ( ! bbcoa_county_exists( $id ) ) {
			bbcoa_settings_redirect( [ 'bbcoa_msg' => 'unknown' ] );
		}
	}

	bbcoa_save_county( $id, $values );

	// Adding a county from the site it belongs to makes it this site's county.
	if ( $is_new && '' === bbcoa_active_county_id() && bbcoa_county_for_this_site() === $id ) {
		update_option( 'bbcoa_active_county', $id );
		delete_option( 'bbcoa_needs_setup' );
	}
	bbcoa_settings_redirect( [ 'bbcoa_msg' => 'saved', 'edit' => $id ] );
}
add_action( 'admin_post_bbcoa_save_county', 'bbcoa_handle_save_county' );

// Deletes a county added on this site, or resets a built-in county.
function bbcoa_handle_delete_county() {
	bbcoa_verify_request( 'bbcoa_delete_county' );
	$id       = isset( $_POST['id'] ) ? sanitize_key( wp_unslash( $_POST['id'] ) ) : '';
	$counties = bbcoa_get_counties();
	if ( ! isset( $counties[ $id ] ) ) {
		bbcoa_settings_redirect( [ 'bbcoa_msg' => 'unknown' ] );
	}
	if ( ! $counties[ $id ]['_builtin'] && $id === bbcoa_active_county_id() ) {
		bbcoa_settings_redirect( [ 'bbcoa_msg' => 'delete_active' ] );
	}
	bbcoa_delete_saved_county( $id );
	bbcoa_settings_redirect( [ 'bbcoa_msg' => $counties[ $id ]['_builtin'] ? 'reset' : 'deleted' ] );
}
add_action( 'admin_post_bbcoa_delete_county', 'bbcoa_handle_delete_county' );

// ---------------------------------------------------------------------------
// Page
// ---------------------------------------------------------------------------

function bbcoa_settings_messages() {
	return [
		'activated'     => [ 'success', 'This site\'s county is saved.' ],
		'saved'         => [ 'success', 'County saved.' ],
		'deleted'       => [ 'success', 'County deleted.' ],
		'reset'         => [ 'success', 'County reset to its built-in settings.' ],
		'unknown'       => [ 'error', 'That county does not exist.' ],
		'id_taken'      => [ 'error', 'That ID is empty or already used. Choose another.' ],
		'name_required' => [ 'error', 'County name is required.' ],
		'delete_active' => [ 'error', 'You cannot delete this site\'s county. Choose a different county for this site first.' ],
	];
}

function bbcoa_render_settings() {
	if ( ! bbcoa_is_settings_admin() ) {
		return;
	}
	$messages = bbcoa_settings_messages();
	$msg      = isset( $_GET['bbcoa_msg'] ) ? sanitize_key( $_GET['bbcoa_msg'] ) : '';
	$edit     = isset( $_GET['edit'] ) ? sanitize_key( $_GET['edit'] ) : '';

	echo '<div class="wrap bbcoa-settings"><h1>County Settings</h1>';
	if ( isset( $messages[ $msg ] ) ) {
		printf( '<div class="notice notice-%s is-dismissible"><p>%s</p></div>', esc_attr( $messages[ $msg ][0] ), esc_html( $messages[ $msg ][1] ) );
	}
	if ( '' !== $edit ) {
		bbcoa_render_county_editor( $edit );
	} else {
		bbcoa_render_active_picker();
		bbcoa_render_county_list();
	}
	echo '</div>';
}

function bbcoa_render_active_picker() {
	$active   = bbcoa_active_county_id();
	$saved    = get_option( 'bbcoa_active_county' );
	$counties = bbcoa_get_counties();
	?>
	<div class="bbcoa-card<?php echo '' === $active ? ' bbcoa-setup' : ''; ?>">
		<h2>This site's county</h2>
		<?php if ( '' === $active ) : ?>
			<p>No county matches <code><?php echo esc_html( bbcoa_normalize_host( home_url() ) ); ?></code> yet. Choose one below, or <a href="<?php echo esc_url( add_query_arg( 'edit', 'new', bbcoa_settings_url() ) ); ?>">add a county</a> with this site's address.</p>
		<?php elseif ( ! bbcoa_county_exists( $saved ) ) : ?>
			<p>Picked automatically because <code><?php echo esc_html( bbcoa_normalize_host( home_url() ) ); ?></code> is one of its Website addresses.</p>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'bbcoa_set_active' ); ?>
			<input type="hidden" name="action" value="bbcoa_set_active">
			<select name="county">
				<option value="">Match by website address</option>
				<?php foreach ( $counties as $id => $county ) : ?>
					<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $id, $saved ); ?>><?php echo esc_html( $county['name'] ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php submit_button( 'Save', 'primary', 'submit', false ); ?>
		</form>
	</div>
	<?php
}

function bbcoa_render_county_list() {
	$active = bbcoa_active_county_id();
	?>
	<div class="bbcoa-card">
		<h2>Counties</h2>
		<p>Built-in counties ship with every plugin update. Changes you save here apply to this site only.</p>
		<table class="widefat striped">
			<thead><tr><th>County</th><th>Website addresses</th><th>Header search</th><th></th></tr></thead>
			<tbody>
			<?php foreach ( bbcoa_get_counties() as $id => $county ) : ?>
				<tr>
					<td><strong><?php echo esc_html( $county['name'] ); ?></strong><?php echo $id === $active ? ' <span class="bbcoa-pill">This site</span>' : ''; ?><br><code><?php echo esc_html( $id ); ?></code></td>
					<td><?php echo implode( '<br>', array_map( 'esc_html', bbcoa_county_hosts( $county ) ) ); ?></td>
					<td><?php echo '1' === (string) $county['header_search'] ? 'On' : 'Off'; ?></td>
					<td class="bbcoa-actions">
						<a class="button" href="<?php echo esc_url( add_query_arg( 'edit', $id, bbcoa_settings_url() ) ); ?>">Edit</a>
						<?php if ( $county['_customized'] || ( ! $county['_builtin'] && $id !== $active ) ) : ?>
							<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-confirm="<?php echo esc_attr( $county['_builtin'] ? 'Reset this county to its built-in settings?' : 'Delete this county?' ); ?>">
								<?php wp_nonce_field( 'bbcoa_delete_county' ); ?>
								<input type="hidden" name="action" value="bbcoa_delete_county">
								<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>">
								<button class="button button-link-delete"><?php echo $county['_builtin'] ? 'Reset' : 'Delete'; ?></button>
							</form>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p><a class="button button-primary" href="<?php echo esc_url( add_query_arg( 'edit', 'new', bbcoa_settings_url() ) ); ?>">Add a county</a></p>
	</div>
	<?php
}

function bbcoa_render_county_editor( $id ) {
	$is_new = 'new' === $id;
	if ( $is_new ) {
		// New counties start from the defaults, with this site's address filled in.
		$county = array_fill_keys( array_keys( bbcoa_county_fields() ), '' );
		$county['site_urls'] = bbcoa_normalize_host( home_url() );
	} else {
		if ( ! bbcoa_county_exists( $id ) ) {
			echo '<p>That county does not exist.</p>';
			return;
		}
		$counties = bbcoa_get_counties();
		$county   = $counties[ $id ];
	}
	$fields = bbcoa_county_fields();
	?>
	<p><a href="<?php echo esc_url( bbcoa_settings_url() ); ?>">&larr; All counties</a></p>
	<h2><?php echo $is_new ? 'Add a county' : 'Edit ' . esc_html( $county['name'] ); ?></h2>

	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'bbcoa_save_county' ); ?>
		<input type="hidden" name="action" value="bbcoa_save_county">
		<?php if ( $is_new ) : ?>
			<input type="hidden" name="is_new" value="1">
			<div class="bbcoa-card">
				<table class="form-table" role="presentation"><tr>
					<th scope="row"><label for="bbcoa-id">ID</label></th>
					<td><input type="text" id="bbcoa-id" name="id" class="regular-text">
					<p class="description">Short lowercase name with no spaces, e.g. <code>greene</code>. Leave blank to make one from the county name. It can't be changed later.</p></td>
				</tr></table>
			</div>
		<?php else : ?>
			<input type="hidden" name="id" value="<?php echo esc_attr( $id ); ?>">
		<?php endif; ?>

		<?php foreach ( bbcoa_county_sections() as $section => $title ) : ?>
			<div class="bbcoa-card">
				<h2><?php echo esc_html( $title ); ?></h2>
				<table class="form-table" role="presentation">
				<?php
				foreach ( $fields as $key => $field ) :
					if ( $field['section'] !== $section ) {
						continue;
					}
					$field_id = 'bbcoa-field-' . $key;
					$name     = 'county_fields[' . $key . ']';
					$value    = (string) $county[ $key ];
					$default  = isset( $field['default'] ) ? $field['default'] : '';
					?>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( $field_id ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
						<td>
						<?php if ( 'urls' === $field['type'] ) : ?>
							<textarea id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="3" class="regular-text code"><?php echo esc_textarea( $value ); ?></textarea>
						<?php elseif ( 'color' === $field['type'] ) : ?>
							<span class="bbcoa-color">
								<input type="color" value="<?php echo esc_attr( substr( '' !== $value ? $value : $default, 0, 7 ) ); ?>" aria-label="Pick <?php echo esc_attr( $field['label'] ); ?>">
								<input type="text" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="code" size="10" placeholder="<?php echo esc_attr( $default ); ?>">
							</span>
						<?php elseif ( 'image' === $field['type'] ) : ?>
							<span class="bbcoa-image">
								<span class="bbcoa-image-preview"><?php
								$preview = bbcoa_banner_url( [ 'banner_image' => $value ] );
								if ( $preview ) {
									printf( '<img src="%s" alt="">', esc_url( $preview ) );
								}
								?></span>
								<input type="text" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
								<button type="button" class="button bbcoa-choose-image">Choose image</button>
							</span>
						<?php elseif ( 'toggle' === $field['type'] ) : ?>
							<label><input type="checkbox" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( '1', $value ); ?>> On</label>
						<?php else : ?>
							<input type="text" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="<?php echo esc_attr( $default ); ?>">
						<?php endif; ?>
						<?php if ( ! empty( $field['help'] ) ) : ?>
							<p class="description"><?php echo esc_html( $field['help'] ); ?><?php echo '' !== $default ? ' Blank uses ' . esc_html( $default ) . '.' : ''; ?></p>
						<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
				</table>
			</div>
		<?php endforeach; ?>

		<?php submit_button( $is_new ? 'Add county' : 'Save county' ); ?>
	</form>

	<?php if ( ! $is_new ) : ?>
		<div class="bbcoa-card">
			<h2>Ship this county with the plugin</h2>
			<p>Changes saved here only apply to this site. To make them the built-in settings for every future install, save this text as <code>plugin/counties/<?php echo esc_html( $id ); ?>/county.php</code> in the plugin's code and release an update.</p>
			<textarea class="large-text code" rows="14" readonly onclick="this.select()"><?php echo esc_textarea( bbcoa_county_file_contents( $county ) ); ?></textarea>
		</div>
	<?php endif;
}
