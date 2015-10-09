<?php
/**
 * Plugin Name: IVIDF Patent Crawler
 * Plugin URI: http://sayenkodesign.com
 * Description: crawl, cache and display patents data from IVIDF website.
 * Version: 1.0
 * Author: Sayenko Design
 */

require_once 'vendor/autoload.php';

/**
 * WordPress dependencies
 */
global $wpdb;
$dbdelta = function($schema) {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    \dbDelta($schema);
};
$base_url = WP_PLUGIN_URL."/".dirname( plugin_basename( __FILE__ ) );

/**
 * Create Lib Dependencies
 */
$loader = new \Twig_Loader_Filesystem([__DIR__ . '/views']);
$twig = new \Twig_Environment($loader, [
    'debug' => false,
    'cache' => false,
    'auto_reload' => true,
    'strict_variables' => true,
]);

/**
 * Create Plugin Classes
 */
$installer = new \IVIDF\Install\Installer($wpdb, $dbdelta);
$settings = new \IVIDF\Config\Settings($twig);
$cache = new \IVIDF\Cache\Cache($wpdb);
$crawler = new \IVIDF\Crawler\Crawler($cache);
$patents = new \IVIDF\Entity\Patents($crawler, $cache, $wpdb);
$patents_shortcode = new \IVIDF\ShortCode\Patents($twig, $patents);

/**
 * Register Hooks
 */
register_activation_hook(__FILE__, [$installer, 'installSchema']);
add_action('admin_menu', function() use($settings) {
    add_menu_page(
        'IVIDF Patent Settings',
        'IVIDF Patent Settings',
        'administrator',
        'ividf-patent-settings',
        [ $settings, 'buildSettingsPage' ]
    );
});
add_shortcode('ividf_patents', [ $patents_shortcode, 'render' ]);

add_action('wp_enqueue_scripts', function() use($base_url){
    wp_register_script('jquery2', '//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js');
    wp_register_script('ividf-patent-crawler-script', $base_url . '/web/js/ividf-patent-crawler.js', ['jquery2']);
    wp_enqueue_script('ividf-patent-crawler-script');

    wp_register_style('ividf-patent-crawler-style', $base_url . '/web/stylesheets/ividf-patent-crawler.css');
    wp_enqueue_style('ividf-patent-crawler-style');
});