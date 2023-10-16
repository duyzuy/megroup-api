<?php 


add_action( 'rest_api_init', function () {
  register_rest_route('dv/v1', '/auth/login',  array(
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => 'dv_auth_login_cb',
      'permission_callback' => '__return_true'
    ));

  register_rest_route('dv/v1', '/auth/register', array(
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => 'dv_auth_registration_cb',
      'permission_callback' => '__return_true'
    ));


  register_rest_route('dv/v1', '/auth/verify', array(
    'methods'  => WP_REST_Server::EDITABLE,
    'callback' => 'dv_auth_verify_user_cb',
    'permission_callback' => '__return_true'
  ));

  register_rest_route('dv/v1', '/auth/forgot-password', array(
    'methods'  => WP_REST_Server::CREATABLE,
    'callback' => 'dv_auth_forgot_password',
    'permission_callback' => '__return_true'
  ));


  // register_rest_route('dv/v1', '/auth/testmail', array(
  //   'methods'  => WP_REST_Server::CREATABLE,
  //   'callback' => 'dv_auth_test_mail',
  //   'permission_callback' => '__return_true'
  // ));

});



function dv_auth_login_cb(WP_REST_Request $request){
  $parameters = $request->get_json_params();

  $username = sanitize_text_field($parameters['username']);
  $password = sanitize_text_field($parameters['password']);


  $error = new WP_Error();

  if (empty($username)) {
    $error->add("username_is_empty", __("username field is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  if (empty($password)) {
    $error->add("password_is_empty", __("password field is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  if (strlen($password) < 8) {
    $error->add("password_length_invalid", __("password minimum 8 charactor.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }



  $data_array =  array(
    "username" => $username,
    "password" => $password,
  );

  $site_url = get_site_url();
  $endpoint = $site_url . '/wp-json/jwt-auth/v1/token';

  $make_call = callAPI('POST', $endpoint , json_encode($data_array));
  $response = json_decode($make_call, true);
  $error_code = $response['data']['status'];

  if (isset($error_code)) {
    $code = str_replace("[jwt_auth]", "", $response['code']);
    $error->add(trim($code), $response['message'], array('status' => $error_code));
    return $error;
  }
  $is_verified = $response['is_verified'] ? (int)$response['is_verified'] : 0;

  if($is_verified === 0 ){
  
    $error->add("user_inactive", "Tài khoản chưa kích hoạt, vui lòng kiểm tra email để kích hoạt tài khoản", array('status' => 401));
    return $error;
  }

  if($response['user_role'] !== "customer" and $response['user_role'] !== "administrator" ){
    $error->add("user_permission_authorize", "user_permission_authorize", array('status' => 401));
    return $error;
  }


  return new WP_REST_Response($response);  

}


function dv_auth_registration_cb(WP_REST_Request $request){

  $parameters = $request->get_json_params();

  $first_name = sanitize_text_field($parameters['first_name'] ? $parameters['first_name'] : "");
  $last_name = sanitize_text_field($parameters['last_name'] ? $parameters['last_name'] : "");
  $email = sanitize_text_field($parameters['email'] ? $parameters['email'] : "");
  $password = sanitize_text_field($parameters['password'] ? $parameters['password'] : "");
 
  $error = new WP_Error();

  if (empty($first_name)) {
    $error->add("first_name_empty", __("first_name field is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }
  if (strlen($first_name) < 2) {
    $error->add("first_name_length", __("first_name length is minimun 2 charactor.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  if (empty($last_name)) {
    $error->add("last_name_empty", __("last_name field  is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  if (strlen($last_name) < 2) {
    $error->add("last_name_length", __("last_name field is minimun 3 charactor.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  if (empty($email)) {
    $error->add("email_empty", __("email field  is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }
  
  if (!is_email($email, false)) {
    $error->add("email_not_valid", __("Email is not valid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  if (email_exists($email)) {
    $error->add("email_exists", __("Email exists", 'wp-rest-user'), array('status' => 400));
    return $error;
  }
 

  if (empty($password)) {
    $error->add("password_empty", __("password field is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }
  if (strlen($password) < 8) {
    $error->add("password_length_invalid", __("password length minimum 8 charactor.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }


  
 
  $username = abs( crc32( uniqid() ) );

  if (!username_exists($username)) {

    $user_id = wp_create_user($username, $password, $email);
    $user = get_user_by('id', $user_id);
    $user->set_role('subscriber');

    //wp_insert_user
    update_user_meta( $user_id, "first_name",  $first_name );
    update_user_meta( $user_id, "last_name",  $last_name );


    //create active_key
    $active_key = bin2hex(random_bytes(64));
    // $str = wp_generate_uuid4();
    // $active_key = wp_hash( $str );
 
    add_user_meta( $user_id, '_dv_active_key', $active_key );
    add_user_meta( $user_id, '_dv_is_verified', 0 );


    /**
     * Send active key to email
     */
    $to = $email;
    $mail_template = dv_template_email_user_activation("Dylan Beauty", $email, $active_key, $username);

    wp_mail( $to, $mail_template['subject'],  $mail_template['message'], $mail_template['headers'] );

    $user_info = array(
      "id" => $user->ID,
      "user_role" => $user->roles[0],
      "user_email" => $user->data->user_email,
      "is_verified" => 0
    );
     
    return new WP_REST_Response( $user_info , 201 );

  } else {
    $error->add("username_invalid", __("username generate not valid.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }


}


function dv_auth_verify_user_cb(WP_REST_Request $request){


  $user_email = $request['user_email'];
  $active_key = $request['active_key'];
  $account_id = $request['account_id'];
  
 
  $error = new WP_Error();

  if (empty($user_email) || empty($account_id)) {
    $error->add("user_invalid", __("user_invalid.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }


  if (!isset($active_key)) {
    $error->add("active_key_invalid", __("active_key_invalid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  $user = get_user_by('email', $user_email);

  if(!$user){
    $error->add("user_invalid", __("user_invalid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  $user_role =  $user->roles[0];

  if(!isset($user_role) or $user_role !== 'subscriber'){
    $error->add("user_invalid", __("user_invalid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  $user_id = $user->ID;

  $meta = get_user_meta($user_id);

  // //compare key
  $curr_active_key = get_user_meta($user_id, "_dv_active_key", true);

  if(strcmp($curr_active_key, $active_key) !== 0){
    $error->add("active_key_invalid", __("active_key_invalid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }


  update_user_meta( $user_id, "_dv_active_key",  null );
  update_user_meta( $user_id, "_dv_is_verified",  1 );
  $user->set_role('customer');
  
  
  $updateUser = get_user_by('id', $user_id);
  $is_verify = get_user_meta($user_id, "_dv_is_verified", true);
  $user_email = $updateUser->data->user_email;

  $user_info = array(
    "id" => $updateUser->ID,
    "user_login" => $updateUser->data->user_login,
    "user_email" => $user_email,
    "is_verified" => (int)$is_verify,
    "role" => $updateUser->roles[0],
  );

  /**
   * Send active key to email
   */

  $to = $user_email;
  $mail_template = dv_template_email_user_active_success("Dylan Beauty", $updateUser);

  wp_mail( $to, $mail_template['subject'],  $mail_template['message'], $mail_template['headers'] );

  
 return new WP_REST_Response(array("user_info" => $user_info, "code" => "verify_success"), 202);


}


  
function dv_auth_forgot_password(WP_REST_Request $request){


  $email = $request['email'];


  $error = new WP_Error();

  
  if(empty($email)){
    $error->add("email_is_empty", __("email_is_empty.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  if(empty($email)){
    $error->add("email_is_empty", __("email_is_empty.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

    
  if (!is_email($email, false)) {
    $error->add("email_not_valid", __("Email is not valid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }


  $user = get_user_by_email($email);

  if(!$user){
    $error->add("user_not_exists", __("user_not_exists", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  $roles = $user->roles;


  if(!in_array("customer", $roles)){
    $error->add("user_invalid_role", __("user_invalid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  $is_verified = get_user_meta($user->ID, "_dv_is_verified", true );

  if($is_verified !== "1" ){
    $error->add("user_invalid_verify", __("user_invalid", 'wp-rest-user'), array('status' => 400));
    return $error;
  }

  $password = random_password();

  wp_set_password( $password, $user->ID );
  
  $user_info = array(
    "id" => $user->ID,
    "user_email" => $user->data->user_email,
  );

  $to = $user->data->user_email;
  $subject = "Quên mật khẩu - Dylan Beauty";
  $message = "Mật khẩu mới của bạn là <p>{$password}</p>";

  $headers = array('Content-Type: text/html; charset=UTF-8','From: Dylan Beauty <vutruongduy2109@gmail.com>');
  

  $send_mail = wp_mail( $to, $subject,  $message, $headers );

  return new WP_REST_Response(array("user_info" => $user_info, "code" => "send_new_password_success"), 202);
}





  
function dv_auth_test_mail(WP_REST_Request $request){

  $str = wp_generate_uuid4();
  $active_key = wp_hash( $str );

  $to = "duyvutruong@vietjetair.com";
  $subject = "Đăng ký tài khoản - Dylan Beauty";
  $message = "Cảm ơn bạn đã đăng ký tài khoản, vui lòng click vào đây để hoàn thiện quá trình đăng ký {$active_key}";

  $headers = array('Content-Type: text/html; charset=UTF-8','From: Dylan Beauty <vutruongduy2109@gmail.com>');
  

  $send_mail = wp_mail( $to, $subject,  $message, $headers );

  return new WP_REST_Response( 'send mail success', $send_mail );
}






/**
 * Customize welcome email for new users in WordPress.
 */
function dv_template_email_user_activation( $blogname, $user_email, $active_key, $username ) {

  // Set email background, border, and font colors
  $email_style = "style='background-color:#f7f7f7;border-radius:6px;border:1px solid #ccc;color:#333;'";
  $content_style = "style='background-color:#fff;padding:20px;border-radius:4px;border:1px solid #ddd;margin:10px 0; width:600px; margin-left: auto; margin-right: auto'";
  $link_style = "style='background-color:#de3d83;border-radius:3px;color:#fff;display:inline-block;font-size:14px;font-weight:bold;margin-top:20px;padding:12px 20px;text-decoration:none;text-transform:uppercase;'";
  $small_style = "style='display:inline-block;font-size:14px;margin-top:20px;padding-left:20px;text-decoration:none;'";

  $frontend_url = "http://localhost:3000";
  // Create message content
  $message = "<div $content_style>";
  $message .= "<h2>" . sprintf( __( 'Chào mừng bạn đến với %s!' ), $blogname ) . "</h2>";
  $message .= "<p>" . __( 'save your favorite blog posts, follow other users, and receive email updates about new content.' ) . "</p>";
  $message .= "<h4>" . __( 'Benefits of Registration' ) . "</h4>";
  $message .= "<ul style='padding-left: 15px'><li>" . __( 'Save blog posts' ) . "</li><li>" . __( 'Follow other users' ) . "</li><li>" . __( 'Receive email updates' ) . "</li></ul>";
  $message .= "<p>" . __( 'To get started, click the button below to set your password.' ) . "</p>";
  $message .= "<a $link_style href='" . $frontend_url . "/verify?activeKey=".$active_key."&userEmail=".$user_email."&acId=".$username."'>" . __( 'Kích hoạt tài khoản ngay' ) . "</a>";
  $message .= "</div>";

  // Create header image
  // $header = "<a href='" . $frontend_url . "'><img src='http://localhost/dylan-woo-cms/wp-content/uploads/2023/09/banner-1-1.jpeg' height='200' alt='" . $blogname . "'></a>";

  // Set email parameters
 
  $subject = sprintf( __( 'Welcome to %s!' ), $blogname );
  $headers = array('Content-Type: text/html; charset=UTF-8','From: Dylan Beauty <vutruongduy2109@gmail.com>');
  // $message = $header . $message;

  // Apply email text domain and send email
  $wp_new_user_notification_email['message'] = $message;
  $wp_new_user_notification_email['subject'] = $subject;
  $wp_new_user_notification_email['headers'] = $headers;

  return $wp_new_user_notification_email;
}



function dv_template_email_user_active_success( $blogname, $user) {

  // Set email background, border, and font colors
  $email_style = "style='background-color:#f7f7f7;border-radius:6px;border:1px solid #ccc;color:#333;'";
  $content_style = "style='background-color:#fff;padding:20px;border-radius:4px;border:1px solid #ddd;margin:10px 0; width:600px; margin-left: auto; margin-right: auto'";
  $link_style = "style='background-color:#de3d83;border-radius:3px;color:#fff;display:inline-block;font-size:14px;font-weight:bold;margin-top:20px;padding:12px 20px;text-decoration:none;text-transform:uppercase;'";
  $small_style = "style='display:inline-block;font-size:14px;margin-top:20px;padding-left:20px;text-decoration:none;'";

  $frontend_url = "http://localhost:3000";
  // Create message content
  $message = "<div $content_style>";
  $message .= "<h2>" . sprintf( __( 'Welcome to %s!' ), $blogname ) . "</h2>";
  $message .= "<p>" . __( 'Kich hoạt tài khoản thành công.' ) . "</p>";
  $message .= "<h4>" . __( 'Benefits of Registration' ) . "</h4>";
  $message .= "<ul style='padding-left: 15px'><li>" . __( 'Save blog posts' ) . "</li><li>" . __( 'Follow other users' ) . "</li><li>" . __( 'Receive email updates' ) . "</li></ul>";
  $message .= "<p>" . __( 'Trở lại website để đặt sản phẩm và khám phá những chương trình khuyến mại.' ) . "</p>";
  $message .= "<a $link_style href='" . $frontend_url . "/login'>" . __( 'Đăng nhập ngay' ) . "</a>";
  $message .= "</div>";

  // Create header image
  // $header = "<a href='" . $frontend_url . "'><img src='http://localhost/dylan-woo-cms/wp-content/uploads/2023/09/banner-1-1.jpeg' height='200' alt='" . $blogname . "'></a>";

  // Set email parameters
 
  $subject = sprintf( __( 'Welcome to %s!' ), $blogname );
  $headers = array('Content-Type: text/html; charset=UTF-8','From: Dylan Beauty <vutruongduy2109@gmail.com>');
  // $message = $header . $message;

  // Apply email text domain and send email
  $wp_new_user_notification_email['message'] = $message;
  $wp_new_user_notification_email['subject'] = $subject;
  $wp_new_user_notification_email['headers'] = $headers;

  return $wp_new_user_notification_email;
}

