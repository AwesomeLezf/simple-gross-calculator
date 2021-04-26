<?php 
/**
 * Plugin Name: Simple Gross Calculator
 * Plugin URI: https://github.com/AwesomeLezf/simple-gross-calculator.git
 * Description: Plugin to calculate gross price, registers a post type and save data to it.
 * Version: 1.0
 * Author: Marcin Kowalski
 * Author URI: https://github.com/AwesomeLezf/
 * License: GPLv3 or later
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('Simple_Gross_Calculator') ) :
    
    //Defining constants
    define( 'SGC_POST_TYPE', 'sgc_products' );
    define( 'SGC_SHORTCODE', 'sgc-form' );


    class Simple_Gross_Calculator {

        /*
         *  __construct
         *
         *  Initialize filters, action, variables and includes
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
         */
        public function __construct() {   

            // Actions            
            // Add scripts to admin
            add_action('admin_enqueue_scripts', array($this, 'scripts_admin')); 
            // Add scripts to template
            add_action('wp_enqueue_scripts', array($this, 'scripts_template')); 


            // Includes with created objects
            include_once 'includes\sgc-register-post-type.php';
            include_once 'includes\sgc-default-meta-box.php';
            include_once 'includes\sgc-shortcode.php';
        }


        /**
         * Scripts admin
         *
         * Enqueue scripts for admin
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
         */
        public function scripts_admin() {

            //Check if is plugin CPT page
            $screen = get_current_screen();
            if ($screen->post_type === SGC_POST_TYPE) {
                wp_enqueue_style('sgc-admin', plugin_dir_url( __FILE__ ) . 'admin/dist/main.bundle.css', false, null);
                wp_enqueue_script('sgc-admin', plugin_dir_url( __FILE__ ) .'admin/dist/bundle.js', ['jquery'], null, true);
            }          
        }


        /**
         * Scripts template
         *
         * Enqueue scripts for template
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
         */
        public function scripts_template() {

            global $post;

            if(!is_admin() &&  has_shortcode( $post->post_content, SGC_SHORTCODE)){
                wp_enqueue_style('sgc-public', plugin_dir_url( __FILE__ ) . 'public/dist/main.bundle.css', false, null);
                // wp_enqueue_script('sgc-public', plugin_dir_url( __FILE__ ) .'public/dist/bundle.js', ['jquery'], null, true);
            }          
        }
    }

    //Create new Simple_Gross_Calculator
    new Simple_Gross_Calculator();

endif; // class_exists check