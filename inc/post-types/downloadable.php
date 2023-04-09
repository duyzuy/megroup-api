<?php 

function dv_post_type_downloadable() {
    $labels = array(
        'name'                  => _x( 'Tải về', 'Post type general name', 'dvutemplate' ),
        'singular_name'         => _x( 'Tải về', 'Post type singular name', 'dvutemplate' ),
        'menu_name'             => _x( 'Tải về', 'Admin Menu text', 'dvutemplate' ),
        'name_admin_bar'        => _x( 'Tải về', 'Add New on Toolbar', 'dvutemplate' ),
        'add_new'               => __( 'Thêm mới', 'dvutemplate' ),
        'add_new_item'          => __( 'Thêm mới', 'dvutemplate' ),
        'new_item'              => __( 'Tải về mới', 'dvutemplate' ),
        'edit_item'             => __( 'Sửa', 'dvutemplate' ),
        'all_items'             => __( 'Tất cả file tải về', 'dvutemplate' ),
        'search_items'          => __( 'Tìm kiếm file', 'dvutemplate' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'dowload' ),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-media-archive',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports'           => array( 'title', 'thumbnail', ),
    );
 
    register_post_type( 'download', $args );
}
 
add_action( 'init', 'dv_post_type_downloadable' );

//custom taxonomy

function dvu_register_category_download() {
    
    $labels = array(
        'name'                       => __( 'Danh mục tải về', 'dvutemplate' ),
        'singular_name'              => __( 'Danh mục tải về', 'dvutemplate' ),
        'menu_name'                  => __( 'Danh mục tải về', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa danh mục tải về', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật danh mục tải về', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm danh mục tải về', 'dvutemplate' ),
        'new_item_name'              => __( 'Thêm danh mục tải về mới', 'dvutemplate' ),
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
        'rewrite'            => array( 'slug' => 'category-download' ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
    );


    register_taxonomy( 'category_download', array( 'download' ), $args );
}
add_action('init', 'dvu_register_category_download');


