<?php 
$options = get_option( 'dvu_options' );
// print_r($options);
?>
    <select id="<?php echo esc_attr( $args['label_for'] ); ?>"
    data-custom="<?php echo esc_attr( $args['dvu_custom_data'] ); ?>"
    name="dvu_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
    <option value="red"
        <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'red', false ) ) : ( '' ); ?>>
        <?php esc_html_e( 'red pill', 'dvutheme' ); ?>
    </option>
    <option value="blue"
        <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'blue', false ) ) : ( '' ); ?>>
        <?php esc_html_e( 'blue pill', 'dvutheme' ); ?>
    </option>
    </select>

<?php