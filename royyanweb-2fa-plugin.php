<?php
/*
Plugin Name: Royyanweb 2FA
Plugin URI: https://royyan.net
Description: Mengirimkan kode 2FA ke WhatsApp saat login.
Version: 1.0.0
Author: RoyyanWeb
Author URI: https://royyanweb.com
*/

defined('ABSPATH') or die('No script kiddies please!');

// Enqueue CSS
function royyanweb_enqueue_styles()
{
    wp_enqueue_style('royyanweb_style', plugins_url('assets/royyanweb_style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'royyanweb_enqueue_styles');

// Start session, but only when needed
function royyanweb_2fa_start_session()
{
    if (!session_id() && !is_admin()) {
        session_start();
    }
}
add_action('init', 'royyanweb_2fa_start_session');

// Include settings only in admin area
if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . 'includes/royyanweb_settings.php');
}

// Include verification only when on login page
function royyanweb_2fa_include_verify_page()
{
    if (is_page('login') || is_page('verify-2fa')) {
        require_once(plugin_dir_path(__FILE__) . 'includes/royyanweb_2fa_verify.php');
    }
}
add_action('wp', 'royyanweb_2fa_include_verify_page');

// Include the 2FA send logic for login action
require_once(plugin_dir_path(__FILE__) . 'includes/royyanweb_2fa_send.php');

// Tambahkan aturan rewrite untuk membuat URL kustom /verify-2fa
function royyanweb_2fa_rewrite_rule()
{
    add_rewrite_rule('^verify-2fa/?$', 'index.php?royyanweb_2fa_verify=1', 'top');
}
add_action('init', 'royyanweb_2fa_rewrite_rule');

// Tambahkan query var untuk menangkap endpoint /verify-2fa
function royyanweb_2fa_query_vars($vars)
{
    $vars[] = 'royyanweb_2fa_verify';
    return $vars;
}
add_filter('query_vars', 'royyanweb_2fa_query_vars');

// Cek query var dan tampilkan halaman verifikasi jika diperlukan
function royyanweb_2fa_template_redirect()
{
    if (get_query_var('royyanweb_2fa_verify') == 1) {
        require_once plugin_dir_path(__FILE__) . 'includes/royyanweb_2fa_verify.php';
        exit;
    }
}
add_action('template_redirect', 'royyanweb_2fa_template_redirect');

// Flush rewrite rules saat plugin diaktifkan
function royyanweb_2fa_flush_rewrite_rules()
{
    royyanweb_2fa_rewrite_rule();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'royyanweb_2fa_flush_rewrite_rules');

// Flush rewrite rules saat plugin dinonaktifkan
function royyanweb_2fa_remove_rewrite_rules()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'royyanweb_2fa_remove_rewrite_rules');
