<?php 
$options = get_option( 'dvu_options_home' );
$label = $args['label_for'];
$data = isset($options[$args['label_for']]) ? $options[$args['label_for']] : null;
// echo '<pre>';
// print_r($data);
// echo '</pre>';
?>
<div class="pyre_row">
  <div class="pyre_col">
    <div class="pyre_metabox_field">
      <label for="<?php echo $label; ?>_title">Tiêu đề</label>
      <div class="pyre_field">
        <input id="<?php echo $label; ?>_title"
          name="dvu_options_home[<?php echo esc_attr( $args['label_for'] ); ?>][title]" class="form-control" type="text"
          value="<?php echo (isset($data['title']) ? sanitize_text_field($data['title']) : ""); ?>" />
      </div>
    </div>
    <div class="pyre_metabox_field">
      <label for="<?php echo $label; ?>_description">Mô tả</label>
      <div class="pyre_field">
        <textarea id="<?php echo $label; ?>_description" class="form-control"
          name="dvu_options_home[<?php echo esc_attr( $args['label_for'] ); ?>][description]" rows="3"
          cols="20"><?php echo isset($data['description']) ? sanitize_text_field( $data['description'] ) : ""; ?></textarea>
      </div>
    </div>
    <div class="pyre_metabox_field">
      <label for="<?php echo $label; ?>_background">Hình nền</label>
      <div class="pyre_field">
        <div class="preview-img">
          <?php 
                    if(isset($data['thumbnail']) && $data['thumbnail'] !== ""){
                        echo '<img src="'.sanitize_text_field($data['thumbnail']).'" style="max-width: 100%"/>';
                    }else{
                        echo '<span>no image</span>';
                    }
                ?>
        </div>
        <?php 
                    if(isset($data['thumbnail']) && $data['thumbnail'] !== ""){ 
                        echo '<a class="button button-warning dvu-btn-remove" href="#">xoá bỏ</a>';
                    }else{
                        echo '<a class="button button-primary dvu-btn-upload" href="#">Chọn hình ảnh</a>';
                    }
                ?>
        <input name="dvu_options_home[<?php echo esc_attr( $args['label_for'] ); ?>][thumbnail]" class="form-control"
          type="hidden"
          value="<?php echo (isset($data['thumbnail']) ? sanitize_text_field($data['thumbnail']) : ""); ?>" />
      </div>
    </div>
  </div>
</div>
<?php