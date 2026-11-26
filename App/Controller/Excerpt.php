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

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'get_the_excerpt', array( $this, '_restrict_excerpt' ), 11, 2 );
	}

	/**
	 * Restrict automatically generated excerpts.
	 *
	 * @param string  $content The excerpt.
	 * @param WP_Post $post    The post.
	 * @return string
	 */
	public function _restrict_excerpt( $content, $post ) {
		if ( ! $post || $post->post_excerpt || post_password_required( $post ) ) {
			return $content;
		}

		$has_restriction_meta = Helper::has_restriction_meta( $post->ID );
		$restricted_post      = $has_restriction_meta && Helper::is_restricted( $post->ID );

		if ( ! $restricted_post && Helper::is_restricted_member() ) {
			$extended              = get_extended( $post->post_content );
			$has_restricted_blocks = false;
			$public_content        = Helper::get_public_content( $extended['main'], $has_restricted_blocks );
			if ( $has_restricted_blocks ) {
				$content = wp_trim_words(
					wp_strip_all_tags( strip_shortcodes( $public_content ) ),
					apply_filters( 'excerpt_length', 55 ),
					apply_filters( 'excerpt_more', ' [&hellip;]' )
				);
			}
		}

		if ( ! $has_restriction_meta ) {
			return $content;
		}

		$args = array(
			'post'    => $post,
			'content' => $content,
		);

		ob_start();

		if ( $restricted_post ) {
			View::render( 'excerpt/disallowed/index', $args );
		} else {
			View::render( 'excerpt/allowed/index', $args );
		}

		return ob_get_clean();
	}
}
