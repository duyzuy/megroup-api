<?php 

function dv_post_type_slider() {
    $labels = array(
        'name'                  => _x( 'Slider', 'Post type general name', 'dvtheme' ),
        'singular_name'         => _x( 'Slider', 'Post type singular name', 'dvtheme' ),
        'menu_name'             => _x( 'Sliders', 'Admin Menu text', 'dvtheme' ),
        'name_admin_bar'        => _x( 'Slider', 'Add New on Toolbar', 'dvtheme' ),
        'add_new'               => __( 'Add New', 'dvtheme' ),
        'add_new_item'          => __( 'Add New Slider', 'dvtheme' ),
        'new_item'              => __( 'New slider', 'dvtheme' ),
        'edit_item'             => __( 'Edit Slider', 'dvtheme' ),
        'all_items'             => __( 'All Slider', 'dvtheme' ),
        'search_items'          => __( 'Search Slider', 'dvtheme' ),
        'parent_item_colon'     => __( 'Parent Slider:', 'dvtheme' ),
        'not_found'             => __( 'No slider found.', 'dvtheme' ),
        'not_found_in_trash'    => __( 'No slider found in Trash.', 'dvtheme' ),
        'featured_image'        => _x( 'Slider Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dvtheme' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dvtheme' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dvtheme' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'dvtheme' ),
        'archives'              => _x( 'Slider archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'dvtheme' ),
        'insert_into_item'      => _x( 'Insert into slider', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'dvtheme' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this slider', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'dvtheme' ),
     
       
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'slider' ),
        'capability_type'    => 'page',
        'menu_icon'          =>'dashicons-format-gallery',
        'has_archive'        => false,
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports'           => array( 'title', 'page-attributes'),
    );
 
    register_post_type( 'slider', $args );
}
 
add_action( 'init', 'dv_post_type_slider' );

//custom taxonomy

function dv_register_category() {
    
    $labels = array(
        'name'                       => __( 'Group slider', 'dvtheme' ),
        'singular_name'              => __( 'Group Slider', 'dvtheme' ),
        'menu_name'                  => __( 'Group Slider', 'dvtheme' ),
        'edit_item'                  => __( 'Edit Group Slider', 'dvtheme' ),
        'update_item'                => __( 'Update Group Slider', 'dvtheme' ),
        'add_new_item'               => __( 'Add New Group Slider', 'dvtheme' ),
        'new_item_name'              => __( 'New Group name', 'dvtheme' ),
        'all_items'                  => __( 'All Group Slider', 'dvtheme' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'dvtheme' ),
        'not_found'                  => __( 'No Group Slider found.', 'dvtheme' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => false,
        'show_in_nav_menus' => true,
        'show_ui'           => true,
        'show_tagcloud'     => true,
        'rewrite'            => array( 'slug' => 'group-slider' ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
    );


    register_taxonomy( 'group_slider', array( 'slider' ), $args );
}
add_action('init', 'dv_register_category');
