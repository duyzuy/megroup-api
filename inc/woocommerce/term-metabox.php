<?php 

    global $pagenow;


    if(!is_admin()){
        return;
    }
 
    if( isset($_GET['taxonomy']) && $_GET['taxonomy'] !== "product_tag" && $_GET['taxonomy'] !== "product_cat" && $_GET['taxonomy'] !== "product_brand"){
  

        $taxonomy_name = $_GET['taxonomy'];

        add_action( "{$taxonomy_name}_edit_form_fields", 'wc_edit_field_by_attribute_type', 10, 2 );
        function wc_edit_field_by_attribute_type($term, $taxonomy) {

            $post_type = $_GET['post_type'];

            if( strcmp($post_type, "product") !== 0){
                return ;
            }

            $tax_id = wc_attribute_taxonomy_id_by_name($taxonomy);
            $taxonomy_data = wc_get_attribute($tax_id);

            if($taxonomy_data->type === "color"){


                $t_id = $term->term_id;
                $term_color = get_term_meta( $t_id, '_attribute_color', true ); 


                ?>

                <tr class="form-field">
                    <th>
                        <label for="term_priority"><?php echo esc_html__( 'Màu sắc', 'dvtheme' ); ?></label>
                    </th>
                    <td>
                        <p class="form-field">
                            <input type="color" id="attr_color_value" name="attr_color_value" value="<?php  echo isset($term_color['value']) ? $term_color['value'] : ""; ?>" />
                        </p>
                    </td>

                </tr>
                <tr class="form-field">
                    <th>
                        <label for="attr_color_name"><?php echo esc_html__( 'Tên màu', 'dvtheme' ); ?></label>
                    </th>
                    <td>
                
                        <input type="text"  id="attr_color_name" name="attr_color_name" value="<?php echo isset($term_color['name']) ? $term_color['name'] : "";?>"/> 
                    
                    </td>

                </tr>

            <?php
            }
        }


        /**
         * create page
         */
 
        add_action( "{$taxonomy_name}_add_form_fields", 'wc_add_field_by_attribute_type', 10, 2 );
        function wc_add_field_by_attribute_type($taxonomy) {
            
            $post_type = $_GET['post_type'];

            if( strcmp($post_type, "product") !== 0){
                return ;
            }

            $id = wc_attribute_taxonomy_id_by_name($taxonomy);
            $taxonomy = wc_get_attribute($id);
        
            if($taxonomy->type === "color"){

            ?> 
                <div class="form-field">
                    <label for="attr_color_value"><?php echo esc_html__( 'Màu sắc', 'dvtheme' ); ?></label>
                    <input type="color"  id="attr_color_value" name="attr_color_value" value="#ffffff"/>
                </div>
                <div class="form-field">
                    <label for="attr_color_name"><?php echo esc_html__( 'Tên màu', 'dvtheme' ); ?></label>
                    <input type="text"  id="attr_color_name" name="attr_color_name" value=""/> 
                </div>
            <?php
            }
        }

        
    }


        if(isset($_POST['taxonomy']) && $_POST['taxonomy'] !== "product_tag" && $_POST['taxonomy'] !== "product_cat"){

  
        //saving data
        
        $taxonomy_name = $_POST['taxonomy'];
        
       
        add_action( "edited_{$taxonomy_name}", "wc_attributes_save", 10, 2 );
        // add_action( "create_{$taxonomy_name}", "wc_attributes_save", 10, 2);

        function wc_attributes_save( $term_id){

     
            $tax_name = $_POST['taxonomy'];


            $tax_id = wc_attribute_taxonomy_id_by_name($tax_name);
            $taxonomy = wc_get_attribute($tax_id);

          
            if($taxonomy->type === "color"){
                $color_name = isset($_POST['attr_color_name']) ? $_POST['attr_color_name'] : "";
                $color_value = isset($_POST['attr_color_value']) ? $_POST['attr_color_value'] : "";
    
                update_term_meta( $term_id, '_attribute_color', array("name" =>  $color_name, "value" => $color_value) );
              
            }
            
         

        }
        
    }

 
 



//save image
// function dvu_post_save( $term_id ) {
	

//     $term_image = $_POST['post_tax_image'];
//     update_term_meta( $term_id, '_p_tax_image', $term_image );
    

    
// }  
// add_action( 'edited_category', 'dvu_post_save' );  
// add_action( 'create_category', 'dvu_post_save' );


// add_action( $taxonomy_name.'_add_form_fields', array ( $this, 'add_category_image' ), 10, 2 );
// add_action( 'created_'.$taxonomy_name, array ( $this, 'save_category_image' ), 10, 2 );
// add_action( $taxonomy_name.'_edit_form_fields', array ( $this, 'update_category_image' ), 10, 2 );
// add_action( 'edited_'.$taxonomy_name, array ( $this, 'updated_category_image' ), 10, 2 );


