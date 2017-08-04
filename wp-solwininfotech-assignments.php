<?php
/*
Plugin Name: Solwin Infotech Plugin Assignments
Plugin URI:  http://tymescripts.com/solwininfotech
Description: WordPress Plugin Assignment for Solwininfotech
Version:     0.1
Author:      Abhijeet Bendre
Author URI:  http://tymescripts.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: wp_solwininfotech_assignments
*/

class Wp_Solwininfotech_Assignments
{

	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'wpsa_add_admin_menu' ));
		add_action( 'admin_init', array( $this, 'wpsa_settings_init' ));
		add_action("admin_enqueue_scripts",array($this, 'wpsa_init_assets'));

		/*ajax hooks for handling Chekbox selection */
		add_action('wp_ajax_wpsa_get_selected_post_types', array($this, 'wpsa_get_selected_post_types'));
		//add_action('wp_ajax_wpsa_get_selected_post_types', array($this, 'wpsa_get_selected_post_types'));
		//$this->wpsa_get_selected_post_types( 'post' );
	}

	function wpsa_init_assets(  ) {

		wp_register_style("wpsa_main" , plugin_dir_url( __FILE__ ).'assets/css/wpsa_main.css',null);
		wp_enqueue_style('wpsa_main');

	}


	function wpsa_add_admin_menu(  ) {

		add_options_page( 'Solwin Infotech Plugin Assignment', 'Solwin Infotech Plugin Assignment', 'manage_options', 'wp_solwininfotech_assignment', array( $this, 'wpsa_options_page' ));

	}

	function wpsa_settings_init(  ) {

		register_setting( 'pluginPage', 'wpsa_settings' );

		add_settings_section(
			'wpsa_alert_box_section',
			__( 'Alert text on front side', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_alert_box_settings_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'wpsa_alert_text_field',
			__( 'Alert Text', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_alert_text_field_render' ),
			'pluginPage',
			'wpsa_alert_box_section'
		);

		add_settings_section(
			'wpsa_cpt_checkbox_section',
			__( 'Checkboxes for all custom post types.', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_cpt_checkbox_settings_section_callback' ),
			'pluginPage'
		);

		$post_types = get_post_types();
		$field_id = 0;

		foreach ( get_post_types( '', 'names' ) as $post_type ) {
			if( in_array( $post_type , array( 'post' , 'page' ) ) )
			{
				add_settings_field(
					'wpsa_checkbox_field_'.$field_id,
					__( $post_type, 'wp_solwininfotech_assignment' ),
					array( $this, 'wpsa_checkbox_field_render' )	,
					'pluginPage',
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

	function wpsa_alert_text_field_render(  ) {

		$options = get_option( 'wpsa_settings' );
		?>
		<input type='text' name='wpsa_settings[wpsa_text_field_0]' value='<?php echo $options['wpsa_text_field_0']; ?>'>
		<?php

	}

	function wpsa_checkbox_field_render( array $args  ) {

		$field_id   = $args['field_id'];
		$post_type   = $args['post_type'];
		$options 		= get_option( 'wpsa_settings' );
		//add_action("wpsa_multi_select_field_".$field_id, array( $his, ''));
		?>
	  <div class="wpsa_cpt_checkbox">
			<input type='checkbox' name='<?php echo "wpsa_settings[wpsa_checkbox_field_".$field_id."]"; ?>' <?php if(!empty($options['wpsa_checkbox_field_'.$field_id])) checked( $options['wpsa_checkbox_field_'.$field_id], 1 ); ?> value='1'>
		</div>
		<div class="multi_slelect_box">
				<select  multiple="multiple" name='<?php echo "wpsa_settings[wpsa_multi_select_field_".$field_id."][]"; ?>'>
					<!-- <option value=''> Your <?php echo $post_type;?>s will be added here </option> -->
					<?php $this->wpsa_get_selected_post_types( 'post', $field_id ); ?>
				</select>
		</div>
		<?php

	}

	function wpsa_alert_box_settings_section_callback(  ) {

		echo __( 'Add text which will be displayed as alert on front side', 'wp_solwininfotech_assignment' );

	}

	function wpsa_cpt_checkbox_settings_section_callback(  ) {

		echo __( 'Check any of the below post type’s checkbox, all posts of that post type will be listed below in multi-selectbox.', 'wp_solwininfotech_assignment' );

	}

	function wpsa_options_page(  ) {

		?>
		<form action='options.php' method='post'>
			<h1>Solwin Infotech Assignments</h1>
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

	}
	
	function wpsa_get_selected_post_types( $post_type, $field_id  ) {

		$args = array(
					'post_type'        => $post_type,
					'post_status'      => 'publish',
			);
		$posts_types_array = get_posts( $args );
   	$options 		= get_option( 'wpsa_settings' );
		$select_options = "";
		$selected = isset( $options["wpsa_multi_select_field_".$field_id] ) ? $options["wpsa_multi_select_field_".$field_id] : "";

		foreach ($posts_types_array as $pt) {
			$option_value = "wpsa_pt_".$pt->ID;
			$select_options .= "<option value='".$option_value."' ";

			if( $selected !== "" )
			{
					if(in_array( $option_value, $selected ))
					{
						$select_options .= "selected=selected ";
					}
			}
			$select_options .="'>";
			$select_options .=  $pt->post_title;
			$select_options .= "</option>";
		}
		echo $select_options;
	}

	//function wpsa_checkbox_field_render( $post_type ) {

	//}

}

new Wp_Solwininfotech_Assignments();
