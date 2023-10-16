<?php 



add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'setting', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_all_setting',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );
} );


function wc_get_all_setting(WP_REST_Request $request) {

    global $woocommerce;
    $setting_key = (string) $request['setting_key'];

    $keys  = ["general",
    "products",
    "tax",
    "shipping",
    "checkout",
    "account",
    "email",
    "email_new_order",
    "email_cancelled_order",
    "email_failed_order",
    "email_customer_on_hold_order",
    "email_customer_processing_order",
    "email_customer_completed_order",
    "email_customer_refunded_order",
    "email_customer_invoice",
    "email_customer_note",
    "email_customer_reset_password",
    "email_customer_new_account"];
    

    $error = new WP_Error();
   
    if(!in_array($setting_key, $keys)){
        $error->add(400, __("key not valid.", 'wp-rest-setting'), array('status' => 400));

        return $error;
    }
      

    $settings = $woocommerce->get("settings/$setting_key");

    foreach( $settings as $setting){
        unset($setting->_links);

    }
  
    
	return new WP_REST_Response( $settings);
}