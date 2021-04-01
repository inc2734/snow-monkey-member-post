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
		'message' => '',
	]
);
?>

<div class="smmp-alert smmp-alert--success">
	<?php echo wp_kses_post( $args['message'] ); ?>
</div>
