<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Controller;

use Snow_Monkey\Plugin\MemberPost\App\Config;

class Edit {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'display_post_states', [ $this, '_display_post_states' ], 10, 2 );
	}

	/**
	 * Add post status comment
	 *
	 * @param array   $post_states The post status.
	 * @param WP_Post $post        The post object.
	 * @return array
	 */
	public function _display_post_states( $post_states, $post ) {
		if ( get_post_meta( $post->ID, Config::get( 'restriction-key' ), true ) ) {
			$post_states[] = esc_html__( 'Members only', 'snow-monkey-member-post' );
		}
		return $post_states;
	}
}
