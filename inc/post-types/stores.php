<?php 

function dv_post_type_store() {
    $labels = array(
        'name'                  => _x( 'Cửa hàng', 'Post type general name', 'dvutemplate' ),
        'singular_name'         => _x( 'Cửa hàng', 'Post type singular name', 'dvutemplate' ),
        'menu_name'             => _x( 'Cửa hàng', 'Admin Menu text', 'dvutemplate' ),
        'name_admin_bar'        => _x( 'Cửa hàng', 'Add New on Toolbar', 'dvutemplate' ),
        'add_new'               => __( 'Thêm mới', 'dvutemplate' ),
        'add_new_item'          => __( 'Thêm cửa hàng', 'dvutemplate' ),
        'new_item'              => __( 'Cửa hàng mới', 'dvutemplate' ),
        'edit_item'             => __( 'Sửa cửa hàng', 'dvutemplate' ),
        'all_items'             => __( 'Tất cả cửa hàng', 'dvutemplate' ),
        'search_items'          => __( 'Tìm kiếm cửa hàng', 'dvutemplate' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'store' ),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-store',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'show_in_rest'        => true,
        'rest_base'           => 'store-api',
        'supports'           => array( 'title'),
        'rest_controller_class' => 'WP_REST_Posts_Controller'
    );
 
    register_post_type( 'store', $args );
}
 
add_action( 'init', 'dv_post_type_store' );

//custom taxonomy

function dv_register_area_store() {
    
    $labels = array(
        'name'                       => __( 'Khu vực', 'dvutemplate' ),
        'singular_name'              => __( 'Khu vực cửa hàng', 'dvutemplate' ),
        'menu_name'                  => __( 'Khu vực cửa hàng', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa khu vực cửa hàng', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật khu vực cửa hàng', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm khu vực', 'dvutemplate' ),
        'new_item_name'              => __( 'Thêm khu vực mới', 'dvutemplate' ),
        'all_items'                  => __( 'Tất cả khu vực', 'dvutemplate' ),
        'add_or_remove_items'        => __( 'Thêm hoặc xóa khu vực', 'dvutemplate' ),
        'not_found'                  => __( 'Không tìm thấy khu vực', 'dvutemplate' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => false,
        'show_in_nav_menus' => true,
        'show_ui'           => false,
        'show_tagcloud'     => false,
        'rewrite'            => array( 'slug' => 'area-store' ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'          => true,
        'rest_base'             => 'area-store',
    );


    register_taxonomy( 'area_store', array( 'store' ), $args );
}
add_action('init', 'dv_register_area_store');

function dv_register_type_store() {
    
    $labels = array(
        'name'                       => __( 'Loại cửa hàng', 'dvutemplate' ),
        'singular_name'              => __( 'Loại cửa hàng', 'dvutemplate' ),
        'menu_name'                  => __( 'Loại cửa hàng', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa loại cửa hàng', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật loại cửa hàng', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm loại', 'dvutemplate' ),
        'new_item_name'              => __( 'Thêm loại mới', 'dvutemplate' ),
        'all_items'                  => __( 'Tất cả loại cửa hàng', 'dvutemplate' ),
        'add_or_remove_items'        => __( 'Thêm hoặc xóa loại', 'dvutemplate' ),
        'not_found'                  => __( 'Không tìm thấy', 'dvutemplate' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => false,
        'show_in_nav_menus' => true,
        'show_ui'           => false,
        'show_tagcloud'     => false,
        'rewrite'            => array( 'slug' => 'type-store' ),
        'hierarchical'      => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rest_base'         => 'type-store',
    );


    register_taxonomy( 'type_store', array( 'store' ), $args );
}
add_action('init', 'dv_register_type_store');

function dv_register_cat_store() {
    
    $labels = array(
        'name'                       => __( 'Mục cửa hàng', 'dvutemplate' ),
        'singular_name'              => __( 'Mục cửa hàng', 'dvutemplate' ),
        'menu_name'                  => __( 'Mục cửa hàng', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa mục cửa hàng', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật mục cửa hàng', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm mục', 'dvutemplate' ),
        'new_item_name'              => __( 'Thêm mục mới', 'dvutemplate' ),
        'all_items'                  => __( 'Tất cả mục cửa hàng', 'dvutemplate' ),
        'add_or_remove_items'        => __( 'Thêm hoặc xóa mục', 'dvutemplate' ),
        'not_found'                  => __( 'Không tìm thấy', 'dvutemplate' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => false,
        'show_in_nav_menus' => true,
        'show_ui'           => false,
        'show_tagcloud'     => false,
        'rewrite'            => array( 'slug' => 'cat-store' ),
        'hierarchical'      => false,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'          => true,
        'rest_base'             => 'cat-store',
    );


    register_taxonomy( 'cat_store', array( 'store' ), $args );
}
add_action('init', 'dv_register_cat_store');


