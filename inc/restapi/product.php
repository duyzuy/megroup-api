<?php 

add_action('rest_api_init', 'wp_rest_filterproducts_endpoints');
function wp_rest_filterproducts_endpoints($request) {
    register_rest_route('dv/v1', 'products', array(
        'methods' => 'GET',
        'callback' => 'get_product_woocommerce',
        ''
    ));
  
}

function get_product_woocommerce(WP_REST_Request $request) {
  $output = array();
  

  $per_page = $request['per_page'] ? (int)$request['per_page'] : 10;

  $filters = $request['filter'];
  $order = $request['order'] ? $request['order'] : "ASC";
  $orderby = $request['orderby'] ? $request['orderby'] : "date";
  $page = $request['page'] ? (int)$request['page'] : 1;
 
  $offset = ($page - 1) * $per_page;

  $category = $request['category'];
          
  // Use default arguments.
  $args = [
    'post_type'      => 'product',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
    'paged'          => 1,
  ];
  
  // Posts per page.
  if ( ! empty( $per_page ) ) {
    $args['posts_per_page'] = $per_page;
  }

  // Pagination, starts from 1.
  if ( ! empty( $page ) ) {
    $args['paged'] = $page;
  }

  // Order condition. ASC/DESC.
  if ( ! empty( $order ) ) {
    $args['order'] = $order;
  }

  // Orderby condition. Name/Price.
  if ( ! empty( $orderby ) ) {
    if ( $orderby === 'price' ) {
      $args['orderby'] = 'meta_value_num';
    } else {
      $args['orderby'] = $orderby;
    }
  }

	 
      // If filter buy category or attributes.
  if ( ! empty( $category ) || ! empty( $filters ) ) {
    $args['tax_query']['relation'] = 'AND';

    // Category filter.
   if ( ! empty( $category ) ) {
      $args['tax_query'][] = array(
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => [ $category ],
      );
    }

    // Attributes filter.
    if ( ! empty( $filters ) ) {
      foreach ( $filters as $filter_key => $filter_value ) {
        if ( $filter_key === 'min_price' || $filter_key === 'max_price' ) {
          continue;
        }

        $args['tax_query'][] = [
          'taxonomy' => $filter_key,
          'field'    => 'term_id',
          'terms'    => explode( ',', $filter_value ),
        ];
      }
    }

    // Min / Max price filter.
    if ( isset( $filters['min_price'] ) || isset( $filters['max_price'] ) ) {
      $price_request = [];

      if ( isset( $filters['min_price'] ) ) {
        $price_request['min_price'] = $filters['min_price'];
      }

      if ( isset( $filters['max_price'] ) ) {
        $price_request['max_price'] = $filters['max_price'];
      }

      $args['meta_query'][] = wc_get_min_max_price_meta_query( $price_request );
      }
  }
  
  $query = new WP_Query( $args );
  $total_products = $query->found_posts;
  $total_page = ceil($total_products/$per_page);
  
	
  $output['status'] = 200;
  $output['message'] = __("Get data success", "wp-rest-product-attribute");
 

  if ( ! $query->have_posts() ) {
    $output['data']  = null;
  }
  else{
    while ( $query->have_posts() ) {
      $query->the_post();

      $product = wc_get_product( get_the_ID() );  
      $product_id = $product->get_id();

      $meta_data = get_post_meta( $product_id );
      
      // Product Properties
      $wcproduct['id'] = $product_id;
      $wcproduct['name'] = $product->get_name();
      $wcproduct['slug'] = $product->slug;
      $wcproduct['price'] = $product->get_regular_price();
      $wcproduct['regular_price'] = $product->get_regular_price();
      $wcproduct['sale_price'] = $product->get_sale_price();
      $wcproduct['onsale'] = !empty($product->get_sale_price()) ? true : false;
      $wcproduct['date_on_sale_from'] = $meta_data['_sale_price_dates_from'][0];
      $wcproduct['date_on_sale_to'] =  $meta_data['_sale_price_dates_to'][0]; 

      $wcproduct['status'] = $product->status;
      $wcproduct['stock_status'] = $meta_data['_stock_status'][0];
      $wcproduct['sku'] = $meta_data['_sku'][0];
      $wcproduct['type'] = $product->get_type();
      $wcproduct['total_sales'] = (int)$meta_data['total_sales'][0];
      $wcproduct['featured'] = $product->is_featured();

      //Get thumbail image
      $thumbnail_id = $meta_data['_thumbnail_id'];
      $thumbnail_url = '';
      if(count($thumbnail_id) > 0){
        $thumbnail_url = wp_get_attachment_image_src( $thumbnail_id[0], 'large' )[0];
      }
      $wcproduct['thumbnail'] = $thumbnail_url;
    
      //Get gallery
      $arr_ids = [];
      $wcproduct['images'] = false;
      $gallery_ids = $meta_data['_product_image_gallery'][0];
      if(!empty($gallery_ids)){
        
        $arr_ids = explode(',', $gallery_ids);
        
        foreach($arr_ids as $key => $id){
          $url = wp_get_attachment_image_src( $id, 'large' )[0];
          $wcproduct['images'][] = $url;
        }
        
      }
      
      //Get categories
      $terms = get_the_terms($product_id, 'product_cat' );
      $categories = [];
        if ($terms && ! is_wp_error($terms)) {
        
          foreach ($terms as $term) {
            $cat_item['name'] = $term->name;
            $cat_item['id'] = $term->term_id;
            $cat_item['slug'] = $term->slug;
            $categories[] = $cat_item;
          }
      }
      $wcproduct['categories'] = $categories;
      
      $prd_attrs = get_post_meta( $product_id, '_product_attributes', true );
      $attrs = [];

      foreach($prd_attrs as $attr_key => $att_value){

        $product_options = wc_get_product_terms($product_id, $attr_key);
        $attr_options = [];
        foreach($product_options as $option_key => $option_value){

          $option['id'] = $option_value->term_id;
          $option['name'] = $option_value->name;
          $option['slug'] = $option_value->slug;
          $option['taxonomy'] = $option_value->taxonomy;
          $option['count'] = $option_value->count;

          $attr_options[] = $option;

        }

        $att_value['options'] = $attr_options;
        $attrs[] = $att_value;
      
      }
      
      $wcproduct['attributes'] = $attrs;
      $wcproduct['average_rating'] = (int)$meta_data['_wc_average_rating'][0];

      // $wcproduct['meta'] = $meta_data;
      $output['data']['list'][] = $wcproduct;
      
    }
    wp_reset_postdata();
    $output['data']['total'] = $total_products;
    $output['data']['total_page'] = $total_page;
    $output['data']['page'] = $page;
    $output['data']['per_page'] = $per_page;
}
  

  return new WP_REST_Response($output);
}