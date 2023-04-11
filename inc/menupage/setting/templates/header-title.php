<?php 
    $options = get_option( 'dvu_options' );
    echo '<pre>';
    print_r($options);
    echo '</pre>';
    $data =  $options[$args['label_for']];
    $title = isset($data['title']) ?  $data['title'] : "";
    $name = isset($data['name']) ?  $data['name'] : "";

?>

<div class="pyre_fields">
    <div class="pyre_col">
        <label for="<?php echo esc_attr( $args['label_for']); ?>_title">Title</label>
        <div class="pyre_control">
            <input 
            id="<?php echo esc_attr( $args['label_for']); ?>_title" 
            name="dvu_options[<?php echo esc_attr( $args['label_for'] ); ?>][title]" 
            value="<?php echo $title; ?>" 
            class="regular-text code"/>
        </div>
    </div>
    
    <div class="pyre_col">
        <label for="<?php echo esc_attr( $args['label_for']); ?>_title">Name</label>
        <div class="pyre_control">
            <input 
            id="<?php echo esc_attr( $args['label_for']); ?>_name" 
            name="dvu_options[<?php echo esc_attr( $args['label_for'] ); ?>][name]" 
            value="<?php echo $name; ?>" 
            class="regular-text code"/>
        </div>
    </div>

</div>
<?php