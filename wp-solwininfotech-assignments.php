<?php
/*
Plugin Name: Solwininfotech Plugin Assignments
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
	}

	function wpsa_add_admin_menu(  ) {

		add_options_page( 'Solwininfotech Plugin Assignment', 'Solwininfotech Plugin Assignment', 'manage_options', 'wp_solwininfotech_assignment', array( $this, 'wpsa_options_page' ));

	}

	function wpsa_settings_init(  ) {

		register_setting( 'pluginPage', 'wpsa_settings' );

		add_settings_section(
			'wpsa_pluginPage_section',
			__( 'Add text which will be displayed as alert on front side', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_settings_section_callback' ),
			'pluginPage'
		);

		add_settings_field(
			'wpsa_alert_text_field',
			__( 'Alert Text', 'wp_solwininfotech_assignment' ),
			array( $this, 'wpsa_alert_text_field_render' ),
			'pluginPage',
			'wpsa_pluginPage_section'
		);

    	$post_types = get_post_types();
			$field_id = 0;
			var_dump( $post_types);
			foreach ( get_post_types( '', 'names' ) as $post_type ) {
					if( in_array( $post_type , array( 'post' , 'page' ) ) )
					{
						add_settings_field(
							'wpsa_checkbox_field_'.$field_id,
							__( $post_type, 'wp_solwininfotech_assignment' ),
							array( $this, 'wpsa_checkbox_field_render' )	,
							'pluginPage',
							'wpsa_pluginPage_section',
							array( 'field_id' => $field_id )
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
		$options 		= get_option( 'wpsa_settings' );
		?>
		<input type='checkbox' name='<?php echo "wpsa_settings[wpsa_checkbox_field_".$field_id."]"; ?>' <?php checked( $options['wpsa_checkbox_field_'.$field_id], 1 ); ?> value='1'>
		<?php

	}


	function wpsa_settings_section_callback(  ) {

		//silence is golden

	}


	function wpsa_options_page(  ) {

		?>
		<form action='options.php' method='post'>

			<h2>Wp_Solwininfotech_Assignment</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php

	}

}

new Wp_Solwininfotech_Assignments();
