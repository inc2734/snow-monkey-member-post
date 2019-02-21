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
		$template_path = apply_filters( 'snow_monkey_member_post_template_path', $template_path, $slug );

		if ( ! file_exists( $template_path ) ) {
			return;
		}

		// @codingStandardsIgnoreStart
		extract( $args );
		// @codingStandardsIgnoreEnd

		include( $template_path );
	}

	/**
	 * get_extended() for filtered content
	 *
	 * @see https://developer.wordpress.org/reference/functions/get_extended/
	 * @param string $content
	 * @return array
	 */
	public static function get_extended( $content ) {
		if ( preg_match( '@<span id="more-(\d+)"><\/span>@', $content, $matches ) ) {
			list( $main, $extended ) = explode( $matches[0], $content, 2 );
			$more_text = $matches[1];
		} else {
			$main      = $content;
			$extended  = '';
			$more_text = '';
		}

		$main = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $main );
		$extended = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $extended );
		$more_text = preg_replace( '/^[\s]*(.*)[\s]*$/', '\\1', $more_text );

		return [
			'main'      => $main,
			'extended'  => $extended,
			'more_text' => $more_text,
		];
	}
}
