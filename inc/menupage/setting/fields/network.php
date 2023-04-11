<?php 
    $options = get_option( 'dvu_options_home' );
    // echo '<pre>';
    // print_r($options);
    // echo '</pre>';
    $label = $args['label_for'];

?>
    <h4>Hình nền</h4>
    <div class="dvu-row">
        <div class="dvu-col">
            <input 
                name="dvu_options_home[<?php echo esc_attr( $args['label_for'] ); ?>]"
                class="pyre_field"
            />
        </div>
    </div>
<?php

