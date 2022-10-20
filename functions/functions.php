<?php 

function callback_for_setting_up_scripts() {
    // wp_enqueue_style( 'style-coupon-creator', plugin_dir_url( __DIR__ )."assets/css/style.css" );
    wp_enqueue_script( 'script-coupon-creator', plugin_dir_url( __DIR__ )."assets/js/script.js", array(), '1.0.0', true );
    wp_enqueue_script( 'printThis', plugin_dir_url( __DIR__ ) . "jasonday-printThis-23be1f8/printThis.js", array( 'jquery' ), null, false );
    wp_enqueue_script( 'ajax-sender', plugins_url( 'assets/js/ajax-sender.js', __DIR__ ), array( 'jquery' ), null, true );
    wp_localize_script( 'ajax-sender', 'ajax', array(
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'my_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'callback_for_setting_up_scripts', 1000 );    

add_action( 'wp_ajax_create_coupon_code', 'create_coupon_code' );
add_action( 'wp_ajax_nopriv_create_coupon_code', 'create_coupon_code' );

add_action( 'wp_ajax_remove_coupon_code', 'remove_coupon_code' );
add_action( 'wp_ajax_nopriv_remove_coupon_code', 'remove_coupon_code' );

add_action( 'wp_ajax_print_code', 'print_code' );
add_action( 'wp_ajax_nopriv_print_code', 'print_code' );

add_action( 'wp_ajax_add_card', 'add_card' );
add_action( 'wp_ajax_nopriv_add_card', 'add_card' );


function create_coupon_code() 
{
    check_ajax_referer('my_nonce', 'nonce', true);


    $coupon_value = isset( $_POST["coupon_value"] ) ? $_POST["coupon_value"] : '';
    $coupon_expiration = isset( $_POST["coupon_expiration"] ) ? $_POST["coupon_expiration"] : '';
    $coupon_length = 9;
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $tempInt = 0;
    $result = '';
    $minimum_amount = $coupon_value + 1;
    
    do{
        $coupon_code = '';
        for ($i = 1; $i < $coupon_length; $i++) 
        {
            $coupon_code .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        $coupon_code = trim($coupon_code, ' ');

        
        global $wpdb;
        $codesDBcheck = $wpdb->get_results(
            $wpdb->prepare("
                SELECT post_title FROM wp_posts 
                WHERE post_type = 'shop_coupon'     
                AND post_title = %s
            ", $coupon_code)
        );

        if(count($codesDBcheck) < 1 && !is_int($coupon_code)){
            $coupon = new WC_Coupon();
            $coupon->set_code( strtoupper($coupon_code) );
            $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
            $result .= '<div class="six_coupon_message_wrapper">';
            $result .= '<div class="six_coupon_code">Kod bonu: <b>' . strtoupper($coupon_code) . '</b></div>' ;
            $coupon->set_amount( $coupon_value );
            $result .= '<div class="six_coupon_value">Wartość bonu: <b>' . $coupon_value . 'zł</b></div>';
            $coupon->set_free_shipping( true );
            $coupon->set_date_expires( $coupon_expiration );
            $coupon->set_minimum_amount( $minimum_amount );
            $result .= '<div class="six_coupon_expire_date">Data ważności: <b>' . $coupon_expiration . '</b></div>' ;
            $result .= '<p style="text-align:center;"><button id="printThis" class="x-btn">Drukuj bon</button></p>';
            $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});jQuery("#printThis").click(function(){jQuery(".six_coupon_message_wrapper").printThis();});</script>';
            $coupon->set_individual_use( true ); 
            $coupon->set_excluded_product_categories( array( 187, 539 ) );
            $coupon->set_usage_limit( 1 );
            $coupon->set_usage_limit_per_user( 1 );
            $coupon->save();
            // wp_send_json_success( 'Coupon Code: "'. $coupon_code .'", Coupon value: "'. $coupon_value .'", Coupon expire at: "'.$coupon_expiration.'"' );
            echo $result;
        }
        $tempInt++;
        if($tempInt > 999)
        {
            $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
            $result .= '<div class="six_coupon_message_wrapper">';
            $result .= '<div class="six_coupon_code">Wystąpił problem podczas generowania kodu</div>';
            $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
            $result .= '</div>';
            echo $result;
            break;
        }
    }
    while(count($codesDBcheck) != 0 || is_int($coupon_code));

    // $return = 'Coupon Code: "'. $coupon_code .'", Coupon value: "'. $coupon_value .'", Coupon expire at: "'.$coupon_expiration.'"';
    // wp_send_json_success();
}


function remove_coupon_code()
{
    check_ajax_referer('my_nonce', 'nonce', true);
    $coupon_code = isset( $_POST["coupon_code"] ) ? $_POST["coupon_code"] : '';
    $result = '';
    if(wc_get_coupon_id_by_code( $coupon_code ))
    {
        $coupon_data = new WC_Coupon($coupon_code);
        if(!empty($coupon_data->id))
        {
            $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
            $result .= '<div class="six_coupon_message_wrapper">';
            $result .= '<div class="six_coupon_code">Wykorzystano bon: <b>' . strtoupper($coupon_data->code) . '</b></div>';
            // wp_delete_post($coupon_data->id); //if you want to delete coupon
            wp_trash_post($coupon_data->id); //if you want to move coupon to trash (they're stored for several days and after that wordpress deletes them)
            $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
            $result .= '</div>';
            // wp_send_json_success( array( 'message' => 'Coupon: "'. $coupon_code .'" deleted."' ) );
            echo $result;
        }

        else
        {
            $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
            $result .= '<div class="six_coupon_message_wrapper">';
            $result .= '<div class="six_coupon_code">Wystąpił problem z bonem: <b>' . $coupon_code . '</b></div>';
            $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'"assets/images/sixsilver-jubiler-menu-xmark.svg" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
            $result .= '</div>';
            echo $result;
        }
    }
    else
    {
        $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
        $result .= '<div class="six_coupon_message_wrapper">';
        $result .= '<div class="six_coupon_code">Brak bonu o takim kodzie: <b>' . $coupon_code . '</b></div>';
        $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
        $result .= '</div>';
        echo $result;
    }
}


function print_code()
    {
        check_ajax_referer('my_nonce', 'nonce', true);
    
        $coupon_code = isset( $_POST["coupon_code"] ) ? $_POST["coupon_code"] : '';
    
        $result = '';
    
        if($coupon_code != '')$coupon_data = new WC_Coupon($coupon_code);
    
        if(wc_get_coupon_id_by_code( $coupon_code ))
        {
            if($coupon_data)
            {
                $discount_type = '';
                switch($coupon_data->discount_type)
                {
                    case "percent":  $discount_type = "%";
                        break;
                    case "fixed_cart":  $discount_type = "zł";
                        break;
                    default:
                        $discount_type = "zł";
                        break;
                }
                $expiry_date = $coupon_data->get_date_expires('edit');
                $expiry_dateTime = $expiry_date->date('d-m-Y');
    
                $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
                $result .= '<div class="six_coupon_message_wrapper">';
                $result .= '<div class="six_coupon_code">Kod bonu: <b>' . strtoupper($coupon_data->code) . '</b></div>';
                $result .= '<div class="six_coupon_amount">Wartość bonu: <b>' . $coupon_data->amount . $discount_type .'</b></div>';
                $result .= '<div class="six_coupon_date">Data ważności: <b>' . $expiry_dateTime . '</b></div>';
                $result .= '<p style="text-align:center;"><button id="printThis" class="x-btn">Drukuj bon</button></p>';
                $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});jQuery("#printThis").click(function(){jQuery(".six_coupon_message_wrapper").printThis();});</script>';
                $result .= '</div>';
                echo $result;
            }
            else
            {
                $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
                $result .= '<div class="six_coupon_message_wrapper">';
                $result .= '<div class="six_coupon_code">Wystąpił problem z kodem: <b>' . $coupon_code . '</b></div>';
                $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
                $result .= '</div>';
                echo $result;
            }
        }
        else
        {
            $result .= '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';
            $result .= '<div class="six_coupon_message_wrapper">';
            $result .= '<div class="six_coupon_code">Brak bonu o takim kodzie: <b>' . $coupon_code . '</b></div>';
            $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
            $result .= '</div>';
            echo $result;
        }
    }


function add_card()
{
    check_ajax_referer('my_nonce', 'nonce', true);


    $card_code = isset( $_POST["card_code"] ) ? $_POST["card_code"] : '';
    $card_name = isset( $_POST["card_name"] ) ? $_POST["card_name"] : '';
    $card_surname = isset( $_POST["card_surname"] ) ? $_POST["card_surname"] : '';
    $card_mail = isset( $_POST["card_mail"] ) ? $_POST["card_mail"] : '';
    $card_tel = isset( $_POST["card_tel"] ) ? $_POST["card_tel"] : '';
    $card_date = isset( $_POST["card_date"] ) ? $_POST["card_date"] : '';


    $result = '<script>jQuery(".six_loader_wrapper").fadeOut("slow");</script>';


    if($card_code != '' || $card_name != '' || $card_surname != '' || $card_mail != '' || $card_tel != '' || $card_date != '')
    {
        // $password_length = 15;
        // $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuwxyz!@#$%^&*()<>.,';
        // $password = '';
        // for ($i = 1; $i < $password_length; $i++) 
        // {
        //     $password .= $characters[mt_rand(0, strlen($characters) - 1)];
        // }
        $password = wp_generate_password(16);


        $user_id = username_exists( $card_code );
        if ( !$user_id && email_exists($card_mail) == false ) {
            $user_id = wp_create_user( $card_code, $password, $card_mail );
            if( !is_wp_error($user_id) ) {
                $user = get_user_by( 'id', $user_id );
                $user->set_role( 'klient-gold' );
                wp_update_user([
                    'ID' => $user_id, // this is the ID of the user you want to update.
                    'first_name' => $card_name,
                    'last_name' => $card_surname,
                    'billing_first_name' => $card_name,
                    'billing_last_name'=> $card_surname,
                    'billing_email' => $card_mail,
                    'show_admin_bar_front' => FALSE
                ]);
                update_user_meta( $user_id, 'billing_phone', $card_tel );
                update_user_meta( $user_id, 'birthday_date', $card_date );


                global $wpdb;
                $wpdb->get_results("UPDATE `wp_usermeta` SET `meta_value`= 1 WHERE `user_id` = $user_id and `meta_key` = 'mailchimp_woocommerce_is_subscribed'");
                // $wpdb->get_results(
                //     $wpdb->prepare("
                //     UPDATE `wp_usermeta` SET `meta_value`= 1 WHERE `user_id` = %s and `meta_key` = 'mailchimp_woocommerce_is_subscribed'
                //     ", $user_id)
                // );

                $adt_rp_key = get_password_reset_key( $user );
                $rp_link = '<a href="' . wp_login_url()."?action=rp&key=$adt_rp_key&login=" . rawurlencode($card_code) . '">' . wp_login_url()."?action=rp&key=$adt_rp_key&login=" . rawurlencode($card_code) . '</a>';
                
                $message = "<p>Witaj ".$card_name.",<br>";
                $message .= "Karta stałego klienta, została przypisana do twojego konta!<br>";
                $message .= "Na ".get_bloginfo( 'name' )." dla maila ".$card_mail.", zostało utworzone konto.<br>";
                $message .= "Numer twojej karty to: " .$card_code . "<br><br>";
                $message .= "Kliknij tutaj, aby ustawić swoje nowe hasło: <br>";
                $message .= $rp_link.'<br></p>';
                // $message = ob_get_clean(); 
                
                $subject = __(get_bloginfo( 'name') . " - Witaj w klubie SIXSILVER");
                $headers = array();

                add_filter( 'wp_mail_content_type', function( $content_type ) {return 'text/html';});

                $headers[] = 'Od: SIXSILVER JUBILER <kontakt@sixsilver.pl>'."\r\n";
                // $headers[] = 'Content-Type: text/html; charset=UTF-8'."\r\n";
                $sent = wp_mail( $card_mail, $subject, $message, $headers);
                
                // Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
                remove_filter( 'wp_mail_content_type', 'set_html_content_type' );


                $result .= '<div class="six_coupon_message_wrapper">';
                $result .= '<div class="six_card_code">Karta dodana poprawnie</div>';
                if($sent) {
                    $result .= '<div class="six_card_mail">Mail o założeniu nowego konta został wysłany do klienta</div>';
                }//message sent!
                else  {
                    $result .= '<div class="six_card_mail">Mail o założeniu nowego konta <b>NIE</b> został wysłany do klienta</div>';
                }
                // $result .= '<div class="six_card_mail">Mail o założeniu nowego konta został wysłany do klienta</div>';
                $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
                $result .= '</div>';
            }
            else{
                $result .= '<div class="six_coupon_message_wrapper">';
                $result .= '<div class="six_card_code">Błąd, podczas tworzenia użytkownika!</div>';
                $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
                $result .= '</div>';
            }
        }
        else{
            $result .= '<div class="six_coupon_message_wrapper">';
            $result .= '<div class="six_card_code">Podany email lub karta rabatowa, jest już w użyciu!</div>';
            $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
            $result .= '</div>';
        }
    }
    else{
        $result .= '<div class="six_coupon_message_wrapper">';
        $result .= '<div class="six_card_code">Wystąpił problem z przesłanymi danymi!</div>';
        $result .= '<img class="six_couponAdd_popup_close" style="float:right; width: 24px;height: 24px;cursor: pointer;" src="'.plugin_dir_url( __DIR__ ).'assets/images/sixsilver-jubiler-menu-xmark.svg"" alt="Zamknij"></div><script>jQuery(".six_couponAdd_popup_close").click(function(){jQuery(this).parent(".six_coupon_message_wrapper").remove();jQuery(".six_coupon_message_box").removeClass("active");});</script>' ;
        $result .= '</div>';
    }
    echo $result;
}
?>