<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Controller;

use Snow_Monkey\Plugin\MemberPost\App\Helper;
use Snow_Monkey\Plugin\MemberPost\App\View;

class Content {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, '_restrict_content' ), 9 );
	}

	/**
	 * Restrict content
	 *
	 * @param string $content The content.
	 * @return string
	 */
	public function _restrict_content( $content ) {
		$post = get_post();

		if ( ! $post || ! Helper::has_restriction_meta( $post->ID ) ) {
			return $content;
		}

		$args = array(
			'post'    => $post,
			'content' => $content,
		);

		ob_start();

		if ( Helper::is_restricted( $post->ID ) ) {
			View::render( 'content/disallowed/index', $args );
		} else {
			View::render( 'content/allowed/index', $args );
		}

		return ob_get_clean();
	}
}
