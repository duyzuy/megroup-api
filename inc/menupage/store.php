<?php 

/**
* Adds a submenu page under a custom post type parent.
*/
add_action('admin_menu', 'wpdocs_register_my_custom_submenu_page');
 
function wpdocs_register_my_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=store',
        'Store config',
        'Store config',
        'manage_options',
        'store-config',
        'dvu_store_submenu_page_callback' );
}
 
function dvu_store_submenu_page_callback() {
    if ( !current_user_can( 'manage_options' ) )  {
		  wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
  
    
    if(isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'area_store'){

        require get_template_directory() . '/inc/menupage/templates/areas.php';
    }
    elseif(isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'type_store'){

        require get_template_directory() . '/inc/menupage/templates/types.php';

    }elseif(isset($_GET['taxonomy']) && $_GET['taxonomy'] == 'cat_store'){

        require get_template_directory() . '/inc/menupage/templates/cat.php';

    }else{

        require get_template_directory() . '/inc/menupage/templates/store.php';

    }
      
}

