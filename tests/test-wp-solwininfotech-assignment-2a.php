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

}
