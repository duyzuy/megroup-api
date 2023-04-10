<?php 


function getAll_navigation_menu(WP_REST_Request $request) {
    

  $menuId = $request['menu_id'] ? (int)$request['menu_id'] : 0;
 
   
	//$menuLocations = get_nav_menu_locations();

  $menuItems = wp_get_nav_menu_items($menuId);
	
  if(!$menuItems){
    $error = new WP_Error();
    if (empty($posts)) {
      $error->add(400, __("Data not found", 'wp-rest-menu'), array('status' => 400));
      return $error;
    }
  
  }

	$menu_parent = [];
  $itemWithoutParent = [];

  
  foreach($menuItems as $key => $item){
	  
    $image_id = get_post_meta( $item->ID, 'jt_hover_image', true );
	  $url_image = wp_get_attachment_image_src( $image_id , 'medium');

    if($url_image){
      $item->menu_thumbnail =  $url_image[0];
    }else{
      $item->menu_thumbnail =  '';
    }

    if( $item->menu_item_parent === "0"){
      array_push($menu_parent, $item);
    }else{
      array_push($itemWithoutParent, $item);
    }
  }

  for($i = 0; $i < count($menu_parent); $i++){

    $childItem = get_child_menu($menu_parent[$i]->ID, $itemWithoutParent);
    $menu_parent[$i]->child_items = $childItem;

  }

	// Create the response object
	$response = new WP_REST_Response( $menu_parent );

	return $response;
	
}

add_action( 'rest_api_init', function () {
        register_rest_route( 'dv/v1', 'menu', array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => 'getAll_navigation_menu',
      'permission_callback' => '__return_true'
			)
		);
	} 
);


function get_child_menu($item_id, $arr) {

  $child_item = [];

    foreach($arr as $key => $item){

      if((int)$item->menu_item_parent === (int)$item_id){

        $child_of_child = get_child_menu($item->ID, $arr);
        $item->child_items = $child_of_child;
        array_push($child_item, $item);
      }
    }

  return $child_item;
}