<?php 


    if(!is_admin()){
        return;
    }
 

    add_action( "product_brand_edit_form_fields", 'wc_edit_field_product_brand', 10, 2 );
    function wc_edit_field_product_brand($term, $taxonomy) {

   // put the term ID into a variable
        $t_id = $term->term_id;
        $term_image = get_term_meta( $t_id, '_brand_logo', true ); 

        ?>
        <tr class="form-field">
          <th>
            <label for="product_brand_image"><?php _e( 'Logo', 'dvutemplate' ); ?></label>
          </th>
          <td class="pyre_field">
            <a href="#" class="button btn-upload-banner">Thay hình ảnh</a>
            <a href="#" class="delete btn-remove-banner">Xoá hình ảnh</a>
            <div class="image-thumbnail-preview pyre_thumbnail">
              <?php 
                if($term_image){
                  echo wp_get_attachment_image( $term_image, 'large', '', array( "class" => "img-responsive" ) ) ;
                }else{
                  echo '<span>No image</span>';
                }
              ?>
            </div>	 
            <input type="hidden" name="product_brand_image" id="product_brand_image" value="<?php echo esc_attr( $term_image ) ? esc_attr( $term_image ) : ''; ?>">
          </td>
        </tr>
      <?php
    }



    /**
     * create page
     */

    add_action( "product_brand_add_form_fields", 'wc_product_brand_create_fields', 10, 2 );
    function wc_product_brand_create_fields($taxonomy) {
        
        ?>
        <div class="form-field pyre_field product_brand-logo">
        <label for="product_brand_image"><?php _e( 'Logo', 'dvutemplate' ); ?></label>
          <a href="#" class="button btn-upload-banner">Chọn logo</a>
          <div class="image-thumbnail-preview pyre_thumbnail" style="width: 100px; height: 100px">
            <span>No image</span>
          </div>
          <input type="hidden" name="product_brand_image" id="product_brand_image" value="">
        </div>
        <?php
      
    }

        

  
        //saving data
        
    $taxonomy_name = "product_brand";
    
    
    add_action( "edited_product_brand", "wc_product_brand_save", 10, 2 );
    add_action( "create_{$taxonomy_name}", "wc_product_brand_save", 10, 2);

    function wc_product_brand_save( $term_id){


         // Check if user has permissions to save data.
        $user = wp_get_current_user();
        $allowed_roles = array( 'editor', 'administrator', 'author' );

        if ( !array_intersect( $allowed_roles, $user->roles ) ) {
          return;
        }
 

      if (array_key_exists('product_brand_image', $_POST)) {
        update_term_meta( $term_id, '_brand_logo', sanitize_text_field($_POST['product_brand_image']) );

      }

    }
