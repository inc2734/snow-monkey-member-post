<?php
/**
 * Class SampleTest
 *
 * @package snow-monkey-blocks
 */

use Snow_Monkey\Plugin\MemberPost\App;
use Snow_Monkey\Plugin\MemberPost\App\Config;

/**
 * Sample test case.
 */
class ExcerptTest extends WP_UnitTestCase {

	public function set_up() {
		parent::set_up();

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
	public function no_restricted_has_excerpt() {
		$post    = $this->_create_post( [ 'post_content' => 'content', 'post_excerpt' => 'excerpt' ] );
		$content = $this->_get_the_excerpt( $post );
		$this->assertEquals( 'excerpt', trim( strip_tags( $content ) ) );
	}

	/**
	 * @test
	 */
	public function restricted() {
		$post = $this->_create_post( [ 'post_content' => 'content', 'post_excerpt' => '' ] );
		update_post_meta( $post->ID, Config::get( 'restriction-key' ), 1 );
		$content = $this->_get_the_excerpt( $post );
		$this->assertEquals( 'Viewing is restricted.', trim( strip_tags( $content ) ) );
	}

	/**
	 * @test
	 */
	public function restricted_has_excerpt() {
		$post = $this->_create_post( [ 'post_content' => 'content', 'post_excerpt' => 'excerpt' ] );
		update_post_meta( $post->ID, Config::get( 'restriction-key' ), 1 );
		$content = $this->_get_the_excerpt( $post );
		$this->assertEquals( 'excerpt', trim( strip_tags( $content ) ) );
	}

	/**
	 * @test
	 */
	public function restricted_has_more() {
		$post = $this->_create_post( [ 'post_content' => 'before<!--more-->after', 'post_excerpt' => '' ] );
		update_post_meta( $post->ID, Config::get( 'restriction-key' ), 1 );
		$content = $this->_get_the_excerpt( $post );
		$this->assertEquals( 'before', trim( strip_tags( $content ) ) );
	}

	/**
	 * @test
	 */
	public function restricted_has_more_only() {
		$post = $this->_create_post( [ 'post_content' => '<!--more-->after', 'post_excerpt' => '' ] );
		update_post_meta( $post->ID, Config::get( 'restriction-key' ), 1 );
		$content = $this->_get_the_excerpt( $post );
		$this->assertEquals( 'Viewing is restricted.', trim( strip_tags( $content ) ) );
	}

	public function test_auto_excerpt_excludes_member_only_blocks_in_nested_content() {
		$content = '<!-- wp:group --><div class="wp-block-group">'
			. '<!-- wp:paragraph --><p>Public text</p><!-- /wp:paragraph -->'
			. '<!-- wp:paragraph {"smmpIsRestrected":true} --><p>Private block</p><!-- /wp:paragraph -->'
			. '</div><!-- /wp:group -->';
		$post = $this->_create_post( array( 'post_content' => $content, 'post_excerpt' => '' ) );
		$this->assertSame( 'Public text', trim( strip_tags( $this->_get_the_excerpt( $post ) ) ) );
	}

	public function test_member_only_post_excerpt_filters_the_teaser_before_more() {
		$content = '<!-- wp:paragraph {"smmpIsRestrected":true} --><p>Private block</p><!-- /wp:paragraph -->'
			. '<!-- wp:paragraph --><p>Public teaser</p><!-- /wp:paragraph -->'
			. '<!-- wp:more --><!--more--><!-- /wp:more -->'
			. '<!-- wp:paragraph --><p>Private continuation</p><!-- /wp:paragraph -->';
		$post = $this->_create_post( array( 'post_content' => $content, 'post_excerpt' => '' ) );
		update_post_meta( $post->ID, Config::get( 'restriction-key' ), 1 );

		$this->assertSame( 'Public teaser', trim( strip_tags( $this->_get_the_excerpt( $post ) ) ) );
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
