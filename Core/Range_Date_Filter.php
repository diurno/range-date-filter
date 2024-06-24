<?php

namespace rangedatef\Core;

use rangedatef\Core\Includes\Classes\Range_Date_Filter_Helpers;
use rangedatef\Core\Includes\Classes\Range_Date_Filter_Settings;
use rangedatef\Core\Includes\Classes\Range_Date_Filter_Run;
use rangedatef\Core\Includes\Classes\Range_Date_Filter_Db;
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Range_Date_Filter' ) ) :

	/**
	 * Main Range_Date_Filter Class.
	 *
	 * @package		RANGEDATEF
	 * @subpackage	Classes/Range_Date_Filter
	 * @since		1.0.0
	 * @author		Federico Cadierno
	 */
	final class Range_Date_Filter {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Range_Date_Filter
		 */
		private static $instance;

		/**
		 * RANGEDATEF helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Range_Date_Filter_Helpers
		 */
		public $helpers;

		/**
		 * RANGEDATEF settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Range_Date_Filter_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'range-date-filter' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'range-date-filter' ), '1.0.0' );
		}

		/**
		 * Main Range_Date_Filter Instance.
		 *
		 * Insures that only one instance of Range_Date_Filter exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Range_Date_Filter	The one true Range_Date_Filter
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Range_Date_Filter ) ) {
				self::$instance					= new Range_Date_Filter;
				self::$instance->base_hooks();
				self::$instance->helpers		= new Range_Date_Filter_Helpers();
				self::$instance->settings		= new Range_Date_Filter_Settings();
				self::$instance->db		        = new Range_Date_Filter_Db();

				//Fire the plugin logic
				new Range_Date_Filter_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'RANGEDATEF/plugin_loaded' );
			}

			return self::$instance;
		}


		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'range-date-filter', FALSE, dirname( plugin_basename( RANGEDATEF_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.