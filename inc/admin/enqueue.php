<?php 

function load_custom_wp_admin_style() {
    $uri = get_template_directory_uri();
    $theme = wp_get_theme( get_template() );
    $version = $theme->get( 'Version' );

 
   
    global $pagenow; 
    global $post_type;

    if($post_type ==  'slider' || $pagenow == 'edit-tags.php' || $pagenow == 'term.php'){
        wp_enqueue_media();
    }
    // wp_enqueue_script( 'wp-color-picker-alpha', $uri.'/assets/js/admin/wp-color-picker-alpha.js', array( 'wp-color-picker' ), $version, true );     
    // wp_enqueue_script('media-js', $uri.'/assets/admin/js/media.js', array('jquery'), $version, false);
    wp_enqueue_script( 'dvu-setting-js', $uri.'/assets/js/admin/product.js',  array('jquery'), $version, false );
    wp_enqueue_style( 'dvu-setting-css', $uri.'/assets/css/admin/global.css', array(), $version, 'all');
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');