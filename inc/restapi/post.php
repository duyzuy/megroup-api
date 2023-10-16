<?php 



add_action( 'rest_api_init', function () {
  register_rest_route( 'dv/v1', 'posts', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'dv_get_all_post',
    'permission_callback' =>  function ( WP_REST_Request $request ) {
      return true;
      },
  ) );

  register_rest_route( 'dv/v1', 'post/(?P<slug>[a-z0-9]+(?:-[a-z0-9]+)*)', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'dv_get_post_detail',
    'permission_callback' =>  function ( WP_REST_Request $request ) {
      return true;
      },
  ) );

  register_rest_route( 'dv/v1', 'posts/featured', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'dv_post_featured',
    'permission_callback' =>  function ( WP_REST_Request $request ) {
      return true;
      },
  ) );

  register_rest_route( 'dv/v1', 'category/posts/(?P<slug>[a-z0-9]+(?:-[a-z0-9]+)*)', array(
    'methods'  => WP_REST_Server::READABLE,
    'callback' => 'dv_get_posts_by_category_slug',
    'permission_callback' =>  function ( WP_REST_Request $request ) {
      return true;
      },
  ) );

} );

function dv_get_all_post(WP_REST_Request $request) {

  $per_page = $request['per_page'] ? (int)$request['per_page'] : 10;

  $page = $request['page'] ? (int)$request['page'] : 1;

  $offset = ($page - 1) * $per_page;

  $category_id =  $request['category_id'];

  $error = new WP_Error();
 
  if(!is_numeric($per_page) || !is_numeric($page)){
    $error->add("invalid", "invalid", array("status" => 400));
    return $error;
  };


  if($per_page > 99){
    $error->add("limitation_item", "limitation_item", array("status" => 400));
    return $error;
  };

  $count_posts = wp_count_posts($post_type = 'post')->publish;

  $args = array(
    'post_type'           => 'post',
    'posts_per_page'      => $per_page,
    'offset'              => $offset,
    'post_status'         => "publish",
    'orderby'             => 'date',
		'order'               => 'DESC',
  );
  if(isset($category_id)){
    $args["category__in"] = $category_id;
  }


  $query = new WP_Query( $args );
  $posts = $query->get_posts(); 

  if ( count($posts) === 0 ){

    $output['list'] = null;

  } else{

    foreach ($posts as $post) {
      
      $post_id = $post->ID;

      $data = array(
        'id'=> $post_id,
        'created_date'=> $post->post_date,
        'title'=> get_the_title($post_id),
        'slug'=>  $post->post_name,
        'thumbnail'=> get_the_post_thumbnail_url($post_id) ? get_the_post_thumbnail_url($post_id) : "",
        'excerpt'=>  $post->post_excerpt,
      );

      $tags = get_the_tags($post_id);


      if($tags){

        foreach($tags as $tag){

          $data['tags'][] = array(
            "id"      =>  $tag->term_id,
            "name"    =>  $tag->name,
            "slug"    =>  $tag->slug,
            "count"   =>  $tag->count,
            "parent"  =>  $tag->parent
          );
        }

      }else{

        $data['tags'] = [];
        
      }
      
      $categories = get_the_category($post->ID);

      foreach($categories as $cat) {
        
        $data['categories'][] = array(
          'id' =>  $cat->term_id,
          "name" => $cat->name,
          "slug" => $cat->slug
        );
      };
    
   

      $author_id = get_post_field ('post_author', $post_id);

      $user = get_user_by( 'id', $author_id );
      
      $data['author'] = array(
        "nickname"  => get_the_author_meta('nickname', $author_id),
        "first_name" =>  get_the_author_meta('user_firstname', $author_id),
        "last_name" =>  get_the_author_meta('user_lastname', $author_id)
      ); 
    


      $output['list'][] = $data;

    }
  }
  
  $output['per_page'] = $per_page;
  $output['total_page'] = ceil($count_posts/$per_page);
  $output['page'] = $page;
  $output['total'] = (int)$count_posts;

	return new WP_REST_Response( $output );
}



function dv_get_post_detail(WP_REST_Request $request) {


  $slug = $request['slug'];

  $error = new WP_Error();

  if(!isset($slug)){
    $error->add("slug_empty", "slug_empty", array("status" => 400));
    return $error;
  };

  $args = array(
    'post_type'           => 'post',
    'posts_per_page'      => 1,
    'post_status'         => "publish",
    'name'                => $slug,
  );

  $query = new WP_Query( $args );

  $posts = $query->get_posts(); 

  if(count($posts) === 0){
    $error->add("post_not_found", "post_not_found", array("status" => 404));
    return $error;
  }

  $post = $posts[0];
  


  $post_id = $post->ID;

  $output = array(
      'id'=> $post_id,
      'created_date'=> $post->post_date,
      'title'=> get_the_title($post_id),
      'excerpt'=>  $post->post_excerpt,
      'slug'=>  $post->post_name,
      'thumbnail'=> get_the_post_thumbnail_url($post_id),
      'content'=> get_the_content(null, true, $post_id ),
     
  );

    $tags = get_the_tags($post_id);
    $output['tags'] = array();
    if($tags){

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
          'posts_per_page'  =>  4,
          "orderby"         =>  "date",
          "order"           =>  "desc",
          'post_status'     =>  "publish",
          'tax_query'       =>  $tax_query 
      );

    $loop = new WP_Query($args_related);

    $output['posts_related'] = array();
    if( $loop->have_posts() ) {
      while( $loop->have_posts() ) : $loop->the_post(); 
       
        $post = get_post( get_the_ID() );

        $post_cats = get_the_category(get_the_ID());
        $p_cats = array();
        if( isset( $post_cats) ){
      
          foreach($post_cats as $cat) {
            
            $p_cats[] = array(
              'id'      =>  $cat->term_id,
              "name"    =>  $cat->name,
              "slug"    =>  $cat->slug,
              "count"   =>  $cat->count,
            );
    
          };
        }


        
        $post_tags = get_the_tags(get_the_ID());
        $p_tags = array();
        if($post_tags){
          foreach($post_tags as $post_tag){

            $p_tags[] = array(
              "id"      =>  $tag->term_id,
              "name"    =>  $tag->name,
              "slug"    =>  $tag->slug,
              "count"   =>  $tag->count,
            );
          }

        }




        $output['posts_related'][] = array(
          'id'=>  $post->ID,
          'title'=>  $post->post_title,
          'excerpt'=>  $post->post_excerpt,
          'slug'=> $post->post_name,
          'thumbnail'=> get_the_post_thumbnail_url(get_the_ID()),
          'created_date'=> $post->post_date,
          "tags"  =>  $p_tags,
          "categories"  =>  $p_cats
        );


      endwhile;
      wp_reset_query();
    }
     


    $author_id = get_post_field ('post_author', $post_id);
    $user = get_user_by( 'id', $author_id );

    
    $output['author'] = array(
      "nickname"        =>  get_the_author_meta('nickname', $author_id),
      "first_name"      =>  get_the_author_meta('user_firstname', $author_id),
      "last_name"       =>  get_the_author_meta('user_lastname', $author_id),
  
    ); 
   
	return new WP_REST_Response( $output );
}


function dv_post_featured(WP_REST_Request $request) {

  $args = array(
    'post_type'           => 'post',
    'posts_per_page'      => 12,
    'post_status'         => "publish",
    'meta_query' =>  array(
      array(
        'key' => 'featured',
        'value' => 1
      )
    )
  );

  $query = new WP_Query( $args );
  $posts = $query->get_posts(); 

  if ( count($posts) === 0 ){

    $output = null;

  } else{

    foreach ($posts as $post) {
      
      $post_id = $post->ID;

      $data = array(
        'id'=> $post_id,
        'created_date'=> $post->post_date,
        'title'=> get_the_title($post_id),
        'excerpt'=> get_the_excerpt($post_id),
        'slug'=>  $post->post_name,
        'thumbnail'=> get_the_post_thumbnail_url($post_id) ? get_the_post_thumbnail_url($post_id) : "",
      );
    
      $tags = get_the_tags($post_id);

      if($tags){

        foreach($tags as $tag){

          $data['tags'][] = array(
            "id"      =>  $tag->term_id,
            "name"    =>  $tag->name,
            "slug"    =>  $tag->slug,
            "count"   =>  $tag->count,
            "parent"  =>  $tag->parent
          );
        }

      }else{

        $data['tags'] = [];
        
      }
      
      $categories = get_the_category($post->ID);

      foreach($categories as $cat) {
        
        $data['categories'][] = array(
          'id' =>  $cat->term_id,
          "name" => $cat->name,
          "slug" => $cat->slug
        );
      };
    
      $author_id = get_post_field ('post_author', $post_id);

      $user = get_user_by( 'id', $author_id );
      
      $data['author'] = array(
        "nickname"  => get_the_author_meta('nickname', $author_id),
        "first_name" =>  get_the_author_meta('user_firstname', $author_id),
        "last_name" =>  get_the_author_meta('user_lastname', $author_id)
      ); 
    
      $output[] = $data;

    }
  }

	return new WP_REST_Response( $output );
}



function dv_get_posts_by_category_slug(WP_REST_Request $request) {

  $per_page = $request['per_page'] ? (int)$request['per_page'] : 10;

  $page = $request['page'] ? (int)$request['page'] : 1;

  $offset = ($page - 1) * $per_page;

  $slug =  $request['slug'];

  $error = new WP_Error();
 
  if(!is_numeric($per_page) || !is_numeric($page)){
    $error->add("invalid", "invalid", array("status" => 400));
    return $error;
  };


  if($per_page > 30){
    $error->add("limitation_item", "limitation_item", array("status" => 400));
    return $error;
  };


  if(empty($slug)){
    $error->add("invalid_slug", "limitation_item", array("status" => 400));
    return $error;
  }

  $term = get_term_by("slug", $slug, "category");
  

  if(!$term){
    $error->add("category_notfound", "category_notfound", array("status" => 404));
    return $error;
  }

  $output = array(
    "id"  =>  $term->term_id,
    "name"    =>  $term->name,
    "slug"    =>  $term->slug,
    "description" =>  $term->description,
  );

  $count_posts = (int)$term->count;
  $thumbnail_id = get_term_meta( $term->term_id, "_p_tax_image", true);

  $output["thumbnail"] = null;
  if( $thumbnail_id){
    $output["thumbnail"] = wp_get_attachment_url( $thumbnail_id );
  }

  $args = array(
    'post_type'           => 'post',
    'posts_per_page'      => $per_page,
    "paged"               => $page,
    'offset'              => $offset,
    'post_status'         => "publish",
    'orderby'             => 'date',
		'order'               => 'DESC',
    "category_name"       => $slug
  );
  if(isset($category_id)){
    $args["category__in"] = $category_id;
  }


  $query = new WP_Query( $args );
  $posts = $query->get_posts(); 

  if ( count($posts) === 0 ){

    $output['list'] = null;

  } else{

    foreach ($posts as $post) {
      
      $post_id = $post->ID;

      $data = array(
        'id'=> $post_id,
        'created_date'=> $post->post_date,
        'title'=> get_the_title($post_id),
        'slug'=>  $post->post_name,
        'thumbnail'=> get_the_post_thumbnail_url($post_id) ? get_the_post_thumbnail_url($post_id) : "",
        'excerpt'=>  $post->post_excerpt,
      );

      $tags = get_the_tags($post_id);


      if($tags){

        foreach($tags as $tag){

          $data['tags'][] = array(
            "id"      =>  $tag->term_id,
            "name"    =>  $tag->name,
            "slug"    =>  $tag->slug,
            "count"   =>  $tag->count,
            "parent"  =>  $tag->parent
          );
        }

      }else{

        $data['tags'] = [];
        
      }
      
      $categories = get_the_category($post->ID);

      foreach($categories as $cat) {
        
        $data['categories'][] = array(
          'id' =>  $cat->term_id,
          "name" => $cat->name,
          "slug" => $cat->slug
        );
      };
    
   

      $author_id = get_post_field ('post_author', $post_id);

      $user = get_user_by( 'id', $author_id );
      
      $data['author'] = array(
        "nickname"  => get_the_author_meta('nickname', $author_id),
        "first_name" =>  get_the_author_meta('user_firstname', $author_id),
        "last_name" =>  get_the_author_meta('user_lastname', $author_id)
      ); 
    


      $output['list'][] = $data;
     

    }
  
  }

  $output['per_page'] = $per_page;
  $output['total_page'] = ceil($count_posts/$per_page);
  $output['page'] = $page;
  $output['total'] = $count_posts;

	return new WP_REST_Response( $output );
}

