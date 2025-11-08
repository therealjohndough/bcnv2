/**
 * Member Dashboard JavaScript
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

jQuery(document).ready(function($) {
    'use strict';

    // Initialize dashboard when page loads
    if ($('.bcn-member-dashboard').length) {
        initMemberDashboard();
    }

    // Initialize testimonial form
    if ($('.bcn-testimonial-form').length) {
        initTestimonialForm();
    }

    // Initialize blog submission form
    if ($('.bcn-blog-submission-form').length) {
        initBlogSubmissionForm();
    }

    // Initialize achievement display
    if ($('.bcn-member-achievements').length) {
        initAchievementDisplay();
    }

    /**
     * Initialize member dashboard
     */
    function initMemberDashboard() {
        const dashboard = $('.bcn-member-dashboard');
        const memberId = dashboard.data('member-id');

        if (!memberId) {
            console.error('Member ID not found');
            return;
        }

        // Load dashboard data
        loadDashboardData(memberId);

        // Initialize tab functionality
        initContentTabs();

        // Initialize settings form
        initSettingsForm(memberId);
    }

    /**
     * Load dashboard data via AJAX
     */
    function loadDashboardData(memberId) {
        $.ajax({
            url: bcnDashboard.ajaxurl,
            type: 'POST',
            data: {
                action: 'get_member_dashboard_data',
                member_id: memberId,
                nonce: bcnDashboard.nonce
            },
            success: function(response) {
                if (response.success) {
                    updateDashboard(response.data);
                } else {
                    console.error('Failed to load dashboard data:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
            }
        });
    }

    /**
     * Update dashboard with data
     */
    function updateDashboard(data) {
        // Update member info
        $('#member-name').text(data.member.name);
        $('#member-level').text(data.member.level.charAt(0).toUpperCase() + data.member.level.slice(1));
        $('#member-points').text(data.member.points + ' points');
        
        // Update level progress
        $('#progress-fill').css('width', data.member.progress + '%');
        $('#current-level').text(data.member.level.charAt(0).toUpperCase() + data.member.level.slice(1));
        $('#next-level').text(data.member.next_level.charAt(0).toUpperCase() + data.member.next_level.slice(1));

        // Update statistics
        updateStats(data.stats);

        // Update achievements
        updateAchievements(data.achievements);

        // Update activity feed
        updateActivityFeed(data.recent_activity);

        // Update events list
        updateEventsList(data.upcoming_events);

        // Update content lists
        updateContentLists(data.testimonials, data.blog_posts);
    }

    /**
     * Update statistics display
     */
    function updateStats(stats) {
        $('#testimonials-count').text(stats.testimonials_submitted || 0);
        $('#blog-posts-count').text(stats.blog_posts_published || 0);
        $('#events-attended-count').text(stats.events_attended || 0);
        $('#connections-count').text(stats.connections_made || 0);
    }

    /**
     * Update achievements display
     */
    function updateAchievements(achievements) {
        const achievementsList = $('#achievements-list');
        achievementsList.empty();

        if (achievements.length === 0) {
            achievementsList.html('<div class="no-achievements">No achievements yet. Start participating to earn badges!</div>');
            return;
        }

        // Show only recent achievements (last 5)
        const recentAchievements = achievements.slice(-5);
        
        recentAchievements.forEach(function(achievement) {
            const achievementElement = createAchievementElement(achievement);
            achievementsList.append(achievementElement);
        });
    }

    /**
     * Create achievement element
     */
    function createAchievementElement(achievementSlug) {
        const achievementDefs = {
            'first_testimonial': { name: 'First Testimonial', icon: '💬', tier: 'bronze' },
            'testimonial_contributor': { name: 'Testimonial Contributor', icon: '📝', tier: 'silver' },
            'testimonial_champion': { name: 'Testimonial Champion', icon: '🏆', tier: 'gold' },
            'testimonial_expert': { name: 'Testimonial Expert', icon: '⭐', tier: 'platinum' },
            'first_blog_post': { name: 'First Blog Post', icon: '✍️', tier: 'bronze' },
            'blog_contributor': { name: 'Blog Contributor', icon: '📰', tier: 'silver' },
            'blog_writer': { name: 'Blog Writer', icon: '📖', tier: 'gold' },
            'blog_author': { name: 'Blog Author', icon: '📚', tier: 'platinum' },
            'blog_expert': { name: 'Blog Expert', icon: '🎓', tier: 'diamond' },
            'event_attendee': { name: 'Event Attendee', icon: '🎟️', tier: 'bronze' },
            'event_regular': { name: 'Event Regular', icon: '🎪', tier: 'silver' },
            'event_champion': { name: 'Event Champion', icon: '🏅', tier: 'gold' },
            'networking_pro': { name: 'Networking Pro', icon: '🤝', tier: 'gold' },
            'community_builder': { name: 'Community Builder', icon: '🌐', tier: 'platinum' },
            'industry_leader': { name: 'Industry Leader', icon: '👑', tier: 'diamond' },
            'premier_member': { name: 'Premier Member', icon: '💎', tier: 'diamond' },
            'pro_member': { name: 'Pro Member', icon: '⚡', tier: 'platinum' },
            'early_adopter': { name: 'Early Adopter', icon: '🚀', tier: 'gold' },
            'social_media_advocate': { name: 'Social Media Advocate', icon: '📱', tier: 'silver' },
            'content_creator': { name: 'Content Creator', icon: '🎨', tier: 'gold' }
        };

        const achievement = achievementDefs[achievementSlug] || { name: achievementSlug, icon: '🏆', tier: 'bronze' };

        return $(`
            <div class="achievement-item achievement-item--${achievement.tier}">
                <div class="achievement-icon">${achievement.icon}</div>
                <div class="achievement-info">
                    <h4>${achievement.name}</h4>
                    <p>Congratulations on earning this achievement!</p>
                </div>
                <div class="achievement-badge">✓</div>
            </div>
        `);
    }

    /**
     * Update activity feed
     */
    function updateActivityFeed(activities) {
        const activityFeed = $('#activity-feed');
        activityFeed.empty();

        if (activities.length === 0) {
            activityFeed.html('<div class="no-activity">No recent activity</div>');
            return;
        }

        activities.forEach(function(activity) {
            const activityElement = createActivityElement(activity);
            activityFeed.append(activityElement);
        });
    }

    /**
     * Create activity element
     */
    function createActivityElement(activity) {
        const icons = {
            'testimonial': '💬',
            'blog': '✍️',
            'event': '🎟️',
            'achievement': '🏆'
        };

        const icon = icons[activity.type] || '📝';
        const date = new Date(activity.date).toLocaleDateString();

        return $(`
            <div class="activity-item">
                <div class="activity-icon">${icon}</div>
                <div class="activity-content">
                    <div class="activity-title">${activity.title}</div>
                    <div class="activity-date">${date}</div>
                </div>
            </div>
        `);
    }

    /**
     * Update events list
     */
    function updateEventsList(events) {
        const eventsList = $('#events-list');
        eventsList.empty();

        if (events.length === 0) {
            eventsList.html('<div class="no-events">No upcoming events</div>');
            return;
        }

        events.forEach(function(event) {
            const eventElement = createEventElement(event);
            eventsList.append(eventElement);
        });
    }

    /**
     * Create event element
     */
    function createEventElement(event) {
        const eventDate = new Date(event.event_date).toLocaleDateString();
        const eventLocation = event.event_location || 'Location TBD';

        return $(`
            <div class="event-item">
                <div class="event-title">${event.post_title}</div>
                <div class="event-date">${eventDate}</div>
                <div class="event-location">${eventLocation}</div>
            </div>
        `);
    }

    /**
     * Update content lists
     */
    function updateContentLists(testimonials, blogPosts) {
        updateTestimonialsList(testimonials);
        updateBlogPostsList(blogPosts);
    }

    /**
     * Update testimonials list
     */
    function updateTestimonialsList(testimonials) {
        const testimonialsList = $('#testimonials-list');
        testimonialsList.empty();

        if (testimonials.length === 0) {
            testimonialsList.html('<div class="no-content">No testimonials submitted yet</div>');
            return;
        }

        testimonials.forEach(function(testimonial) {
            const testimonialElement = createTestimonialElement(testimonial);
            testimonialsList.append(testimonialElement);
        });
    }

    /**
     * Update blog posts list
     */
    function updateBlogPostsList(blogPosts) {
        const blogPostsList = $('#blog-posts-list');
        blogPostsList.empty();

        if (blogPosts.length === 0) {
            blogPostsList.html('<div class="no-content">No blog posts submitted yet</div>');
            return;
        }

        blogPosts.forEach(function(post) {
            const postElement = createBlogPostElement(post);
            blogPostsList.append(postElement);
        });
    }

    /**
     * Create testimonial element
     */
    function createTestimonialElement(testimonial) {
        const date = new Date(testimonial.post_date).toLocaleDateString();
        const status = getTestimonialStatus(testimonial);

        return $(`
            <div class="content-item">
                <div class="content-title">Testimonial</div>
                <div class="content-meta">
                    <span>${date}</span>
                    <span class="content-status ${status.class}">${status.text}</span>
                </div>
            </div>
        `);
    }

    /**
     * Create blog post element
     */
    function createBlogPostElement(post) {
        const date = new Date(post.post_date).toLocaleDateString();
        const status = getBlogPostStatus(post);

        return $(`
            <div class="content-item">
                <div class="content-title">${post.post_title}</div>
                <div class="content-meta">
                    <span>${date}</span>
                    <span class="content-status ${status.class}">${status.text}</span>
                </div>
            </div>
        `);
    }

    /**
     * Get testimonial status info
     */
    function getTestimonialStatus(testimonial) {
        const status = testimonial.testimonial_approval_status || 'pending';
        
        const statusMap = {
            'pending': { text: 'Pending', class: 'pending' },
            'approved': { text: 'Approved', class: 'published' },
            'featured': { text: 'Featured', class: 'published' },
            'rejected': { text: 'Rejected', class: 'draft' }
        };

        return statusMap[status] || { text: 'Unknown', class: 'pending' };
    }

    /**
     * Get blog post status info
     */
    function getBlogPostStatus(post) {
        const status = post.blog_submission_status || post.post_status;
        
        const statusMap = {
            'draft': { text: 'Draft', class: 'draft' },
            'submitted': { text: 'Under Review', class: 'pending' },
            'under_review': { text: 'Under Review', class: 'pending' },
            'approved': { text: 'Approved', class: 'published' },
            'published': { text: 'Published', class: 'published' },
            'rejected': { text: 'Rejected', class: 'draft' }
        };

        return statusMap[status] || { text: 'Unknown', class: 'pending' };
    }

    /**
     * Initialize content tabs
     */
    function initContentTabs() {
        $('.tab-button').on('click', function() {
            const tab = $(this).data('tab');
            
            // Update tab buttons
            $('.tab-button').removeClass('active');
            $(this).addClass('active');
            
            // Update content panels
            $('.content-panel').hide();
            $(`#${tab}-panel`).show();
        });
    }

    /**
     * Initialize settings form
     */
    function initSettingsForm(memberId) {
        $('#member-preferences-form').on('submit', function(e) {
            e.preventDefault();
            
            const formData = $(this).serialize();
            
            $.ajax({
                url: bcnDashboard.ajaxurl,
                type: 'POST',
                data: {
                    action: 'update_member_profile',
                    member_id: memberId,
                    preferences: formData,
                    nonce: bcnDashboard.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('Settings saved successfully!', 'success');
                    } else {
                        showMessage('Failed to save settings', 'error');
                    }
                },
                error: function() {
                    showMessage('Error saving settings', 'error');
                }
            });
        });
    }

    /**
     * Initialize testimonial form
     */
    function initTestimonialForm() {
        $('#testimonial-form').on('submit', function(e) {
            e.preventDefault();
            
            const form = $(this);
            const memberId = form.closest('.bcn-testimonial-form').data('member-id');
            const eventId = form.closest('.bcn-testimonial-form').data('event-id');
            
            const formData = {
                member_id: memberId,
                content: form.find('textarea[name="content"]').val(),
                type: form.find('select[name="type"]').val(),
                rating: form.find('input[name="rating"]:checked').val() || 5,
                industry_focus: form.find('select[name="industry_focus"]').val(),
                event_id: eventId || ''
            };
            
            $.ajax({
                url: bcnTestimonial.ajaxurl,
                type: 'POST',
                data: {
                    action: 'submit_testimonial',
                    ...formData,
                    nonce: bcnTestimonial.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('Testimonial submitted successfully!', 'success');
                        form[0].reset();
                    } else {
                        showMessage('Failed to submit testimonial: ' + response.data, 'error');
                    }
                },
                error: function() {
                    showMessage('Error submitting testimonial', 'error');
                }
            });
        });
    }

    /**
     * Initialize blog submission form
     */
    function initBlogSubmissionForm() {
        const form = $('#blog-submission-form');
        const memberId = form.closest('.bcn-blog-submission-form').data('member-id');
        
        // Word count tracking
        form.find('textarea[name="content"]').on('input', function() {
            const wordCount = $(this).val().split(/\s+/).filter(word => word.length > 0).length;
            $('#word-count').text(wordCount);
        });
        
        // SEO character counting
        form.find('input[name="seo_title"]').on('input', function() {
            $('#seo-title-count').text($(this).val().length);
        });
        
        form.find('textarea[name="seo_description"]').on('input', function() {
            $('#seo-desc-count').text($(this).val().length);
        });
        
        // Form submission
        form.on('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'submit_blog_post');
            formData.append('member_id', memberId);
            formData.append('nonce', bcnBlogSubmission.nonce);
            
            $.ajax({
                url: bcnBlogSubmission.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showMessage(response.data.message, 'success');
                        if (response.data.post_id) {
                            // Redirect to edit page or show success message
                            setTimeout(() => {
                                window.location.href = response.data.edit_url || window.location.href;
                            }, 2000);
                        }
                    } else {
                        showMessage('Failed to submit blog post: ' + response.data, 'error');
                    }
                },
                error: function() {
                    showMessage('Error submitting blog post', 'error');
                }
            });
        });
        
        // Save draft button
        $('#save-draft').on('click', function() {
            form.find('select[name="status"]').val('draft');
            form.submit();
        });
        
        // Delete draft button
        $('#delete-draft').on('click', function() {
            if (confirm('Are you sure you want to delete this draft?')) {
                const postId = form.closest('.bcn-blog-submission-form').data('post-id');
                
                $.ajax({
                    url: bcnBlogSubmission.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'delete_blog_draft',
                        post_id: postId,
                        member_id: memberId,
                        nonce: bcnBlogSubmission.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            showMessage('Draft deleted successfully', 'success');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showMessage('Failed to delete draft', 'error');
                        }
                    },
                    error: function() {
                        showMessage('Error deleting draft', 'error');
                    }
                });
            }
        });
    }

    /**
     * Initialize achievement display
     */
    function initAchievementDisplay() {
        // Add hover effects for achievement items
        $('.achievement-item').on('mouseenter', function() {
            $(this).addClass('achievement-hover');
        }).on('mouseleave', function() {
            $(this).removeClass('achievement-hover');
        });
    }

    /**
     * Show message to user
     */
    function showMessage(message, type) {
        const messageClass = type === 'success' ? 'message success' : 'message error';
        const messageElement = $(`<div class="${messageClass}">${message}</div>`);
        
        // Remove existing messages
        $('.message').remove();
        
        // Add new message
        $('.bcn-member-dashboard, .bcn-testimonial-form, .bcn-blog-submission-form').first().prepend(messageElement);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            messageElement.fadeOut(() => {
                messageElement.remove();
            });
        }, 5000);
    }

    /**
     * Initialize new blog post button
     */
    $('#new-blog-post').on('click', function(e) {
        e.preventDefault();
        
        // Scroll to blog submission form or open modal
        const blogForm = $('.bcn-blog-submission-form');
        if (blogForm.length) {
            $('html, body').animate({
                scrollTop: blogForm.offset().top - 100
            }, 500);
        }
    });
});
