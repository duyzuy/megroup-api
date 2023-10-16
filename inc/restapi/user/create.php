<?php 
add_action( 'rest_api_init', 'create_customer_hook' );


function create_customer_hook() {
  register_rest_route(
    'dv/v1', 'customer/create',
    array(
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => 'dv_woo_create_customer',
      'permission_callback' => '__return_true'
    )
  );
}

function dv_woo_create_customer(WP_REST_Request $request){

   
    global $woocommerce;
    $error = new WP_Error();
    
    $first_name = $request['first_name'] ? $request['first_name'] : "nguyen";
    $last_name = $request['last_name'] ?  $request['last_name'] : "van";
    $email = $request['email'] ?  $request['email'] : "nguyenvana@gmail.com";



    $data = array(
        'email' =>    $email,
        "first_name" => $first_name,
        "last_name"  => $last_name,
    );

    $user_data = $woocommerce->post('customers', $data);
  
    
    // wp_mail( $data['user_email'], 'ACTIVATION SUBJECT', 'CONGRATS BLA BLA BLA. HERE IS YOUR ACTIVATION LINK: ' . $activation_link );

    return new WP_REST_Response($user_data);  
   
}

