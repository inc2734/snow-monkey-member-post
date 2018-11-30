<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

/**
 * You can customize the permissions that can be restricted.
 *
 * @param string $capability
 * @return string
 */
$capability = apply_filters( 'snow_monkey_member_post_restriction_capability', 'edit_others_posts' );

return [
	'restriction-key'        => 'smmp-restriction',
	'restriction-nonce-key'  => 'smmp-restriction-nonce',
	'restriction-capability' => $capability,
];
