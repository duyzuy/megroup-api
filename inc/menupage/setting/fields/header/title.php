<?php 
    $options = get_option( 'dvu_options_home' );
    echo '<pre>';
    print_r($options);
    echo '</pre>';
    $label = $args['label_for'];

?>
    <input 
                id="background_network"
                name="background_network"
                class="pyre_field"
            />
<?php

