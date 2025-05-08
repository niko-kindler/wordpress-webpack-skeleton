<?php

/**
 * Load locale Time
 */
setlocale(LC_TIME, 'de_DE.UTF-8');

/**
 * Add scripts and styles. For dev Mode change the address to your address.
 * wp_enqueue_script( string $handle, string $src = '', string[] $deps = array(), string|bool|null $ver = false, bool $in_footer = false )
 */
add_action('wp_enqueue_scripts', 'my_theme_scripts_and_styles');
function my_theme_scripts_and_styles()
{
    if (strstr($_SERVER['SERVER_NAME'], 'kidz-ev.local')) {
        wp_enqueue_script('main-js', 'http://localhost:3000/bundle.js', null, null, true);
    } else {
        wp_enqueue_script('vendors-js', get_theme_file_uri('/dist/index.e2388769dcdc8d4e99bd.js'), null, '1.0', true);
        wp_enqueue_script('main-js', get_theme_file_uri('/dist/vendors.a555a53aa0c985b96c00.js'), null, '1.0', true);
        wp_enqueue_style('our-main-styles', get_theme_file_uri('/dist/styles.e2388769dcdc8d4e99bd.css'));
    }
}

/**
 * Activate features of Wordpress
 */
add_theme_support('title-tag'); // Title Bar enable
add_theme_support('post-thumbnails'); // Thumbnails enable

/**
 * Register menus
 */
register_nav_menu('header-menu-location', 'Header Menu Location');
register_nav_menu('footer-menu-location', 'Footer Menu Location');

/**
 * Set all scripts to "defer" (loads in parallel to website but waits running until site is completed)
 */
function defer_parsing_of_js($url)
{
    if (is_user_logged_in()) {
        return $url;
    } //don't break WP Admin
    if (false === strpos($url, '.js')) {
        return $url;
    }
    if (strpos($url, 'jquery')) {
        return $url;
    }
    return str_replace(' src', ' defer src', $url);
}
add_filter('script_loader_tag', 'defer_parsing_of_js', 10);