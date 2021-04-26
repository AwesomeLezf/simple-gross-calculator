<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('SGC_Register_Post_Type') ) :

    class SGC_Register_Post_Type {

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
            // Add CPT
            add_action('init', array( $this, 'register_post_type' ));           

            // Filters
            // Change title placeholder
            add_filter('enter_title_here', array($this, 'change_title_placeholder'), 20 , 2 );
        }

        /**
         * Register_post_types
         *
         * Registers the Product post types.
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
         */	
        function register_post_type() {

            $labels = array( 
                'name'               => __( 'Products' , 'Simple Gross Calculator' ),
                'singular_name'      => __( 'Product' , 'Simple Gross Calculator' ),
                'add_new'            => __( 'New Product' , 'Simple Gross Calculator' ),
                'add_new_item'       => __( 'Add New Product' , 'Simple Gross Calculator' ),
                'edit_item'          => __( 'Edit Product' , 'Simple Gross Calculator' ),
                'new_item'           => __( 'New Product' , 'Simple Gross Calculator' ),
                'view_item'          => __( 'View Product' , 'Simple Gross Calculator' ),
                'search_items'       => __( 'Search Product' , 'Simple Gross Calculator' ),
                'not_found'          => __( 'No Products Found' , 'Simple Gross Calculator' ),
                'not_found_in_trash' => __( 'No Products found in Trash' , 'Simple Gross Calculator' ),
            );    
            $args = array(
                'labels'             => $labels,
                'has_archive'        => true,
                'public'             => true,
                'draft'              => true,
                'hierarchical'       => false,
                'supports'           => array(
                    // 'custom-fields', 
                    // 'editor', 
                    // 'excerpt', 
                    // 'page-attributes'
                    // 'post-formats',
                    'title', 
                    // 'thumbnail',
                ),
                'rewrite'           => array( 'slug' => 'products' ),
                'menu_icon'         => 'dashicons-cart',
                'menu_position'     => 4,
                // 'show_in_rest'      => true
            );
            register_post_type(SGC_POST_TYPE, $args);
        }

        /**
         * Change title placeholder
         *
         * Is changing cpt title placeholder
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
        */
        function change_title_placeholder($title , $post){

            if( $post->post_type === SGC_POST_TYPE ){
                $my_title = __('Add product name', 'Add product name');
                return $my_title;
            }
            return $title;
        }
    }


    //Create new SGC_Register_Post_Type
    new SGC_Register_Post_Type();
    
endif; // class_exists check

?>