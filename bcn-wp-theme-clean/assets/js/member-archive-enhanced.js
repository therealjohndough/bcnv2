/**
 * Enhanced Member Archive JavaScript
 * Provides advanced filtering, search, and interaction features
 */

(function($) {
    'use strict';

    // Initialize enhanced member archive functionality
    function initMemberArchiveEnhanced() {
        const $container = $('.member-archive-enhanced');
        if (!$container.length) return;

        // Initialize components
        initFilters();
        initSearch();
        initViewToggle();
        initMemberCards();
        initPagination();
        initAccessibility();
    }

    // Enhanced filtering system
    function initFilters() {
        const $form = $('#member-filter-form');
        const $grid = $('#member-grid');
        const $resultsCount = $('.member-archive-enhanced__results-count');
        
        if (!$form.length) return;

        // Filter on form change
        $form.on('change', 'select, input[type="checkbox"]', function() {
            applyFilters();
        });

        // Filter on search input (with debounce)
        const $searchInput = $form.find('input[name="search"]');
        if ($searchInput.length) {
            $searchInput.on('input', debounce(function() {
                applyFilters();
            }, 300));
        }

        function applyFilters() {
            const formData = $form.serialize();
            const $members = $grid.find('.member-card-enhanced');
            let visibleCount = 0;

            // Show loading state
            $grid.addClass('loading');

            // Filter members based on form data
            $members.each(function() {
                const $member = $(this);
                const membershipLevel = $member.data('membership-level');
                const isFeatured = $member.data('featured') === '1';
                const searchTerm = $searchInput.val().toLowerCase();
                const selectedLevel = $form.find('select[name="membership_level"]').val();
                const featuredOnly = $form.find('input[name="featured_only"]').is(':checked');

                let show = true;

                // Filter by membership level
                if (selectedLevel && selectedLevel !== 'all' && membershipLevel !== selectedLevel) {
                    show = false;
                }

                // Filter by featured status
                if (featuredOnly && !isFeatured) {
                    show = false;
                }

                // Filter by search term
                if (searchTerm) {
                    const memberText = $member.text().toLowerCase();
                    if (!memberText.includes(searchTerm)) {
                        show = false;
                    }
                }

                if (show) {
                    $member.show();
                    visibleCount++;
                } else {
                    $member.hide();
                }
            });

            // Update results count
            $resultsCount.text(`${visibleCount} ${visibleCount === 1 ? 'member' : 'members'} found`);

            // Hide/show empty state
            if (visibleCount === 0) {
                $grid.addClass('empty');
            } else {
                $grid.removeClass('empty');
            }

            // Remove loading state
            setTimeout(() => {
                $grid.removeClass('loading');
            }, 300);
        }
    }

    // Enhanced search functionality
    function initSearch() {
        const $searchInput = $('.member-archive-enhanced__filter-search');
        const $searchIcon = $('.member-archive-enhanced__search-icon');

        if (!$searchInput.length) return;

        // Focus management
        $searchInput.on('focus', function() {
            $(this).closest('.member-archive-enhanced__search-container').addClass('focused');
        }).on('blur', function() {
            $(this).closest('.member-archive-enhanced__search-container').removeClass('focused');
        });

        // Clear search on icon click
        $searchIcon.on('click', function() {
            $searchInput.val('').trigger('input');
            $searchInput.focus();
        });
    }

    // View toggle functionality
    function initViewToggle() {
        const $grid = $('#member-grid');
        const $toggles = $('.member-archive-enhanced__view-toggle');

        if (!$toggles.length) return;

        $toggles.on('click', function(e) {
            e.preventDefault();
            
            const viewType = $(this).data('view');
            
            // Update active state
            $toggles.removeClass('active');
            $(this).addClass('active');
            
            // Update grid class
            $grid.removeClass('grid-view list-view').addClass(`${viewType}-view`);
            
            // Store preference
            localStorage.setItem('member-archive-view', viewType);
        });

        // Restore saved view preference
        const savedView = localStorage.getItem('member-archive-view');
        if (savedView) {
            $toggles.filter(`[data-view="${savedView}"]`).click();
        }
    }

    // Enhanced member cards
    function initMemberCards() {
        const $cards = $('.member-card-enhanced');

        $cards.each(function() {
            const $card = $(this);
            
            // Add hover effects
            $card.on('mouseenter', function() {
                $(this).addClass('hovered');
            }).on('mouseleave', function() {
                $(this).removeClass('hovered');
            });

            // Add click tracking
            $card.on('click', function() {
                const memberId = $(this).data('member-id');
                const memberName = $(this).find('.member-card-enhanced__title').text();
                
                // Google Analytics tracking
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'member_card_click', {
                        'member_id': memberId,
                        'member_name': memberName,
                        'event_category': 'member_interaction'
                    });
                }
            });
        });
    }

    // Pagination enhancements
    function initPagination() {
        const $pagination = $('.member-archive-enhanced__pagination-links');
        
        if (!$pagination.length) return;

        // Add smooth scrolling to pagination
        $pagination.on('click', 'a', function(e) {
            const href = $(this).attr('href');
            if (href && href.includes('#')) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.member-archive-enhanced__results').offset().top - 100
                }, 500);
            }
        });
    }

    // Accessibility enhancements
    function initAccessibility() {
        // Keyboard navigation for filters
        $('.member-archive-enhanced__filter').on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                $(this).find('button, select, input').first().focus();
            }
        });

        // ARIA live region for results count
        const $liveRegion = $('<div>', {
            'aria-live': 'polite',
            'aria-atomic': 'true',
            'class': 'sr-only'
        });
        $('.member-archive-enhanced__results-header').append($liveRegion);

        // Update live region when results change
        $(document).on('member-results-updated', function(e, count) {
            $liveRegion.text(`${count} members found`);
        });
    }

    // Utility function for debouncing
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initMemberArchiveEnhanced();
    });

    // Re-initialize on AJAX content load (if using AJAX pagination)
    $(document).on('member-archive-updated', function() {
        initMemberArchiveEnhanced();
    });

})(jQuery);