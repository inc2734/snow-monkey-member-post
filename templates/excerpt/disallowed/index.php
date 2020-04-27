<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\MemberPost\App\Helper;

$extended = get_extended( $post->post_content );
$main = preg_replace( '/<!-- more -->/', '', $extended['main'] );

if ( ! empty( $main ) && ! empty( $extended['extended'] ) ) {
	/**
	 * @see https://developer.wordpress.org/reference/functions/wp_trim_excerpt/
	 */
	$excerpt_length = apply_filters( 'excerpt_length', 55 );
	$excerpt_more   = apply_filters( 'excerpt_more', ' [&hellip;]' );
	$message = wp_trim_words( $extended['main'], $excerpt_length, $excerpt_more );
} else {
	$message = __( 'Viewing is restricted.', 'snow-monkey-member-post' );

	/**
	 * You can customize the messages that appear on excerpt of unauthorized content.
	 *
	 * @param string
	 * @return staring
	 */
	$message = apply_filters( 'snow_monkey_member_post_restricted_excerpt_message', $message, $post );
}

echo wp_kses_post( $message );
