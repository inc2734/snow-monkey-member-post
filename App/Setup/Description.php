<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Setup;

class Description {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'inc2734_wp_ogp_description', array( $this, '_restrict_description' ), PHP_INT_MAX );
		add_filter( 'inc2734_wp_seo_description', array( $this, '_restrict_description' ), PHP_INT_MAX );
	}

	/**
	 * Exclude member-only blocks from automatically generated public metadata.
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

		$has_restricted_blocks = false;
		$content               = $this->_get_public_content( parse_blocks( $post->post_content ), $has_restricted_blocks );
		if ( ! $has_restricted_blocks ) {
			return $description;
		}

		// Metadata stays public even when the current visitor can read private blocks.
		$content = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $content ) ) );
		return str_replace( array( "\r\n", "\r", "\n" ), ' ', $content );
	}

	/**
	 * Extract saved content without rendering blocks or executing shortcodes.
	 *
	 * @param array $blocks                The parsed blocks.
	 * @param bool  $has_restricted_blocks Whether any member-only blocks were excluded.
	 * @return string
	 */
	protected function _get_public_content( $blocks, &$has_restricted_blocks ) {
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
					$block_content .= $this->_get_public_content( array( $block['innerBlocks'][ $inner_block_index ] ), $has_restricted_blocks );
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
