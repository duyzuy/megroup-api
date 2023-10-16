<?php 

add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'products/shipping-classes', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_shipping_Classes',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );
} );


function wc_get_shipping_Classes(WP_REST_Request $request) {

    global $woocommerce;
 


    $shippings = $woocommerce->get("products/shipping_classes");

    foreach( $shippings as $shipping){
        unset($shipping->_links);

    }
  
    
	return new WP_REST_Response( $shippings);
}