<?php 

require_once(ABSPATH . 'wp-admin/includes/screen.php');

function dvu_create_metabox_store(){

    add_meta_box( 
        'dvu_store_mb', 
        esc_html__( 'Store information', 'dvutemplate' ), 
        'dvu_store_mtb_callback', 
        'store', 
        'advanced', 
        'high' );
}
add_action('add_meta_boxes', 'dvu_create_metabox_store');

function dvu_store_mtb_callback($post){

	    wp_nonce_field( 'dv_save_store_data', 'dv_store_nonce' );
        
        $address = get_post_meta( $post->ID, '_store_address', true);
        $email = get_post_meta( $post->ID, '_store_email', true);
        $phone = get_post_meta( $post->ID, '_store_phone', true);
        $hotline = get_post_meta( $post->ID, '_store_hotline', true);
        $fax = get_post_meta( $post->ID, '_store_fax', true);
        $website = get_post_meta( $post->ID, '_store_website', true);
        $website_link = get_post_meta( $post->ID, '_store_website_link', true);
        $lat = get_post_meta( $post->ID, '_store_lat', true);
        $lang = get_post_meta( $post->ID, '_store_lang', true);
        
        
    
       ?>   
       <div class="pyre_row">

            <div class="pyre_metabox_field pyre_col">

                <label for="pyre_store_city">Thành phố</label>

                <div class="pyre_field">
                    <select name="pyre_store_city" id="pyre_store_city">
                        <option value="">Chọn thành phố</option>
                        <?php 
                            $taxonomy = 'area_store';
                            $term_parents = get_terms( array(
                                'taxonomy' => $taxonomy,
                                'hide_empty' => false,
                                'parent'    =>  0,
                            ) );

                            $term_post = wp_get_post_terms( $post->ID,  $taxonomy, array( 'fields' => 'ids'));
                                
                           
                            foreach($term_parents as $term_parent){
                                
                                echo '<optgroup label="'. $term_parent->name .'">';
                                    
                                        $term_childs = get_term_children($term_parent->term_id, $taxonomy);
                                        foreach($term_childs as $term_child){

                                            $selected = ($term_post[0] == $term_child) ? "selected" : "";

                                            $term = get_term_by( 'id', $term_child, $taxonomy );
                                            echo '<option '.$selected.' value="'.$term->name.'">'.$term->name.'</option>';
                                        }
                                 echo '</optgroup>';
                            }
                        ?>
                    </select>
              
                </div>
            </div>
            <div class="pyre_metabox_field pyre_col">
                <label for="pyre_store_type">Loại</label>
                <div class="pyre_field">
                <select name="pyre_store_type" id="pyre_store_type">
                        <option value="">Chọn loại</option>
                        <?php 
                            $taxonomy = 'type_store';
                            $terms = get_terms( array(
                                'taxonomy' => $taxonomy,
                                'hide_empty' => false,
                                'parent'    =>  0,
                            ) );
                            $term_post = wp_get_post_terms( $post->ID,  $taxonomy, array( 'fields' => 'ids'));
                            foreach($terms as $term){
                                $selected = ($term_post[0] == $term->term_id) ? "selected" : "";

                                echo '<option '.$selected.' value="'.$term->name.'">'.$term->name.'</option>';
                                    
                            }
                        ?>
                    </select>
                    

                </div>
            </div>
            <div class="pyre_metabox_field pyre_col">
                <label for="pyre_store_cat">Danh mục cửa hàng</label>
                <div class="pyre_field">
                <select name="pyre_store_cat" id="pyre_store_cat">
                        <option value="">Chọn loại</option>
                        <?php 
                            $taxonomy = 'cat_store';
                            $terms = get_terms( array(
                                'taxonomy' => $taxonomy,
                                'hide_empty' => false,
                                'parent'    =>  0,
                            ) );

                            $term_post = wp_get_post_terms( $post->ID,  $taxonomy, array( 'fields' => 'ids'));
                            
                            foreach($terms as $term){
                                $selected = ($term_post[0] == $term->term_id) ? "selected" : "";

                                echo '<option  '.$selected.' value="'.$term->name.'">'.$term->name.'</option>';
                                    
                            }
                        ?>
                    </select>
                    

                </div>
            </div>

        </div>
            <div class="pyre_row">

                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_address">Địa chỉ</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_address" id="pyre_store_address" value="<?php echo $address ?>">

                    </div>
                </div>
                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_email">Email</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_email" id="pyre_store_email" value="<?php echo $email ?>">

                    </div>
                </div>

            </div>
            <div class="pyre_row">


                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_phone">Phone</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_phone" id="pyre_store_phone" value="<?php echo $phone ?>">

                    </div>
                </div>
                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_hotline">Hotline</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_hotline" id="pyre_store_hotline" value="<?php echo $hotline ?>">

                    </div>
                </div>


            </div>
            <div class="pyre_row">
                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_fax">Fax</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_fax" id="pyre_store_fax" value="<?php echo $fax ?>">

                    </div>
                </div>

                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_website">Website</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_website" id="pyre_store_website" placeholder="name" value="<?php echo $website ?>">

                    </div>
                    <div class="pyre_field">

                        <input type="text" name="pyre_store_website_link" id="pyre_store_website_link" placeholder="link" value="<?php echo $website_link ?>">

                    </div>
                </div>


            </div>
            <div class="pyre_row">
                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_lat">Lat</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_lat" id="pyre_store_lat" placeholder="Store's lat" value="<?php echo $lat ?>">

                    </div>
                </div>

                <div class="pyre_metabox_field pyre_col">

                    <label for="pyre_store_lang">Lang</label>

                    <div class="pyre_field">

                        <input type="text" name="pyre_store_lang" id="pyre_store_lang" placeholder="Store's lang" value="<?php echo $lang ?>">

                    </div>
                   
                </div>


            </div>





<?php
}

function dv_save_store_data($post_id){

        $nonce_name   = isset( $_POST['dv_store_nonce'] ) ? $_POST['dv_store_nonce'] : '';
        $nonce_action = 'dv_save_store_data';
      
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

        if (array_key_exists('pyre_store_address', $_POST)) {
            update_post_meta( $post_id, '_store_address', sanitize_text_field($_POST['pyre_store_address']) );
        }

        if (array_key_exists('pyre_store_email', $_POST)) {
            update_post_meta( $post_id, '_store_email', sanitize_text_field($_POST['pyre_store_email']) );
        }

        if (array_key_exists('pyre_store_phone', $_POST)) {
            update_post_meta( $post_id, '_store_phone', sanitize_text_field($_POST['pyre_store_phone']) );
        }

        if (array_key_exists('pyre_store_hotline', $_POST)) {
            update_post_meta( $post_id, '_store_hotline', sanitize_text_field($_POST['pyre_store_hotline']) );
        }

        if (array_key_exists('pyre_store_fax', $_POST)) {
            update_post_meta( $post_id, '_store_fax', sanitize_text_field($_POST['pyre_store_fax']) );
        }

        if (array_key_exists('pyre_store_website', $_POST)) {
            update_post_meta( $post_id, '_store_website', sanitize_text_field($_POST['pyre_store_website']) );
        }
        if (array_key_exists('pyre_store_website_link', $_POST)) {
            update_post_meta( $post_id, '_store_website_link', sanitize_text_field($_POST['pyre_store_website_link']) );
        }
        if (array_key_exists('pyre_store_lat', $_POST)) {
            update_post_meta( $post_id, '_store_lat', sanitize_text_field($_POST['pyre_store_lat']) );
        }
        if (array_key_exists('pyre_store_lang', $_POST)) {
            update_post_meta( $post_id, '_store_lang', sanitize_text_field($_POST['pyre_store_lang']) );
        }
   

        
        //save to taxonomy stores
        if(array_key_exists('pyre_store_type', $_POST) && $_POST['pyre_store_type'] != ''){
            wp_set_object_terms( $post_id, $_POST['pyre_store_type'], 'type_store');
        }
        if(array_key_exists('pyre_store_city', $_POST) && $_POST['pyre_store_city'] != ''){
            wp_set_object_terms( $post_id, $_POST['pyre_store_city'], 'area_store');
        }
        if(array_key_exists('pyre_store_cat', $_POST) && $_POST['pyre_store_cat'] != ''){
            wp_set_object_terms( $post_id, $_POST['pyre_store_cat'], 'cat_store');
        }
    
   
}

add_action('save_post', 'dv_save_store_data');