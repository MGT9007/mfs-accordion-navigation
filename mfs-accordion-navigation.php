<?php
/**
 * Plugin Name: MFS Accordion Navigation
 * Plugin URI: https://myfutureself.digital
 * Description: Adds accordion functionality to WordPress navigation blocks for My Future Self Digital
 * Version: 1.0.0
 * Author: MisterT9007
 * Author URI: https://myfutureself.digital
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: mfs-accordion-nav
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Plugin Class
 */
class MFS_Accordion_Navigation {
    
    /**
     * Plugin version
     */
    const VERSION = '1.0.0';
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
    }
    
    /**
     * Enqueue CSS and JavaScript
     */
    public function enqueue_assets() {
        // Enqueue CSS
        wp_enqueue_style(
            'mfs-accordion-nav-css',
            plugin_dir_url(__FILE__) . 'css/accordion-nav.css',
            array(),
            self::VERSION
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'mfs-accordion-nav-js',
            plugin_dir_url(__FILE__) . 'js/accordion-nav.js',
            array(),
            self::VERSION,
            true
        );
    }
}

// Initialize the plugin
function mfs_accordion_navigation_init() {
    new MFS_Accordion_Navigation();
}
add_action('plugins_loaded', 'mfs_accordion_navigation_init');
