<?php
/**
 * Plugin name: Snow Monkey Member Post
 * Description: It's a plugin that provides a function that allows only logged-in users to view articles.
 * Version: 3.0.1
 *
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost;

use Snow_Monkey\Plugin\MemberPost\App\Helper;
use Inc2734\WP_GitHub_Plugin_Updater\Bootstrap as Updater;

define( 'SNOW_MONKEY_MEMBER_POST_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'SNOW_MONKEY_MEMBER_POST_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

class Bootstrap {

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_bootstrap' ] );
	}

	public function _bootstrap() {
		load_plugin_textdomain( 'snow-monkey-member-post', false, basename( __DIR__ ) . '/languages' );

		add_action( 'init', [ $this, '_activate_autoupdate' ] );

		$theme = wp_get_theme( get_template() );
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			add_action( 'admin_notices', [ $this, '_admin_notice_no_snow_monkey' ] );
			return;
		}

		foreach ( glob( SNOW_MONKEY_MEMBER_POST_PATH . '/App/Setup/*.php' ) as $file ) {
			$class_name = '\\Snow_Monkey\\Plugin\\MemberPost\\App\\Setup\\' . str_replace( '.php', '', basename( $file ) );
			if ( class_exists( $class_name ) ) {
				new $class_name();
			}
		}

		new App\Controller\Post();
		new App\Controller\Edit();
		new App\Controller\Content();
		new App\Controller\Excerpt();

		new App\Shortcode\LoginForm();
		new App\Shortcode\RegisterForm();

		if ( ! is_admin() ) {
			add_action( 'render_block', [ $this, '_restricted' ], 10, 2 );
		}
	}

	public function _restricted( $content, $block ) {
		$attributes = $block['attrs'];
		$is_restricted = isset( $attributes['smmpIsRestrected'] ) ? $attributes['smmpIsRestrected'] : false;
		if ( $is_restricted ) {
			$post = get_post();
			if ( $post && Helper::is_restricted( $post->ID ) ) {
				return;
			}
		}
		return $content;
	}

	/**
	 * Activate auto update using GitHub
	 *
	 * @return void
	 */
	public function _activate_autoupdate() {
		new Updater(
			plugin_basename( __FILE__ ),
			'inc2734',
			'snow-monkey-member-post'
		);
	}

	public function _admin_notice_no_snow_monkey() {
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<?php esc_html_e( '[Snow Monkey Member Post] Needs the Snow Monkey.', 'snow-monkey-member-post' ); ?>
			</p>
		</div>
		<?php
	}
}

require_once( __DIR__ . '/vendor/autoload.php' );
new Bootstrap();
