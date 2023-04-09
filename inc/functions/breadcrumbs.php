<?php 
function custom_breadcrumbs() {

    

 // Settings

 $separator          = '/';

 $breadcrums_id      = 'breadcrumbs';

 $breadcrums_class   = 'breadcrumbs saigonhome-breadcrumb';

 $home_title         = 'Trang chủ';

   

 // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)

 $custom_taxonomy    = 'category_product';

    

 // Get the query & post information

 global $post,$wp_query;

    

 // Do not display on the homepage

 if ( !is_front_page() ) {

     // Build the breadcrums

     echo '<nav aria-label="breadcrumb" class="d-flex justify-content-between align-items-center product__breadcrumb"><ol class="breadcrumb" id="' . $breadcrums_id . '" itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb ' . $breadcrums_class . '">';

     // Home page

     echo '<li class="item-home breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a class="dv-bread-link bread-home" itemprop="item" href="' . get_home_url() . '" title="' . $home_title . '"><span itemprop="name">' . $home_title . '</span></a><meta itemprop="position" content="1"></li>';


        
     if ( is_archive() && !is_tax() && !is_category() && !is_tag() && !is_post_type_archive('product') && !is_post_type_archive('download') && !is_post_type_archive('gallery') ) {

           
         echo '<li class="item-current breadcrumb-item item-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-archive" itemprop="name">' . post_type_archive_title($prefix, false) . '</span><meta itemprop="position" content="2"></li>';
     }
    else if(is_post_type_archive('product') ){
        $post_type = get_post_type_object( 'product' );
   
        echo '<li class="item-current breadcrumb-item"><span class="bread-current bread-archive">' .$post_type->label. '</span></li>';
    }  
    else if(is_post_type_archive('download') ){
        $post_type = get_post_type_object( 'download' );
   
        echo '<li class="item-current breadcrumb-item"><span class="bread-current bread-archive">' .$post_type->label. '</span></li>';
    }  
    else if(is_post_type_archive('gallery') ){
        $post_type = get_post_type_object( 'gallery' );
   
        echo '<li class="item-current breadcrumb-item"><span class="bread-current bread-archive">' .$post_type->label. '</span></li>';
    }  
    
    else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {

           
       
         // If post is a custom post type

         $post_type = get_post_type();

           

         // If it is a custom post type display name and link

         if($post_type && $post_type != 'post') {

             $post_type_object = get_post_type_object($post_type);

             $post_type_archive = get_post_type_archive_link($post_type);
     

             echo '<li class="item-cat breadcrumb-item item-custom-post-type-' . $post_type . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '"><span itemprop="name">' . $post_type_object->labels->name . '</span></a><meta itemprop="position" content="2"></li>';

         }

           

         $custom_tax_name = get_queried_object()->name;

         echo '<li class="item-current breadcrumb-item item-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-archive"  itemprop="name">' . $custom_tax_name . '</span><meta itemprop="position" content="3"></li>';

           

    } else if ( is_single() ) {

           

         // If post is a custom post type

         $post_type = get_post_type();

         // If it is a custom post type display name and link
       
         if($post_type != 'post') {

             $post_type_object = get_post_type_object($post_type);
             $post_type_archive = get_post_type_archive_link($post_type);
             echo '<li class="item-cat breadcrumb-item item-custom-post-type-' . $post_type . '"  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '"><span itemprop="name">' . $post_type_object->labels->name . '</span></a></li>';

         }
       
         // Get post category info

        $category = get_the_category();
 
        if(!empty($category)) {

             // Get last category post is in

             $last_category = end($category);

             // Get parent any categories and create array

             $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');

             $cat_parents = explode(',',$get_cat_parents);

             // Loop through parent categories and store in variable $cat_display

             $cat_display = '';

            foreach($category as $key => $cat){
               
                $link = get_term_link( $cat->term_id);

                $cat_display .= '<li class="item-cat breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="'.$link.'" itemprop="item"><span itemprop="name">'.$cat->name.'</span></a><meta itemprop="position" content="2"></li>';

            }
            echo $cat_display;
            echo '<li class="item-current breadcrumb-item item-' . $post->ID . '" itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '" itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="2"></li>';


        }
        $taxonomy_terms = null;

        if($post_type == 'product'){
            $taxonomy_terms = get_the_terms( $post->ID, 'category_product' );
        }else if($post_type == 'download'){
            $taxonomy_terms = get_the_terms( $post->ID, 'category_download' );
        }else if($post_type == 'gallery'){
            $taxonomy_terms = get_the_terms( $post->ID, 'category_gallery' );
        }
           
   
        if($taxonomy_terms) {
               
            
            foreach($taxonomy_terms as $key => $taxonomy_term){
                $cat_id         = $taxonomy_term->term_id;
                $cat_nicename   = $taxonomy_term->slug;
                $cat_name       = $taxonomy_term->name;

                if($post_type == 'product'){
                    $cat_link       = get_term_link($taxonomy_term->term_id, 'category_product');
                }else if($post_type == 'download'){
                    $cat_link       = get_term_link($taxonomy_term->term_id, 'category_download');
                
                }else if($post_type == 'gallery'){
                    $cat_link       = get_term_link($taxonomy_term->term_id, 'category_gallery');
                }

                echo '<li class="item-cat breadcrumb-item item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '" itemscope itemtype="http://schema.org/ListItem"><a itemscope="item" class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '"><span itemscope="name">' . $cat_name . '</span></a><meta itemprop="position" content="2"></li>';
            }
            echo '<li class="item-current breadcrumb-item item-' . $post->ID . '" itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '"  itemprop="name">' . get_the_title() . '</span><meta itemprop="position" content="3"></li>';

            
        }
     
           

     } else if ( is_category() ) {

            

        //  Category page

         $category = get_queried_object();

         $cateid =  $category->term_id;

         $parentcat = $category->parent;



         if($parentcat != '0'){

            $pCat = get_category($parentcat);

            $pName = $pCat->name;

            $pID = $pCat->term_id;

            $cate_parent_link = get_term_link( $pCat  );

                

            echo '<li class="item-current breadcrumb-item item-cat" itemscope itemtype="http://schema.org/ListItem"><a class="bread-cat bread-cat-' . $pID . '" href="' . $cate_parent_link . '" title="' . $pName . '" itemscope="item"><span itemscope="name">' . $pName . '</span></a><meta itemprop="position" content="2"></li>';


            echo '<li class="item-current breadcrumb-item item-cat" itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-cat"  itemscope="name">' . single_cat_title('', false) . '</span><meta itemprop="position" content="3"></li>';

            }

         else if($parentcat == '0'){

            echo '<li class="item-current breadcrumb-item item-cat"  itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-cat"  itemscope="name">' . single_cat_title('', false) . '</span><meta itemprop="position" content="3"></li>';

         }



     } else if ( is_page() ) {

            

         // Standard page

         if( $post->post_parent ){

             // If child page, get parents 

             $anc = get_post_ancestors( $post->ID );

                
             // Get parents in the right order

             $anc = array_reverse($anc);

                

             // Parent page loop

            if ( !isset( $parents ) ) $parents = null;


            $i = 1;
            foreach ( $anc as $ancestor ) {
                $i++;
                 $parents .= '<li class="item-parent breadcrumb-item item-parent-' . $ancestor . '" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a><meta itemprop="position" content="'.$i.'"></li>';

              
             }
             // Display parent pages

             echo $parents;

                

             // Current page

             echo '<li class="item-current breadcrumb-item item-' . $post->ID . '" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name" title="' . get_the_title() . '"> ' . get_the_title() . '</span><meta itemprop="position" content="'.$i.'"></li>';

                

         } else {


             // Just display current page if not parents

             echo '<li class="item-current breadcrumb-item item-' . $post->ID . '" itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-' . $post->ID . '" itemscope="name"> ' . get_the_title() . '</span><meta itemprop="position" content="2"></li>';
    

         }
   

     } else if ( is_tag() ) {


        $term_id        = get_query_var('tag_id');

        $taxonomy       = 'post_tag';
        $term  = get_term_by( 'id', $term_id, $taxonomy);
        $term_link = get_term_link($term_id, $taxonomy);

        $term_name  = $term->name;
  
            

         // Display the tag name

        echo '<li class="item-current breadcrumb-item item-tag-' . $term_id . '" itemscope itemtype="http://schema.org/ListItem"><span class="bread-current bread-tag-' . $term_id . '" itemscope="name">' . $term_name . '</span><meta itemprop="position" content="2"></li>';

        

     } elseif ( is_day() ) {

            

         // Day archive

            

         // Year link

         echo '<li class="item-year breadcrumb-item item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';


            

         // Month link

         echo '<li class="item-month breadcrumb-item item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';


            

         // Day display

         echo '<li class="item-current item-' . get_the_time('j') . '"><span class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</span></li>';

            

     } else if ( is_month() ) {

            

         // Month Archive

            

         // Year link

         echo '<li class="item-year breadcrumb-item item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';

            

         // Month display

         echo '<li class="item-month breadcrumb-item item-month-' . get_the_time('m') . '"><span class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</span></li>';

            

     } else if ( is_year() ) {

            

         // Display year archive

         echo '<li class="item-current breadcrumb-item item-current-' . get_the_time('Y') . '"><span class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</span></li>';

            

     } else if ( is_author() ) {

            

         // Auhor archive

            

         // Get the author information

         global $author;

         $userdata = get_userdata( $author );

            

         // Display author name

         echo '<li class="item-current breadcrumb-item item-current-' . $userdata->user_nicename . '"><span class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</span></li>';

        

     } else if ( get_query_var('paged') ) {

            

         // Paginated archives

         echo '<li class="item-current breadcrumb-item item-current-' . get_query_var('paged') . '"><span class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</span></li>';

            

     } else if ( is_search() ) {

        

         // Search results page

         echo '<li class="item-current breadcrumb-item item-current-' . get_search_query() . '"><span class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</span></li>';

        

     } elseif ( is_404() ) {

            

         // 404 page

         echo '<li class="item-current breadcrumb-item">' . 'Error 404' . '</li>';

     } elseif(is_home()){

         echo '<li class="item-current breadcrumb-item">' .'<span>' . single_post_title( '', false ).'</span>';

     }

    

    echo '</ol>';
     
    if(is_single()){
        $post_type = get_post_type();
       
        if($post_type === 'product'){
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
         
            if(!empty($custom_taxonomy) && $taxonomy_exists) {

                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );

                if($taxonomy_terms){
                    $cat_id         = $taxonomy_terms[0]->term_id;
                    $cat_nicename   = $taxonomy_terms[0]->slug;
                    $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                    $cat_name       = $taxonomy_terms[0]->name;
                }
             

            }
            
           

            // Check if the post is in a category


            if(!empty($cat_id)) {
                ?>
                <span class="nav__back">
                    <a href="<?php echo $cat_link ?>" title="<?php echo $cat_name ?>"><i class="fas fa-chevron-left mr-3"></i> Quay lại</a>
                </span>
                <?php 
            
            }
        }
    }
     
     
     
    echo '</nav>';

        

 }

    

}