<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$args = wp_parse_args(
	// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	$args,
	// phpcs:enable
	[
		'redirect_to' => '',
	]
);
?>

<div class="smmp-alert">
	<?php esc_attr_e( 'Are you attempting to log out?', 'snow-monkey-member-post' ); ?> <a href="<?php echo esc_url( wp_logout_url( $args['redirect_to'] ) ); ?>" class="button logout-link"><?php esc_html_e( 'Log out', 'snow-monkey-member-post' ); ?></a>
</div>
