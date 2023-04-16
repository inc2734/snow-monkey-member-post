<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Setup;

class TextDomain {

	/**
	 * Constructor.
	 */
	public function __construct() {
		load_plugin_textdomain( 'snow-monkey-member-post', false, basename( SNOW_MONKEY_MEMBER_POST_PATH ) . '/languages' );
		add_filter( 'load_textdomain_mofile', array( $this, '_load_textdomain_mofile' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, '_set_script_translations' ), 11 );
	}

	/**
	 * Filters MO file path for loading translations for a specific text domain.
	 * When local .mo file exists, load this.
	 *
	 * @param string $mofile Path to the MO file.
	 * @param string $domain Text domain. Unique identifier for retrieving translated strings.
	 * @return string
	 */
	public function _load_textdomain_mofile( $mofile, $domain ) {
		if ( 'snow-monkey-member-post' === $domain ) {
			$mofilename   = basename( $mofile );
			$local_mofile = SNOW_MONKEY_MEMBER_POST_PATH . '/languages/' . $mofilename;
			if ( file_exists( $local_mofile ) ) {
				return $local_mofile;
			}
		}
		return $mofile;
	}

	/**
	 * Translate script files
	 *
	 * @return void
	 */
	public function _set_script_translations() {
		wp_set_script_translations(
			'snow-monkey-member-post@editor-extension',
			'snow-monkey-member-post',
			SNOW_MONKEY_MEMBER_POST_PATH . '/languages'
		);
	}
}
