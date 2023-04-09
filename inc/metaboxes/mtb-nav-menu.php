<?php 
class Menu_Item_Custom_Fields_Example {

	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 * @since  Menu_Item_Custom_Fields_Example 0.2.0
	 */
	protected static $fields = array();


	/**
	 * Initialize plugin
	 */
	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );

		/*Enqueue scripts for Edit Menu upload image*/
		add_action('admin_enqueue_scripts', function(){
			    wp_enqueue_media();
			    wp_enqueue_script( 'scg-navjs', get_template_directory_uri().'/assets/js/admin/menu-media-uploader.js', array('jquery'), '1.1.0', true );
              
        });
	}


	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		if( isset($_POST['jt-img-id']) && !empty($_POST['jt-img-id'])  ){
			
			$value = $_POST['jt-img-id'][$menu_item_db_id];
			if( !empty($value) ){
				update_post_meta($menu_item_db_id, 'jt_hover_image', $value );
			}else{
				delete_post_meta($menu_item_db_id, 'jt_hover_image' );
			}
		}
			

	}


	/**
	 * Print field
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _fields( $id, $item, $depth, $args ) {
        $upload_link = esc_url( get_upload_iframe_src( 'image', $item->ID ) );

        // See if there's a media id already saved as post meta
        $your_img_id = get_post_meta( $item->ID, 'jt_hover_image', true );
    
        // Get the image src
        $your_img_src = wp_get_attachment_image_src( $your_img_id, 'full' );
    
        // For convenience, see if the array is valid
        $you_have_img = is_array( $your_img_src );
        ?>
    
        <div class="description description-wide jt-bg-image-upload-wrapper">
            <!-- Your image container, which can be manipulated with js -->
            <div class="custom-img-container">
                <?php if ( $you_have_img ) : ?>
                    <img src="<?php echo $your_img_src[0] ?>" alt="" style="max-width:100%;" />
                <?php endif; ?>
            </div>
    
                <!-- Your add & remove image links -->
                <p class="hide-if-no-js">
                    <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
                       href="<?php echo $upload_link ?>">
                        <?php _e('Set thumbnail image') ?>
                    </a>
                    <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
                      href="#">
                        <?php _e('Remove this image') ?>
                    </a>
                </p>
    
            <!-- A hidden input to set and post the chosen image id -->
            <input class="jt-img-id" name="jt-img-id[<?php echo $item->ID; ?>]" type="hidden" value="<?php echo esc_attr( $your_img_id ); ?>" />
        </div>
        <?php 
	}


	/**
	 * Add our fields to the screen options toggle
	 *
	 * @param array $columns Menu item columns
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );

		return $columns;
	}
}
Menu_Item_Custom_Fields_Example::init();