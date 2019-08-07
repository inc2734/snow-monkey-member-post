<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App;

use Inc2734\WP_Plugin_View_Controller\Bootstrap;

class View {

	/**
	 * Renter template
	 *
	 * @param string $slug
	 * @return void
	 */
	public static function render( $slug, $args = [] ) {
		$bootstrap = new Bootstrap(
			[
				'prefix' => 'snow_monkey_member_post_',
				'path'   => SNOW_MONKEY_MEMBER_POST_PATH . '/templates/',
			]
		);

		add_filter(
			'snow_monkey_member_post_view_hierarchy',
			function( $hierarchy ) use ( $slug ) {
				$hierarchy[] = apply_filters( 'snow_monkey_member_post_template_path', current( $hierarchy ), $slug );
				return array_unique( $hierarchy );
			},
			9
		);

		$bootstrap->render( $slug, null, $args );
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
