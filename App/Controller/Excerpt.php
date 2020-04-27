<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Controller;

use Snow_Monkey\Plugin\MemberPost\App\Helper;
use Snow_Monkey\Plugin\MemberPost\App\View;

class Excerpt {

	public function __construct() {
		add_filter( 'the_excerpt', [ $this, '_restrict_excerpt' ], 9 );
	}

	/**
	 * Restrict excerp
	 *
	 * @param string $content
	 * @return string
	 */
	public function _restrict_excerpt( $content ) {
		$post = get_post();

		if ( ! $post || $post->post_excerpt || ! Helper::has_restriction_meta( $post->ID ) ) {
			return $content;
		}

		$args = [
			'post'    => $post,
			'content' => $content,
		];

		ob_start();
		View::render( 'excerpt/index', $args );
		return ob_get_clean();
	}
}
