<?php 


add_action( 'rest_api_init', function () {
    register_rest_route( 'dv/v1', 'slider', array(
      'methods' => 'GET',
      'callback' => 'dv_get_all_slider',
      'permission_callback' =>  function ( WP_REST_Request $request ) {
        return true;
        },
    ) );
} );


function dv_get_all_slider(WP_REST_Request $request) {

  $taxonomy_id = $request['slider_id'] ? (int)$request['slider_id'] : 0;

  $args = array(
    'post_type'           => 'slider',
    'posts_per_page'      => 9,
    'post_status'         => "publish",
    'orderby'             => 'date',
		'order'                => 'DESC',
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
  $output = array();
  if(count( $posts) > 0 ){

    foreach ($posts as $post) {

      $data = array(
       "id" => $post->ID,
       "created_date" => $post->post_date,
       "title" => get_the_title($post->ID),
       "excerpt" => get_the_excerpt($post->ID),
      );
      $slide = get_post_meta( $post->ID, "_slide_options", true );
      $img_mobile = isset($slide['mobile']['url']) ? wp_get_attachment_url( $slide['mobile']['url'] ) : "";

      $img_desktop = isset($slide['desktop']['url']) ? wp_get_attachment_url( $slide['desktop']['url'] ) : "";


      $data['is_show'] = $slide['is_show'];
      $data['desktop_thumbnail'] = $img_mobile;
      $data['mobile_thumbnail'] = $img_desktop;
      $data['slider_link'] = $slide['link'];

      $output[] = $data;
    }

  }
  return  new WP_REST_Response( $output );

}
