<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('SGC_Default_Meta_Box') ) :

    class SGC_Default_Meta_Box {

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
            // Add meta boxes
            add_action('add_meta_boxes',  array($this, 'add_meta_box'));
            // Save meta boxes 
            add_action( 'save_post', array($this, 'save_meta_boxes'), 1, 2); 
            
            // Includes
            include_once 'sgc-additional-fields.php';
            include_once 'sgc-ip-address.php';

        }


        /**
         * Meta_box_content
         *
         * Output the HTML for the metabox.
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
         */	
        function add_meta_box(){

            add_meta_box(
                'sgc_meta_box_content',
                __( 'Additional fields' , 'Additional fields' ),
                array($this, 'meta_box_content'),
                'sgc_products',
                'normal',
                'high'
            );
        }


        /**
         * Add meta box
         *
         * Add meta box for custom fields
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
         */	
        function meta_box_content() {

            global $post;

            // Nonce field to validate form request came from current site
            wp_nonce_field( basename( __FILE__ ), 'sgc_products_fields' );
            
            $object_additional_fields = new SGC_Additional_Fields();

            echo $object_additional_fields->get_output_meta_box($post->ID);
        }


        /**
         * Save meta box
         *
         * Saving meta box content when cpt is saved
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param   int   post_id
         * @param   array post
         * @return	void
         */	
        function save_meta_boxes( $post_id, $post ) {
            
            // Return if the user doesn't have edit permissions.
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }

            // Verify this came from the our screen and with proper authorization,
            // because save_post can be triggered at other times.
            if ( ! isset( $_POST['price'] ) || ! wp_verify_nonce( $_POST['sgc_products_fields'], basename(__FILE__) ) ) {
                return $post_id;
            }

            // Now that we're authenticated, time to save the data.
            // This sanitizes the data from the field and saves it into an array $events_meta.
            $events_meta['price']      = esc_textarea( $_POST['price'] );
            $events_meta['vat_rate']   = esc_textarea( $_POST['vat_rate'] );           
            $events_meta['price_full'] = (float) $events_meta['price'] + ((float)$events_meta['price'] * (float) $events_meta['vat_rate']);

            // Cycle through the $events_meta array.
            // Note, in this example we just have one item, but this is helpful if you have multiple.
            foreach ( $events_meta as $key => $value ) :

                // Don't store custom data twice
                if ( 'revision' === $post->post_type ) {
                    return;
                }

                if ( get_post_meta( $post_id, $key, false ) ) {
                    // If the custom field already has a value, update it.
                    update_post_meta( $post_id, $key, $value );
                } else {
                    // If the custom field doesn't have a value, add it.
                    add_post_meta( $post_id, $key, $value);
                }

            endforeach;

            $object_ip_address = new SGC_Ip_Address();

            if ( ! get_post_meta( $post_id, 'ip_address', false ) ) {
                add_post_meta( $post_id, 'ip_address', $object_ip_address->get_ip_address());
            }
            if ( ! get_post_meta( $post_id, 'created_at', false ) ) {
                add_post_meta( $post_id, 'created_at', date('Y-m-d H:i'));
            }
        }
    }

    // Create new SGC_Default_Meta_Box
    new SGC_Default_Meta_Box();

endif; // class_exists check

?>