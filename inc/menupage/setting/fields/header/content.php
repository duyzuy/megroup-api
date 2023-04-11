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
    <div class="pyre_metabox_field site-name">
      <label for="<?php echo $label; ?>_site_name">Tên website</label>
      <div class="pyre_field">
        <input id="<?php echo $label; ?>_site_name"
          name="dvu_options_header[<?php echo esc_attr( $label ); ?>][site_name]" class="form-control" type="text"
          value="<?php echo (isset($data['site_name']) ? sanitize_text_field($data['site_name']) : ""); ?>" />
      </div>
    </div>
    <div class="pyre_metabox_field site-desc">
      <label for="<?php echo $label; ?>_site_description">Thẻ mô tả</label>
      <div class="pyre_field">
        <textarea id="<?php echo $label; ?>_site_description" class="form-control"
          name="dvu_options_header[<?php echo esc_attr( $args['label_for'] ); ?>][description]" rows="3"
          cols="20"><?php echo isset($data['description']) ? sanitize_text_field( $data['description'] ) : ""; ?></textarea>
      </div>
    </div>
    <div class="pyre_metabox_field site-favicon">
      <label for="<?php echo $label; ?>_site_description">Favicon</label>
      <div class="pyre_field">
        <div class="preview-img">
          <?php 
                    if(isset($data['favicon']) && $data['favicon'] !== ""){
                        echo '<img src="'.sanitize_text_field($data['favicon']).'" style="max-width: 100%"/>';
                    }else{
                        echo '<span>no image</span>';
                    }
                ?>
        </div>
        <?php 
                    if(isset($data['favicon']) && $data['favicon'] !== ""){ 
                        echo '<a class="button button-warning dvu-btn-remove" href="#">xoá bỏ</a>';
                    }else{
                        echo '<a class="button button-primary dvu-btn-upload" href="#">Chọn favicon</a>';
                    }
                ?>
        <input name="dvu_options_header[<?php echo esc_attr( $label); ?>][favicon]" type="hidden"
          value="<?php echo (isset($data['favicon']) ? sanitize_text_field($data['favicon']) : ""); ?>" />
      </div>
    </div>

    <div class="pyre_metabox_field site-logo">
      <label for="<?php echo $label; ?>_site_description">Logo</label>
      <div class="pyre_field">
        <div class="preview-img">
          <?php 
                    if(isset($data['logo']) && $data['logo'] !== ""){
                        echo '<img src="'.sanitize_text_field($data['logo']).'" style="max-width: 100%"/>';
                    }else{
                        echo '<span>no image</span>';
                    }
                ?>
        </div>
        <?php 
                    if(isset($data['logo']) && $data['logo'] !== ""){ 
                        echo '<a class="button button-warning dvu-btn-remove" href="#">xoá bỏ</a>';
                    }else{
                        echo '<a class="button button-primary dvu-btn-upload" href="#">Chọn logo</a>';
                    }
                ?>
        <input name="dvu_options_header[<?php echo esc_attr( $label); ?>][logo]" type="hidden"
          value="<?php echo (isset($data['logo']) ? sanitize_text_field($data['logo']) : ""); ?>" />
      </div>
    </div>

  </div>
</div>