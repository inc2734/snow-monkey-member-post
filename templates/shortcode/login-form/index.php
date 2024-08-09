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

if ( filter_input( INPUT_GET, 'login_error_codes' ) ) {
	View::render( 'shortcode/login-form/error' );
}

$form_action = site_url( 'wp-login.php', 'login_post' );
if ( class_exists( '\XO_Security' ) ) {
	$xo_security_option = get_option( 'xo_security_options' );
	if ( ! empty( $xo_security_option['login_page'] ) && ! empty( $xo_security_option['login_page_name'] ) ) {
		$login_page_name = $xo_security_option['login_page_name'];
		$form_action     = str_replace( 'wp-login.php', $login_page_name . '.php', $form_action );
	}
}
?>

<form name="loginform" class="smmp-login-form" action="<?php echo esc_url( $form_action ); ?>" method="post">
	<div class="c-row c-row--margin-s">
		<div class="c-row__col c-row__col--1-1">
			<div class="c-form-control c-form-control--has-icon">
				<div class="c-form-control__icon">
					<i class="fas fa-user" title="<?php esc_attr_e( 'Username or Email Address', 'snow-monkey-member-post' ); ?>"></i>
				</div>
				<input type="text" name="log" id="user_login" value="">
			</div>
		</div>
		<div class="c-row__col c-row__col--1-1">
			<div class="c-form-control c-form-control--has-icon">
				<div class="c-form-control__icon">
					<i class="fas fa-key" title="<?php esc_attr_e( 'Password', 'snow-monkey-member-post' ); ?>"></i>
				</div>
				<input type="password" name="pwd" id="user_pass" value="">
			</div>
		</div>
		<div class="c-row__col c-row__col--1-1">
			<?php do_action( 'login_form' ); ?>

			<button type="submit" name="wp-submit" id="wp-submit" class="c-btn">
				<?php esc_attr_e( 'Log In', 'snow-monkey-member-post' ); ?>
			</button>
			<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $args['redirect_to'] ); ?>">
		</div>
		<div class="c-row__col c-row__col--1-1">
			<ul class="smmp-login-form__nav">
				<li class="smmp-login-form__nav__item">
					<label for="rememberme">
						<input name="rememberme" type="checkbox" id="rememberme" value="forever">
						<?php esc_html_e( 'Remember Me', 'snow-monkey-member-post' ); ?>
					</label>
				</li>
				<li class="smmp-login-form__nav__item">
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
						<?php esc_html_e( 'Lost your password?', 'snow-monkey-member-post' ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<?php
	$nonce_key = Config::get( 'login-form-nonce-key' );
	$nonce     = wp_create_nonce( Config::get( 'login-form-nonce-key' ) );
	?>
	<input type="hidden" name="<?php echo esc_attr( $nonce_key ); ?>" value="<?php echo esc_attr( $nonce ); ?>">
</form>
