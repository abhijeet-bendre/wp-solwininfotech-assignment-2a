<?php
/**
 *  Assignment-2a: Simple Alert Plugin
 *
 * @package Solwin Infotech Plugin Assignment-2a
 * @version 0.1
 */

/*
Plugin Name: Solwin Infotech Plugin Assignment-2a
Plugin URI:  http://tymescripts.com/solwininfotech
Description: Solwin Infotech Assignment-2a: Simple Alert Plugin
Version:     0.1
Author:      Abhijeet Bendre
Author URI:  http://tymescripts.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: wp_solwininfotech_assignment_2a
*/

namespace  Wp_Solwininfotech_Assignment_2b;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/* Global variables and constants */
global $wpsa_2b_db_version;
$wpsa_2b_db_version = '1.0';

define( 'WPSA_2A_PLUGIN_NAME', 'wp-solwininfotech-assignment-2a' );
define( 'WPSA_2A_PLUGIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

/**
 * Solwininfotech Assignment 2a Class.
 *
 * @category Class
 *
 * @since 0.1
 */
class Wp_Solwininfotech_Assignment_2a {
	/**
	 * Prefix for Multiselect Option boxes
	 *
	 * @var string private
	 */
	private $multiselect_option_prefix  = 'wpsa_pt_';

	/**
	 * Constructor for this class
	 *
	 * @since 0.1
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpsa_add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'wpsa_settings_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wpsa_init_assets' ) );

		/*ajax hooks for handling Chekbox selection */
		add_action( 'wp_ajax_wpsa_get_selected_post_types', array( $this, 'wpsa_get_selected_post_types' ) );
		add_action( 'wp_head', array( $this, 'alert_box_front_end' ) );
	}

	/**
	 * Init assets such as JS/CSS, required by plugin
	 *
	 * @since 0.1
	 */
	function wpsa_init_assets() {
		wp_register_style( 'wpsa_main', plugin_dir_url( __FILE__ ) . 'assets/css/wpsa_main.css',null );
		wp_enqueue_style( 'wpsa_main' );
		wp_register_script( 'wpsa_main_js', plugin_dir_url( __FILE__ ) . 'assets/js/wpsa_main.js' );
		wp_enqueue_script( 'wpsa_main_js', array( 'jquery' ) );
		wp_localize_script( 'wpsa_main_js', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
		wp_localize_script( 'wpsa_main_js', 'site_url', site_url() );
	}

	/**
	 * Add menu under Settings Main menu
	 *
	 * @since 0.1
	 */
	function wpsa_add_admin_menu() {
		add_options_page( 'Solwin Infotech Plugin Assignment 2a', 'Solwin Infotech Plugin Assignment 2a', 'manage_options', 'wp_solwininfotech_assignment', array( $this, 'wpsa_options_page' ) );
	}


	/**
	 * Register setting 'simple_alert' and add its required fields.
	 *
	 * @since 0.1
	 */
	function wpsa_settings_init() {
		$field_id = 0;

		register_setting( 'simple_alert', 'wpsa_settings' );

		add_settings_section(
			'wpsa_alert_box_section',
			__( 'Alert text on front side', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_alert_box_settings_section_callback' ),
			'simple_alert'
		);

		add_settings_field(
			'wpsa_alert_text_field',
			__( 'Alert Text', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_alert_text_field_render' ),
			'simple_alert',
			'wpsa_alert_box_section'
		);

		add_settings_section(
			'wpsa_cpt_checkbox_section',
			__( 'Checkboxes for all custom post types.', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_cpt_checkbox_settings_section_callback' ),
			'simple_alert'
		);

		foreach ( get_post_types( '', 'names' ) as $post_type ) {

			if ( in_array( $post_type , array( 'post', 'page' ), true ) ) {
				add_settings_field(
					'wpsa_checkbox_field_' . $field_id,
					$post_type,
					array( $this, 'wpsa_checkbox_field_render' ),
					'simple_alert',
					'wpsa_cpt_checkbox_section',
					array(
						'field_id' => $field_id,
						'post_type' => $post_type,
					)
				);
			}
			$field_id++;
		}
	}

	/**
	 * Render alert text field on settings Page
	 *
	 * @since 0.1
	 */
	function wpsa_alert_text_field_render() {
		$options = get_option( 'wpsa_settings' );
		?>
		<input type='text' name='wpsa_settings[wpsa_alert_text_field]' value='<?php echo esc_html( $options['wpsa_alert_text_field'] ); ?>'>
		<?php
	}

	/**
	 * Render checkbox field on settings Page
	 *
	 * @param array $args cf7 form.
	 * @since 0.1
	 */
	function wpsa_checkbox_field_render( array $args ) {

		$field_id   = $args['field_id'];
		$post_type  = $args['post_type'];
		$options    = get_option( 'wpsa_settings' );
		?>

		<input type='checkbox'
			data-post-type='<?php echo esc_html( $post_type ); ?>'
			name='<?php echo esc_html( 'wpsa_settings[wpsa_checkbox_field_' . $field_id . ']' ); ?>'
			<?php isset( $options[ "wpsa_checkbox_field_$field_id" ] ) ? checked( $options[ "wpsa_checkbox_field_$field_id" ], 1 ) : ''; ?> value='1'>

		<select class="wpsa_multi_slelect_box" multiple='multiple' data-field-id='<?php echo esc_html( $field_id ); ?>'
			name='<?php echo 'wpsa_settings[wpsa_multi_select_field_' . esc_html( $post_type ) . '][]'; ?>'
			<?php
			if ( empty( $options[ "wpsa_checkbox_field_$field_id" ] ) ) {
				echo 'disabled=disabled';
			}
			?>
			>
			<?php
			if ( ! empty( $options[ "wpsa_checkbox_field_$field_id" ] ) ) {
				$this->wpsa_get_selected_post_types( $post_type, $field_id );
			}
			?>
		</select>
		<?php
	}

	/**
	 * Description text for alert box input field
	 *
	 * @since 0.1
	 */
	function wpsa_alert_box_settings_section_callback() {
		esc_html_e( 'Add text which will be displayed as alert on front side', 'wp_solwininfotech_assignment' );
	}

	/**
	 * Description text for checkbox input field
	 *
	 * @since 0.1
	 */
	function wpsa_cpt_checkbox_settings_section_callback() {
		esc_html_e( 'Check any of the below post type’s checkbox, all posts of that post type will be listed in multi select box.', 'wp_solwininfotech_assignment' );
	}

	/**
	 * Callback function for Settings menu
	 *
	 * @since 0.1
	 */
	function wpsa_options_page() {

		?>
		<form action='options.php' method='post'>
			<h1>Solwin Infotech Assignment-2a: Simple Alert Plugin</h1>
			<?php
			settings_fields( 'simple_alert' );
			do_settings_sections( 'simple_alert' );
			submit_button();
			?>
		</form>
		<?php
	}

	/**
	 * Creates <options></options> string for both backend and ajax call
	 *
	 * @param string $post_type for which options string is to be built.
	 *
	 * @param int    $field_id field_id.
	 *
	 * @since 0.1
	 */
	function wpsa_get_selected_post_types( $post_type = '', $field_id = '' ) {

		if ( ! empty( $_POST['post_type'] ) && ! empty( $_POST['post_type'] ) ) { // Input var okay.
			$post_type = sanitize_text_field( wp_unslash( $_POST['post_type'] ) ); // Input var okay; sanitization okay.
			// @codingStandardsIgnoreLine
			$field_id  = sanitize_text_field( wp_unslash( $_POST['field_id'] ) ); // Input var okay; sanitization okay.
		}

		$args = array(
			'post_type'   => $post_type,
			'post_status' => 'publish',
		);

		$select_options    = '';
		$posts_types_array = new \WP_Query( $args );

		$options           = get_option( 'wpsa_settings' );
		$selected          = isset( $options[ "wpsa_multi_select_field_$post_type" ] ) ? $options[ "wpsa_multi_select_field_$post_type" ] : '';

		$posts_types_array = $posts_types_array->posts;
		foreach ( $posts_types_array as $pt ) {
			$option_value = $this->multiselect_option_prefix . $pt->ID;
			$select_options .= "<option value='" . esc_attr( $option_value ) . "' ";

			if ( '' !== $selected ) {
				if ( in_array( $option_value, $selected, true ) ) {
					$select_options .= 'selected=selected';
				}
			}
			$select_options .= '>';
			$select_options .= $pt->post_title;
			$select_options .= '</option> ';
		}

		echo $select_options; // WPCS: XSS ok.
		if ( ! empty( $_POST['post_type'] ) && ! empty( $_POST['post_type'] ) ) { // Input var okay; sanitization okay.
			die();
		}
	}

	/**
	 * Popuups alert box front end
	 *
	 * @since 0.1
	 */
	function alert_box_front_end() {

		global $post;

		if ( ! is_admin() ) {
			$options    = get_option( 'wpsa_settings' );
			$alert_text = $options['wpsa_alert_text_field'];

			foreach ( $options as $option_key => $option_value ) {

				if ( strpos( $option_key , $post->post_type ) ) {
						$saved_pt_for_alert = $options[ "wpsa_multi_select_field_$post->post_type" ];
					if ( in_array( $this->multiselect_option_prefix . $post->ID, $saved_pt_for_alert, true ) ) {
					?>
						<script>
							alert( "<?php echo esc_html( $alert_text ); ?>" );
						</script>
					<?php
					}
				}
			}
		}
	}

}

new Wp_Solwininfotech_Assignment_2a();
