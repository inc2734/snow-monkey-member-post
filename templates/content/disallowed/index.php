<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\MemberPost\App\View;

$args = wp_parse_args(
	// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	$args,
	// phpcs:enable
	[
		'post'    => false,
		'content' => '',
	]
);

$extended = View::get_extended( $args['content'] );
if ( ! empty( $extended['extended'] ) ) {
	echo $extended['main']; // XSS ok.
}

$message  = __( 'Viewing is restricted.', 'snow-monkey-member-post' );
$message .= __( 'Please login to view this page.', 'snow-monkey-member-post' );

/**
 * You can customize the messages that appear on unauthorized content.
 *
 * @param string $message
 * @return string
 */
$message = apply_filters( 'snow_monkey_member_post_restricted_content_message', $message, $args['post'] );

View::render( 'content/disallowed/message', [ 'message' => $message ] );
