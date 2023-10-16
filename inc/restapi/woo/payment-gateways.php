<?php 

add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'payment-gateways', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_payment_gateway',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );
    register_rest_route( 'dv/v1', 'payment-gateways/(?P<payment_type>[a-z0-9]+(?:-[a-z0-9]+)*)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_detail_payment_gateway',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );
} );


function wc_get_payment_gateway(WP_REST_Request $request) {

    global $woocommerce;
 

    // $error = new WP_Error();
   
    // if(!in_array($setting_key, $keys)){
    //     $error->add(400, __("key not valid.", 'wp-rest-setting'), array('status' => 400));

    //     return $error;
    // }
      
    $available_payment_methods = WC()->payment_gateways()->get_available_payment_gateways();
    $payments = $woocommerce->get("payment_gateways");

    foreach( $payments as $payment){
        unset($payment->_links);
        unset($payment->settings_url);

    }
    
	return new WP_REST_Response( $payments );
}


function wc_get_detail_payment_gateway(WP_REST_Request $request) {

    global $woocommerce;
 
    $payment_type = $request["payment_type"];
    $methods = array("bacs", "cheque", "cod");


    $error = new WP_Error();
   
    if(!in_array($payment_type, $methods)){
        $error->add("method_invalid", __("method_invalid.", 'wp-rest-setting'), array('status' => 400));
        return $error;
    }


    $payment = $woocommerce->get("payment_gateways/".$payment_type);

    unset($payment->settings_url);
    unset($payment->settings_url);
    
	return new WP_REST_Response( $payment);
}
