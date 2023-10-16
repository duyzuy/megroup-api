<?php 

add_action('rest_api_init', 'wp_rest_product_category_endpoints');
function wp_rest_product_category_endpoints($request) {
    register_rest_route('dv/v1', 'product-categories', array(
        'methods' =>  WP_REST_Server::READABLE,
        'callback' => 'get_product_category_woocommerce',
        'permission_callback' => '__return_true'
    ));

    register_rest_route('dv/v1', 'product-category/(?P<slug>[a-z0-9]+(?:-[a-z0-9]+)*)', array(
        'methods' =>  WP_REST_Server::READABLE,
        'callback' => 'get_product_category_detail_woocommerce',
        'permission_callback' => '__return_true'
    ));
  
}

function get_product_category_woocommerce(WP_REST_Request $request) {

    $nested = $request['nested'] ? $request['nested'] : 10;
    $hide_empty = $request['hide_empty'] ? $request['hide_empty'] : 1;


    $args = array(
        'taxonomy'     => "product_cat",
        'orderby'      => "name",
        'show_count'   => true,
        'pad_counts'   => true,
        'hierarchical' => true,
        'hide_empty'   => $hide_empty,
    );

  
    $categories = get_categories( $args );
    $output = array();
    foreach ($categories as $cat) {

        // $metadata = get_term_meta( $cat->term_id, 'thumbnail_id', true  );
        $thumbnail_id = get_woocommerce_term_meta($cat->term_id, 'thumbnail_id', true);

        $image = null;
        if($thumbnail_id !== "0"){
            $image_src = wp_get_attachment_url($thumbnail_id);
            $attachment = get_post( $thumbnail_id );

            $image = array(
                'id' =>  (int)$thumbnail_id,
                'src' =>  $image_src,
                'name' => $attachment->post_title,
                'alt' =>  $attachment->post_title,
            );
        }
       
        $order = get_term_meta($cat->term_id, 'order', true);

        $display_type = get_term_meta($cat->term_id, 'display_type', true) ? get_term_meta($cat->term_id, 'display_type', true) : "default";
     
 
        array_push(
            $output,
            array(
                'id'            => $cat->term_id,
                'name'          => $cat->name,
                'slug'          => $cat->slug,
                "image"         =>  $image,
                "parent"        =>  $cat->parent,
                'count'         => $cat->count,
                "menu_order"    =>   (int)$order,
                "display_type"  => $display_type,
            )
        );
    }
    wp_reset_postdata();

  return new WP_REST_Response($output);
}


function get_product_category_detail_woocommerce(WP_REST_Request $request) {

    $slug = $request['slug'];
    
    $error = new WP_Error();

    if(!isset($slug)){
        $error->add("not_found", __("not_found", 'wp-rest-category detail'), array('status' => 404));
        return $error;
    }

    $term = get_term_by('slug', $slug, "product_cat");

    if(!$term){
        $error->add("not_found", __("not_found", 'wp-rest-category detail'), array('status' => 404));
        return $error;
    }

    $term_children = get_terms('product_cat', array('child_of' => $term->term_id));
    $sub_cats = array();

    if($term_children){

        foreach(  $term_children as $child){
            $thumbnail_id = get_term_meta($child->term_id, 'thumbnail_id', true);

            $image = null;
            if($thumbnail_id !== '0'){
                $image_src =  wp_get_attachment_url($thumbnail_id);

                $attachment = get_post( $thumbnail_id );
                $image = array(
                    'id' =>  (int)$thumbnail_id,
                    'src' =>  $image_src,
                    'name' => $attachment->post_title,
                    'alt' =>  $attachment->post_title,
                );

            }

            $order = get_term_meta($child->term_id, 'order', true);

            $display_type = get_term_meta($child->term_id, 'display_type', true) ? get_term_meta($child->term_id, 'display_type', true) : "default";

            $sub_cats[] = array(
                'id'        =>  $child->term_id,
                'name'      =>  $child->name,
                'slug'      =>  $child->slug,
                'count'     =>  $child->count,
                'parent'    =>  $child->parent,
                "image"     =>  $image,
                "display_type"  =>  $display_type,
                "menu_order"       =>   (int)$order
            );
        }
    }

    $output = array(
        'id' => $term->term_id,
        'name' => $term->name,
        'slug' => $term->slug,
        'parent' => $term->parent,
        'description' => $term->description,
        'count' => $term->count,
        "sub_cats"   =>  $sub_cats

    );
    /**
     * hook helpers/woocommerce
     */
    $brands = dv_get_product_brands_by_category_id($term->term_id);
    
    $output['brands'] = $brands;

    /**
     * hook helpers/woocommerce
     */
    $attributes = dv_get_product_attributes_by_category_id($term->term_id);
    $output['attributes'] = $attributes;

    // $term_meta = get_term_meta( $term->term_id );
    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
    
    $image = null;
    if($thumbnail_id !== '0'){
        $image_src =  wp_get_attachment_url($thumbnail_id);
        $attachment = get_post( $thumbnail_id );
        $image = array(
            'id' =>  (int)$thumbnail_id,
            'src' =>  $image_src,
            'name' => $attachment->post_title,
            'alt' =>  $attachment->post_title,
        );
    }

    $output['image'] = $image;

    $order = get_term_meta($term->term_id, 'order', true);
    
    $output['menu_order'] = (int)$order;

    $display_type = get_term_meta($term->term_id, 'display_type', true) ? get_term_meta($term->term_id, 'display_type', true) : "default";

    $output['display_type'] = $display_type;
       
  return new WP_REST_Response( $output  );
}
