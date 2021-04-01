<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App;

use Snow_Monkey\Plugin\MemberPost\App\Config;

class Helper {

	/**
	 * Return true when the post have restriction meta
	 *
	 * @param int $post_id The post ID.
	 * @return boolean
	 */
	public static function has_restriction_meta( $post_id ) {
		if ( ! $post_id ) {
			return false;
		}

		return (bool) get_post_meta( $post_id, Config::get( 'restriction-key' ), true );
	}

	/**
	 * Return true when the post is restricted
	 *
	 * @param int $post_id The post ID.
	 * @return boolean
	 */
	public static function is_restricted( $post_id ) {
		$return               = true;
		$has_restriction_meta = static::has_restriction_meta( $post_id );

		if ( ! $has_restriction_meta ) {
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
		return apply_filters( 'snow_monkey_member_post_is_restricted', $return, $has_restriction_meta, get_post( $post_id ) );
	}
}
