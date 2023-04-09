<?php 
function dv_get_all_post(WP_REST_Request $request) {

  $per_page = $request['per_page'] ? (int)$request['per_page'] : 10;
  $page = $request['page'] ? (int)$request['page'] : 1;
  $slug = $request['slug'];
  $offset = ($page - 1) * $per_page;

  $count_posts = wp_count_posts($post_type = 'post')->publish;

  $args = array(
    'post_type'           => 'post',
    'posts_per_page'      => $per_page,
    'offset'              => $offset,
    'post_status'         => "publish",
    'orderby'             => 'date',
		'order'                => 'DESC',
    'name'                => $slug,
  );
  $query = new WP_Query( $args );

  $posts = $query->get_posts(); 
  // $posts = get_posts($args);
  $postsData = [];
  
  if ( $posts ) {
    for ($i = 0; $i < count($posts); $i++) {
      $postsData[$i]['id'] = $posts[$i]->ID;
      $postsData[$i]['createAt'] = $posts[$i]->post_date;
      $postsData[$i]['title'] = get_the_title($posts[$i]->ID);
      $postsData[$i]['excerpt'] = get_the_excerpt($posts[$i]->ID);
      $postsData[$i]['slug'] =  $posts[$i]->post_name;
      $postsData[$i]['thumbnail'] = get_the_post_thumbnail_url($posts[$i]->ID);
      $postsData[$i]['commentCount'] = $posts[$i]->comment_count;
      $postsData[$i]['categories'] = get_the_category($posts[$i]->ID);
      $postsData[$i]['tags'] = get_the_tags($posts[$i]->ID);
      $postsData[$i]['content'] = get_the_content(null, true, $posts[$i]->ID );
      $author_id = get_post_field ('post_author', $posts[$i]->ID);
      $postsData[$i]['authorName'] = get_the_author_meta('nickname', $author_id); 
    }

  };
  if($slug){
    $output['data'] = count($postsData) > 0 ? $postsData[0]: $postsData;
  }else{
    $output['posts'] = $postsData;
    $output['perPage'] = $per_page;
    $output['page'] = $page;
    $output['total'] = (int)$count_posts;
  }
  
 

	$response = new WP_REST_Response( $output );



	return $response;
}

add_action( 'rest_api_init', function () {
        register_rest_route( 'dv/v1', 'posts', array(
        'methods' => 'GET',
        'callback' => 'dv_get_all_post',
    ) );
} );