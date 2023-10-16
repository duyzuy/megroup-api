<?php 

add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'shipping/zones', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_shipping_zones',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );

    register_rest_route( 'dv/v1', 'shipping/zones/(?P<zone_id>[0-9]*)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_shipping_zones_detail',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );

      register_rest_route( 'dv/v1', 'shipping/zones/(?P<zone_id>[0-9]*)/locations', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_shipping_zones_location',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );

      register_rest_route( 'dv/v1', 'shipping/zones/(?P<zone_id>[0-9]*)/methods', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_shipping_zones_methods',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );
} );


function wc_get_shipping_zones(WP_REST_Request $request) {

    global $woocommerce;
 

    $zones = $woocommerce->get("shipping/zones");

    foreach( $zones as $zone){
        unset($zone->_links);
    }
    
	return new WP_REST_Response( $zones);
}

// function wc_get_shipping_zones_detail(WP_REST_Request $request) {

//     global $woocommerce;
 
//     $zones = $woocommerce->get("shipping/zones");
//     $zone_ids = array();
//     $zone_id = $request['zone_id'];

//     foreach($zones as $zone){
//             $zone_ids[] = $zone->id;
//     }

//     $error = new WP_Error();
   
//     if(!in_array($zone_id, $zone_ids)){
//         $error->add(400, __("key not valid.", 'wp-rest-setting'), array('status' => 400));
//         return $error;
//     }
      
   

//     $zone = $woocommerce->get("shipping/zones/". $zone_id );

//    unset($zone->_links);

// 	return new WP_REST_Response( $zone);
// }


function wc_get_shipping_zones_location(WP_REST_Request $request) {

    global $woocommerce;

    $zones = $woocommerce->get("shipping/zones");

    $zone_ids = array();
    $zone_id = $request['zone_id'];

    foreach($zones as $zone){
            $zone_ids[] = $zone->id;
    }
  
    $error = new WP_Error();

    if(!in_array($zone_id, $zone_ids)){
        $error->add("zone_id_invalid", __("key not valid.", 'wp-rest-setting'), array('status' => 400));
        return $error;
    }
   
    $zone_locations = $woocommerce->get("shipping/zones/". $zone_id . "/locations" );

    if(!$zone_locations){
        $error->add("zone_id_invalid", __("key not valid.", 'wp-rest-setting'), array('status' => 400));
        return $error;
    }
    foreach( $zone_locations as $zone_location){
        unset($zone_location->_links);
    }
    
    
	return new WP_REST_Response( $zone_locations);
}


function wc_get_shipping_zones_methods(WP_REST_Request $request) {

    global $woocommerce;
    

    $zones = $woocommerce->get("shipping/zones");

    $zone_ids = array();
    $zone_id = $request['zone_id'];

    foreach($zones as $zone){
            $zone_ids[] = $zone->id;
    }


    $error = new WP_Error();

    if(!in_array($zone_id, $zone_ids)){
        $error->add("zone_id_invalid", __("key not valid.", 'wp-rest-setting'), array('status' => 400));
        return $error;
    }

    $zone_methods = $woocommerce->get("shipping/zones/". $zone_id . "/methods" );

    if(!($zone_methods)){
        $error->add("zone_id_invalid", __("key not valid.", 'wp-rest-setting'), array('status' => 400));
        return $error;
    }
    
    foreach( $zone_methods as $zone_method){
        unset($zone_method->_links);
    }
    

    
	return new WP_REST_Response( $zone_methods);

}