<?php 

function dvu_setups(){
    
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'menus' );
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'widgets' ) );
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-logo',  array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array( 'site-title', 'site-description' ) 
            )
        );

    add_theme_support( 'post-formats', array( 'gallery' ) );

    /* Add excerpt to pages */
    add_post_type_support( 'page', 'excerpt' );
  
  
    /* Add support for Selective Widget refresh */
    add_theme_support( 'customize-selective-refresh-widgets' );

  
    /*  Registrer menus. */
    register_nav_menus( array(
      'primary' => __( 'Main Menu', 'dvutemplate' ),
      'footer' => __( 'Footer Menu', 'dvutemplate' ),
      'header_top' => __( 'Top Menu', 'dvutemplate' ),
    ) );
  

        
    
    }
add_action( 'after_setup_theme', 'dvu_setups' );

   



function disable_wp_emojicons() {

    // all actions related to emojis
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  
    // filter to remove TinyMCE emojis
   
  }
  add_action( 'init', 'disable_wp_emojicons' );



  remove_action('wp_head', 'wp_generator');

  function wpbeginner_remove_version() {
    return '';
    }
  add_filter('the_generator', 'wpbeginner_remove_version');


function init_smtp( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587; // May need to be changed
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Username = '';
    $phpmailer->Password = '';
}
    
add_action( 'phpmailer_init', 'init_smtp' );