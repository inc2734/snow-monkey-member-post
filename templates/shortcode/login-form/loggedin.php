<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */
?>

<div class="c-row c-row--margin-s">
	<div class="c-row__col c-row__col--1-1">
		<?php esc_attr_e( 'Are you attempting to log out?', 'snow-monkey-member-post' ); ?>
	</div>
	<div class="c-row__col c-row__col--1-1">
		<a href="<?php echo wp_logout_url( $redirect_to ); ?>" class="button logout-link"><?php esc_html_e( 'Log out', 'snow-monkey-member-post' ); ?></a>
	</div>
</div>
