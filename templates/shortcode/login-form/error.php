<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$login = filter_input( INPUT_GET, 'login_error_codes' );
if ( ! $login ) {
	return;
}

$error_codes = explode( ',', $login );
if ( ! $error_codes ) {
	return;
}
?>
<div class="wpac-alert wpac-alert--warning">
	<?php esc_html_e( 'The username or password is incorrect.', 'snow-monkey-member-post' ); ?>
</div>
