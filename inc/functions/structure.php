<?php 


function archive_title_custom(){
    if ( is_category() ) {
       /* translators: Category archive title. 1: Category name */
       $title = sprintf( __( 'Category: %s' ), single_cat_title( '', true ) );
   } elseif ( is_tag() ) {
       /* translators: Tag archive title. 1: Tag name */
       $title = sprintf( __( 'Tags: %s' ), single_tag_title( '', true ) );
   } elseif ( is_author() ) {
       /* translators: Author archive title. 1: Author name */
       $title = sprintf( __( 'Author: %s' ), '<span class="vcard">' . get_the_author() . '</span>' );
   }  elseif ( is_tax() ) {
       $title = single_term_title( '', true );
   }
   else {
       $title = __( 'Archives' );
       }
 
}
add_filter( 'get_the_archive_title', 'archive_title_custom');


function neotheme_except_post(){
    global $post;
    $post_excerpt = get_the_excerpt( $post->ID );
    $strln = strlen($post_excerpt);
    // $post_excerpt = substr($post_excerpt, 0, 80);
    echo wp_trim_words($post_excerpt, 26);


}


function single_share_post(){
    ?>
		<ul class="socials-shares">
                            
							<li class="js-social js-social-facebook facebook">
								<a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink()?>" rel="nofollow" target="_blank">
                                <i class="fab fa-facebook-f"></i>
								</a>
							</li>
							<li class="js-social js-social-twitter twitter">
								<a href="https://twitter.com/intent/tweet?text=<?php the_title() ?> &amp;url=<?php the_permalink() ?>" title="" rel="nofollow" target="_blank">
                                    <i class="fab fa-twitter"></i>
								</a>
							</li>
                            <li  class="js-social js-social-pinterest pinterest">
                                <a href="https://www.pinterest.com/pin/create/button/" rel="nofollow" target="_blank" data-pin-do="buttonBookmark" data-pin-custom="true" data-pin-count="above">
                                    <i class="fab fa-pinterest"></i>
                                </a>
                            </li>
                           
                            <li  class="js-social js-social-linkedin linkedin">
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink() ?>" rel="nofollow" target="_blank">
                                <i class="fab fa-linkedin-in"></i>
                                </a>
                            </li>
											
			</ul>
    <?php 
}

function single_post_meta(){
    global $post;
    $author_id= $post->post_author;
    ?>
		<span class="single-post-author">
		    <img src="<?php echo esc_url( get_avatar_url( $author_id ) ); ?>" width="30" height="30" class="avata circle" />
		    <span class="author-name"><?php the_author(); ?></span>
        </span>
        <span class="single-post-date"><i class="interior icon-watch"></i><?php the_date( get_option('date_format') ) ?></span>
        <!-- <span class="single-post-comment"><a href="#dv-comments"><i class="interior icon-comment"></i><span class="comment-number-count"><?php // printf( _nx( '1', '%1$s', get_comments_number(), 'comments title', 'dvtheme' ), number_format_i18n( get_comments_number() ) ); ?></span>bình luận</a></span> -->
						
        <?php
}

function dv_post_related(){
  
    $cates = get_the_category();
    foreach($cates as $cate){
        $cate_id = $cate->term_id;
    }
    $args = array(

        'post_type' => 'post',
        'posts_per_page' => 6,
        'cat'       => $cate_id,
        'post__not_in'  => array(get_the_ID()),
        
    );

    $loop_post = new WP_Query($args);

    if($loop_post->have_posts()):
        ?>
        <h4 class="h4 related-title">
            <?php esc_html_e( 'Bài viết khác', 'dvutemplate' ); ?>
    </h4>
        <div class="posts-related row">
        <?php
        while($loop_post->have_posts()): $loop_post->the_post(); ?>
        <div class="col-12 col-sm-6 col-md-4 article">
            <a href="<?php the_permalink() ?>">
                <div class="box-related">
                <?php
                    if(has_post_thumbnail() ):
                        the_post_thumbnail( 'large', ['class'   =>  'img-fluid', 'alt' =>  esc_attr( get_the_post_thumbnail_caption( get_the_ID() ) )] );
                    endif;
                    ?>
                    <div class="content">
                        <h3 class="title small"><?php the_title();?></h3>
                    </div>
                </div>
            </a>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
    ?>
            
</div>
    <?php
endif;


}