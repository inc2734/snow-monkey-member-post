<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Controller;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;
use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

class Edit {

	public function __construct() {
		add_filter( 'display_post_states', [ $this, '_display_post_states' ], 10, 2 );
	}

	/**
	 * Add meta box
	 *
	 * @param string $post_type
	 * @return void
	 */
	public function _display_post_states( $post_states, $post ) {
		if ( get_post_meta( $post->ID, Config::get( 'restriction-key' ), true ) ) {
			$post_states[] = 'メンバー限定記事';
		}
		return $post_states;
	}
}
