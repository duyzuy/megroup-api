<?php 

function dvu_posts( $atts ) {
    $attr = shortcode_atts( array(
        'title'     => 'Ý tưởng kiến trúc',
        'link'      =>  '',
        'ids'       =>  '13,14,16,17',
        'default'   =>  '13'
    ), $atts );
   
    ob_start();

    global $post;
    ?>
                <div class="scg__ideas section">
                    <div class="container">
                        <div class="scg__wrap__ideas">
                            <div class="section__header">
                                <div class="header__title">
                                    <h2 class="title"><?php echo $attr['title'] ?></h2>
                                </div>
                                <div class="scg__right__col d-flex">
                                    <div class="wrap__tab d-none d-xl-block">
                                        <ul class="scg__tabs">
                                        <?php 

                                            $arr_ids = explode(',',$attr['ids']);
                                        
                                            foreach ($arr_ids as $key => $id){
                                                $category = get_term_by( 'ID', $id, 'category');
                                                ?>
                                                <li class="<?php echo $attr['default'] == $category->term_id ? 'active' : '' ?>"><a href="#" class="ajax_post" data-id="<?php echo $category->term_id ?>"><?php echo $category->name ?></a></li>
                                                <?php
                                            
                                            }
                                        ?>
                                           
                                        </ul>
                                    </div>
                                    <div class="view__link">
                                        <a href="#" class="btn btn-link btn__view__more">Xem tất cả <i class="fas fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="section__contents">
                                <div class="section__tab d-none">
                                        <ul class="tab tab__list">
                                            <?php 
                                                foreach ($arr_ids as $key => $id){
                                                    $category = get_term_by( 'ID', $id, 'category');
                                                    ?>
                                                    <li class="<?php echo $attr['default'] == $category->term_id ? 'active' : '' ?>"><a href="#" class="ajax_post" data-id="<?php echo $category->term_id ?>"><?php echo $category->name ?></a></li>
                                                    <?php
                                                }
                                            ?>
                                        </ul>
                                </div>
                                <div class="row__ideal">
                                <?php 
                                    $args = array(
                                    'post_type'     =>  'post',
                                    'cat'   =>  $attr['default'],
                                    'posts_per_page'     =>  5,
                                    'post_status' => 'publish',

                                );
                                $query = new WP_Query( $args );
                                if($query->have_posts()):

                                ?>
                                 <?php while($query->have_posts()): $query->the_post(); ?>
                                    <div class="box__idea">
                                        <div class="inner__box">
                                            <a href="<?php the_permalink(); ?>" alt="<?php the_title() ?>">
                                                <?php 
                                                    $url = '';
                                                        if(has_post_thumbnail()):
                                                            $url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                                                        endif;
                                                ?>
                                                        <div class="image" style="background-image: url('<?php echo $url ?>')"></div>
                                                        <div class="box__content">
                                                            <h3 class="title"><?php the_title() ?></h3>
                                                        </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile ?>
                                 <?php wp_reset_postdata(); ?>                       
                            <?php endif ?>
                                </div>
                                    <div class="idea__tags tags">
                                        <span class="label__tags">Đánh dấu:</span>
                                        <?php 
                                        
                                        $terms = get_terms("post_tag", array('orderby' => 'count', 'order' => 'DESC','hide_empty'=>0 ));
                                        $count = count($terms);
                                        if ( $count > 0 ){
                                    
                                            foreach ( $terms as $term ) { ?>
                                            
                                            <span class="tag"><a href="<?php echo get_term_link( $term ); ?>"><?php echo $term->name; ?></a></span>
                                            
                                        <?php } } ?>
                                       
                                      
                                    </div>
                            </div>
                    </div>
                </div>
            </div>

    <?php 
	return ob_get_clean();
}
add_shortcode( 'posts__ajax', 'dvu_posts' );