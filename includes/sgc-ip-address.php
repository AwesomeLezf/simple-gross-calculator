<?php 

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('SGC_Ip_Address') ) :

    class SGC_Ip_Address {

        /*
         *  __construct
         *
         *  Do nothing
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param	void
         * @return	void
        */
        public function __construct() { 
            // Do nothing
        }      

        /**
         * Get client ip address
         *
         * Get client ip address
         *
         * @date	25/04/2021
         * @since	1.0.0
         *
         * @param   void
         * @return	string
         */	
        function get_ip_address(){

            //whether ip is from the share internet  
            if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
                $ip = $_SERVER['HTTP_CLIENT_IP'];  
            }  
            //whether ip is from the proxy  
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
            }  
            //whether ip is from the remote address  
            else{  
                $ip = $_SERVER['REMOTE_ADDR'];  
            }  
            return $ip;  
        }
    }

endif; // class_exists check

?>