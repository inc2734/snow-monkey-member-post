<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

return [
	'restriction-key'        => 'smmp-restriction',
	'restriction-nonce-key'  => 'smmp-restriction-nonce',
	'restriction-capability' => apply_filters( 'snow_monkey_member_post_restriction_capability', 'edit_others_posts' ),
];
