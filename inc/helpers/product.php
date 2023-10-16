<?php 
function dv_get_product_by_id ($id) {

    $product = wc_get_product( $id );  
    $product_id = $product->get_id();

    $meta_data = get_post_meta( $product_id );

    // Product Properties

    $product_type = $product->get_type();

    $external_url = "";
    if($product_type === "external"){
      $external_url = get_post_meta( $product_id, "_product_url", true );
      
    }

    $data = array(
      'id' => $product_id,
      'name' => $product->get_name(),
      'slug' => $product->slug,
      'price' => $product->get_price(),
      'regular_price' => $product->get_regular_price(),
      'sale_price' => $product->get_sale_price(),
      'on_sale' => $product->is_on_sale(),
      'status' => $product->get_status(),
      'stock_status' => $product->is_in_stock() === true ? "instock" : "outofstock",
      'stock_quantity' => $product->get_stock_quantity(),
      'sku' => $product->get_sku(),
      'type' => $product_type,
      'total_sales' => $product->get_total_sales(),
      'featured' => $product->is_featured(),
      'average_rating' => wc_format_decimal( $product->get_average_rating(), 2 ),
      'rating_count' => $product->get_rating_count(),
      'date_on_sale_from' => isset($meta_data['_sale_price_dates_from']) ? $meta_data['_sale_price_dates_from'][0] : null,
      'date_on_sale_to' => isset($meta_data['_sale_price_dates_to']) ? $meta_data['_sale_price_dates_to'][0] : null, 
      'featured_src' => wp_get_attachment_url( get_post_thumbnail_id( $product->get_id() ) ),
      "external_url"  =>  $external_url,
      "meta"  =>  $meta_data,
    
    );
    
    /*
     * Get all image by ids 
     */
    $data['gallery'] = [];

    // $image_ids = $meta_data['_product_image_gallery'][0];
    $image_ids = get_post_meta( $product_id, "_product_image_gallery", true );


    if(!empty($image_ids) and $image_ids !== ""){
        
        $arr_image_ids = explode(',', $image_ids);
    
        foreach($arr_image_ids as $key => $image_id){
          $thumb_url = wp_get_attachment_image_src( $image_id, 'large' )[0];

          $img = array(
            'id' => $image_id,
            'src' => $thumb_url,
            'name' => get_the_title($image_id),
            'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
          );

          $data['gallery'][] = $img;
    
        }
    }
    //Get categories
    $terms = get_the_terms($product_id, 'product_cat' );
    $categories = array();
    
    if ($terms && ! is_wp_error($terms)) {
        
        foreach ($terms as $term) {
          $cat = array(
           'name' => $term->name,
           'id' => $term->term_id,
           'slug' => $term->slug,
          );
          
          $categories[] = $cat;
        }
    }
    

    $data['categories'] = $categories;
    
    $prd_attrs = get_post_meta( $product_id, '_product_attributes', true );
    $attrs = array();

    foreach($prd_attrs as $attr_key => $att_value){

        $product_options = wc_get_product_terms($product_id, $attr_key);
        $attr_options = array();

        foreach($product_options as $option_key => $option_value){

          $opt = array(
            'id' => $option_value->term_id,
            'name' => $option_value->name,
            'slug' => $option_value->slug,
            'taxonomy' => $option_value->taxonomy,
            'count' => $option_value->count,
          );
       
          $attr_options[] =  $opt;

        }
        $att_value['options'] = $attr_options;

        $attrs[] = $att_value;
    
    }
    
    $data['attributes'] = $attrs;
    

    $term_brands = get_the_terms($product_id, 'product_brand' );
    $data['brand'] = null;

    if($term_brands){
      $brand = $term_brands[0];
      $brand_attachment_id = get_term_meta( $brand->term_id, '_brand_logo', true ); 
      $image = null;

      if( $brand_attachment_id){
        $logo_url = wp_get_attachment_url( $brand_attachment_id  );

        $attachment = get_post( $brand_attachment_id );
        $image = array(
            'id' =>  (int)$brand_attachment_id,
            'src' =>  $logo_url,
            'name' => $attachment->post_title,
            'alt' =>  $attachment->post_title,
        );
      }

      $data['brand'] = array(
        "id"    =>  $brand->term_id,
        "name"  =>   $brand->name,
        "slug"  =>   $brand->slug,
        "image"  =>  $image,
      );

    }

    /*
    * Only for variations of product variable type.
    */

    $attribute_taxonomies = wc_get_attribute_taxonomies(); 
    $variations = array();
    foreach( $attribute_taxonomies as $tax){

      $variant = get_post_meta($product_id, "attribute_pa_{$tax->attribute_name}", true);

      $term = get_term_by('slug', $variant, "pa_{$tax->attribute_name}");

      if(!isset($variant) or $variant === "") continue;

      if( $term ){
        $variations[] = array(
            "id"        =>  (int)$tax->attribute_id,
            'name'      => $tax->attribute_label,
            'slug'      => $tax->attribute_name,
            'taxonomy'  => 'pa_'.$tax->attribute_name,
            "option"    => array(
                  "id"        =>  $term->term_id,
                  "slug"      => $term->slug,
                  "name"      =>   $term->name,
                ),
        );
      }
   
    }
    $data['variations'] = $variations;
         
    return $data;
}