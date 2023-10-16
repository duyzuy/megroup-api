<?php 
function dv_get_post_by_id (int $post_id) {



  $output = array(
      'id'=> $post_id,
      'title'=> get_the_title($post_id),
      'excerpt'=>  $post->post_excerpt,
      'slug'=>  $post->post_name,
      'thumbnail'=> get_the_post_thumbnail_url($post_id),
      'content'=> get_the_content(null, true, $post_id ),
      'created_date'=> $post->post_date,
  );


    $tags = get_the_tags($post_id);
    $output['tags'] = array();

    if( isset($tags) ){

      foreach($tags as $tag){

        $output['tags'][] = array(
          "id"      =>  $tag->term_id,
          "name"    =>  $tag->name,
          "slug"    =>  $tag->slug,
          "count"   =>  $tag->count,
        );
      }

    }
    
    $categories = get_the_category($post->ID);
    $tax_query = array();
    $output['categories'] = array();

    if( isset( $categories) ){
      
      $tax_query['relation'] = 'OR' ;

      foreach($categories as $cat) {
        
        $output['categories'][] = array(
          'id'      =>  $cat->term_id,
          "name"    =>  $cat->name,
          "slug"    =>  $cat->slug,
          "count"   =>  $cat->count,
        );

        $tax_query[] = array(
          'taxonomy'    => 'category',
          'field'       => 'slug',
          'terms'       => $cat->slug,
        );
      };
    }
    /**
     * related post
     */

      $args_related = array( 
          'post_type'       =>  'post',
          'posts_per_page'  =>  1,
          "orderby"         =>  "date",
          "order"           =>  "desc",
          'post_status'     =>  "publish",
          'tax_query'       =>  $tax_query 
      );

    $loop = new WP_Query($args_related);

    $output['related'] = array();
    if( $loop->have_posts() ) {
      while( $loop->have_posts() ) : $loop->the_post(); 
       
        $post = get_post( get_the_ID() );

        $cats = get_the_category(get_the_ID());

        $output['related'][] = array(
          'id'=>  $post->ID,
          'title'=>  $post->post_title,
          'excerpt'=>  $post->post_excerpt,
          'slug'=> $post->post_name,
          'thumbnail'=> get_the_post_thumbnail_url(get_the_ID()),
          'created_date'=> $post->post_date,
          "cats"  =>  $cats
        );

      endwhile;
      wp_reset_query();
    }
     


    $author_id = get_post_field ('post_author', $post_id);
    $user = get_user_by( 'id', $author_id );

    
    $output['author'] = array(
      "nickname"  => get_the_author_meta('nickname', $author_id),
      "avt_url"        =>   get_avatar_url($author_id, ['size' => '80']),
      "first_name" =>  get_the_author_meta('user_firstname', $author_id),
      "last_name" =>  get_the_author_meta('user_lastname', $author_id),
  
    ); 

}