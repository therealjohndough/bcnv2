/**
 * Enhanced Member Cards JavaScript
 * Handles interactive features and UX enhancements
 *
 * @package BCN_WP_Theme
 */

(function($) {
    'use strict';

    // Initialize enhanced member cards
    function initEnhancedMemberCards() {
        // Quick contact modal functionality
        initQuickContactModals();
        
        // Card hover effects and animations
        initCardAnimations();
        
        // Engagement score animations
        initEngagementScoreAnimations();
        
        // Social media link tracking
        initSocialMediaTracking();
        
        // Card filtering and search enhancements
        initCardFiltering();
    }

    /**
     * Initialize quick contact modals
     */
    function initQuickContactModals() {
        // Open modal
        $(document).on('click', '.member-card-enhanced__quick-contact', function(e) {
            e.preventDefault();
            const memberId = $(this).data('member-id');
            const memberName = $(this).data('member-name');
            const modal = $(`#quick-contact-${memberId}`);
            
            if (modal.length) {
                modal.addClass('active');
                $('body').addClass('modal-open');
                
                // Focus management
                modal.find('.member-card-enhanced__quick-contact-option').first().focus();
                
                // Track interaction
                trackMemberInteraction('quick_contact_opened', memberId, memberName);
            }
        });

        // Close modal
        $(document).on('click', '.member-card-enhanced__quick-contact-close', function(e) {
            e.preventDefault();
            closeQuickContactModal();
        });

        // Close modal on backdrop click
        $(document).on('click', '.member-card-enhanced__quick-contact-modal', function(e) {
            if (e.target === this) {
                closeQuickContactModal();
            }
        });

        // Close modal on escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && $('.member-card-enhanced__quick-contact-modal.active').length) {
                closeQuickContactModal();
            }
        });
    }

    /**
     * Close quick contact modal
     */
    function closeQuickContactModal() {
        $('.member-card-enhanced__quick-contact-modal.active').removeClass('active');
        $('body').removeClass('modal-open');
    }

    /**
     * Initialize card animations and hover effects
     */
    function initCardAnimations() {
        // Staggered card loading animation
        $('.member-card-enhanced').each(function(index) {
            $(this).css({
                'animation-delay': `${index * 0.1}s`,
                'opacity': '0'
            });
            
            setTimeout(() => {
                $(this).css('opacity', '1');
            }, index * 100);
        });

        // Enhanced hover effects
        $('.member-card-enhanced').on('mouseenter', function() {
            $(this).addClass('hovered');
            
            // Animate engagement score
            const engagementRing = $(this).find('.member-card-enhanced__engagement-ring');
            if (engagementRing.length) {
                engagementRing.addClass('pulse');
            }
        }).on('mouseleave', function() {
            $(this).removeClass('hovered');
            $(this).find('.member-card-enhanced__engagement-ring').removeClass('pulse');
        });

        // Logo hover effect
        $('.member-card-enhanced__logo-link').on('mouseenter', function() {
            $(this).find('.member-card-enhanced__logo, .member-card-enhanced__logo-placeholder')
                   .addClass('logo-hover');
        }).on('mouseleave', function() {
            $(this).find('.member-card-enhanced__logo, .member-card-enhanced__logo-placeholder')
                   .removeClass('logo-hover');
        });
    }

    /**
     * Initialize engagement score animations
     */
    function initEngagementScoreAnimations() {
        // Animate engagement scores on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const card = $(entry.target);
                    const engagementScore = card.data('engagement');
                    const fill = card.find('.member-card-enhanced__engagement-fill');
                    
                    if (fill.length && engagementScore) {
                        // Animate the progress ring
                        setTimeout(() => {
                            fill.css('stroke-dasharray', `${engagementScore}, 100`);
                        }, 200);
                        
                        // Add completion class for styling
                        if (engagementScore >= 80) {
                            card.addClass('high-engagement');
                        } else if (engagementScore >= 60) {
                            card.addClass('medium-engagement');
                        } else {
                            card.addClass('low-engagement');
                        }
                    }
                }
            });
        }, { threshold: 0.5 });

        $('.member-card-enhanced').each(function() {
            observer.observe(this);
        });
    }

    /**
     * Initialize social media link tracking
     */
    function initSocialMediaTracking() {
        $('.member-card-enhanced__social-link').on('click', function() {
            const platform = $(this).data('platform') || 
                           $(this).attr('class').match(/--(\w+)/)?.[1] || 
                           'unknown';
            const memberId = $(this).closest('.member-card-enhanced').data('member-id');
            const memberName = $(this).closest('.member-card-enhanced')
                                    .find('.member-card-enhanced__title').text().trim();
            
            trackMemberInteraction('social_click', memberId, memberName, { platform });
        });
    }

    /**
     * Initialize enhanced card filtering
     */
    function initCardFiltering() {
        const $cards = $('.member-card-enhanced');
        const $filters = $('.member-archive__filter-form');
        
        if ($filters.length && $cards.length) {
            // Real-time search
            $filters.find('input[type="search"]').on('input', debounce(function() {
                filterCards();
            }, 300));

            // Level filter
            $filters.find('select[name="membership_level"]').on('change', function() {
                filterCards();
            });

            // Featured filter
            $filters.find('input[name="featured_only"]').on('change', function() {
                filterCards();
            });

            // Reset filters
            $filters.find('.button-secondary').on('click', function(e) {
                e.preventDefault();
                resetFilters();
            });
        }
    }

    /**
     * Filter cards based on current filter values
     */
    function filterCards() {
        const $form = $('.member-archive__filter-form');
        const searchTerm = $form.find('input[type="search"]').val().toLowerCase();
        const selectedLevel = $form.find('select[name="membership_level"]').val();
        const featuredOnly = $form.find('input[name="featured_only"]').is(':checked');
        
        $('.member-card-enhanced').each(function() {
            const $card = $(this);
            const cardTitle = $card.find('.member-card-enhanced__title').text().toLowerCase();
            const cardExcerpt = $card.find('.member-card-enhanced__excerpt').text().toLowerCase();
            const cardLevels = $card.find('.member-card-enhanced__level').map(function() {
                return $(this).attr('class').match(/--(\w+)/)?.[1] || '';
            }).get();
            const isFeatured = $card.hasClass('member-card-enhanced--featured');
            
            let showCard = true;
            
            // Search filter
            if (searchTerm && !cardTitle.includes(searchTerm) && !cardExcerpt.includes(searchTerm)) {
                showCard = false;
            }
            
            // Level filter
            if (selectedLevel && !cardLevels.includes(selectedLevel)) {
                showCard = false;
            }
            
            // Featured filter
            if (featuredOnly && !isFeatured) {
                showCard = false;
            }
            
            // Show/hide card with animation
            if (showCard) {
                $card.fadeIn(300).addClass('filtered-in');
            } else {
                $card.fadeOut(300).removeClass('filtered-in');
            }
        });
        
        // Update results count
        updateResultsCount();
    }

    /**
     * Reset all filters
     */
    function resetFilters() {
        $('.member-archive__filter-form')[0].reset();
        $('.member-card-enhanced').fadeIn(300).addClass('filtered-in');
        updateResultsCount();
    }

    /**
     * Update results count display
     */
    function updateResultsCount() {
        const visibleCount = $('.member-card-enhanced.filtered-in:visible').length;
        const totalCount = $('.member-card-enhanced').length;
        
        let $countDisplay = $('.member-archive__results-count');
        if (!$countDisplay.length) {
            $countDisplay = $('<div class="member-archive__results-count"></div>');
            $('.member-archive__filters').after($countDisplay);
        }
        
        $countDisplay.html(`
            <span class="results-text">
                Showing ${visibleCount} of ${totalCount} members
            </span>
        `);
    }

    /**
     * Track member interactions for analytics
     */
    function trackMemberInteraction(action, memberId, memberName, additionalData = {}) {
        // Basic interaction tracking
        const interaction = {
            action: action,
            member_id: memberId,
            member_name: memberName,
            timestamp: new Date().toISOString(),
            url: window.location.href,
            ...additionalData
        };
        
        // Send to analytics (implement based on your analytics solution)
        if (typeof gtag !== 'undefined') {
            gtag('event', 'member_interaction', {
                'event_category': 'member_cards',
                'event_label': action,
                'custom_parameter_1': memberId,
                'custom_parameter_2': memberName
            });
        }
        
        // Store in localStorage for offline tracking
        try {
            const interactions = JSON.parse(localStorage.getItem('bcn_member_interactions') || '[]');
            interactions.push(interaction);
            
            // Keep only last 100 interactions
            if (interactions.length > 100) {
                interactions.splice(0, interactions.length - 100);
            }
            
            localStorage.setItem('bcn_member_interactions', JSON.stringify(interactions));
        } catch (e) {
            console.warn('Could not store member interaction:', e);
        }
    }

    /**
     * Debounce function for performance
     */
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

    /**
     * Initialize member card enhancements when DOM is ready
     */
    $(document).ready(function() {
        initEnhancedMemberCards();
        
        // Add CSS classes for enhanced functionality
        $('body').addClass('member-cards-enhanced-loaded');
    });

    // Expose functions for external use
    window.BCNMemberCards = {
        filterCards: filterCards,
        resetFilters: resetFilters,
        trackInteraction: trackMemberInteraction,
        closeQuickContact: closeQuickContactModal
    };

})(jQuery);