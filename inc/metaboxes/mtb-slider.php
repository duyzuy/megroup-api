<?php 

require_once(ABSPATH . 'wp-admin/includes/screen.php');

function neo_create_metabox_slide(){

    add_meta_box( 
        'dv_portfolio_mb', 
        esc_html__( 'Slide option', 'giabao' ), 
        'neo_slide_mtb_callback', 
        'slider', 
        'advanced', 
        'high' );
}
add_action('add_meta_boxes', 'neo_create_metabox_slide');

function neo_slide_mtb_callback($post){

	    wp_nonce_field( 'dv_save_slider_data', 'dv_slide_nonce' );
        
        $type = 'image';
        $slide_link = get_post_meta($post->ID, '_slide_link', true);
        $type = get_post_meta($post->ID, '_slide_type', true);
        $bn_pc = get_post_meta($post->ID, '_banner_desktop', true);
        $bn_mb = get_post_meta($post->ID, '_banner_mobile', true);
        $bn_pc_url = get_post_meta($post->ID, '_banner_desktop_url', true);
        $bn_mb_url = get_post_meta($post->ID, '_banner_mobile_url', true);
        $youtube = get_post_meta($post->ID, '_slide_youtube_id', true);
        $show_hide = get_post_meta($post->ID, '_slide_showhide', true);
    
       ?>
        <p><strong>Chọn loại slider</strong></p>
         <div class="pyre_metabox_field pyre_slider_type">

                <div class="pyre_field checkbox_field">
                    <input type="radio" name="pyre_slide_type" value="image" id="pyre_slide_type_1" <?php echo $type == 'image' ? 'checked' : '' ?>>
                    <label for="pyre_slide_type_1">Hình ảnh</label>
                </div>
                <div class="pyre_field checkbox_field">
                    <input type="radio" name="pyre_slide_type" value="youtube" id="pyre_slide_type_2" <?php echo $type == 'youtube' ? 'checked' : '' ?>>
                    <label for="pyre_slide_type_2">Video Youtube</label>
                </div>
        
        </div>
        <div class="pyre_wrap_content">
            <div class="pyre_tab_type pyre_content_image_banner <?php echo $type == 'image' ? 'active' : '' ?>">
                <div class="pyre_row">
                    <div class="pyre_metabox_field pyre_col">
                
                        <label>Hình ảnh (Desktop only)</label>
                    
                        <div class="pyre_field">

                            <div class="pyre_thumbnail">
                            
                            <?php if($bn_pc) { 

                                echo wp_get_attachment_image( $bn_pc, 'large', '', array( "class" => "img-responsive" ) ) ;

                            } else{ ?>
                                <div class="pyre_thumbnail_noimage">
                                    <span>No image</span>
                                </div>
                            
                            <?php } ?>
                            </div>
                            
                            <a href="#" class="button btn-upload-banner button-primary" id="pyre-banner-upload-pc">Chọn banner</a>
                            <input type="hidden" name="pyre_banner_desktop" value="<?php echo $bn_pc; ?>">
                            <input type="hidden" class="thumbnail_url" name="pyre_banner_desktop_url" value="<?php echo $bn_pc_url ?>" >
                        </div>
                    </div>
                    <div class="pyre_metabox_field pyre_col">
                
                        <label>Hình ảnh (Mobile only)</label>
                    
                        <div class="pyre_field">

                            <div class="pyre_thumbnail">
                            <?php if($bn_mb) { 

                                echo wp_get_attachment_image( $bn_mb, 'large', '', array( "class" => "img-responsive" ) ) ;

                                } else{ ?>
                                <div class="pyre_thumbnail_noimage">
                                    <span>No image</span>
                                </div>

                                <?php } ?>                                 
                            </div>
                            <a href="#" class="button btn-upload-banner button-primary" id="pyre-banner-upload-mb">Chọn banner</a>
                            <input type="hidden" name="pyre_banner_mobile" value="<?php echo $bn_mb ?>" >
                            <input type="hidden" class="thumbnail_url" name="pyre_banner_mobile_url" value="<?php echo $bn_mb_url ?>" >
                        </div>
                    </div>
                </div>
                <div class="pyre_metabox_field">

                    <label for="pyre_gallery_project_recent">Đường dẫn banner</label>
                
                    <div class="pyre_field">
                
                        <input type="text" class="form-control" name="pyre_slide_link" id="pyre_slide_link" value= "<?php echo esc_attr($slide_link) ?>">
            
                    </div>
                </div>
            </div>
            <!--endtab-->
            <div class="pyre_tab_type pyre_content_video_banner <?php echo $type == 'youtube' ? 'active' : '' ?>">
                <div class="pyre_metabox_field">

                    <label for="pyre_slide_youtube_id">Youtube id
                    <span class="help">example: https://youtu.be/lsy2Al1_u3E your id is: <b>lsy2Al1_u3E</b></span>
                    </label>
                    

                    <div class="pyre_field">

                        <input type="text" class="form-control" name="pyre_slide_youtube_id" id="pyre_slide_youtube_id" value= "<?php echo esc_attr($youtube) ?>">

                    </div>
                </div>
            </div>

            <div class="pyre_metabox_field">

                <label for="pyre_slide_showhide">Cho phép hiển thị</label>

                <div class="pyre_field">

                    <input type="checkbox" name="pyre_slide_showhide" id="pyre_slide_showhide" value="show" <?php echo ($show_hide === 'show') ? 'checked' : '' ?>>

                </div>
            </div>
        </div>
	    <?php
}

function dv_save_slider_data($post_id){

        $nonce_name   = isset( $_POST['dv_slide_nonce'] ) ? $_POST['dv_slide_nonce'] : '';
        $nonce_action = 'dv_save_slider_data';
      
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

    if (array_key_exists('pyre_slide_link', $_POST)) {

        update_post_meta( $post_id, '_slide_link', sanitize_text_field($_POST['pyre_slide_link']) );

    }
    if (array_key_exists('pyre_banner_mobile_url', $_POST)) {

        update_post_meta( $post_id, '_banner_mobile_url', sanitize_text_field($_POST['pyre_banner_mobile_url']) );

    }
    if (array_key_exists('pyre_banner_desktop_url', $_POST)) {

        update_post_meta( $post_id, '_banner_desktop_url', sanitize_text_field($_POST['pyre_banner_desktop_url']) );

    }
    if (array_key_exists('pyre_slide_type', $_POST)) {

        update_post_meta( $post_id, '_slide_type', sanitize_text_field($_POST['pyre_slide_type']) );

    }
    if (array_key_exists('pyre_banner_desktop', $_POST)) {

        update_post_meta( $post_id, '_banner_desktop', $_POST['pyre_banner_desktop'] );

    }
    if (array_key_exists('pyre_banner_mobile', $_POST)) {

        update_post_meta( $post_id, '_banner_mobile', $_POST['pyre_banner_mobile'] );

    }
    if (array_key_exists('pyre_slide_youtube_id', $_POST)) {

        update_post_meta( $post_id, '_slide_youtube_id', sanitize_text_field($_POST['pyre_slide_youtube_id']) );

    }
   

        update_post_meta( $post_id, '_slide_showhide', $_POST['pyre_slide_showhide'] );

    
   
}

add_action('save_post', 'dv_save_slider_data');
