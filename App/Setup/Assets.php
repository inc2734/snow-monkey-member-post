<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Setup;

class Assets {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_style' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, '_enqueue_block_editor_extension' ], 9 );
		add_action( 'enqueue_block_editor_assets', [ $this, '_enqueue_block_editor_assets' ] );
	}

	/**
	 * Enqueue assets.
	 */
	public function _enqueue_style() {
		wp_enqueue_style(
			'snow-monkey-member-post',
			SNOW_MONKEY_MEMBER_POST_URL . '/dist/css/style.css',
			[ \Framework\Helper::get_main_style_handle() ],
			filemtime( SNOW_MONKEY_MEMBER_POST_PATH . '/dist/css/style.css' )
		);
	}

	/**
	 * Enqueue editor extension
	 *
	 * @return void
	 */
	public function _enqueue_block_editor_extension() {
		$asset = include( SNOW_MONKEY_MEMBER_POST_PATH . '/dist/js/editor-extension.asset.php' );
		wp_enqueue_script(
			'snow-monkey-member-post@editor-extension',
			SNOW_MONKEY_MEMBER_POST_URL . '/dist/js/editor-extension.js',
			$asset['dependencies'],
			filemtime( SNOW_MONKEY_MEMBER_POST_PATH . '/dist/js/editor-extension.js' ),
			true
		);
	}

	/**
	 * Enqueue editor assets
	 *
	 * @return void
	 */
	public function _enqueue_block_editor_assets() {
		wp_enqueue_style(
			'snow-monkey-member-post@editor',
			SNOW_MONKEY_MEMBER_POST_URL . '/dist/css/editor.css',
			[],
			filemtime( SNOW_MONKEY_MEMBER_POST_PATH . '/dist/css/editor.css' )
		);
	}
}
