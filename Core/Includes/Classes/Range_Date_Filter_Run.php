<?php

namespace rangedatef\Core\Includes\Classes;
use rangedatef\Core\Range_Date_Filter;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Range_Date_Filter_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		RANGEDATEF
 * @subpackage	Classes/Range_Date_Filter_Run
 * @author		Federico Cadierno
 * @since		1.0.0
 */
class Range_Date_Filter_Run{

	private static $classes_instance;

	/**
	 * Our Range_Date_Filter_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$mainClass = new Range_Date_Filter;
		self::$classes_instance = $mainClass::instance();
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
		add_action( 'admin_menu', array( $this, 'settings_panel' ), 100, 1 );
		add_action( 'admin_init', array( $this,'articles_filter_create_table'), 30 );
		add_shortcode( 'articles-filter', array( $this,'articles_filter_shortcode'), 40 );
		add_action('wp_ajax_filter_articles', array( $this,'filter_articles'), 50);
		add_action('wp_ajax_nopriv_filter_articles', array( $this,'filter_articles'), 50);
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles() {
		wp_enqueue_style( 'rangedatef-backend-styles', RANGEDATEF_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), RANGEDATEF_VERSION, 'all' );
		wp_enqueue_script( 'rangedatef-backend-scripts', RANGEDATEF_PLUGIN_URL . 'core/includes/assets/js/backend-scripts.js', array(), RANGEDATEF_VERSION, false );
		wp_enqueue_style( 'wp-color-picker' );
		
		wp_enqueue_script( 'wp-color-picker-alpha', RANGEDATEF_PLUGIN_URL . 'core/includes/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), null, true );
		
		wp_enqueue_script( 'wp-color-picker-alpha' );

		wp_localize_script( 'rangedatef-backend-scripts', 'rangedatef', array(
			'plugin_name'    => __( RANGEDATEF_NAME, 'range-date-filter' ),
			"ajaxurl"        => admin_url("admin-ajax.php")
		));

		wp_add_inline_script(
			'wp-color-picker-alpha',
			'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
		);
	}

	public function articles_filter_create_table( ) {
		self::$classes_instance->db->createTables();
	}

	public function settings_panel( $admin_bar ) {

		add_menu_page( 'Settings', 'Date Range Filter', 'manage_options', 'rangedatef-plugin',array(&$this, 'rangedatef_setting_render')); 
		//add_submenu_page( 'rangedatef-plugin', 'Settings', 'Settings', 'manage_options', 'rangedatef-settings-plugin', 
                            //array(&$this, 'rangedatef_setting_render')); 

	}


	public function rangedatef_setting_render(  ) {
		$postTypesNames = self::$classes_instance->settings->get_post_types();
		$rangedatef_settings = self::$classes_instance->settings->get_settings();

		$html_form = '<div class="rangedatef-wrapper-form">';
		$html_form .= '<form id="post-types-form">';
		$html_form .= '<div class="row">';
		$html_form .= '<h1>Date Range Filter</h1>';
		$html_form .= '<p>Select the Custom Post types you want to add to the Range Filter.</p>';
		$html_form .= '</div>';
		$html_form .= '<ul class="post-types-list">';
		$postTypesSelected = unserialize($rangedatef_settings->post_type);
		foreach($postTypesNames as $item => $value) {
			$checked = "";
			if( in_array($value, $postTypesSelected)) {
				$checked = "checked";
			}
			$html_form .= '<li>';
            $html_form .= '<input type="checkbox" name="postType[]" id="'.$value.'" value="'.$value.'" '.$checked.' />';  
            $html_form .= '<label class="cgpt-checkbox-label" for="'.$value.'">'.$item.'</label>';       
            $html_form .= '</li>';
        }        
        $html_form .= '</ul>';
        $html_form .= '<div class="row">';
        $html_form .= '<label for="ui_slider_color">Slider Color</label>';
        $html_form .= '<input type="text" name="ui_slider_color" class="color-picker" data-alpha-enabled="true" value="'.$rangedatef_settings->ui_slider_color.'" />';
        $html_form .= '</div>';
        $html_form .= '<div class="row">';
        $html_form .= '<label for="ui_slider_range_color">Range Color</label>';
        $html_form .= '<input type="text" name="ui_slider_range_color" class="color-picker" data-alpha-enabled="true" value="'.$rangedatef_settings->ui_slider_range_color.'" />';
        $html_form .= '</div>';
		$html_form .= '<div class="row">';
        $html_form .= '<label for="ui_slider_handle_color">Handle Color</label>';
        $html_form .= '<input type="text" name="ui_slider_handle_color" class="color-picker" data-alpha-enabled="true" value="'.$rangedatef_settings->ui_slider_handle_color.'" />';
        $html_form .= '</div>';
		$html_form .= '<div class="row-submit">';
		$html_form .= '<input type="button" class="button" id="post-type-submit" value="Save" />';
		$html_form .= '</div>';
		$html_form .= '</form>';
		$html_form .= '<div class="msg"></div>';
		

		$html_form .= '</div>';
		echo $html_form;

	}

	public function articles_filter_shortcode( $args ) {
        
		do_action( 'RANGEDATEF_add_external_assets');

		$rangedatef_settings = self::$classes_instance->settings->get_settings();
		$ui_slider_bgcolor = ($rangedatef_settings->ui_slider_color) ? $rangedatef_settings->ui_slider_color : '#ffffff';
		$ui_slider_range_color = ($rangedatef_settings->ui_slider_range_color) ? $rangedatef_settings->ui_slider_range_color : 'linear-gradient(90deg, rgba(210, 210, 210, .6) 27%, rgba(204, 204, 204, 0.7) 70%)';
  		$ui_slider_handle_color = ($rangedatef_settings->ui_slider_handle_color) ? $rangedatef_settings->ui_slider_handle_color : 'linear-gradient(0deg, rgba(230,230,230,1) 0%, rgba(225,225,225,1) 33%, rgba(210,210,210,1) 69%, rgba(228,228,228,1) 100%)';
		
		$filter_html = '';
		$filter_html .= '<style>';
		$filter_html .= '#slider-range { background:'.$ui_slider_bgcolor.'}';
		$filter_html .= '#slider-range .ui-slider-range { background:'.$ui_slider_range_color.'}';
		$filter_html .= '#slider-range .ui-slider-handle { background:'.$ui_slider_handle_color.'}';
		$filter_html .= '</style>';
		$filter_html .= '<div class="articles-filter-wrapper">';
		$filter_html .= '<div class="articles-filter-container">';
		$filter_html .= '<label for="amount">Date range:</label>
		<input type="text" id="date-selected" style="border: 0; color: #f6931f; font-weight: bold;" size="100" />';
		$filter_html .= '<input type="hidden" id="date-from" name="date-from" value />
		<input type="hidden" id="date-to" name="date-to" value /><div id="slider-range"></div>';
		$filter_html .= '</div>';
		$filter_html .= '<div id="article-results" class="results">';
		$filter_html .= '<div class="row">';
		$filter_html .= '<div class="article">';
		$filter_html .= '<div class="article--title"><h2></h2></div>';
		$filter_html .= '<div class="article--body"><p></p></div>';
		$filter_html .= '</div>'; // end article
		$filter_html .= '</div>'; // end of row
		$filter_html .= '</div>'; // end of results
		$filter_html .= '</div>'; // end of container
	
		return $filter_html;
		
	}

	function filter_articles() {

		$rangedatef_settings = self::$classes_instance->settings->get_settings();

		try {
   
			$date_from = date("Y-m-d",strtotime($_POST['date_from']));
			$date_to = date("Y-m-d",strtotime($_POST['date_to']));

			$meta_query = array(
				'relation' => 'AND',
				array(
					'key' => 'date_from',
					'value' => $date_from,
					'compare' => '>=',
					'type' => 'DATE',
				),
				array(
					'key' => 'date_to',
					'value' => $date_to,
					'compare' => '<=',
					'type' => 'DATE',
				),
			);
		
			$args = array(
				'post_type'     => unserialize($rangedatef_settings->post_type),
				'nopaging'      => true,
				'meta_query'    => $meta_query
			);
			
			$query = new \WP_Query( $args );
						
			$articles = $query->posts;			
			$response = array("articlesRecords" => $articles);
			wp_send_json($response);
		
		
			exit();
		} catch(Exception $e) {
			print_r($e);
		}	
	}

}
