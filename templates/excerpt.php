<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$excerpt = __( 'Viewing is restricted.', 'snow-monkey-member-post' );

echo wp_kses_post(
	apply_filters( 'snow_monkey_member_post_restricted_excerpt', $excerpt )
);
