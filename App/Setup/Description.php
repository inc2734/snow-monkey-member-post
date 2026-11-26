<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Setup;

use Snow_Monkey\Plugin\MemberPost\App\Helper;

class Description {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'inc2734_wp_ogp_description', array( $this, '_restrict_description' ), PHP_INT_MAX );
		add_filter( 'inc2734_wp_seo_description', array( $this, '_restrict_description' ), PHP_INT_MAX );
	}

	/**
	 * Exclude member-only content from automatically generated public metadata.
	 *
	 * @param string $description The description.
	 * @return string
	 */
	public function _restrict_description( $description ) {
		if ( ! $description || is_front_page() || ( ! is_singular() && ! is_home() ) ) {
			return $description;
		}

		$post = get_queried_object();
		if ( ! $post instanceof \WP_Post ) {
			return $description;
		}

		// Explicit descriptions and excerpts are authored for public use.
		if ( get_post_meta( $post->ID, 'wp-seo-meta-description', true ) || wp_strip_all_tags( strip_shortcodes( $post->post_excerpt ) ) ) {
			return $description;
		}

		$has_restriction_meta  = Helper::has_restriction_meta( $post->ID );
		$saved_content         = $has_restriction_meta ? Helper::get_public_teaser( $post ) : $post->post_content;
		$has_restricted_blocks = false;
		$content               = Helper::get_public_content( $saved_content, $has_restricted_blocks );
		if ( ! $has_restriction_meta && ! $has_restricted_blocks ) {
			return $description;
		}

		// Metadata stays public even when the current visitor can read private content.
		$content = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $content ) ) );
		return str_replace( array( "\r\n", "\r", "\n" ), ' ', $content );
	}
}
