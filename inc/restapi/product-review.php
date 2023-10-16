<?php 

add_action('rest_api_init', 'wp_rest_rproducts_reivews_endpoints');
function wp_rest_rproducts_reivews_endpoints($request) {
    register_rest_route('dv/v1', 'products/reviews', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'get_product_review_woocommerce',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('dv/v1', 'products/review', array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'create_product_review_woocommerce',
        'permission_callback' => '__return_true'
    ));
  
}

function get_product_review_woocommerce(WP_REST_Request $request) {
    

    $per_page = $request['per_page'] ? (int)$request['per_page'] : 10;

    $order = $request['order'] ? $request['order'] : "desc";
    $orderby = $request['orderby'] ? $request['orderby'] : "date";
    $page = $request['page'] ? (int)$request['page'] : 1;
    $per_page = $request['per_page'] ? (int)$request['per_page'] : 20;

    $product_id = $request['product_id'];

    global $woocommerce;
    $error = new WP_Error();

    if(!$product_id or !is_numeric($product_id)){
        $error->add("product_invalid", __("product Id is not valid", 'wp-rest-review-add'), array('status' => 400));
        return $error;    

    }

    $product = wc_get_product( $product_id );

    if(!$product){
        $error->add("product_id_invalid", __("product Id is not valid", 'wp-rest-review-add'), array('status' => 400));
        return $error;    
    }

    $params = array(
        "product" => [$product_id], 
        "page" => $page, 
        "per_page" => $per_page, 
        "order" =>  $order,
        "orderby" => $orderby,
        "status"    => "approved"
    );

    $reviews = $woocommerce->get('products/reviews', $params);
  
    $output = array();

  

        $rating  = $product->get_average_rating();
        $count   = $product->get_rating_count();
        $rating_1 = $product->get_rating_count(1);
        $rating_2 = $product->get_rating_count(2);
        $rating_3 = $product->get_rating_count(3);
        $rating_4 = $product->get_rating_count(4);
        $rating_5 = $product->get_rating_count(5);

        if(count($reviews)){
            
            foreach($reviews as $review){
                unset($review->_links);
                unset($review->reviewer_email);
                unset($review->product_permalink);
                // $nice_name = $review->get_nic
                $output['list'][] = $review;
            }
        }

        $output["rating"] = $rating;
        $output['page'] = $page;
        $output['per_page'] = $per_page;
        $output["total_items"] = $count;
        $output['total_page'] = ceil($count/$per_page);
        $output["starts"] = array(
                "1" => $rating_1, 
                '2' => $rating_2,
                '3' => $rating_3,
                "4" => $rating_4,
                '5' => $rating_5,
        );

  

    return new WP_REST_Response($output);

}
 

function create_product_review_woocommerce(WP_REST_Request $request) {


    global $woocommerce;
    $product_id = $request['product_id'];
    $review = $request['review'];
    $rating = $request['rating'];

    $output = array();

    $product = wc_get_product( $product_id );
    
    $error = new WP_Error();

    if(!$product){
        $error->add("product_invalid", __("product_invalid", 'wp-rest-review-add'), array('status' => 400));
        return $error;
    }

    if(!is_user_logged_in()){

        $error->add("Unauthorized", __("Unauthorized", 'wp-rest-review-add'), array('status' => 401));
        return $error;      
    }

    if(!$rating or $rating == null or $rating == ""){

        $error->add("rating_empty", __("rating is not empty", 'wp-rest-review-add'), array('status' => 400));
        return $error;      
    }
    if((int)$rating < 0 or (int)$rating > 5 or !is_numeric($rating)){
        $error->add("rating_invalid", __("rating is not valid", 'wp-rest-review-add'), array('status' => 400));
        return $error;      
    }
    
    $review = wp_strip_all_tags( $review, false );

    if(!$review or strlen($review) < 20){

        $error->add(401, __("review is not valid les than 20.", 'wp-rest-review-add'), array('status' => 400));
        return $error;      
    }
    
    $current_user = wp_get_current_user();

    $output['user'] = $current_user;

    $data = [
        'product_id' => $product_id,
        'review' => sanitize_text_field( $review ),
        'reviewer' => $current_user->data->user_nicename,
        'reviewer_email' => $current_user->data->user_email,
        'rating' => (int)$rating,
        "verified" => false,
        "status"    => "hold"

    ];

    $review = $woocommerce->post('products/reviews', $data);

    return new WP_REST_Response($review);

}