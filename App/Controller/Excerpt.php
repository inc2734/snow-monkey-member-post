<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Controller;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;
use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

class Excerpt extends Content {

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

		if ( ! $this->_is_restricted( $post ) ) {
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
