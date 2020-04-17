<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\MemberPost\App\View;

$extended = View::get_extended( $content );
echo $extended['main']; // XSS ok.

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
$message = apply_filters( 'snow_monkey_member_post_allowed_content_message', $message, $post );

View::render( 'content/allowed/message', [ 'message' => $message ] );

echo $extended['extended']; // XSS ok.
