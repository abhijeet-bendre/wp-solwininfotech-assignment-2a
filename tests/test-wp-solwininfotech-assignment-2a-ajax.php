<?php
/**
 * Class Wp_Solwininfotech_Assignment_2a_Test
 *
 * @package Wp_Solwininfotech_Assignment_2a
 */

	/**
	 * Ajax Test case for Assignment-2a: Simple Alert Plugin.
	 *
	 * @group ajax
	 */
class Wp_Solwininfotech_Assignment_2a_Ajax_Test extends WP_Ajax_UnitTestCase  {

	/**
	 * Prefix for Multiselect Option boxes
	 *
	 * @var string private
	 */
	private $multiselect_option_prefix  = 'wpsa_pt_';


	/**
	 * Test post_type post Ajax callback returns <options></options> string
	 */
	public function test_if_post_type_post_ajax_callback_works() {
		// Simulate $_POST variable.
		$_POST['wpsa_ajax_nonce'] = wp_create_nonce( 'checkbox_post_type' );
		$_POST['field_id'] = 1;
		$_POST['checked_post_type'] = 'post';
		$select_options = '';
		// Get the recent saved posts.
		$args = array(
			'post_type'   => 'post',
			'post_status' => 'publish',
		);
		$posts_types_array = new \WP_Query( $args );
		$posts_types_array = $posts_types_array->posts;

		foreach ( $posts_types_array as $pt ) {
			$option_value = $this->multiselect_option_prefix . $pt->ID;
			$select_options .= "<option value='" . esc_attr( $option_value ) . "'";
			$select_options .= '>';
			$select_options .= $pt->post_title;
			$select_options .= '</option> ';
		}

		try {
			$this->_handleAjax( 'wpsa_get_selected_post_types' );
			$this->fail( 'Expected exception: WPAjaxDieContinueException' );
		} catch ( WPAjaxDieContinueException $e ) {
				 // We expected this, do nothing.
		}

		// The output should be a 1 for success.
		$this->assertEquals( $select_options, $this->_last_response );
		wp_reset_postdata();
	}

	/**
	 * Test post_type page Ajax callback returns <options></options> string
	 */
	public function test_if_post_type_page_ajax_callback_works() {
		// Simulate $_POST variable.
		$_POST['wpsa_ajax_nonce'] = wp_create_nonce( 'checkbox_post_type' );
		$_POST['field_id'] = 1;
		$_POST['checked_post_type'] = 'page';
		$select_options = '';

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

		// Check if cout returned is 5.
		$count_posts = wp_count_posts( 'page', 'readable' );
		$this->assertEquals( 5, $count_posts->publish );

		// Get the recent saved posts.
		$args = array(
			'post_type'   => 'page',
			'post_status' => 'publish',
		);
		$page_post_type_array = new \WP_Query( $args );
		$page_post_type_array = $page_post_type_array->posts;

		foreach ( $page_post_type_array as $pt ) {
			$option_value = $this->multiselect_option_prefix . $pt->ID;
			$select_options .= "<option value='" . esc_attr( $option_value ) . "'";
			$select_options .= '>';
			$select_options .= $pt->post_title;
			$select_options .= '</option> ';
		}

		try {
			$this->_handleAjax( 'wpsa_get_selected_post_types' );
			$this->fail( 'Expected exception: WPAjaxDieContinueException' );
		} catch ( WPAjaxDieContinueException $e ) {
				 // We expected this, do nothing.
		}

		// The output should be a same for success.
		$this->assertEquals( $select_options, $this->_last_response );
		wp_reset_postdata();
	}

}
