<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

$extended = get_extended( $post->post_content );
if ( empty( $extended['extended'] ) ) {
	return;
}

echo wp_kses_post( $extended['main'] );

$message  = __( 'Viewing is restricted.', 'snow-monkey-member-post' );
$message .= sprintf(
	__( 'Please %1$slogin%2$s to view this page.', 'snow-monkey-member-post' ),
	'<a href="' . wp_login_url() . '">',
	'</a>'
);

/**
 * You can customize the messages that appear on unauthorized content.
 *
 * @param string $message
 * @return string
 */
$message = apply_filters( 'snow_monkey_member_post_restricted_content_message', $message );

View::render( 'content/_disallow-message', [ 'message' => $message ] );
