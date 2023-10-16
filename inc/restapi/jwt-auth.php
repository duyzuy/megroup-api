<?php 

function mod_jwt_auth_token_before_dispatch( $data, $user ) {
 

    $data["user_role"] = $user->roles[0];
    $data["is_verified"] = get_user_meta($user->ID, "_dv_is_verified", true);

    return $data;
}

add_filter( 'jwt_auth_token_before_dispatch', 'mod_jwt_auth_token_before_dispatch', 10, 2 );