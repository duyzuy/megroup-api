<?php 

function dvu_post_type_gallery() {
    $labels = array(
        'name'                  => _x( 'Gallery', 'Post type general name', 'dvutemplate' ),
        'singular_name'         => _x( 'Gallery', 'Post type singular name', 'dvutemplate' ),
        'menu_name'             => _x( 'Gallery', 'Admin Menu text', 'dvutemplate' ),
        'name_admin_bar'        => _x( 'Gallery', 'Add New on Toolbar', 'dvutemplate' ),
        'add_new'               => __( 'Thêm mới', 'dvutemplate' ),
        'add_new_item'          => __( 'Thêm mới gallery', 'dvutemplate' ),
        'new_item'              => __( 'Gallery mới', 'dvutemplate' ),
        'edit_item'             => __( 'Sửa', 'dvutemplate' ),
        'all_items'             => __( 'Tất cả Galleries', 'dvutemplate' ),
        'search_items'          => __( 'Tìm kiếm file', 'dvutemplate' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'gallery' ),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-images-alt2',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail', ),
    );
 
    register_post_type( 'gallery', $args );
}
 
add_action( 'init', 'dvu_post_type_gallery' );

//custom taxonomy

function dvu_register_category_gallery() {
    
    $labels = array(
        'name'                       => __( 'Danh mục gallery', 'dvutemplate' ),
        'singular_name'              => __( 'Danh mục gallery', 'dvutemplate' ),
        'menu_name'                  => __( 'Danh mục gallery', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa danh mục gallery', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật danh mục gallery', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm danh mục gallery', 'dvutemplate' ),
        'new_item_name'              => __( 'Thêm danh mục mới', 'dvutemplate' ),
        'all_items'                  => __( 'Tất cả danh mục', 'dvutemplate' ),
        'add_or_remove_items'        => __( 'Thêm hoặc xóa danh mục', 'dvutemplate' ),
        'not_found'                  => __( 'Không tìm thấy danh mục', 'dvutemplate' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_ui'           => true,
        'show_tagcloud'     => true,
        'rewrite'            => array( 'slug' => 'category-gallery' ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
    );


    register_taxonomy( 'category_gallery', array( 'gallery' ), $args );
}
add_action('init', 'dvu_register_category_gallery');

function dvu_register_tags_gallery() {
    
    $labels = array(
        'name'                       => __( 'Thẻ gallery', 'dvutemplate' ),
        'singular_name'              => __( 'Thẻ gallery', 'dvutemplate' ),
        'menu_name'                  => __( 'Thẻ gallery', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa Thẻ', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật Thẻ ', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm Thẻ', 'dvutemplate' ),
        'new_item_name'              => __( 'Thêm Thẻ mới', 'dvutemplate' ),
        'all_items'                  => __( 'Tất cả danh mục', 'dvutemplate' ),
        'add_or_remove_items'        => __( 'Thêm hoặc xóa thẻ', 'dvutemplate' ),
        'not_found'                  => __( 'Không tìm thấy thẻ', 'dvutemplate' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_ui'           => true,
        'show_tagcloud'     => true,
        'rewrite'            => array( 'slug' => 'tag-gallery' ),
        'hierarchical'      => false,
        'show_admin_column' => true,
        'query_var'         => true,
    );


    register_taxonomy( 'tags_gallery', array( 'gallery' ), $args );
}
add_action('init', 'dvu_register_tags_gallery');


