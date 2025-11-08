/**
 * Main JavaScript file
 *
 * @package BCN_WP_Theme
 */

(function($) {
    'use strict';

    // Initialize on document ready
    $(document).ready(function() {
        // Add class to body when page is scrolled
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 100) {
                $('body').addClass('scrolled');
            } else {
                $('body').removeClass('scrolled');
            }
        });

        // Handle skip link focus
        $('.skip-link').on('click', function(e) {
            var target = $(this).attr('href');
            $(target).attr('tabindex', '-1').focus();
        });

        // Add focus class to menu items on keyboard navigation
        $('.main-navigation').find('a').on('focus blur', function() {
            $(this).parents('li').toggleClass('focus');
        });

        // Handle external links
        $('a[href^="http"]').not('a[href*="' + window.location.hostname + '"]').attr({
            target: '_blank',
            rel: 'noopener noreferrer'
        });
    });

    // Window load events
    $(window).on('load', function() {
        // Remove loading class if present
        $('body').removeClass('loading');
    });

})(jQuery);
