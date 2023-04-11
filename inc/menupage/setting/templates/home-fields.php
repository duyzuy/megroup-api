<?php 
    $options = get_option( 'dvu_options_home' );
    echo '<pre>';
    print_r($options);
    echo '</pre>';
    $label = $args['label_for'];

?>
    <h4>Section 1</h4>
    <div class="dvu-row">
        <div class="dvu-col">
            <input 
                name="<?php echo $label['section']; ?>"
                class="pyre_field"
            />
        </div>
    </div>
<?php

