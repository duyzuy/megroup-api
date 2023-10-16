<?php 
add_filter( 'rest_authentication_errors', function( $result ) {

    // if ( true === $result || is_wp_error( $result ) ) {
    
    //     return $result;
    
    // }
    
    // if ( ! is_user_logged_in() && ! user_can( get_current_user_id(), 'export' ) ) {
        
    //     return new WP_Error(
    //         'rest_not_logged_in',
    //         __( 'Silence is golden.' ),
    //         array( 'status' => 401 )
    //     );
    
    // }
    
    // return $result;
    
} );


// add_filter('jwt_auth_expire', 'on_jwt_expire_token',10,1);	
// public function on_jwt_expire_token($exp){		
// 	$days = 1;
// 	$exp = time() + (60 * 60 * 24 * $days);			
// 	return $exp;
// }

add_filter(
    'jwt_auth_expire',
    function ( $expire, $issued_at ) {
        // Set $expire to 2 years.
        $expire = time() + (DAY_IN_SECONDS * 1);

        return $expire;
    },
    10,
    2
);

