<?php

namespace rangedatef\Core\Includes\Classes;


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
    
    class Range_Date_Filter_Db
    {
        
        private static $instance;
        public         $table_settings;
        private        $_wpdb;
        
        public function __construct() {
            global $wpdb;
            $this->table_settings = $wpdb->prefix.'rangedatef_settings';
        }
        
        public function getTheSettings() {
            global $wpdb;
            $sql_query = 'SELECT * FROM `'.$this->table_settings.'`';
            return $wpdb->get_row( $sql_query , OBJECT );
        }
        
        public function saveThePostTypeSeleted( $postArr ) {
            global $wpdb;
            $this->truncateTable();            
            $serialisiedValue = "'".serialize($postArr['postType'])."'";

            $wpdb->query('INSERT INTO `'.$this->table_settings.'` (`post_type`, `ui_slider_color`, `ui_slider_range_color`, `ui_slider_handle_color`) 
                VALUES 
            ('.$serialisiedValue .', "'.$postArr['ui_slider_color'].'", "'.$postArr['ui_slider_range_color'].'", "'.$postArr['ui_slider_handle_color'].'" )');      
        }
        
        public function updateThePostTypeSeleted( $data, $where ) {
            global $wpdb;
            $wpdb->update( $this->table_settings, $data, $where );  
        }
        
        public function createTables() {
            global $wpdb;
            
            $sql = 'CREATE TABLE IF NOT EXISTS `'.$this->table_settings.'` (
                      `id` int(11) NOT NULL AUTO_INCREMENT,
                      `post_type` varchar(100) NOT NULL,
                      `ui_slider_color` varchar(150) NULL,
                      `ui_slider_range_color` varchar(150) NULL,
                      `ui_slider_handle_color` varchar(150) NULL,
                      UNIQUE KEY id (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

            $wpdb->query($sql);

        }
        
        function dropTables() {
            global $wpdb;
            
            $sql = 'DROP TABLE IF EXISTS `'.$this->table_settings.'`';
            $wpdb->query($sql);
        }

        function truncateTable() {
            global $wpdb;
            
            $sql = 'TRUNCATE TABLE `'.$this->table_settings.'`';
            $wpdb->query($sql);
        }
        
        
        
    }
