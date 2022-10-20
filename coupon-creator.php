<?php
/**
* Plugin Name: Coupon creator
* Description: Coupon creator for sixsilver.pl. It allows to add/remove/print coupons.
* Version: 1.0.0
* Author: Kamil Krygier
* Author URI: https://www.linkedin.com/in/kamil-krygier-132940166
**/ 
    
if(!is_admin())include(plugin_dir_path( __FILE__ ).'form.php');
include(plugin_dir_path( __FILE__ ).'functions/functions.php');

function redirect_admin( $redirect_to, $request, $user ){

    //is there a user to check?

    if ( isset( $user->user_email ) ) {

        //check for admins
        if ( $user->user_email == 'sklep@sixsilver.pl' || $user->user_email == 'agora@sixsilver.pl' ) {

            $homeURL = get_home_url();
            $redirect_to = $homeURL .'/wygeneruj-kupon-284235/';

        }
    }

    return $redirect_to;
}

add_filter( 'login_redirect', 'redirect_admin', 10, 3 );