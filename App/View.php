<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App;

class View {

	/**
	 * Renter template
	 *
	 * @param string $slug
	 * @return void
	 */
	public static function render( $slug, $args = [] ) {
		$template_path = SNOW_MONKEY_MEMBER_POST_PATH . '/templates/' . $slug . '.php';

		if ( ! file_exists( $template_path ) ) {
			return;
		}

		// @codingStandardsIgnoreStart
		extract( $args );
		// @codingStandardsIgnoreEnd

		include( $template_path );
	}
}
