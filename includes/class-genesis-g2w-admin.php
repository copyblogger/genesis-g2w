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

	const CLIENT_ID	             = 'JBW1kpzc9bgmlMkZlB9I4gyFZdO0i4m1';
	const CLIENT_SECRET          = 'hg1TkLA4YCG330qz';

	const REDIRECT_URI		     = 'http://tailoreddev.loc/wp-admin/admin.php?page=genesis-g2w';
	const AUTHORIZATION_ENDPOINT = 'https://api.citrixonline.com/oauth/authorize';
	const TOKEN_ENDPOINT		 = 'https://api.citrixonline.com/oauth/access_token';

	static $client;
	static $state;

	function __construct() {

		add_action( 'admin_init', array( $this, 'oauth_callack' ) );
		add_action( 'admin_enqueue_scripts' , array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'media_buttons' , array( $this, 'add_my_media_button' ) );
		add_action( 'admin_footer' , array( $this, 'footer_modal'));
		add_action( 'wp_ajax_gtw_get_webinars'   , array( $this, 'ajax_get_webinars' ) );

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

	static public function admin_enqueue_scripts() {
        wp_enqueue_script( 'jquery-magnific-popup', GENESIS_GTW_PLUGIN_URL .'/js/jquery.magnific-popup.min.js', array('jquery' ) );
        wp_enqueue_script( 'affiliate_links_admin', GENESIS_GTW_PLUGIN_URL .'/js/admin.js', array('jquery', 'jquery-magnific-popup' ) );
        wp_enqueue_style( 'magnific-popup-css' , GENESIS_GTW_PLUGIN_URL .'/css/magnific-popup.css' );
        wp_enqueue_style ( 'affiliate_links_admin_style', GENESIS_GTW_PLUGIN_URL .'/css/admin.css' );
    }

    function add_my_media_button() {
	    echo '<button id="insert_gtw_form_link" class="button"><img src="'.GENESIS_GTW_PLUGIN_URL.'/images/gtw_icon.png"> Add Webinar</button>';
	}

	static function footer_modal() { ?>
		<div id="insert_gtw_form" class="white-popup mfp-hide">
		  	<h1>Add Webinar Registration</h1>
		  	<form method="post" id="gtw_insert_form">
		  		<table class="form-table">
		  			<tr>
		  				<th>Select webinar</th>
		  				<td>
					  		<select id="webinar_key">
							  	<option>Loading webinars....</option>
							</select>
						</td>
					</tr>
				</table>
				<button type="submit" class="button-primary">Insert Registration Form</button>
			</form>
		</div>
	<?php }

	public static function ajax_get_webinars() {
  		$output = '';
  		$webinars = Genesis_G2W()->get_webinars();
  		foreach( $webinars as $webinar ) {
  			$output .= sprintf(
  				'<option value="%d">%s</option>',
  				$webinar['key'],
  				$webinar['title'].' ('.$webinar['date'].')'
  			);
  		}
  		echo $output;
  		wp_die();
	}

	public static function oauth_callack() {
		if ( isset ( $_GET['code'] ) ) {
			self::setup_oauth();
			$credentials = self::get_credentials();
			update_option( 'genesis_gtw_credentials', $credentials );
		}
	}

	private static function setup_oauth() {

		if ( ! class_exists( 'oAuth_Client' ) ) {
			require_once( 'oAuth2_Client.php' );
		}
		require_once( 'GrantType/IGrantType.php' );
		require_once( 'GrantType/AuthorizationCode.php' );

		self::$state   = 'gtw_oauth_token';
		self::$client  = new oAuth_Client\oAuth_Client( self::CLIENT_ID, self::CLIENT_SECRET );

		$credentials = get_option( 'genesis_gtw_credentials' );

		if ( $token ) {

			self::$client->setAccessToken( $credentials['token'] );
			self::$client->setAccessTokenType( 1 );

		}

	}

	private static function get_credentials(){

		$params = array( 'code' => $_GET['code'], 'redirect_uri' => self::REDIRECT_URI );

		$response = self::$client->getAccessToken( self::TOKEN_ENDPOINT, 'authorization_code', $params );

		$token = array(
			'token' => $response['result']['access_token'],
			'organiser_key' => $response['result']['organizer_key']
		);

		return $token;

	}

	/**
	 * The Genesis GoToWebinar plugin settings form.
	 *
	 * @since 0.9.0
	 */
	function form() {
		self::setup_oauth();
		$url = self::$client->getAuthenticationUrl(self::AUTHORIZATION_ENDPOINT, self::REDIRECT_URI, array( 'state' => self::$state ) );
		?>

		<table class="form-table">
		<tbody>

			<tr valign="top">
				<th>Authenticate</th>
				<td>
				<a class="button-primary button-ccontatct"  href="<?php echo $url; ?>">Link GoToWebinar Account</a>
				</td>
			</tr>

		</tbody>
		</table>

		<?php
		echo '<pre>'.print_r( get_option( 'genesis_gtw_credentials' ), true ).'</pre>';
	}

}
