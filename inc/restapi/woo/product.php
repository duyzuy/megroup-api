<?php 



add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'products', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_all_products',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );

    register_rest_route( 'dv/v1', 'product/(?P<slug>[a-z0-9]+(?:-[a-z0-9]+)*)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_product_detail',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );

});


function wc_get_all_products(WP_REST_Request $request) {
  $output = array();

  $per_page = $request['per_page'] ? (int)$request['per_page'] : 10;

  $filters = $request['filter'];
  $order = $request['order'] ? $request['order'] : "asc";
  $orderby = $request['orderby'] ? $request['orderby'] : "date";
  $onSale = $request['on_sale'];
  $featured = $request['featured'];
  $orderby = $request['orderby'] ? $request['orderby'] : "date";
  $page = $request['page'] ? (int)$request['page'] : 1;
 
  $offset = ($page - 1) * $per_page;

  $category_id = $request['category_id'];
  $brand_id = $request['brand_id'];
          
  // Use default arguments.
  $args = [
    'post_type'       => 'product',
    'posts_per_page'  => $per_page,
    'post_status'     => 'publish',
    'paged'           => $page,
    "order"           => $order
  ];

  $error = new WP_Error();
   
  if($per_page > 40){
      $error->add("maximum_perpage", __("maximum product number.", 'wp-rest-setting'), array('status' => 400));
      return $error;
  }

  // Orderby condition. Name/Price.
  if ( ! empty( $orderby ) ) {
    if ( $orderby === 'price' ) {
      $args['orderby'] = 'meta_value_num';
      $args['meta_key'] = '_price';

    } else {
      $args['orderby'] = $orderby;
    }
  }

  if($onSale){
    $args['meta_key'] = '_sale_price';
    $args['meta_value'] = '0';
    $args['meta_compare'] = '>=';
  }

  if($featured){

  }

      // If filter buy category or attributes.
  if ( !empty( $category_id ) || !empty( $filters ) || !empty( $brand_id )) {
    $args['tax_query']['relation'] = 'AND';

    // Category filter.
    if ( isset($category_id) ) {

        $args['tax_query'][] = array(
          'taxonomy' => 'product_cat',
          'field'    => 'term_id',
          'terms'    => [ $category_id ],
        );
      
    }

       // Category filter.
    if ( isset($brand_id) ) {

      $args['tax_query'][] = array(
        'taxonomy' => 'product_brand',
        'field'    => 'term_id',
        'terms'    => [ $brand_id ],
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
  

    if ( ! $query->have_posts() ) {
        $output['list'] = [];
        $output['total'] = 0;
        $output['total_page'] = 0;
        $output['page'] = $page;
        $output['per_page'] = $per_page;
    }
    else{
        while ( $query->have_posts() ) {
        $query->the_post();
          
        $product_id = get_the_ID();
        
        /**
         * hook helpers/product
         */
        $product = dv_get_product_by_id($product_id);
        

        $output['list'][] =  $product;
        
        }
        wp_reset_postdata();
        $output['total'] = $total_products;
        $output['total_page'] = $total_page;
        $output['page'] = $page;
        $output['per_page'] = $per_page;
    }
    

  return new WP_REST_Response($output);
}

function wc_get_product_detail(WP_REST_Request $request) {
    global $woocommerce;

    $product_slug = $request["slug"];
    $products = $woocommerce->get('products', array(
      'slug' => $product_slug, "per_page" => 1,
    ));

    // $product_type = $product->get_type();

    // $product_url = "";
    // if($product_type === "external"){
    //   $product_url = get_post_meta( $product_id, "_product_url", true );
      
    // }

    $error = new WP_Error();
    if(!count($products)){

        $error->add("product_not_found", __("Product not found.", 'wp-rest-product-detail'), array('status' => 404));
        return $error;

    }

    $product =  $products[0];

    $attribute_taxonomies = wc_get_attribute_taxonomies(); 
   
    $product_attributes = get_post_meta($product->id, "_product_attributes", true);
    
    $attributes_variant = array();
    $count = 0;

    foreach($product_attributes as $attr_key => $attr_value){
    
      // $term = get_term_by( $field:string, $value:string|integer, $taxonomy:string, $output:string, $filter:string )

      foreach( $attribute_taxonomies as $tax){

        if("pa_{$tax->attribute_name}" === $attr_key){

          $taxonomy = array(
            "id"  => (int)$tax->attribute_id,
            "name"  =>  $tax->attribute_label,
            "type"  =>  $tax->attribute_type,
            "taxonomy"  => 'pa_'.$tax->attribute_name,
          );
         
          $options = wc_get_product_terms($product->id, $attr_key);
          $opts = array();
          foreach($options as $option){

            $opt = array(
              'id'  =>  $option->term_id,
              'name'  =>  $option->name,
              'slug'  =>  $option->slug,
            );

            if($tax->attribute_type === "color"){
   
              $meta_color = get_term_meta( $option->term_id, '_attribute_color', true ) ? get_term_meta( $option->term_id, '_attribute_color', true ) : null; 
      
              $opt['meta_data'] = $meta_color;
            }

            $opts[] = $opt;
          }

          $attributes_variant[$count] = $taxonomy;
          $attributes_variant[$count]['options'] = $opts;

          break;
        }
      
      }
      
      $count++;
    }

    $product->attributes_variants = $attributes_variant;

    /**
     * brand
     */


    $brands = get_the_terms($product->id, 'product_brand' );
    $brand = null;
    
    if($brands){
      $brand = $brands[0];
      $brand_attachment_id = get_term_meta( $brand->term_id, '_brand_logo', true ); 
      $image = null;

      if( $brand_attachment_id){
        $logo_url = wp_get_attachment_url( $brand_attachment_id  );

        $attachment = get_post( $brand_attachment_id );
        $image = array(
            'id'    =>  (int)$brand_attachment_id,
            'src'   =>  $logo_url,
            'name'  =>  $attachment->post_title,
            'alt'   =>  $attachment->post_title,
        );
      }

      $brand = array(
        "id"      =>    $brand->term_id,
        "name"    =>    $brand->name,
        "slug"    =>    $brand->slug,
        "count"   =>    $brand->count,
        "image"   =>    $image,
      );

    }

    $product->brand = $brand;
    
    unset($product->_links);
    unset($product->permalink);
    unset($product->downloads);
    unset($product->download_limit);
    unset($product->download_expiry);
    unset($product->downloadable);
    unset($product->price_html);
    unset($product->menu_order);
    unset($product->button_text);

    
	return new WP_REST_Response( $product );
}
