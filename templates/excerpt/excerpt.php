<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$message = __( 'Viewing is restricted.', 'snow-monkey-member-post' );

/**
 * You can customize the messages that appear on excerpt of unauthorized content.
 *
 * @param string
 * @return staring
 */
$message = apply_filters( 'snow_monkey_member_post_restricted_excerpt_message', $message );

echo wp_kses_post( $message );
