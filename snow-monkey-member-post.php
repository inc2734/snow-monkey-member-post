<?php
/**
 * Plugin name: Snow Monkey Member Post
 * Description: It's a plugin that provides a function that allows only logged-in users to view articles.
 * Version: 1.1.4
 *
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost;

define( 'SNOW_MONKEY_MEMBER_POST_URL', plugin_dir_url( __FILE__ ) );
define( 'SNOW_MONKEY_MEMBER_POST_PATH', plugin_dir_path( __FILE__ ) );

class Bootstrap {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_bootstrap' ] );
		add_action( 'init', [ $this, '_activate_autoupdate' ] );
	}

	public function _bootstrap() {
		load_plugin_textdomain( 'snow-monkey-member-post', false, basename( __DIR__ ) . '/languages' );

		$theme = wp_get_theme();
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			return;
		}

		new App\Setup\Assets();

		new App\Controller\Post();
		new App\Controller\Content();
		new App\Controller\Excerpt();

		new App\Shortcode\LoginForm();
		new App\Shortcode\RegisterForm();
	}

	/**
	 * Activate auto update using GitHub
	 *
	 * @return void
	 */
	public function _activate_autoupdate() {
		new \Inc2734\WP_GitHub_Plugin_Updater\GitHub_Plugin_Updater(
			plugin_basename( __FILE__ ),
			'inc2734',
			'snow-monkey-member-post'
		);
	}
}

require_once( __DIR__ . '/vendor/autoload.php' );
new Bootstrap();
