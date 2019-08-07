<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Controller;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;

class Edit {

	public function __construct() {
		add_filter( 'display_post_states', [ $this, '_display_post_states' ], 10, 2 );
	}

	/**
	 * Add post status comment
	 *
	 * @param array $post_states
	 * @param WP_Post $post
	 * @return array
	 */
	public function _display_post_states( $post_states, $post ) {
		if ( get_post_meta( $post->ID, Config::get( 'restriction-key' ), true ) ) {
			$post_states[] = esc_html__( 'Members only', 'snow-monkey-member-post' );
		}
		return $post_states;
	}
}
