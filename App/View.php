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

		/**
		 * You can customize the template to load.
		 *
		 * @param string $template_path
		 * @param string $slug
		 * @return string
		 */
		$template_path = apply_filters( 'snow_monkey_member_post_tepmlate_path', $template_path, $slug );

		if ( ! file_exists( $template_path ) ) {
			return;
		}

		// @codingStandardsIgnoreStart
		extract( $args );
		// @codingStandardsIgnoreEnd

		include( $template_path );
	}
}
