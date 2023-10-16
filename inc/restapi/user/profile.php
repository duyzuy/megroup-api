<?php 
add_action( 'rest_api_init', function () {
  register_rest_route(
    'dv/v1', 'customer/profile',
    array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'dv_get_profile',
      'permission_callback' => '__return_true'
    )
  );
  register_rest_route(
    'dv/v1', 'customer/profile/update',
    array(
      'methods'  => WP_REST_Server::EDITABLE,
      'callback' => 'dv_update_profile',
      'permission_callback' => '__return_true'
    )
  );
  register_rest_route(
    'dv/v1', 'customer/password',
    array(
      'methods'  => WP_REST_Server::EDITABLE,
      'callback' => 'dv_change_password',
      'permission_callback' => '__return_true'
    )
  );
});



function dv_get_profile(WP_REST_Request $request){

    $is_loged_in = is_user_logged_in();
    global $woocommerce;
    $error = new WP_Error();

    if(!$is_loged_in){
        $error->add("unauthorize", __("Unauthorized.", 'wp-rest-user'), array('status' => 401));
        return $error;
    }else{
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        $customer_data = $woocommerce->get('customers/' . $user_id);

        $is_verified = (int)get_user_meta($user_id, "_dv_is_verified", true);

        if($is_verified !== 1){
          $error->add("account_inactive", __("User is inactive.", 'wp-rest-user'), array('status' => 401));
          return $error;
        }

        if( $customer_data->role !== "customer" and $customer_data->role !== "administrator"){
          $error->add("user_no_permision", __("No permission.", 'wp-rest-user'), array('status' => 503));
          return $error;
        }
      
        unset($customer_data->_links);
        unset($customer_data->meta_data);
        return new WP_REST_Response($customer_data);  
    }
     
   
}

function dv_update_profile(WP_REST_Request $request){

  $is_loged_in = is_user_logged_in();
  global $woocommerce;
  $error = new WP_Error();

  if(!$is_loged_in){
      $error->add("unauthorize", __("Unauthorized.", 'wp-rest-user'), array('status' => 401));
      return $error;
  }else{
      $current_user = wp_get_current_user();
      $user_id = $current_user->ID;
      $data_update = array();
      $billing = $request['billing'];
      $shipping = $request['shipping'];
      $first_name = $request['first_name'];
      $last_name = $request['last_name'];

      if(!isset($billing) and !isset($shipping) and !isset($first_name) and !isset($last_name)){
        $error->add("invalid_payload", __("invalid_payload.", 'wp-rest-user'), array('status' => 400));
        return $error;
      }
      if(isset($first_name)){
        $data_update['first_name'] = sanitize_text_field($first_name);
      }
      if(isset($last_name)){
        $data_update['last_name'] = sanitize_text_field($last_name);
      }

      if(isset($billing)){

        if(isset($billing['first_name'])){
          $data_update['billing']['first_name'] = sanitize_text_field($billing['first_name']);
        }


        if(isset($billing['last_name'])){
          $data_update['billing']['last_name'] = sanitize_text_field( $billing['last_name'] );
        }

        if(isset($billing['company'])){
          $data_update['billing']['company'] = sanitize_text_field($billing['company']);
        }
        
        if(isset($billing['address_1'])){
          $data_update['billing']['address_1'] = sanitize_text_field($billing['address_1']);
        }
        if(isset($billing['address_2'])){
          $data_update['billing']['address_2'] = sanitize_text_field($billing['address_2']);
        }
        if(isset($billing['city'])){
          $data_update['billing']['city'] = sanitize_text_field($billing['city']);
        }
        if(isset($billing['state'])){
          $data_update['billing']['state'] = sanitize_text_field($billing['state']);
        }

        if(isset($billing['postcode'])){
          $data_update['billing']['postcode'] = sanitize_text_field($billing['postcode']);
        }
        if(isset($billing['country'])){
          $data_update['billing']['country'] = sanitize_text_field($billing['country']);
        }
        if(isset($billing['email'])){

          if (!is_email($billing['email'], false)) {
            $error->add("billing_email_not_valid", __("Email is not valid", 'wp-rest-profile'), array('status' => 400));
            return $error;
          }

          $data_update['billing']['email'] = sanitize_text_field($billing['email']);
        }
        if(isset($billing['phone'])){

          if (!is_phone_number($billing['phone'])) {
            $error->add("billing_phone_not_valid", __("Phone is not valid", 'wp-rest-profile'), array('status' => 400));
            return $error;
          }

          $data_update['billing']['phone'] = normalize_telephone_number($billing['phone']);
        }
      }

      if(isset($shipping)){

        if(isset($shipping['first_name'])){
          $data_update['shipping']['first_name'] = sanitize_text_field($shipping['first_name']);
        }


        if(isset($shipping['last_name'])){
          $data_update['shipping']['last_name'] = sanitize_text_field( $shipping['last_name'] );
        }

        if(isset($shipping['company'])){
          $data_update['shipping']['company'] = sanitize_text_field($shipping['company']);
        }
        
        if(isset($shipping['address_1'])){
          $data_update['shipping']['address_1'] = sanitize_text_field($shipping['address_1']);
        }
        if(isset($shipping['address_2'])){
          $data_update['shipping']['address_2'] = sanitize_text_field($shipping['address_2']);
        }
        if(isset($shipping['city'])){
          $data_update['shipping']['city'] = sanitize_text_field($shipping['city']);
        }
        if(isset($shipping['state'])){
          $data_update['shipping']['state'] = sanitize_text_field($shipping['state']);
        }

        if(isset($shipping['postcode'])){
          $data_update['shipping']['postcode'] = sanitize_text_field($shipping['postcode']);
        }
        if(isset($shipping['country'])){
          $data_update['shipping']['country'] = sanitize_text_field($shipping['country']);
        }
        if(isset($shipping['phone'])){

          if (!is_phone_number($shipping['phone'])) {
            $error->add("shipping_phone_not_valid", __("Phone is not valid", 'wp-rest-profile'), array('status' => 400));
            return $error;
          }

          $data_update['shipping']['phone'] = normalize_telephone_number($shipping['phone']);
        }
      
      }
     

      $is_verified = (int)get_user_meta($user_id, "_dv_is_verified", true);

      if($is_verified !== 1){
        $error->add("account_inactive", __("User is inactive.", 'wp-rest-user'), array('status' => 401));
        return $error;
      }

      if( $current_user->roles[0] !== "customer" and $current_user->roles[0] !== "administrator"){
        $error->add("user_no_permision", __("No permission.", 'wp-rest-user'), array('status' => 503));
        return $error;
      }

        $user_info = $woocommerce->put('customers/' . $user_id, $data_update);
      
      unset($user_info->_links);
      unset($user_info->meta_data);

      return new WP_REST_Response($user_info);  
  }
 
}


function dv_change_password(WP_REST_Request $request){

  $is_loged_in = is_user_logged_in();
  global $woocommerce;
  $error = new WP_Error();

  if(!$is_loged_in){
      $error->add("unauthorize", __("Unauthorized.", 'wp-rest-user'), array('status' => 401));
      return $error;
  }

  $password = $request['password'];

  if(!isset($password)){
    $error->add("password_empty", __("password empty", 'wp-rest-profile'), array('status' => 400));
    return $error;
  }

  if(strlen($password) < 8){
    $error->add("password_length_invalid", __("password length minimum 8 charactors", 'wp-rest-profile'), array('status' => 400));
    return $error;
  }
  $current_user = wp_get_current_user();
  $user_id = $current_user->ID;

  $password = sanitize_text_field( $password );

  wp_set_password( $password, $user_id );
  
  $user_info = array(
    "id" => $current_user->ID,
    "user_email" => $current_user->data->user_email,

  );

  return new WP_REST_Response(array('user_info' => $user_info, "code" => "update_password_success"));  
}



