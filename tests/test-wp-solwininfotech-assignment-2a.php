<?php
/**
 * Class Wp_Solwininfotech_Assignment_2a_Test
 *
 * @package Wp_Solwininfotech_Assignment_2a
 */

/**
 * Sample test case.
 */
class Wp_Solwininfotech_Assignment_2a_Test extends WP_UnitTestCase {

	/**
	 * Test if Plugin is active.
	 */
	function test_is_plugin_active() {
		$this->assertTrue( is_plugin_active( WPSA_2A_PLUGIN_NAME . '/' . WPSA_2A_PLUGIN_NAME . 'php' ) );
	}

	/**
	 * Test if altleast one post exists
	 */
	function test_if_altleast_one_post_type_post_exists() {
		$this->factory()->post->create_many(
			1,
			array(
				'post_status' => 'publish',
				'post_type' => 'post',
			)
		);

		$count1 = wp_count_posts( 'post', 'readable' );
		$this->assertGreaterThanOrEqual( 1, $count1->publish );
	}

	/**
	 * Test if altleast one page exists
	 */
	function test_if_altleast_one_post_type_page_exists() {
		$this->factory()->post->create_many(
			1,
			array(
				'post_status' => 'publish',
				'post_type' => 'page',
			)
		);

		$count1 = wp_count_posts( 'page', 'readable' );
		$this->assertGreaterThanOrEqual( 1, $count1->publish );
	}


}
