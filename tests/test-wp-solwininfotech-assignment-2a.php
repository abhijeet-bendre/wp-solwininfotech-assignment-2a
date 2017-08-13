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
	 * Data for Alert text
	 *
	 * @var string private
	 */
	 private $wpsa_alert_text_field = 'Assignment-2a: Simple Alert Plugin';

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


	/**
	 * Test alert text option is saved
	 */
	public function test_alert_text_option_is_saved() {
		// Simulate $_POST variable.
		$_POST['wpsa_alert_text_field'] = $this->wpsa_alert_text_field;
		update_option( 'wpsa_alert_text_field', $_POST['wpsa_alert_text_field'] );
		$this->assertEquals( $this->wpsa_alert_text_field, get_option( 'wpsa_alert_text_field' ) );
	}


}
