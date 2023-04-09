<?php 

function dvu_register_script(){
    $uri = get_template_directory_uri();
    $theme = wp_get_theme( get_template() );
    $version = $theme->get( 'Version' );
    
    wp_enqueue_style('megroup-bootstrap', $uri . '/assets/css/bootstrap.min.css', array(), '4.5.2', 'all');
    wp_enqueue_style('megroup-f-icon', $uri . '/assets/css/all.min.css', array(), '5.14.0', 'all');
    wp_enqueue_style('megroup-swipe', $uri . '/assets/css/swipe.css', array(), '6.2.0', 'all');

    wp_enqueue_style('megroup-gutenberg', $uri . '/assets/css/gutenberg.css', array(), $version, 'all');
    wp_enqueue_style('megroup-global', $uri . '/assets/css/theme.css', array(), $version, 'all');
    
    wp_scripts()->add_data( 'jquery', 'group', 1 );
    wp_scripts()->add_data( 'jquery-core', 'group', 1 );

    wp_enqueue_script('megroup-jquery', $uri . '/assets/js/jquery.js', array('jquery'), '3.5.1', false);
    wp_enqueue_script('megroup-swipe-js', $uri . '/assets/js/swipe.js', array('jquery'), '2.2.1', true);
    // wp_enqueue_script('megroup-bootstrap-js-map', $uri . '/assets/js/bootstrap.min.js.map', array('jquery'), '4.0.0', true);
    wp_enqueue_script('megroup-bootstrap-js', $uri . '/assets/js/bootstrap.min.js', array('jquery'), '4.0.0', true);
   

   

    wp_enqueue_script('global', $uri . '/assets/js/global.js', array('jquery'), $version, true);
      wp_localize_script( 'global', 'object', array(
          'ajaxUrl'   => admin_url( 'admin-ajax.php'),
          'wpNonce'   => wp_create_nonce( 'dvu_ajax_nonce' ),
          'loading'   => 'loading...',
      ) );

      wp_register_style('light_gallery', get_template_directory_uri() . '/assets/css/shortcodes/lightgallery.css', array(), '1.8.0', 'all');
      wp_register_script( 'product_lightbox', get_template_directory_uri() . '/assets/js/shortcodes/product-lightbox.js', array(), '1.0.0', true );
      wp_register_script( 'mouse_wheel', get_template_directory_uri() . '/assets/js/shortcodes/jquery.mousewheel.min.js', array(), '3.7.1', true );
      wp_register_script( 'light_gallery', get_template_directory_uri() . '/assets/js/shortcodes/lightgallery-all.min.js', array(), '3.7.1', true );
      wp_register_script('megroup-map-js', $uri . '/assets/js/map.js', array('jquery'), '1.0.0', true);

    }
add_action('wp_enqueue_scripts', 'dvu_register_script', 100);


function smartwp_remove_wp_block_library_css(){
  // wp_dequeue_style( 'wp-block-library' );
  wp_dequeue_style( 'wp-block-library-theme' );
  wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
} 
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );