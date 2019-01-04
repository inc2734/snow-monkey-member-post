<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$login = filter_input( INPUT_GET, 'register_error_codes' );
if ( ! $login ) {
	return;
}

$error_codes = explode( ',', $login );
if ( ! $error_codes ) {
	return;
}
?>
<div class="wpac-alert wpac-alert--warning">
	<?php
	$error_messages = [];
	if ( in_array( 'username_exists', $error_codes ) ) {
		$error_messages[] = esc_html__( 'Sorry, that username already exists!', 'snow-monkey-member-post' );
	}

	if ( in_array( 'email_exists', $error_codes ) ) {
		$error_messages[] = esc_html__( 'This email is already registered, please choose another one.', 'snow-monkey-member-post' );
	}

	if ( array_diff( [ 'username_exists', 'email_exists' ], $error_codes ) ) {
		$error_messages[] = esc_html__( 'The username or email address is incorrect.', 'snow-monkey-member-post' );
	}

	echo wp_kses_post( implode( '<br>', $error_messages ) );
	?>
</div>
