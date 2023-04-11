<?php 
function dv_get_post_detail(WP_REST_Request $request) {
$post_id = $request['post_id'];
$slug = $request['slug'];
$postDetail = new stdClass();
$args = array(
  'post_type' => 'post',
  'name' => $slug
);
// Custom query. 
 $query = new WP_Query( $args );

 $post = $query->get_posts(); 

 if($post){
  $post = $post[0];
  $postDetail->id = $post->ID;
  $postDetail->createAt = $post->post_date;
  $postDetail->title = get_the_title( $post->ID );
  $postDetail->excerpt = get_the_excerpt( $post->ID );
  $postDetail->content = get_the_content( '', true, $post->ID );
  $postDetail->slug =  $post->post_name ;
  $postDetail->thumbnail = get_the_post_thumbnail_url( $post->ID);
  $postDetail->commentCount = $post->comment_count;
  $postDetail->categories = get_the_category( $post->ID );
  $postDetail->tags = get_the_tags( $post->ID );
  $author_id = get_post_field ('post_author', $post->ID);
  $postDetail->authorName = get_the_author_meta( 'nickname' , $author_id ); 

 }else{
  	$response->set_status( 400 );
 }

	$response = new WP_REST_Response( $slug );

	return $response;
}

add_action( 'rest_api_init', function () {
        register_rest_route( 'dv/v1', 'posts', array(
          'methods'  => WP_REST_Server::READABLE,
        'callback' => 'dv_get_post_detail',
    ) );
} );