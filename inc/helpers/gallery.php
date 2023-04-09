<?php 


function dvu_custom_postype_related(){
    global $post;
    $post_type = get_post_type( $post->ID );
    if($post_type == 'gallery'){
        $taxonomy = 'category_gallery';
        $custom_taxterms = wp_get_object_terms( $post->ID, $taxonomy, array('fields' => 'ids') );
    }
   
    $args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => 6, // you may edit this number
        'orderby' => 'rand',
        'post__not_in'  => array($post->ID),
        'tax_query' => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'ids',
                'terms' => $custom_taxterms
            )
        ),
    );
    $posts = new WP_Query($args);

    if($posts->have_posts()):
        ?>
        <ul class="posts-lists related-lists-post">
                <?php
                while($posts->have_posts()): $posts->the_post(); ?>
                    <li class="item">
                            <a href="<?php the_permalink() ?>">
                               
                                <?php
                                    if(has_post_thumbnail() ):
                                        the_post_thumbnail( 'thumbnail', ['class'   =>  'img-fluid', 'alt' =>  esc_attr( get_the_title() )] );
                                    endif;
                                ?>
                              
                                <div class="post_content">
                                    <time><?php echo get_the_date( get_option('date_format'), $post->ID ) ?></time>
                                    <h3 class="title h6"><?php the_title();?></h3>
                                    
                                </div>
                            </a>
                    </li>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
           </ul>
    <?php
    endif;
}

       