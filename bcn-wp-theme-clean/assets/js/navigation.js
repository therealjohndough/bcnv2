/**
 * Navigation functionality
 *
 * @package BCN_WP_Theme
 */

(function() {
    'use strict';

    // Mobile menu toggle functionality
    function initMobileMenu() {
        const navigation = document.querySelector('.main-navigation');
        if (!navigation) {
            return;
        }

        const menu = navigation.querySelector('ul');
        if (!menu) {
            return;
        }

        // Create mobile menu toggle button if it doesn't exist
        if (!navigation.querySelector('.menu-toggle')) {
            const button = document.createElement('button');
            button.className = 'menu-toggle';
            button.setAttribute('aria-controls', 'primary-menu');
            button.setAttribute('aria-expanded', 'false');
            button.innerHTML = '<span class="menu-toggle-text">Menu</span>';
            navigation.insertBefore(button, menu);

            button.addEventListener('click', function() {
                const isExpanded = button.getAttribute('aria-expanded') === 'true';
                button.setAttribute('aria-expanded', !isExpanded);
                menu.classList.toggle('toggled');
            });
        }

        // Handle submenu toggles on mobile
        const menuItems = menu.querySelectorAll('.menu-item-has-children');
        menuItems.forEach(function(item) {
            const link = item.querySelector('a');
            if (link) {
                const dropdownToggle = document.createElement('button');
                dropdownToggle.className = 'dropdown-toggle';
                dropdownToggle.setAttribute('aria-expanded', 'false');
                dropdownToggle.innerHTML = '<span class="screen-reader-text">Expand child menu</span>';
                
                link.parentNode.insertBefore(dropdownToggle, link.nextSibling);

                dropdownToggle.addEventListener('click', function() {
                    const submenu = item.querySelector('.sub-menu');
                    if (submenu) {
                        const isExpanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
                        dropdownToggle.setAttribute('aria-expanded', !isExpanded);
                        submenu.classList.toggle('toggled');
                    }
                });
            }
        });
    }

    // Smooth scroll for anchor links
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = link.getAttribute('href');
                if (href === '#' || href === '') {
                    return;
                }

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initMobileMenu();
            initSmoothScroll();
        });
    } else {
        initMobileMenu();
        initSmoothScroll();
    }
})();
