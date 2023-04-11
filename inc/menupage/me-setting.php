<?php 
function wpdocs_register_my_custom_menu_page() {
    add_menu_page(
        __( 'Me setting', 'dvutheme' ),
        'Me setting',
        'manage_options',
        'me_setting',
        'dvu_homepage_html',
        'dashicons-welcome-widgets-menus',
        10
    );
	add_submenu_page( 'me_setting', 'Thiết lập trang chủ', 'Trang chủ',
	'manage_options', 'me_setting');

	add_submenu_page( 'me_setting', 'Thiết lập thông tin đầu trang', 'Đầu trang',
	'manage_options', 'me_setting_header', 'submenu_header_callback', 5);
	
	add_submenu_page( 'me_setting', 'Thiết lập thông tin chân trang', 'Chân trang',
	'manage_options', 'me_setting_footer', 'submenu_footer_callback', 10);
	
	
	// add_submenu_page( $parent_slug:string, $page_title:string, $menu_title:string, $capability:string, $menu_slug:string, $callback:callable, $position:integer|float|null )
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );


function dvu_homepage_html(){
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	require get_template_directory() . '/inc/menupage/setting/home-options.php';
}

//add submenu header fields

function submenu_header_callback () {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	require get_template_directory() . '/inc/menupage/setting/header-options.php';

}

function submenu_footer_callback () {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	require get_template_directory() . '/inc/menupage/setting/footer-options.php';

}


function dvu_settings_init() {
	// Register a new setting for "mepage" page.
	register_setting( 'mepage_header', 'dvu_options_header' );
	register_setting( 'mepage', 'dvu_options_home' );
	register_setting( 'mepage_footer', 'dvu_options_footer' );
	
	// Register a new section in the "mepage_header", "mepage", "mepage_footer" page.
	add_settings_section(
		'dvu_section_header',
		__( 'Cấu hình Header.', 'dvutheme' ), 
		'dvu_section_header_callback',
		'mepage_header',
	);

	add_settings_section(
		'dvu_section_home',
		__( 'Các thành phần trang chủ', 'dvutheme' ), 
		'dvu_section_homepage_callback',
		'mepage',
	);
	add_settings_section(
		'dvu_section_footer',
		__( 'Cấu hình Footer.', 'dvutheme' ), 
		'dvu_section_footer_callback',
		'mepage_footer'
	);

	// Register a new fields
	add_settings_field(
		'dvu_home_showreal_fields', 
		 __( 'Showreal', 'dvutheme' ),
		'dvu_home_showreal_fields_callback',
		'mepage',
		'dvu_section_home',
		array(
			'label_for'         => 'dvu_home_showreal',
			'class'             => 'dvu_row',
		)
	);

	add_settings_field(
		'dvu_home_news_fields', 
		 __( 'News', 'dvutheme' ),
		'dvu_home_news_fields_callback',
		'mepage',
		'dvu_section_home',
		array(
			'label_for'         => 'dvu_home_news',
			'class'             => 'dvu_row',
		)
	);

	add_settings_field(
		'dvu_home_network_fields', 
		 __( 'Network', 'dvutheme' ),
		'dvu_home_network_fields_callback',
		'mepage',
		'dvu_section_home',
		array(
			'label_for'         => 'dvu_home_network',
			'class'             => 'dvu_row',
		)
	);


	//header fields
	add_settings_field(
		'dvu_header_field_title', 
		 __( 'Title', 'dvutheme' ),
		'dvu_header_fields_cb',
		'mepage_header',
		'dvu_section_header',
		array(
			'label_for'         => 'dvu_field_content',
			'class'             => 'dvu_row',
		)
	);
	add_settings_field(
		'dvu_header_field_social', 
		 __( 'Socials', 'dvutheme' ),
		'dvu_header_fields_cb',
		'mepage_header',
		'dvu_section_header',
		array(
			'label_for'         => 'dvu_field_social',
			'class'             => 'dvu_row',
		)
	);
	
}

add_action( 'admin_init', 'dvu_settings_init' );


function dvu_section_header_callback( $args ) {
	?>
<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Các thông tin tiêu đề của trang web', 'dvutheme' ); ?>
</p>
<?php
}

function dvu_section_homepage_callback( $args ) {
	?>
<p id="<?php echo esc_attr( $args['id'] ); ?>">
  <?php esc_html_e( 'Thiết lập các phần hiển thị từng section ngoài trang chủ', 'dvutheme' ); ?></p>
<?php
}

function dvu_section_footer_callback( $args ) {
	?>
<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Cấu hình Chân trang', 'dvutheme' ); ?></p>
<?php
}

function dvu_header_fields_cb( $args ) {
	
	if($args['label_for'] === 'dvu_field_content'){
		require get_template_directory() . '/inc/menupage/setting/fields/header/content.php';
	}else if($args['label_for'] === 'dvu_field_logo'){
		require get_template_directory() . '/inc/menupage/setting/fields/header/social.php';
	}
	
}
//home fields
function dvu_home_showreal_fields_callback($args)  {
	require get_template_directory() . '/inc/menupage/setting/fields/showreal.php';
}
function dvu_home_news_fields_callback($args)  {
	require get_template_directory() . '/inc/menupage/setting/fields/news.php';
}
function dvu_home_network_fields_callback($args)  {
	require get_template_directory() . '/inc/menupage/setting/fields/network.php';
}