<?php 
function wc_attr_type_field_add() {
 
    ?>
        <div class="form-field">
              
        <label for="my-field">Type</label>
            <select name="attribute_type" id="attribute_type">
                <option value="select">Select</option>
                <option value="label">Label</option>
                <option value="color">Color</option>
                <option value="text">Text</option>
            </select>
        </div>
    <?php
}
add_action( 'woocommerce_after_add_attribute_fields', 'wc_attr_type_field_add' );

function wc_attr_type_field_edit() {
    $id = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;
    
    $type = isset($id) ? wc_get_attribute($id)->type : "";

   
    ?>
       <tr class="form-field ">

            <th scope="row" valign="top">
                <label for="attribute_type">Type</label>
            </th>
            <td>
                <select name="attribute_type" id="attribute_type">
                    <option value="select"  <?php echo $type === "select" ? "selected" : "" ?> >Select</option>
                    <option value="label"  <?php echo $type === "label" ? "selected" : "" ?> >Label</option>
                    <option value="color" <?php echo $type === "color" ? "selected" : "" ?> >Color</option>
                    <option value="text" <?php echo $type === "text" ? "selected" : "" ?> >Text</option>

                </select>
            </td>
        </tr>
    <?php
}

add_action( 'woocommerce_after_edit_attribute_fields', 'wc_attr_type_field_edit' );



function wc_attr_type_field_save( $id ) {
    if ( is_admin() && isset( $_POST['attribute_type'] ) ) {
        global $wpdb;
        $table_name =  $wpdb->prefix ."woocommerce_attribute_taxonomies";
        $value = $_POST['attribute_type'];
        $wpdb->update($table_name, array("attribute_type" => $value),array('attribute_id'=>$id));
 
    }
}
add_action( 'woocommerce_attribute_added', 'wc_attr_type_field_save' );
add_action( 'woocommerce_attribute_updated', 'wc_attr_type_field_save' );

