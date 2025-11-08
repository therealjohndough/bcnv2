<?php
/**
 * Member Registration and CPT Creation
 * 
 * Handles automatic member CPT creation with proper schema
 * when members sign up and upload logos
 * 
 * @package BCN_WP_Theme
 */

defined('ABSPATH') || exit;

/**
 * Handle member registration form submission
 */
function bcn_handle_member_registration() {
    // Verify nonce
    if (!isset($_POST['bcn_member_registration_nonce']) || 
        !wp_verify_nonce($_POST['bcn_member_registration_nonce'], 'bcn_member_registration')) {
        wp_die('Security check failed');
    }
    
    // Sanitize and validate input
    $member_data = array(
        'name' => sanitize_text_field($_POST['member_name'] ?? ''),
        'contact_person' => sanitize_text_field($_POST['member_name'] ?? ''),
        'company' => sanitize_text_field($_POST['member_company'] ?? ''),
        'email' => sanitize_email($_POST['member_email'] ?? ''),
        'phone' => sanitize_text_field($_POST['member_phone'] ?? ''),
        'website' => esc_url_raw($_POST['member_website'] ?? ''),
        'address' => sanitize_textarea_field($_POST['member_address'] ?? ''),
        'description' => sanitize_textarea_field($_POST['member_description'] ?? ''),
        'membership_level' => sanitize_text_field($_POST['membership_level'] ?? 'pro-member'),
        'featured' => isset($_POST['member_featured']) ? 1 : 0,
        'logo' => $_FILES['member_logo'] ?? null,
    );
    
    // Validate required fields
    if (empty($member_data['name']) || empty($member_data['email'])) {
        wp_die('Name and email are required');
    }
    
    // Check if member already exists
    $existing_member = get_posts(array(
        'post_type' => 'bcn_member',
        'meta_query' => array(
            array(
                'key' => 'bcn_member_email',
                'value' => $member_data['email'],
                'compare' => '='
            )
        ),
        'posts_per_page' => 1
    ));
    
    if (!empty($existing_member)) {
        wp_die('A member with this email already exists');
    }
    
    // Create member CPT
    $member_post = array(
        'post_title' => $member_data['name'],
        'post_content' => $member_data['description'],
        'post_status' => 'publish',
        'post_type' => 'bcn_member',
        'meta_input' => array(
            'bcn_member_company' => $member_data['company'],
            'bcn_member_contact_person' => $member_data['contact_person'],
            'bcn_member_email' => $member_data['email'],
            'bcn_member_phone' => $member_data['phone'],
            'bcn_member_website' => $member_data['website'],
            'bcn_member_address' => $member_data['address'],
            'bcn_member_featured' => $member_data['featured'],
            'bcn_member_registration_date' => current_time('mysql'),
            'bcn_member_status' => 'pending',
        )
    );
    
    $member_id = wp_insert_post($member_post);
    
    if (is_wp_error($member_id)) {
        wp_die('Failed to create member profile');
    }
    
    // Set membership level taxonomy
    wp_set_post_terms($member_id, array($member_data['membership_level']), 'bcn_membership_level');
    
    // Handle logo upload
    if (!empty($member_data['logo']) && $member_data['logo']['error'] === 0) {
        $logo_id = bcn_handle_member_logo_upload($member_data['logo'], $member_id);
        if ($logo_id) {
            set_post_thumbnail($member_id, $logo_id);
        }
    }
    
    // Add JSON-LD schema
    bcn_add_member_schema($member_id, $member_data);
    
    // Send notification email
    bcn_send_member_registration_notification($member_id, $member_data);
    
    // Redirect to success page
    wp_redirect(add_query_arg('member_registered', '1', home_url('/members/')));
    exit;
}
add_action('admin_post_bcn_member_registration', 'bcn_handle_member_registration');
add_action('admin_post_nopriv_bcn_member_registration', 'bcn_handle_member_registration');

/**
 * Handle member logo upload
 */
function bcn_handle_member_logo_upload($file, $member_id) {
    if (!function_exists('media_handle_upload')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
    
    // Validate file type
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'svg');
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_types)) {
        return false;
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }
    
    // Upload file
    $attachment_id = media_handle_upload('member_logo', $member_id);
    
    if (is_wp_error($attachment_id)) {
        return false;
    }
    
    // Update attachment metadata
    wp_update_attachment_metadata($attachment_id, array(
        'alt' => get_the_title($member_id) . ' Logo'
    ));
    
    return $attachment_id;
}

/**
 * Add JSON-LD schema for member
 */
function bcn_add_member_schema($member_id, $member_data) {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $member_data['name'],
        'url' => get_permalink($member_id),
        'logo' => get_the_post_thumbnail_url($member_id, 'full'),
        'description' => $member_data['description'],
        'email' => $member_data['email'],
        'telephone' => $member_data['phone'],
        'address' => array(
            '@type' => 'PostalAddress',
            'addressLocality' => 'Buffalo',
            'addressRegion' => 'NY',
            'addressCountry' => 'US'
        ),
        'memberOf' => array(
            '@type' => 'Organization',
            'name' => 'Buffalo Cannabis Network',
            'url' => home_url()
        ),
        'sameAs' => array()
    );
    
    // Add website to sameAs
    if (!empty($member_data['website'])) {
        $schema['sameAs'][] = $member_data['website'];
    }
    
    // Add social media links if available
    $social_links = get_post_meta($member_id, 'bcn_member_social_links', true);
    if (!empty($social_links)) {
        foreach ($social_links as $platform => $url) {
            if (!empty($url)) {
                $schema['sameAs'][] = $url;
            }
        }
    }
    
    // Store schema in post meta
    update_post_meta($member_id, 'bcn_member_schema', $schema);
}

/**
 * Output member schema in head
 */
function bcn_output_member_schema() {
    if (is_singular('bcn_member')) {
        $member_id = get_the_ID();
        $schema = get_post_meta($member_id, 'bcn_member_schema', true);
        
        if (!empty($schema)) {
            echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
        }
    }
}
add_action('wp_head', 'bcn_output_member_schema');

/**
 * Send member registration notification
 */
function bcn_send_member_registration_notification($member_id, $member_data) {
    $admin_email = get_option('admin_email');
    $member_url = get_permalink($member_id);
    
    $subject = 'New Member Registration: ' . $member_data['name'];
    
    $message = "A new member has registered:\n\n";
    $message .= "Name: " . $member_data['name'] . "\n";
    $message .= "Company: " . $member_data['company'] . "\n";
    $message .= "Email: " . $member_data['email'] . "\n";
    $message .= "Phone: " . $member_data['phone'] . "\n";
    $message .= "Website: " . $member_data['website'] . "\n";
    $message .= "Membership Level: " . ucfirst(str_replace('-', ' ', $member_data['membership_level'])) . "\n";
    $message .= "Featured: " . ($member_data['featured'] ? 'Yes' : 'No') . "\n\n";
    $message .= "View Member Profile: " . $member_url . "\n";
    $message .= "Edit in Admin: " . admin_url('post.php?post=' . $member_id . '&action=edit') . "\n";
    
    wp_mail($admin_email, $subject, $message);
    
    // Send welcome email to member
    $member_subject = 'Welcome to Buffalo Cannabis Network!';
    $member_message = "Hello " . $member_data['name'] . ",\n\n";
    $member_message .= "Welcome to the Buffalo Cannabis Network! Your member profile has been created and is now live.\n\n";
    $member_message .= "View your profile: " . $member_url . "\n\n";
    $member_message .= "If you have any questions, please don't hesitate to contact us.\n\n";
    $member_message .= "Best regards,\n";
    $member_message .= "Buffalo Cannabis Network Team";
    
    wp_mail($member_data['email'], $member_subject, $member_message);
}

/**
 * Member registration form shortcode
 */
function bcn_member_registration_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_title' => 'true',
        'redirect_url' => home_url('/members/')
    ), $atts);
    
    ob_start();
    ?>
    <div class="bcn-member-registration-form">
        <?php if ($atts['show_title'] === 'true') : ?>
            <h2 class="bcn-member-registration-form__title">Join Our Network</h2>
            <p class="bcn-member-registration-form__description">
                Become a member of the Buffalo Cannabis Network and connect with industry professionals.
            </p>
        <?php endif; ?>
        
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" 
              enctype="multipart/form-data" class="bcn-member-registration-form__form">
            
            <?php wp_nonce_field('bcn_member_registration', 'bcn_member_registration_nonce'); ?>
            <input type="hidden" name="action" value="bcn_member_registration">
            
            <div class="bcn-member-registration-form__row">
                <div class="bcn-member-registration-form__field">
                    <label for="member_name" class="bcn-member-registration-form__label">Contact Person Name *</label>
                    <input type="text" id="member_name" name="member_name" 
                           class="bcn-member-registration-form__input" required>
                </div>
                
                <div class="bcn-member-registration-form__field">
                    <label for="member_company" class="bcn-member-registration-form__label">Company Name *</label>
                    <input type="text" id="member_company" name="member_company" 
                           class="bcn-member-registration-form__input" required>
                </div>
            </div>
            
            <div class="bcn-member-registration-form__row">
                <div class="bcn-member-registration-form__field">
                    <label for="member_email" class="bcn-member-registration-form__label">Email *</label>
                    <input type="email" id="member_email" name="member_email" 
                           class="bcn-member-registration-form__input" required>
                </div>
                
                <div class="bcn-member-registration-form__field">
                    <label for="member_phone" class="bcn-member-registration-form__label">Phone</label>
                    <input type="tel" id="member_phone" name="member_phone" 
                           class="bcn-member-registration-form__input">
                </div>
            </div>
            
            <div class="bcn-member-registration-form__field">
                <label for="member_website" class="bcn-member-registration-form__label">Website</label>
                <input type="url" id="member_website" name="member_website" 
                       class="bcn-member-registration-form__input" 
                       placeholder="https://example.com">
            </div>
            
            <div class="bcn-member-registration-form__field">
                <label for="member_address" class="bcn-member-registration-form__label">Business Address</label>
                <textarea id="member_address" name="member_address" rows="3"
                          class="bcn-member-registration-form__textarea"
                          placeholder="Enter your business address"></textarea>
            </div>
            
            <div class="bcn-member-registration-form__field">
                <label for="membership_level" class="bcn-member-registration-form__label">Membership Level</label>
                <select id="membership_level" name="membership_level" class="bcn-member-registration-form__select">
                    <option value="pro-member">Pro Member</option>
                    <option value="premier-member">Premier Member</option>
                </select>
            </div>
            
            <div class="bcn-member-registration-form__field">
                <label for="member_logo" class="bcn-member-registration-form__label">Company Logo</label>
                <input type="file" id="member_logo" name="member_logo" 
                       class="bcn-member-registration-form__file" 
                       accept="image/*">
                <p class="bcn-member-registration-form__help">
                    Upload your company logo (PNG, JPG, or SVG, max 5MB)
                </p>
            </div>
            
            <div class="bcn-member-registration-form__field">
                <label for="member_description" class="bcn-member-registration-form__label">Description</label>
                <textarea id="member_description" name="member_description" 
                          class="bcn-member-registration-form__textarea" 
                          rows="4" 
                          placeholder="Tell us about your business..."></textarea>
            </div>
            
            <div class="bcn-member-registration-form__field">
                <label class="bcn-member-registration-form__checkbox-label">
                    <input type="checkbox" name="member_featured" value="1" 
                           class="bcn-member-registration-form__checkbox">
                    <span class="bcn-member-registration-form__checkbox-text">
                        Feature this member prominently (additional fee may apply)
                    </span>
                </label>
            </div>
            
            <div class="bcn-member-registration-form__actions">
                <button type="submit" class="bcn-member-registration-form__submit">
                    Join Network
                </button>
            </div>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('member_registration_form', 'bcn_member_registration_form_shortcode');