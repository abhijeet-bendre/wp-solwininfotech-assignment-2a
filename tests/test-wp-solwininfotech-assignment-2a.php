<?php
/**
 * Class Wp_Solwininfotech_Assignment_2a_Test
 *
 * @package Wp_Solwininfotech_Assignment_2a
 */

/**
 * Test case for Assignment-2a: Simple Alert Plugin.
 */
class Wp_Solwininfotech_Assignment_2a_Test extends WP_UnitTestCase {

	/**
	 * Data for Alert text
	 *
	 * @var string private
	 */
	private $wpsa_alert_text_field = 'Assignment-2a: Simple Alert Plugin';

	/**
	 * Multi Select option Key
	 *
	 * @var string private
	 */
	private $multiselect_name = 'wpsa_multi_select_field_1';

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
		update_option( 'wpsa_settings', array( $_POST['wpsa_alert_text_field'] ) );
		$this->assertContains( $this->wpsa_alert_text_field, get_option( 'wpsa_settings' ) );
	}

	/**
	 * Test if checked option is saved.
	 */
	public function test_check_box_is_saved() {
		// Simulate $_POST check box variable (checked value is 1).
		$_POST['wpsa_checkbox_field_1'] = 1;
		update_option( 'wpsa_settings', array( $_POST['wpsa_checkbox_field_1'] ) );
		$this->assertContains( 1, get_option( 'wpsa_settings' ) );
	}

	/**
	 * Test posts multi select option is saved
	 */
	public function test_posts_multi_select_option_is_saved() {

		// Create temproray posts post_type.
		for ( $i = 0; $i < 5; $i++ ) {
				$this->factory()->post->create(
					array(
						'post_status' => 'publish',
						'post_type' => 'post',
						'post_title' => 'Post Title ' . $i,
					)
				);
		}

		// Check if cout returned is 5.
		$count_posts = wp_count_posts( 'post', 'readable' );
		$this->assertEquals( 5, $count_posts->publish );

		$args = array(
			'post_type'   => 'post',
			'post_status' => 'publish',
		);
		$posts_types_array = new \WP_Query( $args );
		$posts_types_array = $posts_types_array->posts;

		// Simulate $_POST variable.
		$_POST[ $this->multiselect_name ] = $posts_types_array;

		update_option( 'wpsa_settings', array( $_POST[ $this->multiselect_name ] ) );
		$this->assertContains( $posts_types_array, get_option( 'wpsa_settings' ) );
	}

	/**
	 * Test page multi select option is saved
	 */
	public function test_page_multi_select_option_is_saved() {

		// Create temproray page post_type.
		for ( $i = 0; $i < 5; $i++ ) {
				$this->factory()->post->create(
					array(
						'post_status' => 'publish',
						'post_type' => 'page',
						'post_title' => 'Page Title ' . $i,
					)
				);
		}

		// Check if count returned is 5.
		$count_posts = wp_count_posts( 'page', 'readable' );
		$this->assertEquals( 5, $count_posts->publish );

		$args = array(
			'post_type'   => 'page',
			'post_status' => 'publish',
		);
		$page_post_type_array = new \WP_Query( $args );
		$page_post_type_array = $page_post_type_array->posts;

		// Simulate $_POST variable.
		$_POST[ $this->multiselect_name ] = $page_post_type_array;

		update_option( 'wpsa_settings', array( $_POST[ $this->multiselect_name ] ) );
		$this->assertContains( $page_post_type_array, get_option( 'wpsa_settings' ) );
	}
}
