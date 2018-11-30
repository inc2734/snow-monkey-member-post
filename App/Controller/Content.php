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
		if ( ! $this->_is_restriction( get_post() ) ) {
			return $content;
		}

		if ( is_user_logged_in() ) {
			return $content;
		}

		ob_start();
		View::render( 'content' );
		return ob_get_clean();
	}

	/**
	 * Restrict excerp
	 *
	 * @param string $content
	 * @return string
	 */
	public function _restrict_excerpt( $content ) {
		if ( ! $this->_is_restriction( get_post() ) ) {
			return $content;
		}

		ob_start();
		View::render( 'excerpt' );
		return ob_get_clean();
	}

	/**
	 * Return true when the post is restricted
	 *
	 * @param WP_Post $_post
	 * @return boolean
	 */
	protected function _is_restriction( $_post ) {
		if ( ! $_post ) {
			return false;
		}

		$restriction = (int) get_post_meta( $_post->ID, Config::get( 'restriction-key' ), true );
		if ( 1 !== $restriction ) {
			return false;
		}

		return true;
	}
}
