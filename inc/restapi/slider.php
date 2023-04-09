<?php 
function dv_get_all_slider(WP_REST_Request $request) {

  $per_page = $request['per_page'] ? (int)$request['per_page'] : 20;
  $page = $request['page'] ? (int)$request['page'] : 1;
  $slug = $request['slug'];
  $offset = ($page - 1) * $per_page;
  $count_posts = (int)wp_count_posts($post_type = 'slider')->publish;

  $taxonomy_id = $request['tax_id'] ? (int)$request['tax_id'] : 0;

  $args = array(
    'post_type'           => 'slider',
    'posts_per_page'      => $per_page,
    'offset'              => $offset,
    'post_status'         => "publish",
    'orderby'             => 'date',
		'order'                => 'DESC',
    'name'                => $slug,
    'tax_query'         => array( 'relation' => 'OR',
        array(
            'taxonomy' => 'group_slider',
            'field'    => 'term_id',
            'terms'    => array( $taxonomy_id ),
        ),)
  );
  $query = new WP_Query( $args );

  $posts = $query->get_posts(); 
  $error = new WP_Error();
  if (empty($posts)) {
    $error->add(400, __("Slider not found", 'wp-rest-slider'), array('status' => 400));
    return $error;
  }


  $postsData = [];
  $output = new stdClass();
  if ( $posts ) {
    for ($i = 0; $i < count($posts); $i++) {
      $postsData[$i]->id = $posts[$i]->ID;
      $postsData[$i]->createAt = $posts[$i]->post_date;
      $postsData[$i]->title = get_the_title($posts[$i]->ID);
      $postsData[$i]->excerpt = get_the_excerpt($posts[$i]->ID);
      $postsData[$i]->bannerLink = get_post_meta($posts[$i]->ID, 'duvu_link_slide', true);
      $postsData[$i]->thumbnail = get_the_post_thumbnail_url($posts[$i]->ID);
      $postsData[$i]->status = $posts[$id]->status;
    }

  };


  $response['status'] = 200;
  $response['statusText'] = __("Get data success", "wp-rest-slider");
  $response['data']['list']  = $postsData;
  $response['data']['perPage']  = $per_page;
  $response['data']['page']  = $page;
  $response['data']['total']  = $count_posts;


  return  new WP_REST_Response( $response );


}

add_action( 'rest_api_init', function () {
        register_rest_route( 'dv/v1', 'slider', array(
        'methods' => 'GET',
        'callback' => 'dv_get_all_slider',
    ) );
} );