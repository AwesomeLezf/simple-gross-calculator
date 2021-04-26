<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('SGC_Additional_Fields') ) :

    class SGC_Additional_Fields {
        
        public $additionals_fields = array();

        /*
         *  __construct
         *
         * Initialize variables
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
        */
        public function __construct() { 
            
            // variables
            $this->additionals_fields = $this->get_fields();
        }


        /**
         * Get fields
         *
         * Return array of current additional fields
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	array
         */	
        private function get_fields(){
            $additionals_fields = array(
                'base' => array(
                    array(
                        'type'       => 'input-number',
                        'name'       => 'price',
                        'label'      =>  __( 'Product price (in PLN)' , 'Product price (in PLN)' ),
                        'ad_classes' => 'js-sgc-price',
                        'attr'       => 'required',
                    ),                
                    array(
                        'type'       => 'select',
                        'name'       => 'vat_rate',
                        'label'      => __( 'Vat rate' , 'Vat rate' ),
                        'ad_classes' => 'js-sgc-vat',
                        'attr'       => 'required',
                        'options'    => array(
                            array(
                                'value' => '0.23',
                                'label' => '23%',
                            ),
                            array(
                                'value' => '0.22',
                                'label' => '22%',
                            ),
                            array(
                                'value' => '0.08',
                                'label' => '8%',
                            ),
                            array(
                                'value' => '0.07',
                                'label' => '7%',
                            ),
                            array(
                                'value' => '0.05',
                                'label' => '5%',
                            ),
                            array(
                                'value' => '0.03',
                                'label' => '3%',
                            ),
                            array(
                                'value' => '0',
                                'label' => '0%',
                            ),
                            array(
                                'value' => '0',
                                'label' => __( 'zw.' , 'zw.' ),
                            ),
                            array(
                                'value' => '0',
                                'label' => __( 'np.' , 'np.' ),
                            ),
                            array(
                                'value' => '0',
                                'label' => __( 'o.o.' , 'o.o.' ),
                            ),
                        ),
                    ),
                ),
                'automatically-filled-fields' => array(
                    array(
                        'type'       => 'input-text',
                        'name'       => 'price_full',
                        'label'      =>  __( 'Product price + VAT (in PLN)' , 'Product price + VAT (in PLN)' ),
                        'ad_classes' => 'pointer-events-none disabled-input js-sgc-price-full',
                        'attr'       => '',
                    ),
                    array(
                        'type'       => 'input-text',
                        'name'       => 'ip_address',
                        'label'      =>  __( 'Ip address' , 'Ip address' ),
                        'ad_classes' => 'pointer-events-none disabled-input',
                        'attr'       => '',
                    ),
                    array(
                        'type'       => 'input-text',
                        'name'       => 'created_at',
                        'label'      =>  __( 'Created date' , 'Created date' ),
                        'ad_classes' => 'pointer-events-none disabled-input',
                        'attr'       => '',
                    ),
                ),                
            );
            return $additionals_fields;
        }

        
        /**
         * Get output meta box
         *
         * Function return content displayed in meta box fields
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	int     post->ID
         * @return	string
         */
        public function get_output_meta_box($post_id){
            
            $output_fields = '
                <div class="px-4 my-4">
            ';

            foreach($this->additionals_fields['base'] as $field){
                
                switch($field['type']){
                    
                    case 'input-number':
                        $output_fields .= $this->get_input_number($post_id, $field);
                        break;
                    
                    case 'select':
                        $output_fields .= $this->get_select($post_id, $field);
                        break;
                }                
            }

            $output_fields .= '
                <div class="mt-4 p-4 bg-gray-700 rounded-md">
                    <span class="block pb-2 mb-2 text-lg text-white font-medium border-b-1 border-solid border-white">' .  __( 'Automatically filled fields' , 'Automatically filled fields' ) . '</span>
            ';
            
            foreach($this->additionals_fields['automatically-filled-fields'] as $field){
                
                switch($field['type']){

                    case 'input-text':
                        $output_fields .= $this->get_input_text($post_id, $field);
                        break;                    
                }                
            }            
            $output_fields .= '
                </div>
            ';

            $output_fields .= '
                </div>
            ';

            return $output_fields;
        }


        /**
         * Get input number
         *
         * Function return input number view
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	int    post_id
         * @param	array  field
         * @return	string
         */	
        private function get_input_number($post_id, $field){

            // Get the data if it's already been entered
            $get_field = get_post_meta( $post_id, $field['name'], true );

            $input = '
                <div class="flex items-center py-2">
                    <label for="' . $field['name'] . '" class="block w-4/12 pr-12 text-sm font-medium text-gray-800">' . $field['label'] . '</label>
                    <input type="number" name="' . $field['name'] . '" id="' . $field['name'] . '" value="' . esc_textarea($get_field) . '" min="0" ' .  $field['attr'] . '
                        class="mt-1 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md ' . $field['ad_classes'] . '">
                </div>
            ';
            return $input;
        }


        /**
         * Get input text
         *
         * Function return input text view
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	int    post_id
         * @param	array  field
         * @return	string
         */	
        private function get_input_text($post_id, $field){

            // Get the data if it's already been entered
            $get_field = get_post_meta( $post_id, $field['name'], true );

            $input = '
                <div class="flex items-center py-2">
                    <label ' . ($field['ad_classes'] != '' ? 'for="' . $field['name'] . '" ' : '') . 'class="block w-4/12 pr-12 text-sm font-medium text-white">' . $field['label'] . '</label>
                    <input type="text" name="' . $field['name'] . '" id="' . $field['name'] . '" value="' . esc_textarea($get_field) . '" ' . $field['attr'] . '
                        class="mt-1 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm ' . $field['ad_classes'] . '">
                </div>
            ';
            return $input;
        }

        /**
         * Get input public text
         *
         * Function return input public text view
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	int    post_id
         * @param	array  field
         * @return	string
         */	
        private function get_input_public_text($post_id, $field){

            // Get the data if it's already been entered
            $get_field = get_post_meta( $post_id, $field['name'], true );

            $input = '
                <div class="flex items-center py-2">
                    <label for="' . $field['name'] . '" class="block w-4/12 pr-12 text-sm text-gray-800 font-medium">' . $field['label'] . '</label>
                    <input type="text" name="' . $field['name'] . '" id="' . $field['name'] . '" value="' . esc_textarea($get_field) . '" ' . $field['attr'] . '
                        class="mt-1 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm">
                </div>
            ';
            return $input;
        }


        /**
         * Get select
         *
         * Function return select view
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	int    post_id
         * @param	array  field
         * @return	string
         */	
        private function get_select($post_id, $field){

            // Get the data if it's already been entered
            $get_field = get_post_meta( $post_id, $field['name'], true );

            $select = '
                <div class="flex items-center py-2">
                    <label for="' . $field['name'] . '" class="block w-4/12 pr-12 text-sm font-medium text-gray-800">' . $field['label'] . '</label>
                    <select id="vat-rate" name="vat_rate" ' . $field['attr'] . '
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ' . $field['ad_classes'] . '">
            ';         
            foreach($field['options'] as $option){
                $select .= '
                            <option value="' . $option['value'] . '" ' . ($vat_rate === $option['value'] ? 'selected' : '') . '>' . $option['label'] . '</option>
                ';
            }
            $select .= '
                    </select>
                </div>
            ';
            
            return $select;
        }

        /**
         * Get shortcode fields
         *
         * Function return content displayed in shortcode form.
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	string
         */
        public function get_shortcode_fields(){
            
            $output_fields = '
                <div class="px-4 my-4">
            ';

            $product_name_field =  array(
                'name'       => 'title',
                'label'      =>  __( 'Add product name' , 'Add product name' ),
                'ad_classes' => '',
                'attr'       => 'required',
            );

            $output_fields .= $this->get_input_public_text($post_id, $product_name_field);

            foreach($this->additionals_fields['base'] as $field){

                switch($field['type']){
                    
                    case 'input-number':
                        $output_fields .= $this->get_input_number($post_id, $field);
                        break;
                    
                    case 'select':
                        $output_fields .= $this->get_select($post_id, $field);
                        break;
                }                
            }

            $output_fields .= '
                </div>
            ';

            return $output_fields;
        }
    }    
    
endif; // class_exists check

?>