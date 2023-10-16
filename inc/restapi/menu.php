<?php 

add_action( 'rest_api_init', function () {
  register_rest_route( 'dv/v1', 'menu', array(
          'methods' => WP_REST_Server::READABLE,
          'callback' => 'getAll_navigation_menu',
          'permission_callback' => '__return_true'
        )
      );
    } 
);


function getAll_navigation_menu(WP_REST_Request $request) {
    

  $menuId = $request['menu_id'] ? (int)$request['menu_id'] : 6;
  $menuType = $request['menu_type'] ? (int)$request['menu_type'] : "primary";
 
   
	$menuLocations = get_nav_menu_locations();

  $menu_items = wp_get_nav_menu_items($menuId);

  // print_r($menuLocations);
  $error = new WP_Error();

  if(!$menu_items){

    $error->add("no_menu_items", __("no_menu_items", 'wp-rest-menu'), array('status' => 404));
      return $error;
  }

	$parent_items = array();
  $item_without_parent = array();

  
  foreach($menu_items as $key => $item){
	  
   

    $object_slug = "";
    $object_src = null;

    $menu_thumbnail = null;

    $attachment_id = get_post_meta( $item->ID, 'jt_hover_image', true );
	  
    if($attachment_id){

      $attachment_src = wp_get_attachment_url(  $attachment_id );
      $menu_thumbnail = $attachment_src;
    }


    if($item->object === "product_cat" && $item->type === "taxonomy"){
      $term = get_term_by("id", $item->object_id, "product_cat");

      $object_slug = $term->slug;

      $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);

      if($thumbnail_id !== '0'){
          $image_src =  wp_get_attachment_url($thumbnail_id);
          $object_src = $image_src;

      }
    }

    if($item->object === "product_brand" && $item->type === "taxonomy"){
      $term = get_term_by("id", $item->object_id, "product_brand");

      $object_slug = $term->slug;

      $thumbnail_id = get_term_meta($term->term_id, '_brand_logo', true);

      if($thumbnail_id !== '0'){
          $image_src =  wp_get_attachment_url($thumbnail_id);
          $object_src = $image_src;

      }
    }

    if($item->object === "category" && $item->type === "taxonomy"){
      $term = get_term_by("id", $item->object_id, "category");

      $object_slug = $term->slug;

      $thumbnail_id = get_term_meta($term->term_id, "_p_tax_image", true);
   
      if($thumbnail_id !== '0'){
          $image_src =  wp_get_attachment_url($thumbnail_id);
          $object_src = $image_src;
      }
    }


    if(($item->object === "product" && $item->type === "post_type") || $item->object === "post" && $item->type === "post_type"){
  

      $post = get_post( $item->object_id );

      $object_slug = $post->post_name;
      $thumbnail_id = get_post_thumbnail_id( $item->object_id );
      if($thumbnail_id){
        $object_src = wp_get_attachment_url($thumbnail_id);
      }

    }

    if( $item->menu_item_parent === "0"){
      array_push($parent_items, array(
        "id"                =>  $item->ID,
        "menu_order"        =>  $item->menu_order,
        "menu_item_parent"  =>  (int)$item->menu_item_parent,
        "title"             =>  $item->title,
        "type"              =>  $item->type,
        "type_label"        =>  $item->type_label,
        "description"       =>  $item->description,
        "object"            =>  $item->object,
        "object_id"         =>  (int)$item->object_id,
        "object_slug"       =>  $object_slug,
        "object_src"        =>  $object_src,
        "menu_thumbnail"    =>  $menu_thumbnail,
    
      ));
    }else{
      array_push($item_without_parent, array( 
        "id"                =>  $item->ID,
        "menu_order"        =>  $item->menu_order,
        "menu_item_parent"  =>  (int)$item->menu_item_parent,
        "title"             =>  $item->title,
        "type"              =>  $item->type,
        "type_label"        =>  $item->type_label,
        "description"       =>  $item->description,
        "object"            =>  $item->object,
        "object_id"         =>  (int)$item->object_id,
        "object_slug"       =>  $object_slug,
        "object_src"        =>  $object_src,
          "menu_thumbnail"    =>  $menu_thumbnail

    ));
    }
  }

  $count = 0;
  foreach($parent_items as $item_parent){

    $child_items = get_child_menu($item_parent['id'], $item_without_parent);
    $parent_items[$count]['child_items'] = $child_items;
    $count++;
  }

	// Create the response object
	return new WP_REST_Response( $parent_items );
	
}



function get_child_menu($item_id, $menu_items) {

  $child_item = [];

    foreach($menu_items as $key => $item){

      if($item['menu_item_parent'] === $item_id){

        $child_of_child = get_child_menu($item['id'], $menu_items);
        $item['child_items'] = $child_of_child;
        array_push($child_item, $item);
      }
    }

  return $child_item;
}
