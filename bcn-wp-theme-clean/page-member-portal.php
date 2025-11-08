<?php
/**
 * Member Portal Page Template
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

get_header();

// Check if user is logged in and has member access
$current_user = wp_get_current_user();
$member_id = null;

if ($current_user->ID) {
    // Try to find member profile by email or user ID
    $member_query = new WP_Query(array(
        'post_type' => 'bcn_member',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'bcn_member_email',
                'value' => $current_user->user_email,
                'compare' => '='
            ),
            array(
                'key' => 'bcn_member_user_id',
                'value' => $current_user->ID,
                'compare' => '='
            )
        ),
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if ($member_query->have_posts()) {
        $member_query->the_post();
        $member_id = get_the_ID();
    }
    wp_reset_postdata();
}

// If no member found, show login/registration form
if (!$member_id) {
    ?>
    <main id="primary" class="site-main member-portal-login">
        <div class="container">
            <header class="page-header">
                <h1 class="page-title">Member Portal</h1>
                <p class="page-subtitle">Access your BCN member dashboard and exclusive content</p>
            </header>
            
            <div class="member-login-form">
                <?php if (!is_user_logged_in()) : ?>
                    <div class="login-section">
                        <h2>Sign In</h2>
                        <?php wp_login_form(array(
                            'redirect' => get_permalink(),
                            'form_id' => 'member-login-form',
                            'label_username' => 'Email Address',
                            'label_password' => 'Password',
                            'label_remember' => 'Remember Me',
                            'label_log_in' => 'Sign In to Portal'
                        )); ?>
                        
                        <div class="login-links">
                            <a href="<?php echo wp_lostpassword_url(); ?>">Forgot your password?</a>
                            <a href="<?php echo wp_registration_url(); ?>">Need an account?</a>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="access-denied">
                        <h2>Member Access Required</h2>
                        <p>Your account is not associated with a BCN member profile. Please contact us for assistance.</p>
                        <div class="contact-info">
                            <p><strong>Contact BCN:</strong></p>
                            <p>Email: <a href="mailto:info@buffalocannabisnetwork.com">info@buffalocannabisnetwork.com</a></p>
                            <p>Phone: <a href="tel:+17161234567">(716) 123-4567</a></p>
                        </div>
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="button">Sign Out</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <?php
} else {
    // Member found, show dashboard
    $member_levels = wp_get_post_terms($member_id, 'bcn_membership_level', array('fields' => 'slugs'));
    $is_pro_member = in_array('pro-member', $member_levels);
    $is_premier_member = in_array('premier-member', $member_levels);
    $can_submit_content = get_post_meta($member_id, 'bcn_member_can_submit_content', true);
    ?>
    
    <main id="primary" class="site-main member-portal">
        <div class="container">
            <header class="portal-header">
                <div class="welcome-section">
                    <h1 class="portal-title">Welcome back, <?php echo esc_html($current_user->display_name); ?>!</h1>
                    <p class="portal-subtitle">Your BCN Member Portal</p>
                    <?php if ($is_premier_member) : ?>
                        <div class="member-badge premier">Premier Member</div>
                    <?php elseif ($is_pro_member) : ?>
                        <div class="member-badge pro">Pro Member</div>
                    <?php endif; ?>
                </div>
                <div class="portal-actions">
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="button button-secondary">Sign Out</a>
                </div>
            </header>
            
            <!-- Member Dashboard -->
            <section class="member-dashboard-section">
                <?php echo do_shortcode('[bcn_member_dashboard member_id="' . $member_id . '"]'); ?>
            </section>
            
            <!-- Quick Actions -->
            <section class="quick-actions-section">
                <h2>Quick Actions</h2>
                <div class="quick-actions-grid">
                    <div class="quick-action-card">
                        <div class="action-icon">💬</div>
                        <h3>Submit Testimonial</h3>
                        <p>Share your BCN experience</p>
                        <button class="button button-primary" onclick="openTestimonialModal()">Submit Now</button>
                    </div>
                    
                    <?php if ($can_submit_content && ($is_pro_member || $is_premier_member)) : ?>
                    <div class="quick-action-card">
                        <div class="action-icon">✍️</div>
                        <h3>Write Blog Post</h3>
                        <p>Share industry insights</p>
                        <button class="button button-primary" onclick="openBlogModal()">Start Writing</button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="quick-action-card">
                        <div class="action-icon">🎟️</div>
                        <h3>Upcoming Events</h3>
                        <p>View and register for events</p>
                        <a href="<?php echo get_post_type_archive_link('bcn_event'); ?>" class="button button-primary">View Events</a>
                    </div>
                    
                    <div class="quick-action-card">
                        <div class="action-icon">👥</div>
                        <h3>Member Directory</h3>
                        <p>Connect with other members</p>
                        <a href="<?php echo get_post_type_archive_link('bcn_member'); ?>" class="button button-primary">Browse Members</a>
                    </div>
                </div>
            </section>
            
            <!-- Member Resources -->
            <section class="member-resources-section">
                <h2>Member Resources</h2>
                <div class="resources-grid">
                    <div class="resource-card">
                        <h3>Industry News</h3>
                        <p>Stay updated with the latest cannabis industry news</p>
                        <a href="<?php echo get_post_type_archive_link('bcn_news'); ?>" class="button">Read News</a>
                    </div>
                    
                    <div class="resource-card">
                        <h3>Member Resources</h3>
                        <p>Access exclusive member-only content and tools</p>
                        <a href="<?php echo get_post_type_archive_link('bcn_resource'); ?>" class="button">View Resources</a>
                    </div>
                    
                    <div class="resource-card">
                        <h3>Your Achievements</h3>
                        <p>View your badges and progress</p>
                        <button class="button" onclick="openAchievementsModal()">View Achievements</button>
                    </div>
                </div>
            </section>
            
            <!-- Recent Activity -->
            <section class="recent-activity-section">
                <h2>Your Recent Activity</h2>
                <div class="activity-timeline" id="activity-timeline">
                    <div class="loading">Loading your activity...</div>
                </div>
            </section>
        </div>
    </main>
    
    <!-- Testimonial Modal -->
    <div id="testimonial-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Submit a Testimonial</h2>
            <?php echo do_shortcode('[bcn_testimonial_form member_id="' . $member_id . '"]'); ?>
        </div>
    </div>
    
    <!-- Blog Modal -->
    <?php if ($can_submit_content && ($is_pro_member || $is_premier_member)) : ?>
    <div id="blog-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Write a Blog Post</h2>
            <?php echo do_shortcode('[bcn_blog_submission_form member_id="' . $member_id . '"]'); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Achievements Modal -->
    <div id="achievements-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Your Achievements</h2>
            <?php echo do_shortcode('[bcn_member_achievements member_id="' . $member_id . '"]'); ?>
        </div>
    </div>
    
    <script>
    // Modal functionality
    function openTestimonialModal() {
        document.getElementById('testimonial-modal').style.display = 'block';
    }
    
    function openBlogModal() {
        document.getElementById('blog-modal').style.display = 'block';
    }
    
    function openAchievementsModal() {
        document.getElementById('achievements-modal').style.display = 'block';
    }
    
    // Close modals when clicking X
    document.querySelectorAll('.modal-close').forEach(function(closeBtn) {
        closeBtn.onclick = function() {
            this.closest('.modal').style.display = 'none';
        }
    });
    
    // Close modals when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
    </script>
    <?php
}

get_footer();
?>
