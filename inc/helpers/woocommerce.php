<?php 

/**
 * Get attributes by category id
 */

function dv_get_product_attributes_by_category_id($category_id) {

    $product_ids = get_posts( 
      array(
      'post_type' => 'product',
      'numberposts' => -1,
      'post_status' => 'publish',
      'fields' => 'ids',
      'tax_query' => array(
          array(
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'     =>  $category_id,
            'operator'  => 'IN'
              )
           ),
       )
    );
  
    $attribute_taxonomies = wc_get_attribute_taxonomies(); 
    $attributes = array();
  
    foreach($attribute_taxonomies as $tax){
  
      $attr = array(
       'id' =>  (int)$tax->attribute_id,
       'name' =>  $tax->attribute_label,
       'slug' =>  $tax->attribute_name,
       'type' =>  $tax->attribute_type,
       'orderBy' =>  $tax->attribute_orderby,
       'taxonomy' =>  'pa_'.$tax->attribute_name,
       'hasArchives' =>  false
      );
   
      $options = array();
      $keys = array(); 
  
      foreach ( $product_ids as $product_id ) {
        
        $product_terms = wc_get_product_terms($product_id, 'pa_'.$tax->attribute_name);
        
        if(count($product_terms) === 0) continue;
        
        if(in_array($product_terms[0]->term_id, $keys)) continue;
  
        $opt = array(
          'id'         => $product_terms[0]->term_id,
          'name'       => $product_terms[0]->name,
          'slug'       => $product_terms[0]->slug,
          'taxonomy'   => $product_terms[0]->taxonomy,
          'count'      => $product_terms[0]->count,
        );
  
        if($tax->attribute_type === "color"){
     
          $meta_color = get_term_meta( $product_terms[0]->term_id, '_attribute_color', true ); 
  
          $opt['meta_data'] = $meta_color;
        }
  
        $keys[] = $product_terms[0]->term_id;
        
        $options[] = $opt;
      
  
      }
  
      $attr['options'] = $options;
        $attributes[] = $attr;
    }
    
    return  $attributes;
    
}

/**
 * get brands
 */
function dv_get_product_brands_by_category_id($category_id = null) {


    $args =  array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
    );

    if(isset($category_id)){
        $args['tax_query'] = array(
            array(
              'taxonomy' => 'product_cat',
              'field'    => 'term_id',
              'terms'     =>  $category_id,
              'operator'  => 'IN'
                )
            );
    };

    $product_ids = get_posts($args);
    
    $brands = array();
    $keys = array();
    foreach( $product_ids  as $product_id){

        $terms = get_the_terms($product_id, 'product_brand' );

      
       
        if($terms){
          $brand = $terms[0];
          $brand_attachment_id = get_term_meta( $brand->term_id, '_brand_logo', true ); 
          $image = null;
          if(in_array($terms[0]->term_id, $keys)) continue;
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
          $keys[] = $terms[0]->term_id;
          $brands[] = array(
            "id"    =>  $brand->term_id,
            "name"  =>   $brand->name,
            "slug"  =>   $brand->slug,
            "image"  =>  $image,
          );
        
        }
    }
    return $brands;
}
  

function dv_get_product_categories_by_brand_id($brand_id = null) {


    $args =  array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
    );

    if(isset($brand_id)){
        $args['tax_query'] = array(
            array(
              'taxonomy' => 'product_brand',
              'field'    => 'term_id',
              'terms'     =>  $brand_id,
              'operator'  => 'IN'
                )
            );
    };

    $product_ids = get_posts($args);
    
    $categories = array();
    $keys = array();
    foreach( $product_ids  as $product_id){

        $terms = get_the_terms($product_id, 'product_cat' );
       
        if($terms){

          $cat = $terms[0];
          $attachment_id = get_term_meta( $cat->term_id, 'thumbnail_id', true ); 
          $image = null;

          if(in_array($terms[0]->term_id, $keys)) continue;

          if( $attachment_id){

            $logo_url = wp_get_attachment_url( $attachment_id  );

            $attachment = get_post( $attachment_id );

            $image = array(
                'id' =>  (int)$attachment_id,
                'src' =>  $logo_url,
                'name' => $attachment->post_title,
                'alt' =>  $attachment->post_title,
            );
            
          }

          $keys[] = $terms[0]->term_id;

          $categories[] = array(
            "id"    =>  $cat->term_id,
            "name"  =>   $cat->name,
            "slug"  =>   $cat->slug,
            "image"  =>  $image,
          );
        
        }
    }
    return $categories;
}



function dv_get_product_attributes_by_brand_id($brand_id) {

  $product_ids = get_posts( 
    array(
    'post_type' => 'product',
    'numberposts' => -1,
    'post_status' => 'publish',
    'fields' => 'ids',
    'tax_query' => array(
        array(
          'taxonomy' => 'product_brand',
          'field'    => 'term_id',
          'terms'     =>  $brand_id,
          'operator'  => 'IN'
            )
         ),
     )
  );

  $attribute_taxonomies = wc_get_attribute_taxonomies(); 
  $attributes = array();

  foreach($attribute_taxonomies as $tax){

    $attr = array(
     'id' =>  (int)$tax->attribute_id,
     'name' =>  $tax->attribute_label,
     'slug' =>  $tax->attribute_name,
     'type' =>  $tax->attribute_type,
     'orderBy' =>  $tax->attribute_orderby,
     'taxonomy' =>  'pa_'.$tax->attribute_name,
     'hasArchives' =>  false
    );
 
    $options = array();
    $keys = array(); 

    foreach ( $product_ids as $product_id ) {
      
      $product_terms = wc_get_product_terms($product_id, 'pa_'.$tax->attribute_name);
      
      if(count($product_terms) === 0) continue;
      
      if(in_array($product_terms[0]->term_id, $keys)) continue;

      $opt = array(
        'id'         => $product_terms[0]->term_id,
        'name'       => $product_terms[0]->name,
        'slug'       => $product_terms[0]->slug,
        'taxonomy'   => $product_terms[0]->taxonomy,
        'count'      => $product_terms[0]->count,
      );

      if($tax->attribute_type === "color"){
   
        $meta_color = get_term_meta( $product_terms[0]->term_id, '_attribute_color', true ); 

        $opt['meta_data'] = $meta_color;
      }

      $keys[] = $product_terms[0]->term_id;
      
      $options[] = $opt;
    

    }

    $attr['options'] = $options;
      $attributes[] = $attr;
  }
  
  return  $attributes;
  
}