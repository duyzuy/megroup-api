<?php 

function dvu_gallery( $atts ) {
    $attr = shortcode_atts( array(
        'title'    => 'Thư viện hình ảnh',
        'link'      =>  '',
        'limit'     =>  6
    ), $atts );
   
    ob_start();

    $gallery = array(
        'post_type'			=>	'gallery',
        'posts_per_page'    =>  $attr['limit']
    );
    global $post;
    ?>
                <div class="scg__gallery section">
                    <div class="container">
                        <div class="scg__wrap__gallery">
                            <div class="section__header">
                                <div class="section__title">
                                    <h2 class="title"><?php echo $attr['title'] ?></h2>
                                </div>
                                <div class="col-right">
                                    <a href="<?php echo $attr['link'] ?>" class="btn btn-link btn__view__more">Xem tất cả <i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                            <?php 
                                $query = new WP_Query( $gallery );
                                if($query->have_posts()):
                                    ?>
                            <div class="scg__galleries swiper-container">
                                   <div class="swiper-wrapper">
                                        <?php while($query->have_posts()): $query->the_post(); ?>
                                        <div class="gallery__box swiper-slide">
                                            <div class="gallery__inner">
                                                <div class="box__image">
                                                <a href="<?php the_permalink(); ?>" alt="<?php the_title() ?>">
                                                    <?php 
                                                        if(has_post_thumbnail()):
                                                            the_post_thumbnail( 'large', ['class' => 'img-fluid', 'alt' => esc_attr( get_the_title( $post->ID ) )]);
                                                        endif;
                                                    ?>
                                                </a>
                                                </div>
                                                <div class="box__content">
                                                    <a href="<?php the_permalink(); ?>"><h3 class="title"><?php the_title() ?></h3></a>
                                                    <?php 
                                                        $terms = get_the_terms( get_the_ID(), 'tags_gallery' );
                                                        if($terms):
                                                            $output = '<div class="tags"><span class="label">Đánh dấu:</span>';
                                                            foreach($terms as $term){
                                                                $output .= '<span class="tag">'.$term->name.'</span>';
                                                            } 
                                                            $output .='</div>';
                                                            echo $output;
                                                        endif;
                                                    ?>
                                                 
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            endwhile;
                                            wp_reset_postdata();
                                      ?>

                                   </div> 
                            </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>

    <?php 
	return ob_get_clean();
}
add_shortcode( 'galleries', 'dvu_gallery' );