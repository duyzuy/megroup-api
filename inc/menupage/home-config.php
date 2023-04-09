<?php 
function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        __( 'Home config', 'dvutemplate' ),
        'SCG config',
        'manage_options',
        'dvu-config.php',
        'dvu_homepage_callback',
        'dashicons-admin-home',
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );


function dvu_homepage_callback(){
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}


        return '123';
}
