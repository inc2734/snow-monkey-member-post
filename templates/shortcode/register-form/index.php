<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

use Snow_Monkey\Plugin\MemberPost\App\Config;
use Snow_Monkey\Plugin\MemberPost\App\View;

$args = wp_parse_args(
	// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	$args,
	// phpcs:enable
	array(
		'redirect_to' => '',
	)
);

if ( filter_input( INPUT_GET, 'register_error_codes' ) ) {
	View::render( 'shortcode/register-form/error' );
}

$form_action = site_url( 'wp-login.php?action=register', 'login_post' );
if ( class_exists( '\XO_Security' ) ) {
	$xo_security_option = get_option( 'xo_security_options' );
	if ( ! empty( $xo_security_option['login_page'] ) && ! empty( $xo_security_option['login_page_name'] ) ) {
		$login_page_name = $xo_security_option['login_page_name'];
		$form_action     = str_replace( 'wp-login.php', $login_page_name . '.php', $form_action );
	}
}
?>

<form name="registerform" class="smmp-register-form" action="<?php echo esc_url( $form_action ); ?>" method="post">
	<div class="c-row c-row--margin-s">
		<div class="c-row__col c-row__col--1-1">
			<div class="c-form-control c-form-control--has-icon">
				<div class="c-form-control__icon">
					<i class="fas fa-user" title="<?php esc_attr_e( 'Username', 'snow-monkey-member-post' ); ?>"></i>
				</div>
				<input type="text" name="user_login" id="user_login" value="">
			</div>
		</div>
		<div class="c-row__col c-row__col--1-1">
			<div class="c-form-control c-form-control--has-icon">
				<div class="c-form-control__icon">
					<i class="fas fa-envelope" title="<?php esc_attr_e( 'Email', 'snow-monkey-member-post' ); ?>"></i>
				</div>
				<input type="email" name="user_email" id="user_email" value="">
			</div>
		</div>
		<div class="c-row__col c-row__col--1-1">
			<?php do_action( 'register_form' ); ?>

			<button type="submit" name="wp-submit" id="wp-submit" class="c-btn">
				<?php esc_attr_e( 'Register', 'snow-monkey-member-post' ); ?>
			</button>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $args['redirect_to'] ); ?>">
		</div>
		<div class="c-row__col c-row__col--1-1">
			<ul class="smmp-login-form__nav">
				<li class="smmp-login-form__nav__item">
					<?php esc_html_e( 'Registration confirmation will be emailed to you.', 'snow-monkey-member-post' ); ?>
				</li>
			</ul>
		</div>
	</div>

	<?php
	$nonce_key = Config::get( 'register-form-nonce-key' );
	$nonce     = wp_create_nonce( Config::get( 'register-form-nonce-key' ) );
	?>
	<input type="hidden" name="<?php echo esc_attr( $nonce_key ); ?>" value="<?php echo esc_attr( $nonce ); ?>">
</form>
