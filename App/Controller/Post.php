<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Controller;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;
use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

class Post {

	public function __construct() {
		if ( ! current_user_can( Config::get( 'restriction-capability' ) ) ) {
			return;
		}

		add_action( 'add_meta_boxes', [ $this, '_add_meta_boxes' ] );
		add_action( 'save_post', [ $this, '_save_post' ] );
	}

	/**
	 * Add meta box
	 *
	 * @param string $post_type
	 * @return void
	 */
	public function _add_meta_boxes( $post_type ) {
		/**
		 * You can customize the type of post that you can restrict.
		 *
		 * @param array active_post_types
		 * @return array
		 */
		$active_post_types = apply_filters( 'snow_monkey_member_post_active_post_types', [ 'post' ] );

		if ( ! in_array( $post_type, $active_post_types ) ) {
			return;
		}

		add_meta_box(
			'snow-monkey-member-post',
			esc_html__( 'Snow Monkey Member Post', 'snow-monkey-member-post' ),
			function() {
				View::render( 'meta-box' );
			}
		);
	}

	/**
	 * Update restriction meta value
	 *
	 * @param int $post_id
	 * @return void
	 */
	public function _save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$nonce_key = Config::get( 'restriction-nonce-key' );
		$nonce     = filter_input( INPUT_POST, $nonce_key );
		if ( ! wp_verify_nonce( $nonce, $nonce_key ) ) {
			return;
		}

		$restriction_key = Config::get( 'restriction-key' );
		$restriction     = filter_input( INPUT_POST, $restriction_key, FILTER_VALIDATE_INT );
		if ( 1 === $restriction ) {
			update_post_meta( $post_id, $restriction_key, $restriction );
		} elseif ( 0 === $restriction ) {
			delete_post_meta( $post_id, $restriction_key );
		}
	}
}
