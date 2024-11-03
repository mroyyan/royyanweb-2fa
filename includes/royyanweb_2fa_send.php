<?php
function royyanweb_2fa_send($user_login, $user)
{
    $number = get_option('royyanweb_2fa_number');
    $message_format = get_option('royyanweb_2fa_message');
    $appkey = get_option('royyanweb_2fa_appkey');
    $authkey = get_option('royyanweb_2fa_authkey');

    $code = rand(100000, 999999);
    $message = str_replace('{CODE}', $code, $message_format);

    $_SESSION['royyanweb_2fa_code'] = $code;

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://wa.royyan.net/api/create-message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'appkey' => $appkey,
            'authkey' => $authkey,
            'to' => $number,
            'message' => $message,
            'sandbox' => 'false'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    if ($response === false) {
        error_log("WhatsApp 2FA failed: " . curl_error($curl));
    } else {
        error_log("WhatsApp 2FA sent: " . $response);
    }

    // Redirect to the custom verify-2fa endpoint
    wp_redirect(home_url('/verify-2fa'));
    exit;
}
add_action('wp_login', 'royyanweb_2fa_send', 10, 2);
