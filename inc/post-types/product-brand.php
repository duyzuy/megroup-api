<?php 


//custom taxonomy

function dv_register_category_product() {
    
    $labels = array(
        'name'                       => __( 'Thương hiệu', 'dvutemplate' ),
        'singular_name'              => __( 'Thương hiệu', 'dvutemplate' ),
        'menu_name'                  => __( 'Thương hiệu', 'dvutemplate' ),
        'edit_item'                  => __( 'sửa Thương hiệu', 'dvutemplate' ),
        'update_item'                => __( 'Cập nhật Thương hiệu', 'dvutemplate' ),
        'add_new_item'               => __( 'Thêm Thương hiệu', 'dvutemplate' ),
        'new_item_name'              => __( 'Thêm Thương hiệu mới', 'dvutemplate' ),
        'all_items'                  => __( 'Tất cả Thương hiệu', 'dvutemplate' ),
        'add_or_remove_items'        => __( 'Thêm hoặc xóa Thương hiệu', 'dvutemplate' ),
        'not_found'                  => __( 'Không tìm thấy Thương hiệu', 'dvutemplate' ),
    );

    $args = array(
        'labels'            => $labels,
        'public'            => true,
        'show_in_nav_menus' => true,
        'show_ui'           => true,
        'show_tagcloud'     => true,
        'rewrite'            => array( 'slug' => 'brand' ),
        'hierarchical'      => true,
        'show_admin_column' => true,
        'query_var'         => true,
    );


    register_taxonomy( 'product_brand', array( 'product' ), $args );
}
add_action('init', 'dv_register_category_product');
