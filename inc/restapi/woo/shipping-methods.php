<?php 

add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'shipping-methods', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_shipping_methods',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );
} );


function wc_get_shipping_methods(WP_REST_Request $request) {

    global $woocommerce;
 

    // $error = new WP_Error();
   
    // if(!in_array($setting_key, $keys)){
    //     $error->add(400, __("key not valid.", 'wp-rest-setting'), array('status' => 400));

    //     return $error;
    // }
      

    $shippings = $woocommerce->get("shipping_methods");

    foreach( $shippings as $shipping){
        unset($shipping->_links);

    }
    
	return new WP_REST_Response( $shippings);
}