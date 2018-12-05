<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Setup;

class Assets {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, '_enqueue_style' ] );
	}

	public function _enqueue_style() {
		wp_enqueue_style(
			'snow-monkey-member-post',
			SNOW_MONKEY_MEMBER_POST_URL . '/dist/css/style.min.css',
			[],
			filemtime( SNOW_MONKEY_MEMBER_POST_PATH . '/dist/css/style.min.css' )
		);
	}
}
