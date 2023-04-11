<?php 
$options = get_option( 'dvu_options_header' );
$label = $args['label_for'];
$data = isset($options[$label]) ? $options[$label] : null;
echo '<pre>';
print_r($options);
echo '</pre>';
?>
<div class="pyre_row">
  <div class="pyre_col">
    <div class="pyre_metabox_field">
      <label for="<?php echo $label; ?>_description">Mô tả</label>
      <div class="pyre_field">
        <textarea id="<?php echo $label; ?>_description" class="form-control"
          name="dvu_options_header[<?php echo esc_attr( $args['label_for'] ); ?>][description]" rows="3"
          cols="20"><?php echo isset($data['description']) ? sanitize_text_field( $data['description'] ) : ""; ?></textarea>
      </div>
    </div>
  </div>
</div>