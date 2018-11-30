<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$extended = get_extended( $post->post_content );
if ( ! empty( $extended['extended'] ) ) {
	echo wp_kses_post( $extended['main'] );
}
?>

<div class="wpac-alert">
	<?php
	$content  = __( 'Viewing is restricted.', 'snow-monkey-member-post' );
	$content .= sprintf(
		__( 'Please %1$slogin%2$s to view this page.', 'snow-monkey-member-post' ),
		'<a href="' . wp_login_url() . '">',
		'</a>'
	);

	echo wp_kses_post(
		apply_filters( 'snow_monkey_member_post_restricted_content', $content )
	);
	?>
</div>
