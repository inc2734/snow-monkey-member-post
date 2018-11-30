<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Controller;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;
use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

class Content {

	public function __construct() {
		add_filter( 'the_content', [ $this, '_restrict_content' ] );
		add_filter( 'the_excerpt', [ $this, '_restrict_excerpt' ] );
	}

	/**
	 * Restrict content
	 *
	 * @param string $content
	 * @return string
	 */
	public function _restrict_content( $content ) {
		$post = get_post();

		if ( ! $this->_has_restriction_meta( $post ) ) {
			return $content;
		}

		$args = [
			'post'    => $post,
			'content' => $content,
		];

		ob_start();
		if ( $this->_is_restricted( $post ) ) {
			View::render( 'content/disallowed/index', $args );
		} else {
			View::render( 'content/allowed/index', $args );
		}
		return ob_get_clean();
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

	/**
	 * Return true when the post is restricted
	 *
	 * @param WP_Post $post
	 * @return boolean
	 */
	protected function _is_restricted( $post ) {
		$return = true;
		$has_restriction_meta = $this->_has_restriction_meta( $post );

		if ( ! $post || ! $has_restriction_meta ) {
			return false;
		}

		if ( is_user_logged_in() ) {
			$return = false;
		}

		/**
		 * You can customize whether the content is restricted or not.
		 *
		 * @param boolean $return
		 * @param boolean $has_restriction_meta
		 * @param WP_Post $post
		 */
		return apply_filters( 'snow_monkey_member_post_is_restricted', $return, $has_restriction_meta, $post );
	}

	/**
	 * Return true when the post have restriction meta
	 *
	 * @param WP_Post $post
	 * @return boolean
	 */
	protected function _has_restriction_meta( $post ) {
		if ( ! $post ) {
			return false;
		}

		return (bool) get_post_meta( $post->ID, Config::get( 'restriction-key' ), true );
	}
}
