<?php



add_action( 'rest_api_init', function () {
  register_rest_route( 'dv/v1', 'product/attributes', array(
  'methods' => 'GET',
  'callback' => 'dv_get_all_attribute',
  'permission_callback' => '__return_true'
) );
} );

function dv_get_all_attribute(WP_REST_Request $request) {

  $category_id = $request['category_id'] ? (int)$request['category_id'] : 0;


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
  
  return  new WP_REST_Response( $attributes );
  
}
