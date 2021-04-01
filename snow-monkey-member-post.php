<?php
/**
 * Plugin name: Snow Monkey Member Post
 * Description: It's a plugin that provides a function that allows only logged-in users to view articles.
 * Version: 5.0.0
 * Tested up to: 5.7
 * Requires at least: 5.5
 * Requires PHP: 5.6
 *
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost;

use WP_Block_Type_Registry;
use Snow_Monkey\Plugin\MemberPost\App\Helper;
use Inc2734\WP_GitHub_Plugin_Updater\Bootstrap as Updater;

define( 'SNOW_MONKEY_MEMBER_POST_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'SNOW_MONKEY_MEMBER_POST_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

class Bootstrap {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_bootstrap' ] );
	}

	/**
	 * Plugins loaded.
	 */
	public function _bootstrap() {
		load_plugin_textdomain( 'snow-monkey-member-post', false, basename( __DIR__ ) . '/languages' );

		add_action( 'init', [ $this, '_add_attributes_to_blocks' ], 11 );
		add_action( 'init', [ $this, '_activate_autoupdate' ] );

		$theme = wp_get_theme( get_template() );
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			add_action(
				'admin_notices',
				function() {
					?>
						<div class="notice notice-warning is-dismissible">
							<p>
								<?php esc_html_e( '[Snow Monkey Member Post] Needs the Snow Monkey.', 'snow-monkey-member-post' ); ?>
							</p>
						</div>
					<?php
				}
			);
			return;
		}

		new App\Setup\Assets();
		new App\Setup\CurrentUser();
		new App\Setup\TextDomain();

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

	/**
	 * Filters the content of a single block.
	 *
	 * @param string $content The block content about to be appended.
	 * @param array  $block   The full block, including name and attributes.
	 * @return strinig
	 */
	public function _restricted( $content, $block ) {
		$attributes    = $block['attrs'];
		$is_restricted = isset( $attributes['smmpIsRestrected'] ) ? $attributes['smmpIsRestrected'] : false;

		if ( $is_restricted ) {
			if ( Helper::is_restricted_member() ) {
				$args = [
					'post' => get_post(),
				];
				ob_start();
				App\View::render( 'block/disallowed/index', $args );
				return ob_get_clean();
			}
		}
		return $content;
	}

	/**
	 * Adds attributes to all blocks, to avoid `Invalid parameter(s): attributes` error in Gutenberg.
	 *
	 * @see https://github.com/Codeinwp/gutenberg-animation/blob/a0efe29a3ce023e0f562bb9a51d34b345431b642/class-gutenberg-animation.php#L105-L119
	 */
	public function _add_attributes_to_blocks() {
		$attributes = [];
		foreach ( glob( SNOW_MONKEY_MEMBER_POST_PATH . '/src/extension/*', GLOB_ONLYDIR ) as $dir ) {
			foreach ( glob( $dir . '/attributes.json' ) as $file ) {
				$_attributes = file_get_contents( $file );
				$_attributes = json_decode( $_attributes, true );
				$attributes  = array_merge( $attributes, $_attributes );
			}
		}

		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $registered_blocks as $name => $block ) {
			foreach ( $attributes as $name => $detail ) {
				$block->attributes[ $name ] = $detail;
			}
		}
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
			'snow-monkey-member-post',
			[
				'homepage' => 'https://snow-monkey.2inc.org',
			]
		);
	}
}

require_once( __DIR__ . '/vendor/autoload.php' );
new Bootstrap();
