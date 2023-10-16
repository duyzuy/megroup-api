<?php 

add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'order', array(
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => 'wc_make_order',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );

    register_rest_route( 'dv/v1', 'order/(?P<order_id>\d+)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_order_detail',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );


      register_rest_route( 'dv/v1', 'orders', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_order_list',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );

} );


function wc_make_order(WP_REST_Request $request) {

    global $woocommerce;
    
    $methods =  array("bacs", "cheque", "cod");
    $shipping_lines_ids = array("flat_rate", "free_shipping", "local_pickup");
    $data = array();
    $error = new WP_Error();
 
      /**
       * 
       * 3 type of payment: bacs | cheque | cod
       *
       */
    $payment_method = $request['payment_method'];


    $payment_method_title = isset($request['payment_method_title']) ? $request['payment_method_title'] : "";
    // $set_paid = $request['set_paid'];

    $billing = $request['billing'];
    $shipping = $request['shipping'];
    $line_items = $request['line_items'];
    $shipping_lines = $request['shipping_lines'];
    $order_note = $request['note'] ? $request['note'] : "";



    if(!in_array($payment_method, $methods) or !isset($payment_method)){
        $error->add("payment_method_invalid", __("payment_method_invalid.", 'wp-rest-order'), array('status' => 400));
        return $error;
    }

    if(!isset($billing['first_name'])){
        $error->add("billing_fist_name_empty", __("billing_fist_name_empty.", 'wp-rest-order'), array('status' => 400));
        return $error;
    }
    
    if(!isset($billing['last_name'])){
        $error->add("billing_last_name_empty", __("billing_last_name_empty.", 'wp-rest-order'), array('status' => 400));
        return $error;
    }

    if(!isset($billing['email'])){
        $error->add("billing_email_empty", __("billing_email_empty", 'wp-rest-order'), array('status' => 400));
        return $error;
    }

    if (!is_email($billing['email'], false)) {
        $error->add("billing_email_invalid", __("billing_email_invalid", 'wp-rest-order'), array('status' => 400));
        return $error;
        }

    if(!isset($billing['phone'])){
        $error->add("billing_phone_empty", __("billing_phone_empty", 'wp-rest-order'), array('status' => 400));
    return $error;

    }

    if (!is_phone_number($billing['phone'])) {
        $error->add("billing_phone_invalid", __("billing_phone_invalid", 'wp-rest-order'), array('status' => 400));
        return $error;
    }
    $products = array();
    if (!isset($line_items) or count($line_items) === 0) {
        $error->add("product_invalid", __("product_invalid", 'wp-rest-order'), array('status' => 400));
        return $error;
    }else{
        $count = 0;
        foreach($line_items as $item){

            $is_product = get_post_type( $item['product_id'] ) === 'product';

            if(!$is_product){
                $error->add("product_invalid", __("product_invalid", 'wp-rest-order'), array('status' => 400));
                return $error;
            }
            
       
            $prd = wc_get_product($item['product_id']);

            $status = $prd->get_status();

            if( $status !== "publish"){
                $error->add("product_invalid", __("product_invalid", 'wp-rest-order'), array('status' => 400));
                return $error;
            }

            /**
             * manage stock
             */

             if( $prd->get_manage_stock() ) {
                $stock_quantity = $prd->get_stock_quantity();
            
                // now we can print it or do whatever
                if($stock_quantity === 0){
                    $error->add("out_of_stock", __("product_invalid", 'wp-rest-order'), array('status' => 400));
                    return $error;
                }
               
            } else {
                
                $stock_status = $prd->get_stock_status();
                if( 'instock' === $stock_status ) {
                 
                }
                if( 'outofstock' === $stock_status ) {
                    $error->add("out_of_stock", __("product_invalid", 'wp-rest-order'), array('status' => 400));
                    return $error;
                }
                // there is also "onbackorder" value can be returned
            }

         
            $product_type = $prd->get_type();

            if($product_type !== "variable" and $product_type !== "simple" ){
                $error->add("product_invalid", __("product_invalid", 'wp-rest-order'), array('status' => 400));
                return $error;
            }
            $variation_ids = array();
            if($product_type === "variable" ){
                
             
                if(!isset($item['variation_id'])){
                    $error->add("variation_id_invalid", __("variation_id_invalid", 'wp-rest-order'), array('status' => 400));
                    return $error;
                }

             
                $available_variations = $prd->get_available_variations();
                // $products[$count]['variation'] = $available_variations;
                
              
                foreach($available_variations as $variation){
                    $variation_ids[] = $variation['variation_id'];
                }

                if(!in_array($item['variation_id'],$variation_ids)){
                    $error->add("product_invalid_variation", __("product_invalid_variation", 'wp-rest-order'), array('status' => 400));
                    return $error;
                }

                // $products[$count] = $available_variations;
            }
           
        //   $products[$count] = $variation_ids;
            // $count++;
            
        }
        // $item['product_id']
    }
   
    $data['payment_method'] =  $payment_method;
    $data['payment_method_title'] =  $payment_method_title;
    $data['set_paid'] =  false;

    $data['billing'] = array(
        "first_name"    =>  sanitize_text_field($billing['first_name']),
        "last_name"     =>  sanitize_text_field( $billing['last_name'] ),
        "email"         =>  sanitize_text_field($billing['email']),
        "phone"         =>  normalize_telephone_number($billing['phone']),
        "company"       =>  isset($billing['company']) ? sanitize_text_field($billing['company']) : "",
        "address_1"     =>  isset($billing['address_1']) ? sanitize_text_field($billing['address_1']) : "",
        "address_2"     =>  isset($billing['address_2']) ? sanitize_text_field($billing['address_2']) : "",
        "city"          =>  isset($billing['city']) ? sanitize_text_field($billing['city']) : "",
        "state"         =>  isset($billing['state']) ? sanitize_text_field($billing['state']) : "",
        "postcode"      =>  isset($billing['postcode']) ? sanitize_text_field($billing['postcode']) : "",
        "country"       =>  isset($billing['country']) ? sanitize_text_field($billing['country']) : "",
    );

    $data['shipping'] = array(
        "first_name"    =>  isset($shipping['first_name']) ? sanitize_text_field($shipping['first_name']) : $data['billing']['first_name'],
        "last_name"     =>  isset($shipping['last_name']) ? sanitize_text_field($shipping['last_name'] ) : $data['billing']['last_name'],
        "phone"         =>  isset($shipping['phone']) ? normalize_telephone_number($shipping['phone']) : $data['billing']['phone'],
        "company"       =>  isset($shipping['company']) ? sanitize_text_field($shipping['company']) : $data['billing']['company'],
        "address_1"     =>  isset($shipping['address_1']) ? sanitize_text_field($shipping['address_1']) :  $data['billing']['address_1'],
        "address_2"     =>  isset($shipping['address_2']) ? sanitize_text_field($shipping['address_2']) :  $data['billing']['address_2'],
        "city"          =>  isset($shipping['city']) ? sanitize_text_field($shipping['city']) : $data['billing']['city'],
        "state"         =>  isset($shipping['state']) ? sanitize_text_field($shipping['state']) : $data['billing']['state'],
        "postcode"      =>  isset($shipping['postcode']) ? sanitize_text_field($shipping['postcode']) : $data['billing']['postcode'],
        "country"       =>  isset($shipping['country']) ? sanitize_text_field($shipping['country']) : $data['billing']['country'],
    );

    $data['line_items'] = $line_items;
    $data['shipping_lines'] = $shipping_lines;
   
    $user_id = 0;
    $order_note_data = array(
        'note' => $order_note,
        "added_by_user" => true,
        "customer_note" => true,
    );
 
    if(is_user_logged_in()){
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $order_note_data['author'] = $current_user->user_nicename;
    }

    $data['customer_id'] = $user_id;


    $order = $woocommerce->post("orders", $data);

    /**
     * create order note
     */
   

    $woocommerce->post('orders/'.$order->id.'/notes', $order_note_data);

    unset( $order->payment_url);
    unset( $order->_links);

	return new WP_REST_Response( $order);
}


function wc_get_order_detail(WP_REST_Request $request) {

    
    $error = new WP_Error();
    if(!is_user_logged_in()){
        $error->add("unAuthorize", __("unAuthorize.", 'wp-rest-order'), array('status' => 403));
        return $error;
    }

    $order_id = $request['order_id'] ?  (int)$request['order_id'] : null;
   

    $user = wp_get_current_user();
    $args = array( 
        "per_page"  =>  1,
        "orderby"   =>  "date",
        "order"     =>  "desc",
        "customer"  => $user->ID
    );
 

    if(!isset($order_id)){
        $error->add("order_id_invalid", __("order_id_invalid.", 'wp-rest-order'), array('status' => 400));
        return $error;
    }

    $args['include'] = [$order_id];
    
    global $woocommerce;
    $orders = $woocommerce->get('orders', $args);


//    $order = $woocommerce->get('orders/'. $order_id);

   if(count($orders) === 0){
        $error->add("order_notfound", __("order_notfound.", 'wp-rest-order'), array('status' => 404));
        return $error;
   }

   $order = $orders[0];

   unset($order->_links);
   unset($order->currency_symbol);
   unset($order->payment_url);
   unset($order->version);
  
	return new WP_REST_Response( $orders[0]);
}

function wc_get_order_list(WP_REST_Request $request) {



    global $woocommerce;

    $per_page = $request['per_page'] ? (int)$request['per_page'] : 10;
    $page = $request['page'] ? (int)$request['page'] : 1;
    $order = $request['order'] ? $request['order'] : "asc";
    $orderby = $request['orderby'] ? $request['orderby'] : "date";

    $args = array( 
        "per_page"  =>  $per_page,
        "page"      =>  $page,
        "orderby"   =>  "date",
        "order"     =>  "desc"
    );
    $error = new WP_Error();
    
    if(!is_user_logged_in()){
        $error->add("unAuthorize", __("unAuthorize.", 'wp-rest-order'), array('status' => 403));
        return $error;
    }

 
    $user = wp_get_current_user();

    $args['customer'] = $user->ID;

    $orders = $woocommerce->get('orders', $args);

    /**
     * select frrom DB
     */

    global $wpdb;
    $table_order =  $wpdb->prefix ."wc_orders";
   
    $totalItem = $wpdb->get_var($wpdb->prepare("
    SELECT COUNT(ID) FROM " . $table_order . " 
    WHERE type = 'shop_order' AND customer_id = ".$user->ID."", array() ) );

    
    $output = array(
        "page"          => $page,
        "per_page"      => $per_page,
        "total"         => (int)$totalItem,
        "total_page"    =>  ceil($totalItem/$per_page),
    );
    
    foreach( $orders as $order ){
        $data = array(
            "id"    =>  $order->id,
            "status" =>  $order->status,
            "total" =>  $order->total,
            "order_key" =>  $order->order_key,
            "payment_method" => $order->payment_method,
            "payment_method_title" => $order->payment_method_title,
            "date_created_gmt" => $order->date_created_gmt,
            "date_modified_gmt" => $order->date_modified_gmt,
            "date_completed_gmt" => $order->date_completed_gmt,
            "date_paid_gmt" => $order->date_paid_gmt,
            "customer_id" => $order->customer_id,
            "customer_ip_address" => $order->customer_ip_address,
            "customer_user_agent" => $order->customer_user_agent,
            "customer_note" => $order->customer_note,
        );


        $output['list'][] = $data;
    }

    return new WP_REST_Response( $output );

}