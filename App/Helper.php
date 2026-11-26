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
	 * Return true when the member is restricted.
	 *
	 * @return boolean
	 */
	public static function is_restricted_member() {
		/**
		 * You can customize whether the member is restricted or not.
		 *
		 * @param boolean $return
		 * @param boolean $has_restriction_meta
		 * @param WP_Post $post
		 * @return boolean
		 */
		return apply_filters(
			'snow_monkey_member_post_is_restricted_member',
			! is_user_logged_in()
		);
	}

	/**
	 * Return true when the post is restricted
	 *
	 * @param int $post_id The post ID.
	 * @return boolean
	 */
	public static function is_restricted( $post_id ) {
		$has_restriction_meta = static::has_restriction_meta( $post_id );

		/**
		 * You can customize whether the content is restricted or not.
		 *
		 * @param boolean $return
		 * @param boolean $has_restriction_meta
		 * @param WP_Post $post
		 * @return boolean
		 */
		return apply_filters(
			'snow_monkey_member_post_is_restricted',
			$has_restriction_meta && static::is_restricted_member(),
			$has_restriction_meta,
			get_post( $post_id )
		);
	}

	/**
	 * Return the teaser before the More marker on the first page.
	 *
	 * @param WP_Post $post The post.
	 * @return string
	 */
	public static function get_public_teaser( $post ) {
		list( $first_page ) = explode( '<!--nextpage-->', $post->post_content, 2 );
		$extended           = get_extended( $first_page );
		return '' !== trim( $extended['extended'] ) ? $extended['main'] : '';
	}

	/**
	 * Extract public saved HTML without rendering blocks or running shortcodes.
	 *
	 * @param string $content               The saved content.
	 * @param bool   $has_restricted_blocks Whether any member-only blocks were excluded.
	 * @return string
	 */
	public static function get_public_content( $content, &$has_restricted_blocks = false ) {
		return static::_get_public_block_content( parse_blocks( $content ), $has_restricted_blocks );
	}

	/**
	 * Extract the saved HTML from unrestricted blocks.
	 *
	 * @param array $blocks                The parsed blocks.
	 * @param bool  $has_restricted_blocks Whether any member-only blocks were excluded.
	 * @return string
	 */
	protected static function _get_public_block_content( $blocks, &$has_restricted_blocks ) {
		$content = array();

		foreach ( $blocks as $block ) {
			if ( ! empty( $block['attrs']['smmpIsRestrected'] ) ) {
				$has_restricted_blocks = true;
				continue;
			}

			$inner_block_index = 0;
			$block_content     = '';
			foreach ( $block['innerContent'] as $fragment ) {
				if ( null === $fragment ) {
					$block_content .= static::_get_public_block_content( array( $block['innerBlocks'][ $inner_block_index ] ), $has_restricted_blocks );
					++$inner_block_index;
				} else {
					$block_content .= $fragment;
				}
			}
			$content[] = $block_content;
		}

		return implode( ' ', $content );
	}
}
