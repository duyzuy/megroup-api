<?php 



add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'brands', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => 'wc_get_product_brands',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
            return true;
        },
    ) );

    register_rest_route( 'dv/v1', 'brand/(?P<slug>[a-z0-9]+(?:-[a-z0-9]+)*)', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_brand_detail',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );

      register_rest_route( 'dv/v1', 'brand/(?P<brand_id>[0-9]*)/categories', array(
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'wc_get_product_categories_by_brand_id',
        'permission_callback' =>  function ( WP_REST_Request $request ) {
              return true;
          },
      ) );

});


function wc_get_product_brands(WP_REST_Request $request) {

    $terms = get_terms([
        'taxonomy' => "product_brand",
        'orderby'    => 'name',
		'order'      => 'ASC',
        'hide_empty' => false,
    ]);

    $output = array();
    foreach( $terms as $term){

        $brand_attachment_id = get_term_meta( $term->term_id, '_brand_logo', true ); 
        $image = null;
        if($brand_attachment_id){
            $image_src =  wp_get_attachment_url($brand_attachment_id);
    
            $attachment = get_post( $brand_attachment_id );
            $image = array(
                'id' =>  (int)$brand_attachment_id,
                'src' =>  $image_src,
                'name' => $attachment->post_title,
                'alt' =>  $attachment->post_title,
            );
    
        }
        $data = array(
            "id"    =>  $term->term_id,
            "name"  =>   $term->name,
            "slug"  =>   $term->slug,
            "count"  =>   $term->count,
            "image"  =>  $image,
        );

        $output[] = $data;
    }

  return new WP_REST_Response($output);
}


function wc_get_brand_detail(WP_REST_Request $request) {

    $slug = $request["slug"];

    $error = new WP_Error();
    
    if(!isset($slug)){
        $error->add("slug_invalid", "slug_invalid", array("status" => 400));
        return $error;
    }
    $term = get_term_by("slug", $slug, "product_brand");
    
    if(!$term){
        $error->add("not_found", "not_found", array("status" => 404));
        return $error;
    }

  
    
    $output = array();

    $brand_attachment_id = get_term_meta( $term->term_id, '_brand_logo', true ); 
    $image = null;
    if($brand_attachment_id){
        $image_src =  wp_get_attachment_url($brand_attachment_id);

        $attachment = get_post( $brand_attachment_id );
        $image = array(
            'id' =>  (int)$brand_attachment_id,
            'src' =>  $image_src,
            'name' => $attachment->post_title,
            'alt' =>  $attachment->post_title,
        );

    }


    $data = array(
        "id"    =>  $term->term_id,
        "name"  =>   $term->name,
        "slug"  =>   $term->slug,
        "count"  =>   $term->count,
        "description"  =>   $term->description,
        "image"  =>  $image,
    );

    $categories = dv_get_product_categories_by_brand_id($term->term_id);

    $data['categories'] = $categories;

    $attributes = dv_get_product_attributes_by_brand_id($term->term_id);

    $data['attributes'] = $attributes;


  return new WP_REST_Response($data);
}


function wc_get_product_categories_by_brand_id(WP_REST_Request $request) {

    $brand_id = $request['brand_id'] ? (int)$request['brand_id'] : 0;

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

    $categories = array();
    $keys = array();
    foreach ( $product_ids as $id ) {
        
        $product_categories = wc_get_product_terms($id,'product_cat');

        if(count($product_categories) === 0) continue;

        if(in_array($product_categories[0]->term_id, $keys)) continue;


        $thumbnail_id = get_term_meta($product_categories[0]->term_id, "thumbnail_id", true);

        $image = array();
        if($thumbnail_id !== "0"){
            $image_src =  wp_get_attachment_url($thumbnail_id);
            $attachment = get_post( $thumbnail_id );
            $image = array(
                'id' =>  (int)$thumbnail_id,
                'src' =>  $image_src,
                'name' => $attachment->post_title,
                'alt' =>  $attachment->post_title,
            );
          
        }else{
            $image = null;
        }

        $categories[] = array(
            "id"            =>  $product_categories[0]->term_id,
            "name"          =>  $product_categories[0]->name,
            "slug"          =>  $product_categories[0]->slug,
            "count"          =>  $product_categories[0]->count,
            "image"          => $image
        );

       
        $keys[] = $product_categories[0]->term_id;
    }

    
    return  new WP_REST_Response(  $categories );
        
  }
  
  