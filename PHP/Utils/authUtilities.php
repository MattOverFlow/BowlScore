<?php 

    function set_token_cookie($userid, $remember) {
        $cookie_name = "token";
        $cookie_value = $userid;
        $cookie_options = array(
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'None',
        ); 
        if($remember == "on") {
            $cookie_options['expires'] = time() + 86400 * 365;
        }
        setcookie($cookie_name, $cookie_value, $cookie_options);
    }

?>