<?php 



add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'woo-data', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_all_woo_data',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );
} );


function wc_get_all_woo_data(WP_REST_Request $request) {

    global $woocommerce;
    
    $data_type =  $request['data_type'];
    $country_code =  $request['country_code'];

    $types  = ["countries",
    "currencies",
    "continents",
   ];
    


    $error = new WP_Error();
   
    if(!in_array($data_type, $types)){
        $error->add(400, __("data not valid.", 'wp-rest-data-type'), array('status' => 400));

        return $error;
    }
    $endpoint = $data_type;
    if($data_type === "countries"){
       
        if($country_code !== ""){
            $endpoint =  $endpoint . "/" . $country_code;
        }
    }

    $woo_datas = $woocommerce->get("data/$endpoint");

    foreach( $woo_datas as $data){
        unset($data->_links);

    }
  
    
	return new WP_REST_Response( $woo_datas);
}