<?php
/**
 * Tests for public descriptions of member-only blocks.
 *
 * @package snow-monkey-member-post
 */

use Snow_Monkey\Plugin\MemberPost\App\Setup\Description;

class DescriptionTest extends WP_UnitTestCase {

	protected $description;

	public function set_up() {
		parent::set_up();
		$this->description = new Description();
	}

	public function tear_down() {
		remove_filter( 'inc2734_wp_ogp_description', array( $this->description, '_restrict_description' ), PHP_INT_MAX );
		remove_filter( 'inc2734_wp_seo_description', array( $this->description, '_restrict_description' ), PHP_INT_MAX );
		parent::tear_down();
	}

	protected function create_post( $content, $args = array() ) {
		$post_id = self::factory()->post->create(
			array_merge(
				array( 'post_content' => $content, 'post_status' => 'publish', 'post_excerpt' => '' ),
				$args
			)
		);
		$this->go_to( get_permalink( $post_id ) );
		return $post_id;
	}

	protected function paragraph( $text, $restricted = false ) {
		$attributes = $restricted ? ' {"smmpIsRestrected":true}' : '';
		return '<!-- wp:paragraph' . $attributes . ' --><p>' . $text . '</p><!-- /wp:paragraph -->';
	}

	public function test_public_description_excludes_private_blocks_for_every_visitor() {
		$this->create_post( $this->paragraph( 'Public' ) . $this->paragraph( 'Secret', true ) . $this->paragraph( 'Visible' ) );
		$member = self::factory()->user->create( array( 'role' => 'subscriber' ) );
		foreach ( array( 0, $member ) as $user_id ) {
			wp_set_current_user( $user_id );
			foreach ( array( 'inc2734_wp_ogp_description', 'inc2734_wp_seo_description' ) as $hook ) {
				$this->assertSame( 'Public Visible', apply_filters( $hook, 'Public Secret Visible' ) );
			}
		}
	}

	public function test_nested_private_blocks_and_private_parent_blocks_are_excluded() {
		$content  = '<!-- wp:group --><div class="wp-block-group">';
		$content .= $this->paragraph( 'Public' ) . $this->paragraph( 'Nested secret', true );
		$content .= '</div><!-- /wp:group -->';
		$content .= '<!-- wp:group {"smmpIsRestrected":true} --><div class="wp-block-group">';
		$content .= $this->paragraph( 'Private parent secret' ) . '</div><!-- /wp:group -->';
		$this->create_post( $content );
		$this->assertSame( 'Public', trim( apply_filters( 'inc2734_wp_ogp_description', 'Leaked content' ) ) );
	}

	public function test_private_only_content_produces_an_empty_description() {
		$this->create_post( $this->paragraph( 'Secret', true ) );
		$this->assertSame( '', apply_filters( 'inc2734_wp_ogp_description', 'Secret' ) );
		$this->assertSame( '', apply_filters( 'inc2734_wp_seo_description', 'Secret' ) );
	}

	public function test_explicit_excerpt_and_meta_description_are_preserved() {
		$post_id = $this->create_post( $this->paragraph( 'Secret', true ), array( 'post_excerpt' => 'Public excerpt' ) );
		$this->assertSame( 'Public excerpt', apply_filters( 'inc2734_wp_ogp_description', 'Public excerpt' ) );
		wp_update_post( array( 'ID' => $post_id, 'post_excerpt' => '' ) );
		update_post_meta( $post_id, 'wp-seo-meta-description', 'Public description' );
		$this->go_to( get_permalink( $post_id ) );
		$this->assertSame( 'Public description', apply_filters( 'inc2734_wp_ogp_description', 'Public description' ) );
		$this->assertSame( 'Public description', apply_filters( 'inc2734_wp_seo_description', 'Public description' ) );
	}

	public function test_unrestricted_content_and_disabled_auto_description_are_preserved() {
		$this->create_post( $this->paragraph( 'Public' ) );
		$this->assertSame( 'Customized description', apply_filters( 'inc2734_wp_ogp_description', 'Customized description' ) );
		$this->create_post( $this->paragraph( 'Secret', true ) );
		$this->assertSame( '', apply_filters( 'inc2734_wp_seo_description', '' ) );
	}

	public function test_description_uses_the_queried_post_instead_of_the_loop_post() {
		$this->create_post( $this->paragraph( 'Public' ) . $this->paragraph( 'Secret', true ) );
		$GLOBALS['post'] = self::factory()->post->create_and_get( array( 'post_content' => 'Other post' ) );
		$this->assertSame( 'Public', apply_filters( 'inc2734_wp_ogp_description', 'Public Secret' ) );
	}

	public function test_false_restriction_attributes_remain_public() {
		$content  = '<!-- wp:paragraph {"smmpIsRestrected":false} --><p>Public</p><!-- /wp:paragraph -->';
		$content .= $this->paragraph( 'Secret', true );
		$this->create_post( $content );
		$this->assertSame( 'Public', apply_filters( 'inc2734_wp_ogp_description', 'Public Secret' ) );
	}

	public function test_empty_excerpt_markup_does_not_bypass_filtering() {
		$this->create_post( $this->paragraph( 'Public' ) . $this->paragraph( 'Secret', true ), array( 'post_excerpt' => '<!-- empty -->' ) );
		$this->assertSame( 'Public', apply_filters( 'inc2734_wp_ogp_description', 'Public Secret' ) );
	}

	public function test_description_generation_does_not_execute_shortcodes() {
		$calls = 0;
		add_shortcode( 'smmp_description_test', function () use ( &$calls ) {
			++$calls;
			return 'Dynamic content';
		} );
		$this->create_post( $this->paragraph( 'Public' ) . '[smmp_description_test]' . $this->paragraph( 'Secret', true ) );
		$this->assertSame( 'Public', apply_filters( 'inc2734_wp_ogp_description', 'Public Secret' ) );
		$this->assertSame( 0, $calls );
		remove_shortcode( 'smmp_description_test' );
	}

	public function test_page_and_posts_page_descriptions_are_filtered() {
		$page_id = $this->create_post( $this->paragraph( 'Public' ) . $this->paragraph( 'Secret', true ), array( 'post_type' => 'page' ) );
		$this->assertSame( 'Public', apply_filters( 'inc2734_wp_ogp_description', 'Public Secret' ) );
		update_option( 'show_on_front', 'page' );
		update_option( 'page_for_posts', $page_id );
		update_option( 'page_on_front', self::factory()->post->create( array( 'post_type' => 'page' ) ) );
		$this->go_to( get_permalink( $page_id ) );
		$this->assertTrue( is_home() );
		$this->assertSame( 'Public', apply_filters( 'inc2734_wp_ogp_description', 'Public Secret' ) );
	}
}
