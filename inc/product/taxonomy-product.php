<?php
/**
 * Adding Image Field
 * @return void 
 */

function category_add_image( $term ) {
	
	?>
	<div class="form-field">
		<label for="taxImage"><?php _e( 'Image', 'building' ); ?></label>
		<input type="text" name="taxonomy_image" id="taxonomy_image" value="">
	</div>
<?php
}
add_action( 'category_product_add_form_fields', 'category_add_image', 10, 2 );


//addfield in edit
function category_edit_image( $term ) {
	
	// put the term ID into a variable
	$t_id = $term->term_id;
    $term_image = get_term_meta( $t_id, 'taxonomy_image', true ); 
	?>
	<tr class="form-field">
		<th><label for="taxonomy_image"><?php _e( 'Image', 'building' ); ?></label></th>
		 
		<td>	 
			<input type="text" name="taxonomy_image" id="taxonomy_image" value="<?php echo esc_attr( $term_image ) ? esc_attr( $term_image ) : ''; ?>">
		</td>
	</tr>
<?php
}
add_action( 'category_product_edit_form_fields', 'category_edit_image', 10 );


//save image
function category_product_save_image( $term_id ) {
	
	if ( isset( $_POST['taxonomy_image'] ) ) {
		$term_image = $_POST['taxonomy_image'];
		if( $term_image ) {
			 update_term_meta( $term_id, 'taxonomy_image', $term_image );
		}
	} 
		
}  
add_action( 'edited_category_product', 'category_product_save_image' );  
add_action( 'create_category_product', 'category_product_save_image' );

/*
// Add term page
function product_taxonomy_add_new_meta_field() {
	// this will add the custom meta field to the add new term page
	?>
	<div class="form-field">
		<label for="term_meta_image"><?php _e( 'Image Link', 'building' ); ?></label>
		<input type="text" name="term_meta_image" id="term_meta_image" value="">
		<p class="description"><?php _e( 'Enter a value for this field','building' ); ?></p>
	</div>
<?php
}
add_action( 'category_product_add_form_fields', 'product_taxonomy_add_new_meta_field', 10, 2 );


//add to editpage

function product_taxonomy_edit_meta_field($term) {
 
	// put the term ID into a variable
	$t_id = $term->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="term_meta_image"><?php _e( 'Image Link', 'building' ); ?></label></th>
		<td>
			<input type="text" name="term_meta_image" id="term_meta_image" value="<?php echo esc_attr( $term_meta['custom_term_meta'] ) ? esc_attr( $term_meta['custom_term_meta'] ) : ''; ?>">
			<p class="description"><?php _e( 'Enter a value for this field','building' ); ?></p>
		</td>
	</tr>
<?php
}
add_action( 'category_product_edit_form_fields', 'product_taxonomy_edit_meta_field', 10, 2 );


function save_taxonomy_custom_meta( $term_id ) {
	if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['term_meta'][$key] ) ) {
				$term_meta[$key] = $_POST['term_meta'][$key];
			}
		}
		// Save the option array.
		update_option( "taxonomy_$t_id", $term_meta );
	}
}  
add_action( 'edited_category', 'save_taxonomy_custom_meta', 10, 2 );  
add_action( 'create_category', 'save_taxonomy_custom_meta', 10, 2 );
*/
