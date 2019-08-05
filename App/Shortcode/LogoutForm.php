<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Shortcode;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;
use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

class LogoutForm {

	public function __construct() {
		add_shortcode( 'snow_monkey_member_post_logout_form', [ $this, '_view' ] );
	}

	/**
	 * Register shortcode
	 *
	 * @param array $atts
	 * @return string
	 * @see https://core.trac.wordpress.org/browser/trunk/src/wp-login.php
	 */
	public function _view( $atts ) {
		if ( ! is_user_logged_in() ) {
			ob_start();
			View::render( 'shortcode/logout-form/loggedout' );
			return ob_get_clean();
		}

		$atts = shortcode_atts(
			[
				'redirect_to' => $this->_get_current_url(),
			],
			$atts
		);

		ob_start();
		View::render( 'shortcode/logout-form/index', $atts );
		return ob_get_clean();
	}

	/**
	 * Return current URL
	 *
	 * @return string
	 */
	protected function _get_current_url() {
		$path = filter_input( INPUT_SERVER, 'REQUEST_URI' );
		$path = remove_query_arg( 'login_error_codes', $path );
		$path = remove_query_arg( 'register_error_codes', $path );
		return home_url( $path );
	}
}
