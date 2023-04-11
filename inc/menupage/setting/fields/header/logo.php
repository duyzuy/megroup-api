<?php 
    $options = get_option( 'dvu_options_home' );
    echo '<pre>';
    print_r($options);
    echo '</pre>';
    $label = $args['label_for'];

?>
     <input 
                id="dvu_field_logo"
                name="dvu_field_logo[logo]"
                class="pyre_field"
            />
<?php

