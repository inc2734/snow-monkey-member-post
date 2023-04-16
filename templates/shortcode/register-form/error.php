<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$error_codes = filter_input( INPUT_GET, 'register_error_codes' );
$error_codes = explode( ',', $error_codes );
?>
<div class="smmp-alert smmp-alert--warning">
	<?php
	$error_messages = array();
	if ( in_array( 'username_exists', $error_codes, true ) ) {
		$error_messages[] = esc_html__( 'Sorry, that username already exists!', 'snow-monkey-member-post' );
	}

	if ( in_array( 'email_exists', $error_codes, true ) ) {
		$error_messages[] = esc_html__( 'This email is already registered, please choose another one.', 'snow-monkey-member-post' );
	}

	if ( array_diff( array( 'username_exists', 'email_exists' ), $error_codes ) ) {
		$error_messages[] = esc_html__( 'The username or email address is incorrect.', 'snow-monkey-member-post' );
	}

	echo wp_kses_post( implode( '<br>', $error_messages ) );
	?>
</div>
