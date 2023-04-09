<?php 

function dvu_products( $atts ) {
    $attr = shortcode_atts( array(
        'title'    => 'Sản phẩm',
        'link'      =>  '',
        'limit'     =>  6
    ), $atts );
   
    ob_start();

    $gallery = array(
        'post_type'			=>	'product',
        'posts_per_page'    =>  $attr['limit'],
        'post_status' => 'publish',
    );
    global $post;
    ?>
    

                <div class="scg__product section">
                    <div class="container">
                        <div class="scg__wrap__product">
                            <div class="section__header">
                                <div class="section__title">
                                    <h2 class="title text-white"><?php echo $attr['title'] ?></h2>
                                </div>
                                <div class="col-right">
                                    <a href="<?php echo $attr['link'] ?>" class="btn btn-link text-white btn__view__more">Xem tất cả <i class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                            <?php 
                                $query = new WP_Query( $gallery );
                                if($query->have_posts()):
                                    ?>
                            <div class="scg__product__list swiper-container">
                                   <div class="swiper-wrapper">
                                        <?php while($query->have_posts()): $query->the_post(); ?>

                                       


                                        <div class="swiper-slide product__box">
                                            <div class="product__inner">
                                                <a href="<?php the_permalink(); ?>" alt="<?php the_title() ?>">
                                                    <div class="product__image">

                                                        <?php 
                                                            if(has_post_thumbnail()):
                                                                the_post_thumbnail( 'large', ['class' => 'img-fluid', 'alt' => esc_attr( get_the_title( $post->ID ) )]);
                                                            endif;

                                                            $new = get_post_meta($post->ID, '_product_new', true);
                                                            if($new){
                                                                echo '<span class="badget"><span class="badget__label">Mới</span></span>';
                                                            }
                                                        ?>
                                                    </div>
                                                    <!--end-box-image-->
                                                    <div class="product__content">
                                                        <div class="header-box">
                                                            <div class="product-box-cat">
                                                                <?php 
                                                                            $terms = get_the_terms( get_the_ID(), 'category_product' );
                                                                            if($terms){
                                                                                $output = '';
                                                                                foreach($terms as $key => $term){
                                                                                
                                                                                    $term_name = $term->name;
                                                                                    $output .= '<span class="term-cat">' . $term_name . '</span>';
                                                                                } 
                                                                                echo $output;
                                                                            }
                                                                ?>
                                                            </div>
                                                            <h3 class="title"> <?php the_title(); ?> </h3>

                                                        </div>

                                                    </div>
                                                </a>
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
add_shortcode( 'products', 'dvu_products' );