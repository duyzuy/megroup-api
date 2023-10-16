<?php 

add_action('rest_api_init', function () {

  register_rest_route('dv/v1', 'products/getByIds', array(
      'methods' =>  WP_REST_Server::READABLE,
      'callback' => 'get_products_by_ids_woocommerce',
      'permission_callback' => '__return_true'
  ));

});



function get_products_by_ids_woocommerce(WP_REST_Request $request) {

    $product_types = array("product", "product_variation");
    $product_ids = $request['product_ids'];
    $product_type = isset($request['product_type']) ? $request['product_type'] : "product";
            
    // Use default arguments.
    $args = [
      'post_type'      => $product_type,
      'post_status'    => 'publish',
      'posts_per_page' => 1,
    ];
    
    // Posts per page.
    if ( !isset( $product_ids ) ) {
      $error->add("product_ids_invalid", __("product_ids_invalid", 'wp-wc-product-ids'), array('status' => 400));
      return $error;      
    }

    $error = new WP_Error();
    if(!in_array($product_type, $product_types)){
        $error->add("product_type_invalid", __("product_type_invalid", 'wp-wc-product-ids'), array('status' => 400));
        return $error;      
    }

 
    $arr_product_ids = explode(",", $product_ids);

    foreach($arr_product_ids as $id){
        if(!is_numeric($id)){
          $error->add("product_type_invalid", __("product_type_invalid", 'wp-wc-product-ids'), array('status' => 400));
          return $error;    
        }
    }

    $args['post__in'] = $arr_product_ids;
    $args['posts_per_page'] = count($arr_product_ids);

    $query = new WP_Query( $args );

    $products_data = array();
    

    if ( ! $query->have_posts() ) {
      $products_data = null;
    } else {
      while ( $query->have_posts() ) {
        $query->the_post();

        $product_id = get_the_ID();

        $product = dv_get_product_by_id($product_id);
        

      //   $product = wc_get_product( get_the_ID() );  
      //   $product_id = $product->get_id();

      //   $meta_data = get_post_meta( $product_id );

      //   $wcproduct = array(
      //       'id'                  => $product_id,
      //       'name'                => $product->get_name(),
      //       'slug'                => $product->slug,
      //       'price'               => $product->get_price(),
      //       'regular_price'       => $product->get_regular_price(),
      //       'sale_price'          => $product->get_sale_price(),
      //       'on_sale'             => $product->is_on_sale(),
      //       'status'              => $product->get_status(),
      //       'stock_status'        => $product->is_in_stock() === true ? "instock" : "outofstock",
      //       'stock_quantity'      => $product->get_stock_quantity(),
      //       'sku'                 => $product->get_sku(),
      //       'type'                => $product->get_type(),
      //       'total_sales'         => $product->get_total_sales(),
      //       'featured'            => $product->is_featured(),
      //       'average_rating'      => wc_format_decimal( $product->get_average_rating(), 2 ),
      //       'rating_count'        => $product->get_rating_count(),
      //       'date_on_sale_from'   => isset($meta_data['_sale_price_dates_from']) ? $meta_data['_sale_price_dates_from'][0] : null,
      //       'date_on_sale_to'     => isset($meta_data['_sale_price_dates_to']) ? $meta_data['_sale_price_dates_to'][0] : null, 
      //       "featured_src"        =>  wp_get_attachment_url( get_post_thumbnail_id( $product->get_id() ) ),
      //   );
       

      //   $image_ids =  get_post_meta( $product_id, "_product_image_gallery", true); 

      //   if(!empty($gallery_ids) and $gallery_ids !== ""){
          
      //     $arr_image_ids = explode(',', $image_ids);

      //     $gallery = array();

      //     foreach($arr_image_ids as $key => $image_id){
        
      //       $img = array(
      //           "id"    =>  $image_id,
      //           "src"   =>   wp_get_attachment_image_src( $image_id, 'large' )[0],
      //           "name"  =>  get_the_title($image_id),
      //           "alt"   =>  get_post_meta($image_id, '_wp_attachment_image_alt', true),
      //       );

      //       $gallery[] = $img;

      //     }
      //     $wcproduct['gallery'] = $gallery;
      //   }else{
      //     $wcproduct['gallery'] = null;
      //   }
        
      //  /*
      //   * GET CATEGORIES 
      //   */

      //   $terms = get_the_terms($product_id, 'product_cat' );

      //   $categories = array();
      //     if ($terms && !is_wp_error($terms)) {
          
      //       foreach ($terms as $term) {
      //         $cat = array(
      //           'name' => $term->name,
      //           'id' => $term->term_id,
      //           'slug' => $term->slug,
      //         );
           
      //         $categories[] = $cat;
      //       }
      //   }


      //   $wcproduct['categories'] = $categories;
        
      //   $prd_attrs = get_post_meta( $product_id, '_product_attributes', true );
      //   $attrs = array();

      //   foreach($prd_attrs as $attr_key => $att_value){

      //     $product_options = wc_get_product_terms($product_id, $attr_key);
      //     $options = [];

      //     foreach($product_options as $option_key => $option_value){

      //       $opt = array(
      //         'id' => $option_value->term_id,
      //         'name' => $option_value->name,
      //         'slug' => $option_value->slug,
      //         'taxonomy' => $option_value->taxonomy,
      //         'count' => $option_value->count,
      //       );
           
      //       $options[] = $opt;

      //     }

      //     $att_value['options'] = $options;
      //     $attrs[] = $att_value;
        
      //   }
      //   $wcproduct['attributes'] = $attrs;
        
      //   /*
      //    * Only for variations of product variable type.
      //    */

      //   $attribute_taxonomies = wc_get_attribute_taxonomies(); 

      //   foreach( $attribute_taxonomies as $tax){

      //     $variant = get_post_meta($product_id, "attribute_pa_".$tax->attribute_name, true);

      //     $term = get_term_by('slug', $variant, 'pa_'.$tax->attribute_name);

      //     if(!isset($variant) or $variant === "") continue;

      //     $variant = array(
      //       "id"  =>  (int)$tax->attribute_id,
      //       'name' => $tax->attribute_label,
      //       'slug' => $tax->attribute_name,
      //       'taxonomy' => 'pa_'.$tax->attribute_name,
      //       "option" => array(
      //         "id"  =>  $term->term_id,
      //         "slug"  => $term->slug,
      //         "name"  =>   $term->name,
      //       ),
      //     );

      //     $wcproduct['variations'][] = $variant;
        
      //   }
       
       $products_data[] = $product;
        
      }

      wp_reset_postdata();

    }
  
  return new WP_REST_Response($products_data);
}