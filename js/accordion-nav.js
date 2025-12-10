/**
 * MFS Accordion Navigation JavaScript
 * Version: 1.0.0
 */

(function() {
    'use strict';
    
    /**
     * Initialize accordion navigation
     */
    function initAccordionNav() {
        // Find all navigation items with submenus
        const navItems = document.querySelectorAll('.wp-block-navigation-item.has-child');
        
        if (navItems.length === 0) {
            return;
        }
        
        navItems.forEach(function(item) {
            const link = item.querySelector('a, .wp-block-navigation-item__content');
            const submenu = item.querySelector('.wp-block-navigation__submenu-container');
            
            if (!link || !submenu) {
                return;
            }
            
            // Check if this is the current page or ancestor
            const isCurrent = item.classList.contains('current-menu-item') || 
                            item.classList.contains('current-menu-parent') ||
                            item.classList.contains('current-menu-ancestor');
            
            // Set initial state - open if current
            if (isCurrent) {
                item.classList.add('is-open');
                submenu.classList.add('is-active');
            }
            
            // Add click handler for accordion behavior
            link.addEventListener('click', function(e) {
                // If already open and clicking the same link, allow navigation
                if (item.classList.contains('is-open') && link.getAttribute('href') !== '#') {
                    // Check if we're clicking a link vs just toggling
                    const href = link.getAttribute('href');
                    if (href && href !== '#' && href !== '') {
                        return; // Allow navigation
                    }
                }
                
                // Prevent default to handle accordion
                e.preventDefault();
                e.stopPropagation();
                
                // Close all sibling items at the same level
                const parentContainer = item.parentElement;
                const siblings = parentContainer.querySelectorAll(':scope > .wp-block-navigation-item.has-child');
                
                siblings.forEach(function(sibling) {
                    if (sibling !== item) {
                        sibling.classList.remove('is-open');
                        const siblingSubmenu = sibling.querySelector('.wp-block-navigation__submenu-container');
                        if (siblingSubmenu) {
                            siblingSubmenu.classList.remove('is-active');
                        }
                    }
                });
                
                // Toggle current item
                const isCurrentlyOpen = item.classList.contains('is-open');
                
                if (isCurrentlyOpen) {
                    item.classList.remove('is-open');
                    submenu.classList.remove('is-active');
                } else {
                    item.classList.add('is-open');
                    submenu.classList.add('is-active');
                }
            });
            
            // Optional: Allow double-click to navigate if accordion is open
            let clickCount = 0;
            let clickTimer = null;
            
            link.addEventListener('click', function(e) {
                clickCount++;
                
                if (clickCount === 1) {
                    clickTimer = setTimeout(function() {
                        clickCount = 0;
                    }, 300);
                } else if (clickCount === 2) {
                    clearTimeout(clickTimer);
                    clickCount = 0;
                    
                    // Double-click detected - allow navigation
                    const href = link.getAttribute('href');
                    if (href && href !== '#' && href !== '') {
                        window.location.href = href;
                    }
                }
            });
        });
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
