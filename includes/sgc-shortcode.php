<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('SGC_Shortcode') ) :

    class SGC_Shortcode {


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
            // Add notice about shortcode
            add_action('admin_notices', array($this, 'admin_shortcode_notice'));
            
            // Register shortcode
            add_shortcode( SGC_SHORTCODE, array($this, 'create_shortcode'));

            // Includes
            include_once 'sgc-additional-fields.php';
            include_once 'sgc-ip-address.php';
        }        

        /**
         * Admin schortcode notice
         *
         * Display shortcode notice
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
        */
        public function admin_shortcode_notice() {

            $screen = get_current_screen();
            if (($screen->base === 'edit') && ($screen->post_type === SGC_POST_TYPE)) {
                echo '
                    <div class="notice notice-success is-dismissible">
                        <p>' . __( 'Shortcode to display product form: [' . SGC_SHORTCODE . ']', 'Shortcode to display product form: [' . SGC_SHORTCODE . ']' ) . '</p>
                    </div>
                ';
            }
        }

        /**
         * Create Shortcode
         *
         * Create shortcode
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	string
        */
        public function create_shortcode() {

            $object_additional_fields = new SGC_Additional_Fields();
            
            $shortcode = '
                <div class="max-w-240 p-12 mx-auto border-2 border-solid border-gray-900 rounded-md">                    
            ';
            
            if(!isset($_POST['price'])){

                $form = '
                    <span class="block mb-8 text-2xl text-center font-bold">' . __('Add product to SGC CPT', 'Add product to SGC CP') . '</span>        
                    <form action="" method="post">
                ';

                $form .= $object_additional_fields->get_shortcode_fields();

                $form .= '
                        <div class="flex justify-center mt-4">
                            <button class="focus:outline-none focus:ring-indigo-500 focus:text-gray-900 focus:border-indigo-500 focus:bg-white hover:bg-white px-8 py-4 bg-gray-900 border-2 border-solid border-gray-900 rounded-md text-white transition-all duration-300" type="submit">' . __('Send', 'Send') . '</button>
                        </div>
                    </form>
                ';
                $shortcode .= $form;                
            }
            else{

                $tax_amount = (float)$_POST['price'] * (float) $_POST['vat_rate'];
                $gross_amount = (float) $_POST['price'] + $tax_amount;

                $s_tax_amount = (string)number_format($tax_amount, 2, '.', '');
                $s_gross_amount = (string)number_format($gross_amount, 2, '.', '');
                
                $this->save_form($_POST, $s_gross_amount);   
                $shortcode .= '
                    <span class="block mb-4 text-2xl text-center font-bold">' . __('The new product has been successfully added', 'The new product has been successfully added') . '</span> 
                    <p class="text-center">
                        ' . sprintf(__('Product price <b>%s</b>, is: <b>%s gross PLN</b>, the tax amount is <b>%s PLN</b>.', 'SGC message'), $_POST['title'], $s_gross_amount, $s_tax_amount) . '
                    </p>
                ';
                unset($_POST);
            }
            $shortcode .= '</div>';      

            return $shortcode;
        }

        /**
         * Save form
         *
         * Save form data to CPT
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
        */
        public function save_form($data, $full_price){
            global $wpdb;
		    $table_post     = $wpdb->prefix.'posts';
		    $table_postmeta = $wpdb->prefix.'postmeta';

            $title    = filter_var($data['title'], FILTER_SANITIZE_STRING);
            $price    = filter_var($data['price'], FILTER_SANITIZE_STRING);
            $vat_rate = filter_var($data['vat_rate'], FILTER_SANITIZE_STRING);
            $slug  = $this->prevent_slug_duplicate(sanitize_title($title));


            $object_ip_address = new SGC_Ip_Address();
            $postmeta_fields = array(
                array(
                    'meta_key'   => 'price', 
                    'meta_value' => $price, 
                ),
                array(
                    'meta_key'   => 'vat_rate', 
                    'meta_value' => $vat_rate, 
                ),
                array(
                    'meta_key'   => 'price_full', 
                    'meta_value' => $full_price, 
                ),
                array(
                    'meta_key'   => 'ip_address', 
                    'meta_value' => $object_ip_address->get_ip_address(), 
                ),
                array(
                    'meta_key'   => 'created_at', 
                    'meta_value' => date('Y-m-d H:i'), 
                ),
            ); 
            
            $wpdb->insert($table_post, array(
                'post_author'       => 0,
				'post_date'         => date('Y-m-d H:i:s'),
                'post_title'        => $title,
                'post_status'       => 'publish',
                'comment_status'    => 'closed',
                'ping_status'       => 'closed',
                'post_name'         => $slug,
                'post_modified'     => date('Y-m-d H:i:s'),
                'post_type'         => 'sgc_products',
			));
        
            $inserted_post_id = $wpdb->insert_id;


            foreach($postmeta_fields as $field){
                $wpdb->insert($table_postmeta, array(
                    'post_id'    => $inserted_post_id,
                    'meta_key'   => $field['meta_key'], 
                    'meta_value' => $field['meta_value'], 
                ));   
            }
        }


        /**
         * Prevent slug duplicate
         *
         * Function prevent slug duplicate
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	string
        */
        public function prevent_slug_duplicate($slug){

            global $wpdb;
		    $table_post = $wpdb->prefix.'posts';
            $post_type = SGC_POST_TYPE;

            $sql = "SELECT ID FROM $table_post WHERE post_type = '$post_type' AND post_name LIKE '$slug%'";
		    $sql_result = $wpdb->get_results($sql);
            $sql_row_count = sizeof($sql_result);

            if($sql_row_count > 0){
		        return $slug."-$sql_row_count";
            }
            else{
                return $slug;
            }
        }
    }

    // Create new SGC_Shortcode
    new SGC_Shortcode();
    
endif; // class_exists check