/**
 * Member Archive JavaScript
 * 
 * Handles filtering, searching, and interactions for the member archive page
 */

(function($) {
    'use strict';
    
    // Initialize when document is ready
    $(document).ready(function() {
        initMemberArchive();
    });
    
    function initMemberArchive() {
        // Initialize filters
        initFilters();
        
        // Initialize search
        initSearch();
        
        // Initialize member cards
        initMemberCards();
        
        // Initialize marquee interactions
        initMarqueeInteractions();
    }
    
    function initFilters() {
        const $membershipFilter = $('#membership-filter');
        const $featuredFilter = $('#featured-filter');
        const $memberGrid = $('#member-grid');
        
        // Filter by membership level
        $membershipFilter.on('change', function() {
            const selectedLevel = $(this).val();
            filterMembers(selectedLevel, $featuredFilter.val());
        });
        
        // Filter by featured status
        $featuredFilter.on('change', function() {
            const selectedFeatured = $(this).val();
            filterMembers($membershipFilter.val(), selectedFeatured);
        });
        
        function filterMembers(membershipLevel, featuredStatus) {
            const $cards = $memberGrid.find('.bcn-member-card');
            
            $cards.each(function() {
                const $card = $(this);
                const cardMembership = $card.data('membership');
                const cardFeatured = $card.data('featured');
                
                let showCard = true;
                
                // Filter by membership level
                if (membershipLevel && !cardMembership.includes(membershipLevel)) {
                    showCard = false;
                }
                
                // Filter by featured status
                if (featuredStatus === 'featured' && cardFeatured !== 'true') {
                    showCard = false;
                }
                
                // Show/hide card with animation
                if (showCard) {
                    $card.fadeIn(300);
                } else {
                    $card.fadeOut(300);
                }
            });
            
            // Update grid layout after filtering
            setTimeout(function() {
                $memberGrid.masonry('layout');
            }, 350);
        }
    }
    
    function initSearch() {
        const $searchInput = $('#search-members');
        const $memberGrid = $('#member-grid');
        let searchTimeout;
        
        $searchInput.on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Debounce search
            searchTimeout = setTimeout(function() {
                searchMembers(searchTerm);
            }, 300);
        });
        
        function searchMembers(searchTerm) {
            const $cards = $memberGrid.find('.bcn-member-card');
            
            if (searchTerm === '') {
                $cards.fadeIn(300);
                return;
            }
            
            $cards.each(function() {
                const $card = $(this);
                const cardTitle = $card.data('title');
                const cardText = $card.text().toLowerCase();
                
                if (cardTitle.includes(searchTerm) || cardText.includes(searchTerm)) {
                    $card.fadeIn(300);
                } else {
                    $card.fadeOut(300);
                }
            });
        }
    }
    
    function initMemberCards() {
        const $memberGrid = $('#member-grid');
        
        // Initialize Masonry layout if available
        if (typeof $.fn.masonry !== 'undefined') {
            $memberGrid.masonry({
                itemSelector: '.bcn-member-card',
                columnWidth: '.bcn-member-card',
                percentPosition: true,
                transitionDuration: '0.3s'
            });
        }
        
        // Add hover effects
        $memberGrid.on('mouseenter', '.bcn-member-card', function() {
            $(this).addClass('bcn-member-card--hover');
        }).on('mouseleave', '.bcn-member-card', function() {
            $(this).removeClass('bcn-member-card--hover');
        });
        
        // Add click tracking
        $memberGrid.on('click', '.bcn-member-card__logo-link, .bcn-member-card__title a', function(e) {
            const memberName = $(this).closest('.bcn-member-card').find('.bcn-member-card__title').text();
            
            // Track member profile view
            if (typeof gtag !== 'undefined') {
                gtag('event', 'member_profile_view', {
                    'member_name': memberName,
                    'event_category': 'member_interaction'
                });
            }
        });
    }
    
    function initMarqueeInteractions() {
        // Pause marquee on hover
        $('.bcn-member-logo-marquee').on('mouseenter', function() {
            $(this).addClass('bcn-marquee--paused');
        }).on('mouseleave', function() {
            $(this).removeClass('bcn-marquee--paused');
        });
        
        // Add click tracking for marquee logos
        $('.bcn-marquee__logo-link').on('click', function() {
            const memberName = $(this).attr('aria-label');
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'marquee_logo_click', {
                    'member_name': memberName,
                    'event_category': 'member_interaction'
                });
            }
        });
    }
    
    // Utility functions
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = function() {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Expose functions globally if needed
    window.BCNMemberArchive = {
        filterMembers: function(membershipLevel, featuredStatus) {
            const $membershipFilter = $('#membership-filter');
            const $featuredFilter = $('#featured-filter');
            $membershipFilter.val(membershipLevel);
            $featuredFilter.val(featuredStatus);
            $membershipFilter.trigger('change');
        },
        searchMembers: function(searchTerm) {
            $('#search-members').val(searchTerm).trigger('input');
        }
    };
    
})(jQuery);