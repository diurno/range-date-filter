<?php

namespace rangedatef\Core\Includes\Classes;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Range_Date_Filter_Helpers
 *
 * This class contains repetitive functions that
 * are used globally within the plugin.
 *
 * @package		RANGEDATEF
 * @subpackage	Classes/Range_Date_Filter_Helpers
 * @author		Federico Cadierno
 * @since		1.0.0
 */
class Range_Date_Filter_Helpers{

	function __construct(){
		$this->add_hooks();
	}

	private function add_hooks(){

		add_action( 'RANGEDATEF_add_external_assets', array( $this, 'add_external_assets' ), 10 );

	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	
	public function add_external_assets( )
	{
		wp_enqueue_style( 'rangedatef-jquery-ui-css', 'https://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css', null,null, 'all' );
		wp_register_script( 'rangedatef-jquery-ui', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.3/jquery-ui.min.js', array('jquery'), null, true );
		wp_enqueue_script('rangedatef-jquery-ui');
		wp_enqueue_style( 'rangedatef-frontend-styles', RANGEDATEF_PLUGIN_URL . 'core/includes/templates/css/frontend-styles.css', array(), RANGEDATEF_VERSION, 'all' );
		wp_enqueue_script( 'rangedatef-frontend-scripts', RANGEDATEF_PLUGIN_URL . 'core/includes/templates/js/frontend-scripts.js', array(), null, true );

		wp_localize_script( 'rangedatef-frontend-scripts', 'rangedatef', array(
			'plugin_name'    => __( RANGEDATEF_NAME, 'range-date-filter' ),
			"ajaxurl"        => admin_url("admin-ajax.php")
		));
	}
 

	 

}
