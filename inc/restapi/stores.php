<?php 


// Make sure to use PHP >= 5.4
add_action( 'rest_api_init', function () {
        // Field name to register.
             $fields = [
            '_store_address',
            '_store_email',
            '_store_phone',
            '_store_hotline',
            '_store_fax',
            '_store_website',
            '_store_website_link',
            '_store_lat',
            '_store_lang' 
        ];
        foreach($fields as $field){
       
            register_rest_field( 'store', $field,
                array(
                    'get_callback'    => function ( $object ) use ( $field ) {
                        // Get field as single value from post meta.
                        return get_post_meta( $object['id'], $field, true );
                    },
                    'update_callback' => function ( $value, $object ) use ( $field ) {
                        // Update the field/meta value.
                        update_post_meta( $object->ID, $field, $value );
                    },
                    'schema'          => array(
                        'type'        => 'string',
                        'arg_options' => array(
                            'sanitize_callback' => function ( $value ) {
                                // Make the value safe for storage.
                                return sanitize_text_field( $value );
                            },
                            'validate_callback' => function ( $value ) {
                                // Valid if it contains exactly 10 English letters.
                                return (bool) preg_match( '/\A[a-z]{10}\Z/', $value );
                            },
                        ),
                    ),
                )
            );
        }
    }
);