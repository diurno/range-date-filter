<?php


namespace rangedatef\Core\Includes\Classes;
use rangedatef\Core\Includes\Classes\Range_Date_Filter_Db;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Range_Date_Filter_Settings
 *
 * This class contains all of the plugin settings.
 * Here you can configure the whole plugin data.
 *
 * @package		RANGEDATEF
 * @subpackage	Classes/Range_Date_Filter_Settings
 * @author		Federico Cadierno
 * @since		1.0.0
 */
class Range_Date_Filter_Settings{

	/**
	 * The plugin name
	 *
	 * @var		string
	 * @since   1.0.0
	 */
	private $plugin_name;

	/**
	 * Our Range_Date_Filter_Settings constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){

		$this->plugin_name = RANGEDATEF_NAME;
		$this->add_settings_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### CALLABLE FUNCTIONS
	 * ###
	 * ######################
	 */

	/**
	 * Return the plugin name
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	string The plugin name
	 */
	public function get_plugin_name(){
		return apply_filters( 'RANGEDATEF/settings/get_plugin_name', $this->plugin_name );
	}

	/**
	 * Return the existing post types
	 *
	 * @access	public
	 * @since	1.0.0
	 * @return	array post types array
	 */
	function get_post_types() {
		
		$args = array(
			'public'   => true,
			'_builtin' => false
		);
  
		$output = 'names'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')
  
		$post_types = get_post_types( $args, $output, $operator );


		return $post_types;
	}

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_settings_hooks(){	
		add_action( 'wp_ajax_filter_articles_save_post_types', array( $this, 'filter_articles_save_post_types' ), 30 );
		add_action('wp_ajax_nopriv_filter_articles_save_post_types', array( $this,'filter_articles_save_post_types'), 30);
	}

	public function filter_articles_save_post_types() {	
		$dbClass = new Range_Date_Filter_Db;
		return $dbClass->saveThePostTypeSeleted( $_POST );   		
	}

	public function get_settings() {	
		$dbClass = new Range_Date_Filter_Db;
		return $dbClass->getTheSettings( );   		
	}
}
