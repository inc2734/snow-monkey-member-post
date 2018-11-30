<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;

$nonce_key = Config::get( 'restriction-nonce-key' );
$nonce     = wp_create_nonce( Config::get( 'restriction-nonce-key' ) );
?>
<input
	type="hidden"
	name="<?php echo esc_attr( $nonce_key ); ?>"
	value="<?php echo esc_attr( $nonce ); ?>"
>
<p>
	<label>
		<input
			type="hidden"
			name="<?php echo esc_attr( Config::get( 'restriction-key' ) ); ?>"
			value="0"
		>
		<input
			type="checkbox"
			name="<?php echo esc_attr( Config::get( 'restriction-key' ) ); ?>"
			value="1"
			<?php checked( 1, get_post_meta( get_the_ID(), Config::get( 'restriction-key' ), true ) ); ?>
		>
		<?php esc_html_e( 'Allow login users to display this article', 'snow-monkey-member-post' ); ?>
	</label>
</p>
