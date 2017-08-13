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
		update_option( 'wpsa_alert_text_field', $_POST['wpsa_alert_text_field'] );
		$this->assertEquals( $this->wpsa_alert_text_field, get_option( 'wpsa_alert_text_field' ) );
	}

	/**
	 * Test if checked option is saved.
	 */
	public function test_check_box_is_saved() {
		// Simulate $_POST check box variable (checked value is 1).
		$_POST['wpsa_alert_text_field_1'] = 1;
		update_option( 'wpsa_alert_text_field_1', $_POST['wpsa_alert_text_field_1'] );
		$this->assertEquals( 1, get_option( 'wpsa_alert_text_field_1' ) );
	}

	/**
	 * Test multi select option is saved
	 */
	public function test_posts_multi_select_option_is_saved() {

		// Create temproray posts.
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
		$_POST['wpsa_alert_text_field'] = $posts_types_array;

		update_option( $this->multiselect_name, $_POST['wpsa_alert_text_field'] );
		$this->assertEquals( $posts_types_array, get_option( $this->multiselect_name ) );
	}
}
