<?php 

require_once(ABSPATH . 'wp-admin/includes/screen.php');

function dvu_register_product_metabox(){
    add_meta_box( 
        'dv_product_information_mb', 
        esc_html__( 'Đặc điểm', 'dvtemplate' ), 
        'dvu_product_information_cb', 
        'product', 
        'advanced', 
        'high' );

    add_meta_box( 
        'dv_product_mb', 
        esc_html__( 'Thông tin thêm', 'dvtemplate' ), 
        'dvu_product_content_cb', 
        'product', 
        'advanced', 
        'high' );


   
}
add_action('add_meta_boxes', 'dvu_register_product_metabox');

function dvu_product_information_cb($post){

        

        $meta_content = get_post_meta($post->ID, '_product_information', true);
        
        wp_editor($meta_content, 'meta_content_editor', array(
            'wpautop'               =>  true,
            'media_buttons' =>      true,
            'textarea_name' =>      'pyre_product_information',
            'textarea_rows' =>      20,
            'teeny'                 =>  true
    ));
}


function dvu_product_content_cb($post){

	    wp_nonce_field( 'dv_save_product_data', 'dv_product_nonce' );

        $gallery = get_post_meta($post->ID, '_gallery_product_id', true);
        $barcode = get_post_meta($post->ID, '_product_barcode', true);
        $size = get_post_meta($post->ID, '_product_size', true);
        $weight = get_post_meta($post->ID, '_product_weight', true);
        $new = get_post_meta($post->ID, '_product_new', true);
        $filedownload = get_post_meta($post->ID, '_product_download', true);

    
       ?>
        <div class="pyre_row">
            <div class="pyre_metabox_field pyre_col">
                <label for="pyre_product_barcode">Mã sản phẩm</label>
                <div class="pyre_field">
                    <input type="text" class="form-control" name="pyre_product_barcode" id="pyre_product_barcode" value="<?php echo $barcode ?>">
                </div>
            </div>

            <div class="pyre_metabox_field pyre_col">
                <label for="pyre_product_size">Kích thước (dai x rong x cao (mm))</label>
                <div class="pyre_field">
                    <input type="text" class="form-control" name="pyre_product_size" id="pyre_product_size" value="<?php echo $size ?>">
                </div>
            </div>
        </div>
        <div class="pyre_row">
            <div class="pyre_metabox_field pyre_col">

                <label for="pyre_product_weight">Trọng lượng (kg)</label>

                <div class="pyre_field">

                    <input type="text" class="form-control" name="pyre_product_weight" id="pyre_product_weight" value="<?php echo $weight ?>">

                </div>
            </div>
            <div class="pyre_metabox_field pyre_col">

                <label for="pyre_product_download">Dowload</label>
            
                <div class="pyre_field">
                    <select name="pyre_product_download" id="pyre_product_download">
                        <option value="">Chọn file</option>
                        <?php 
                            $args = array(
                                'post_type' => 'download',
                                'posts_per_page' => -1
                            );
                            $query = new WP_Query($args);
                            
                            if ($query->have_posts() ) : 
                            
                                while ( $query->have_posts() ) : $query->the_post();
                                $id = get_the_ID();
                                ?>
                                    <option value="<?php echo $id ?>" <?php echo ($filedownload == $id) ? 'selected' : '' ?>><?php the_title() ?></option>
                                        <?php 
                                endwhile;
                        
                                wp_reset_postdata();
                            endif;

                        ?>
    
                    </select>
                
                </div>
        </div>
            
        </div>
        <div class="pyre_metabox_field ">

        <label for="pyre_product_new">Sản phẩm mới</label>

            <div class="pyre_field">

                <input type="checkbox" class="form-control" name="pyre_product_new" id="pyre_product_new" <?php echo  ($new != null) ? 'checked' : '' ?>>

            </div>
        </div>
        
       
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
                <input type="hidden" class="form-control" name="pyre_gallery_product" id="pyre_gallery_product"
                    value="<?php echo esc_attr($gallery) ?>">
                <a href="#" class="button btn-upload-gallery-multi button-primary" id="pr-gallery-upload">Chọn hình ảnh</a>
                <?php if($gallery){ ?>
                <a href="#" class="button btn-remove-gallery" id="pr-gallery-remove">xóa tất cả</a>
                <?php } ?>


            </div>
        </div>

<?php
}

function dv_save_product_data($post_id){

        $nonce_name   = isset( $_POST['dv_product_nonce'] ) ? $_POST['dv_product_nonce'] : '';
        $nonce_action = 'dv_save_product_data';
      
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

  

    if (array_key_exists('pyre_gallery_product', $_POST)) {

        update_post_meta( $post_id, '_gallery_product_id', sanitize_text_field($_POST['pyre_gallery_product']) );

    }
    if (array_key_exists('pyre_product_barcode', $_POST)) {

        update_post_meta( $post_id, '_product_barcode', sanitize_text_field($_POST['pyre_product_barcode']) );

    }
    if (array_key_exists('pyre_product_size', $_POST)) {

        update_post_meta( $post_id, '_product_size', sanitize_text_field($_POST['pyre_product_size']) );

    }
    if (array_key_exists('pyre_product_weight', $_POST)) {

        update_post_meta( $post_id, '_product_weight', sanitize_text_field($_POST['pyre_product_weight']) );

    }
    if ( isset( $_POST['pyre_product_download'] ) ){

        update_post_meta( $post_id, '_product_download',  $_POST['pyre_product_download'] );
    }
  

        update_post_meta( $post_id, '_product_new',  $_POST['pyre_product_new'] );
    
    if ( isset( $_POST['pyre_product_information'] ) ){

        update_post_meta( $post_id, '_product_information',  $_POST['pyre_product_information'] );
    }
	

   
}

add_action('save_post', 'dv_save_product_data');