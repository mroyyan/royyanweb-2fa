<?php
function royyanweb_2fa_settings_page()
{
    add_options_page(
        'Royyanweb WhatsApp 2FA Settings',
        'RoyyanWeb 2FA',
        'manage_options',
        'royyanweb_2fa_settings',
        'royyanweb_2fa_settings_html'
    );
}
add_action('admin_menu', 'royyanweb_2fa_settings_page');

function royyanweb_2fa_settings_html()
{
?>
    <div class="wrap">
        <h1>RoyyanWeb 2FA Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('royyanweb_2fa_settings');
            do_settings_sections('royyanweb_2fa_settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

function royyanweb_2fa_settings_init()
{
    register_setting('royyanweb_2fa_settings', 'royyanweb_2fa_number');
    register_setting('royyanweb_2fa_settings', 'royyanweb_2fa_message');
    register_setting('royyanweb_2fa_settings', 'royyanweb_2fa_appkey');
    register_setting('royyanweb_2fa_settings', 'royyanweb_2fa_authkey');

    add_settings_section(
        'royyanweb_2fa_section',
        'Settings',
        null,
        'royyanweb_2fa_settings'
    );

    add_settings_field('royyanweb_2fa_number', 'Nomor WhatsApp', 'royyanweb_2fa_number_callback', 'royyanweb_2fa_settings', 'royyanweb_2fa_section');
    add_settings_field('royyanweb_2fa_message', 'Format Pesan 2FA', 'royyanweb_2fa_message_callback', 'royyanweb_2fa_settings', 'royyanweb_2fa_section');
    add_settings_field('royyanweb_2fa_appkey', 'App Key', 'royyanweb_2fa_appkey_callback', 'royyanweb_2fa_settings', 'royyanweb_2fa_section');
    add_settings_field('royyanweb_2fa_authkey', 'Auth Key', 'royyanweb_2fa_authkey_callback', 'royyanweb_2fa_settings', 'royyanweb_2fa_section');
}

function royyanweb_2fa_number_callback()
{
    $value = get_option('royyanweb_2fa_number', '');
    echo '<input type="text" name="royyanweb_2fa_number" value="' . esc_attr($value) . '">';
}

function royyanweb_2fa_message_callback()
{
    $value = get_option('royyanweb_2fa_message', 'Kode 2FA Anda adalah {CODE}');
    echo '<input type="text" name="royyanweb_2fa_message" value="' . esc_attr($value) . '">';
}

function royyanweb_2fa_appkey_callback()
{
    $value = get_option('royyanweb_2fa_appkey', '');
    echo '<input type="text" name="royyanweb_2fa_appkey" value="' . esc_attr($value) . '">';
}

function royyanweb_2fa_authkey_callback()
{
    $value = get_option('royyanweb_2fa_authkey', '');
    echo '<input type="text" name="royyanweb_2fa_authkey" value="' . esc_attr($value) . '">';
}

add_action('admin_init', 'royyanweb_2fa_settings_init');
