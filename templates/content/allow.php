<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

$extended = get_extended( $post->post_content );
echo wp_kses_post( $extended['main'] );

if ( empty( $extended['extended'] ) ) {
	return;
}

$message = __( 'From here on, it is content for members only.', 'snow-monkey-member-post' );

/**
 * You can customize the message displayed in the permitted content.
 *
 * @param string $message
 * @return string
 */
$message = apply_filters( 'snow_monkey_member_post_allowed_content_message', $message );

View::render( 'content/_allow-message', [ 'message' => $message ] );

echo wp_kses_post( $extended['extended'] );
