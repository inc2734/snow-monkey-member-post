<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

$extended = get_extended( $post->post_content );
echo wp_kses_post( $extended['main'] );
?>

<?php if ( ! empty( $extended['extended'] ) ) : ?>
	<div class="wpac-alert wpac-alert--success">
		<?php
		$content = __( 'From here on, it is content for members only.', 'snow-monkey-member-post' );

		echo wp_kses_post(
			apply_filters( 'snow_monkey_member_post_allowed_content_message', $content )
		);
		?>
	</div>

	<?php echo wp_kses_post( $extended['extended'] ); ?>
<?php endif; ?>
