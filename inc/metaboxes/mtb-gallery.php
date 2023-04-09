<?php 

require_once(ABSPATH . 'wp-admin/includes/screen.php');

function dvu_register_gallery_metabox(){
    add_meta_box( 
        'dv_gallery_information_mb', 
        esc_html__( 'Thư viện', 'dvtemplate' ), 
        'dvu_gallery_information_cb', 
        'gallery', 
        'advanced', 
        'high' );



   
}
add_action('add_meta_boxes', 'dvu_register_gallery_metabox');




function dvu_gallery_information_cb($post){

	    wp_nonce_field( 'dv_save_gallery_data', 'dv_gallery_nonce' );

        $gallery = get_post_meta($post->ID, '_image_gallery_ids', true);
     
  
    
       ?>

        <div class="pyre_metabox_field">

            <label for="pyre_slider_type">Thư viện hình ảnh</label>

            <div class="pyre_field">

                <ul class="gallery-thumb">
                    <?php 
                                        if($gallery){
                                            $elements = explode(',', $gallery);
                                            for($i = 0; $i < count($elements); $i++){
                                                // $attachment_title = get_the_title($elements[$i]);
                                                // echo $attachment_title;
                                                echo'<li class="thumb-image thumb-image-'.$i.'"><span class="js_btn-remove-img" data-id="'.$elements[$i].'">x</span>'. wp_get_attachment_image( $elements[$i], array('100', '100'), "", array( "class" => "img-responsive" ) ) .'</li>';
                                            };
                                        }
                                        ?>
                </ul>
                <input type="hidden" class="form-control" name="pyre_image_gallery" id="project-gallery-value"
                    value="<?php echo esc_attr($gallery) ?>">
                <a href="#" class="button btn-upload-gallery-multi button-primary" id="pr-gallery-upload">Chọn hình ảnh</a>
                <?php if($gallery){ ?>
                <a href="#" class="button btn-remove-gallery" id="pr-gallery-remove">xóa tất cả</a>
                <?php } ?>


            </div>
        </div>

<?php
}

function dv_save_gallery_data($post_id){

        $nonce_name   = isset( $_POST['dv_gallery_nonce'] ) ? $_POST['dv_gallery_nonce'] : '';
        $nonce_action = 'dv_save_gallery_data';
      
        // Check if nonce is set.
        if ( ! isset( $nonce_name ) ) {
            return;
        }
 
        // Check if nonce is valid.
        if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
            return;
        }
 
        // Check if user has permissions to save data.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
 
        //Check if not an autosave.
        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }
 
        //Check if not a revision.
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

  

    if (array_key_exists('pyre_image_gallery', $_POST)) {

        update_post_meta( $post_id, '_image_gallery_ids', sanitize_text_field($_POST['pyre_image_gallery']) );

    }
    
	

   
}

add_action('save_post', 'dv_save_gallery_data');