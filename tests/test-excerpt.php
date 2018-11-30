<?php
/**
 * Class SampleTest
 *
 * @package snow-monkey-blocks
 */

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App;
use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;

/**
 * Sample test case.
 */
class ExcerptTest extends WP_UnitTestCase {

	public function setup() {
		parent::setup();

		new App\Controller\Excerpt();
	}

	/**
	 * @test
	 */
	public function no_restricted() {
		$post    = $this->_create_post( [ 'post_content' => 'content', 'post_excerpt' => '' ] );
		$content = $this->_get_the_excerpt( $post );
		$this->assertEquals( 'content', trim( strip_tags( $content ) ) );
	}

	/**
	 * @test
	 */
	public function restricted() {
		$post = $this->_create_post( [ 'post_content' => 'content', 'post_excerpt' => '' ] );
		update_post_meta( $post->ID, Config::get( 'restriction-key' ), 1 );
		$content = $this->_get_the_excerpt( $post );
		$this->assertNotEquals( 'content', trim( strip_tags( $content ) ) );
	}

	/**
	 * @test
	 */
	public function restricted_has_more() {
		$post = $this->_create_post( [ 'post_content' => 'before<!--more-->after', 'post_excerpt' => '' ] );
		update_post_meta( $post->ID, Config::get( 'restriction-key' ), 1 );
		$content = $this->_get_the_excerpt( $post );
		$this->assertNotEquals( 'content', trim( strip_tags( $content ) ) );
		$this->assertRegExp( '/^before/', trim( strip_tags( $content ) ) );
	}

	protected function _create_post( $args ) {
		$post_id = $this->factory->post->create( $args );
		return get_post( $post_id );
	}

	protected function _get_the_excerpt( $_post ) {
		global $post;
		$post = $_post;
		setup_postdata( $post );
		ob_start();
		the_excerpt();
		wp_reset_postdata();
		return ob_get_clean();
	}
}
