<?php
/**
 * Plugin Name: MFS Accordion Navigation
 * Plugin URI: https://myfutureself.digital
 * Description: Adds accordion functionality to WordPress navigation blocks for My Future Self Digital
 * Version: 1.3.0
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
    const VERSION = '1.3.0';
    
    /**
     * Constructor
     */
    public function __construct() {
        // Use wp_head and wp_footer for better compatibility with WordPress.com
        add_action('wp_head', array($this, 'add_inline_css'), 100);
        add_action('wp_footer', array($this, 'add_inline_js'), 100);
        
        // Also try traditional enqueue as backup
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'), 999);
    }
    
    /**
     * Add inline CSS directly to head
     */
    public function add_inline_css() {
        $is_logged_in = is_user_logged_in();
        
        // Add body class for easier CSS targeting
        add_filter('body_class', function($classes) use ($is_logged_in) {
            if (!$is_logged_in) {
                $classes[] = 'mfs-logged-out';
            } else {
                $classes[] = 'mfs-logged-in';
            }
            return $classes;
        });
        ?>
        <style id="mfs-accordion-nav-css">
        /**
         * MFS Accordion Navigation Styles
         * Version: 1.3.0
         */

        <?php if (!$is_logged_in): ?>
        /* For logged-out users: Hide all navigation items by default */
        body.mfs-logged-out .wp-block-navigation ul li,
        body.mfs-logged-out .wp-block-page-list > li,
        body.mfs-logged-out .wp-block-pages-list__item {
            display: none !important;
        }
        
        /* Show specific items using data attributes (will be added via JS) */
        body.mfs-logged-out .wp-block-pages-list__item.mfs-show,
        body.mfs-logged-out .wp-block-page-list > li.mfs-show,
        body.mfs-logged-out .wp-block-navigation ul li.mfs-show {
            display: list-item !important;
        }
        <?php endif; ?>

        /* Hide all submenus by default */
        .wp-block-navigation__submenu-container,
        .wp-block-navigation-item .wp-block-navigation__submenu-container,
        .wp-block-pages-list__item .wp-block-navigation__submenu-container,
        li.has-child > ul {
            display: none !important;
        }

        /* Show submenus when active or current */
        .wp-block-navigation__submenu-container.is-active,
        .wp-block-navigation-item.current-menu-item > .wp-block-navigation__submenu-container,
        .wp-block-navigation-item.current-menu-parent > .wp-block-navigation__submenu-container,
        .wp-block-navigation-item.current-menu-ancestor > .wp-block-navigation__submenu-container,
        .wp-block-pages-list__item.current-menu-item > .wp-block-navigation__submenu-container,
        .wp-block-pages-list__item.current-menu-parent > .wp-block-navigation__submenu-container,
        .wp-block-pages-list__item.current-menu-ancestor > .wp-block-navigation__submenu-container,
        li.has-child.is-open > ul {
            display: block !important;
        }

        /* Add indicator arrows for expandable items */
        .wp-block-navigation-item.has-child > .wp-block-navigation-item__content::after,
        .wp-block-navigation-item.has-child > a::after,
        .wp-block-navigation-item.wp-block-navigation-submenu > .wp-block-navigation-item__content::after,
        .wp-block-navigation-item.wp-block-navigation-submenu > a::after,
        .wp-block-pages-list__item.has-child > a::after,
        li.has-child > a::after {
            content: " ▸";
            font-size: 0.8em;
            margin-left: 5px;
            transition: transform 0.3s ease;
            display: inline-block;
        }

        /* Rotate arrow when open */
        .wp-block-navigation-item.has-child.is-open > .wp-block-navigation-item__content::after,
        .wp-block-navigation-item.has-child.is-open > a::after,
        .wp-block-navigation-item.wp-block-navigation-submenu.is-open > .wp-block-navigation-item__content::after,
        .wp-block-navigation-item.wp-block-navigation-submenu.is-open > a::after,
        .wp-block-pages-list__item.has-child.is-open > a::after,
        li.has-child.is-open > a::after {
            content: " ▾";
            transform: rotate(0deg);
        }

        /* Smooth transitions for submenu appearance */
        .wp-block-navigation__submenu-container {
            transition: all 0.3s ease;
        }

        /* Optional: Add some indentation for better hierarchy visibility */
        .wp-block-navigation__submenu-container .wp-block-navigation-item {
            padding-left: 15px;
        }

        /* Optional: Hover effect for interactive feedback */
        .wp-block-navigation-item.has-child > a:hover::after,
        .wp-block-navigation-item.has-child > .wp-block-navigation-item__content:hover::after,
        .wp-block-navigation-item.wp-block-navigation-submenu > a:hover::after,
        .wp-block-navigation-item.wp-block-navigation-submenu > .wp-block-navigation-item__content:hover::after,
        .wp-block-pages-list__item.has-child > a:hover::after,
        li.has-child > a:hover::after {
            opacity: 0.7;
        }

        /* Ensure clickable area for accordion functionality */
        .wp-block-navigation-item.has-child > a,
        .wp-block-navigation-item.has-child > .wp-block-navigation-item__content,
        .wp-block-navigation-item.wp-block-navigation-submenu > a,
        .wp-block-navigation-item.wp-block-navigation-submenu > .wp-block-navigation-item__content,
        .wp-block-pages-list__item.has-child > a,
        li.has-child > a {
            cursor: pointer;
        }
        </style>
        <?php
    }
    
    /**
     * Add inline JavaScript directly to footer
     */
    public function add_inline_js() {
        $is_logged_in = is_user_logged_in();
        ?>
        <script id="mfs-accordion-nav-js">
        /**
         * MFS Accordion Navigation JavaScript
         * Version: 1.3.0
         */

        (function() {
            'use strict';
            
            console.log('MFS Accordion Navigation: Initializing...');
            
            <?php if (!$is_logged_in): ?>
            // For logged-out users, show only specific menu items
            console.log('MFS Accordion Navigation: User is logged out, filtering menu items');
            
            // Find all navigation items
            const allNavItems = document.querySelectorAll(
                '.wp-block-pages-list__item, ' +
                '.wp-block-page-list > li, ' +
                '.wp-block-navigation ul li'
            );
            
            allNavItems.forEach(function(item) {
                const link = item.querySelector('a');
                if (!link) return;
                
                const href = link.getAttribute('href') || '';
                const text = link.textContent.toLowerCase().trim();
                
                // Show these specific items
                if (
                    text.includes('about my future self') ||
                    text === 'about my future self digital' ||
                    href.includes('/about') ||
                    text === 'login' ||
                    href.includes('/login') ||
                    href === window.location.origin + '/' ||
                    href === window.location.origin ||
                    text === 'home' ||
                    text === 'homepage' ||
                    item.classList.contains('current-menu-item') // Current page
                ) {
                    item.classList.add('mfs-show');
                    console.log('MFS Accordion Navigation: Showing item - ' + text);
                }
            });
            
            return; // Don't initialize accordion for logged-out users
            <?php endif; ?>
            
            /**
             * Initialize accordion navigation
             */
            function initAccordionNav() {
                console.log('MFS Accordion Navigation: Starting initialization');
                
                // Find all navigation items with submenus - try multiple selectors
                // Support both Navigation block and Pages List block
                const navItems = document.querySelectorAll(
                    '.wp-block-navigation-item.has-child, ' +
                    '.wp-block-navigation-item.wp-block-navigation-submenu, ' +
                    '.wp-block-navigation-submenu, ' +
                    '.wp-block-pages-list__item.has-child, ' +
                    'li.has-child'
                );
                
                console.log('MFS Accordion Navigation: Found ' + navItems.length + ' items with submenus');
                
                if (navItems.length === 0) {
                    console.log('MFS Accordion Navigation: No submenu items found');
                    return;
                }
                
                navItems.forEach(function(item, index) {
                    console.log('MFS Accordion Navigation: Processing item ' + (index + 1));
                    
                    const link = item.querySelector('a, .wp-block-navigation-item__content');
                    const submenu = item.querySelector('.wp-block-navigation__submenu-container, ul');
                    
                    if (!link) {
                        console.log('MFS Accordion Navigation: No link found for item ' + (index + 1));
                        return;
                    }
                    
                    if (!submenu) {
                        console.log('MFS Accordion Navigation: No submenu found for item ' + (index + 1));
                        return;
                    }
                    
                    console.log('MFS Accordion Navigation: Setting up item ' + (index + 1));
                    
                    // Mark item as having children
                    item.classList.add('has-child');
                    
                    // Check if this is the current page or ancestor
                    const isCurrent = item.classList.contains('current-menu-item') || 
                                    item.classList.contains('current-menu-parent') ||
                                    item.classList.contains('current-menu-ancestor') ||
                                    item.classList.contains('current_page_item') ||
                                    item.classList.contains('current_page_parent') ||
                                    item.classList.contains('current_page_ancestor');
                    
                    // Set initial state - open if current
                    if (isCurrent) {
                        console.log('MFS Accordion Navigation: Item ' + (index + 1) + ' is current, opening');
                        item.classList.add('is-open');
                        submenu.classList.add('is-active');
                        submenu.style.display = 'block';
                    }
                    
                    // Add click handler for accordion behavior
                    link.addEventListener('click', function(e) {
                        console.log('MFS Accordion Navigation: Click on item ' + (index + 1));
                        
                        // Prevent default navigation
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Close all sibling items at the same level
                        const parentContainer = item.parentElement;
                        const siblings = parentContainer.querySelectorAll(':scope > .wp-block-navigation-item');
                        
                        siblings.forEach(function(sibling) {
                            if (sibling !== item) {
                                sibling.classList.remove('is-open');
                                const siblingSubmenu = sibling.querySelector('.wp-block-navigation__submenu-container, ul');
                                if (siblingSubmenu) {
                                    siblingSubmenu.classList.remove('is-active');
                                    siblingSubmenu.style.display = 'none';
                                }
                            }
                        });
                        
                        // Toggle current item
                        const isCurrentlyOpen = item.classList.contains('is-open');
                        
                        if (isCurrentlyOpen) {
                            console.log('MFS Accordion Navigation: Closing item ' + (index + 1));
                            item.classList.remove('is-open');
                            submenu.classList.remove('is-active');
                            submenu.style.display = 'none';
                        } else {
                            console.log('MFS Accordion Navigation: Opening item ' + (index + 1));
                            item.classList.add('is-open');
                            submenu.classList.add('is-active');
                            submenu.style.display = 'block';
                        }
                    });
                });
                
                console.log('MFS Accordion Navigation: Initialization complete');
            }
            
            // Initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAccordionNav);
            } else {
                initAccordionNav();
            }
            
            // Re-initialize after AJAX navigation (if theme uses it)
            document.addEventListener('wp-navigation-loaded', initAccordionNav);
            
        })();
        </script>
        <?php
    }
    
    /**
     * Traditional enqueue as backup
     */
    public function enqueue_assets() {
        // This is kept as a backup method
        // The inline method above should work on WordPress.com
    }
}

// Initialize the plugin
function mfs_accordion_navigation_init() {
    new MFS_Accordion_Navigation();
}
add_action('plugins_loaded', 'mfs_accordion_navigation_init');

// Add admin notice
add_action('admin_notices', 'mfs_accordion_nav_admin_notice');
function mfs_accordion_nav_admin_notice() {
    $screen = get_current_screen();
    if ($screen->id === 'plugins') {
        ?>
        <div class="notice notice-success">
            <p><strong>MFS Accordion Navigation:</strong> Plugin is active and will automatically work with navigation blocks that have submenu items.</p>
        </div>
        <?php
    }
}