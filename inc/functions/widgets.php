<?php

add_action( 'widgets_init', 'dvu_footer_1' );
function dvu_footer_1() {
    register_sidebar( array(
        'name' => __( 'Footer 1 content', 'dvutemplate' ),
        'id' => 'footer-1',
        'description' => __( 'Widgets in this area will display content  footer 1', 'dvutemplate' ),
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget__title">',
        'after_title'   => '</div>',
    ) );
}


add_action( 'widgets_init', 'dvu_footer_2' );
function dvu_footer_2() {
    register_sidebar( array(
        'name' => __( 'Footer 2 content', 'dvutemplate' ),
        'id' => 'footer-2',
        'description' => __( 'Widgets in this area will display content  footer 2', 'dvutemplate' ),
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget__title">',
        'after_title'   => '</div>',
    ) );
}

add_action( 'widgets_init', 'dvu_footer_3' );
function dvu_footer_3() {
    register_sidebar( array(
        'name' => __( 'Footer 3 content', 'dvutemplate' ),
        'id' => 'footer-3',
        'description' => __( 'Widgets in this area will display content  footer 3', 'dvutemplate' ),
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="widget__title">',
        'after_title'   => '</div>',
    ) );
}

add_action( 'widgets_init', 'dvu_footer_4' );
function dvu_footer_4() {
    register_sidebar( array(
        'name' => __( 'Footer 4 content', 'dvutemplate' ),
        'id' => 'footer-4',
        'description' => __( 'Widgets in this area will display content  footer 4', 'dvutemplate' ),
        'before_widget' => '<div id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="footer-title">',
        'after_title'   => '</div>',
    ) );
}



add_action( 'widgets_init', 'dvu_aside_post' );
function dvu_aside_post() {
    register_sidebar( array(
        'name' => __( 'Sidebar blog', 'dvutemplate' ),
        'id' => 'blog-sidebar',
        'description' => __( 'Widgets in this area will display aside post', 'dvutemplate' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget'  => '</aside>',
	'before_title'  => '<h3 class="widget-title">',
	'after_title'   => '</h3>',
    ) );
}

add_filter( 'widget_text', 'do_shortcode');