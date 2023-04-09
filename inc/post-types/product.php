<?php 

function dv_post_type_product() {
    $labels = array(
        'name'                  => _x( 'sản phẩm', 'Post type general name', 'dvutemplate' ),
        'singular_name'         => _x( 'sản phẩm', 'Post type singular name', 'dvutemplate' ),
        'menu_name'             => _x( 'sản phẩm', 'Admin Menu text', 'dvutemplate' ),
        'name_admin_bar'        => _x( 'sản phẩm', 'Add New on Toolbar', 'dvutemplate' ),
        'add_new'               => __( 'Thêm mới', 'dvutemplate' ),
        'add_new_item'          => __( 'Thêm sản phẩm mới', 'dvutemplate' ),
        'new_item'              => __( 'Sản phẩm mới', 'dvutemplate' ),
        'edit_item'             => __( 'Sửa sản phẩm', 'dvutemplate' ),
        'all_items'             => __( 'Tất cả sản phẩm', 'dvutemplate' ),
        'search_items'          => __( 'Tìm kiếm sản phẩm', 'dvutemplate' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'san-pham' ),
        'capability_type'    => 'post',
        'menu_icon'          => 'dashicons-cart',
        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        'supports'           => array( 'title', 'author', 'thumbnail', 'editor' ),
    );
 
    register_post_type( 'product', $args );
}
 
add_action( 'init', 'dv_post_type_product' );

//custom taxonomy

function dv_register_category_product() {
    
    $labels = array(
        'name'                       => __( 'Danh mục sản phẩm', 'dvutemplate' ),
        'singular_name'              => __( 'Danh mục sản phẩm', 'dvutemplate' ),
        'menu_name'                  => __( 'Danh mục sản phẩm', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa danh mục sản phẩm', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật danh mục sản phẩm', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm danh mục', 'dvutemplate' ),
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
        'rewrite'            => array( 'slug' => 'danh-muc-san-pham' ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
    );


    register_taxonomy( 'category_product', array( 'product' ), $args );
}
add_action('init', 'dv_register_category_product');

function dv_register_tags_product() {
    
    $labels = array(
        'name'                       => __( 'Thẻ sản phẩm', 'dvutemplate' ),
        'singular_name'              => __( 'Thẻ sản phẩm', 'dvutemplate' ),
        'menu_name'                  => __( 'Thẻ sản phẩm', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa Thẻ sản phẩm', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật Thẻ sản phẩm', 'dvutemplate' ),
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
        'rewrite'            => array( 'slug' => 'tag-product' ),
        'hierarchical'      => false,
        'show_admin_column' => true,
        'query_var'         => true,
    );


    register_taxonomy( 'tags_product', array( 'product' ), $args );
}
add_action('init', 'dv_register_tags_product');
