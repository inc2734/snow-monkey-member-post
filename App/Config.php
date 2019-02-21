<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App;

class Config {

	/**
	 * Config data
	 *
	 * @var array
	 */
	protected static $config = [];

	/**
	 * Retrun specific config data
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function get( $key ) {
		if ( ! static::$config ) {
			static::_set_config_data();
		}

		if ( array_key_exists( $key, static::$config ) ) {
			return static::$config[ $key ];
		}
	}

	/**
	 * Set config data
	 *
	 * @return void
	 */
	protected static function _set_config_data() {
		static::$config = include_once( SNOW_MONKEY_MEMBER_POST_PATH . '/config/config.php' );
	}
}
