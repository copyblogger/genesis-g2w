<?php
/**
 * Registers a new admin page for the Genesis GoToWebinar Plugin
 *
 * @since 0.9.0
 */
class Genesis_G2W_Admin extends Genesis_Admin_Form {

	/**
	 * Create an admin menu item and settings page.
	 *
	 * @since 0.9.0
	 */
	function __construct() {

		$page_id = 'genesis-g2w';

		$menu_ops = array(
			'submenu' => array(
				'parent_slug' => 'genesis',
				'page_title'  => __( 'Genesis GoToWebinar Settings', 'genesis-g2w' ),
				'menu_title'  => __( 'GoToWebinar', 'genesis-g2w' )
			)
		);

		$page_ops = array(
			'save_button_text'  => __( 'Save Settings', 'genesis-g2w' ),
			'reset_button_text' => __( 'Reset Settings', 'genesis-g2w' ),
			'saved_notice_text' => __( 'Settings saved.', 'genesis-g2w' ),
			'reset_notice_text' => __( 'Settings reset.', 'genesis-g2w' ),
			'error_notice_text' => __( 'Error saving settings.', 'genesis-g2w' ),
		);

		$settings_field = 'genesis-g2w';

		$default_settings = array(
			'api_key'       => '',
			'access_token'  => '',
			'organizer_key' => '',
		);

		$this->create( $page_id, $menu_ops, $page_ops, $settings_field, $default_settings );

	}

	/**
	 * The Genesis GoToWebinar plugin settings form.
	 *
	 * @since 0.9.0
	 */
	function form() {

		?>

		<table class="form-table">
		<tbody>

			<tr valign="top">
				<th scope="row"><?php _e( 'API Key', 'genesis-g2w' ); ?></th>
				<td>
					<input type="text" name="<?php $this->field_name( 'api_key' ); ?>" id="<?php $this->field_id( 'api_key' ); ?>" value="<?php $this->field_value( 'api_key' ); ?>" />
				</td>
			</tr>


			<tr valign="top">
				<th scope="row"><?php _e( 'Access Token', 'genesis-g2w' ); ?></th>
				<td>
					<input type="text" name="<?php $this->field_name( 'access_token' ); ?>" id="<?php $this->field_id( 'access_token' ); ?>" value="<?php $this->field_value( 'access_token' ); ?>" />
				</td>
			</tr>


			<tr valign="top">
				<th scope="row"><?php _e( 'Organizer Key', 'genesis-g2w' ); ?></th>
				<td>
					<input type="text" name="<?php $this->field_name( 'organizer_key' ); ?>" id="<?php $this->field_id( 'organizer_key' ); ?>" value="<?php $this->field_value( 'organizer_key' ); ?>" />
				</td>
			</tr>

		</tbody>
		</table>

		<?php

	}

}
