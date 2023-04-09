<?php 

require_once(ABSPATH . 'wp-admin/includes/screen.php');

function dvu_register_downloadable_metabox(){
    add_meta_box( 
        'dv_download_information_mb', 
        esc_html__( 'Thông tin', 'dvtemplate' ), 
        'dvu_downloadable_information_cb', 
        'download', 
        'advanced', 
        'high' );


   
}
add_action('add_meta_boxes', 'dvu_register_downloadable_metabox');


function dvu_downloadable_information_cb($post){

	    wp_nonce_field( 'dv_save_downloadable_data', 'dv_downloadable_nonce' );

        $file_id = get_post_meta($post->ID, '_downloadable_id', true);
        $url = '';
        $fileId = '';
        
        if($file_id){
            $file = get_post( $file_id );
            $url = $file->guid;
            $fileId = $file->ID;
        }
       
        

       ?>
        <div class="pyre_metabox_field">

            <label for="pyre_downloadable_link">Đường dẫn file</label>

            <div class="pyre_field inline-group">

                <input type="text" class="form-control" id="pyre_downloadable_link" disabled value="<?php echo $url ?>">
                <input type="hidden" name="pyre_downloadable_id" value="<?php echo $fileId ?>">
                <a href="#" class="button btn-upload-file button-primary" id="pr-gallery-upload">Chọn file</a>
            </div>
        </div>
       
        

<?php
}

function dv_save_downloadable_data($post_id){

        $nonce_name   = isset( $_POST['dv_downloadable_nonce'] ) ? $_POST['dv_downloadable_nonce'] : '';
        $nonce_action = 'dv_save_downloadable_data';
      
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

  

    if (array_key_exists('pyre_downloadable_id', $_POST)) {

        update_post_meta( $post_id, '_downloadable_id', sanitize_text_field($_POST['pyre_downloadable_id']) );

    }
    
	

   
}

add_action('save_post', 'dv_save_downloadable_data');


