<?php
//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'extra_category_fields');

//add extra fields to category edit form callback function
function extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Category Image Url'); ?></label></th>
        <td>
            <input type="text" name="Cat_meta[img]" id="Cat_meta[img]" size="3" style="width:100%;" value="<?php echo $cat_meta['img'] ? $cat_meta['img'] : ''; ?>"><br />
            <span class="description"><?php _e('Image for category: use full url with '); ?></span>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="extra1"><?php _e('extra field'); ?></label></th>
        <td>
            <input type="text" name="Cat_meta[extra1]" id="Cat_meta[extra1]" size="25" style="width:100%;" value="<?php echo $cat_meta['extra1'] ? $cat_meta['extra1'] : ''; ?>"><br />
            <span class="description"><?php _e('extra field'); ?></span>
        </td>
    </tr>
<?php
}


// save extra category extra fields hook
add_action ( 'edited_category', 'save_extra_category_fileds');

// save extra category extra fields callback function
function save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}



//first get the current category ID
$cat_id = get_query_var('cat');

//then i get the data from the database
$cat_data = get_option("category_$cat_id");

//and then i just display my category image if it exists
if (isset($cat_data['img'])){
    echo '<div class="category_image"><img src="'.$cat_data['img'].'"></div>';
}
