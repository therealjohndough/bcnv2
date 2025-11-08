<?php
/**
 * Member directory features.
 *
 * @package BCN_WP_Theme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Member post type is registered in includes/post-types.php

/**
 * Register the Membership Level taxonomy.
 */
function bcn_register_membership_level_taxonomy() {
    $labels = array(
        'name'              => _x('Membership Levels', 'taxonomy general name', 'bcn-wp-theme'),
        'singular_name'     => _x('Membership Level', 'taxonomy singular name', 'bcn-wp-theme'),
        'search_items'      => __('Search Membership Levels', 'bcn-wp-theme'),
        'all_items'         => __('All Membership Levels', 'bcn-wp-theme'),
        'parent_item'       => __('Parent Membership Level', 'bcn-wp-theme'),
        'parent_item_colon' => __('Parent Membership Level:', 'bcn-wp-theme'),
        'edit_item'         => __('Edit Membership Level', 'bcn-wp-theme'),
        'update_item'       => __('Update Membership Level', 'bcn-wp-theme'),
        'add_new_item'      => __('Add New Membership Level', 'bcn-wp-theme'),
        'new_item_name'     => __('New Membership Level Name', 'bcn-wp-theme'),
        'menu_name'         => __('Membership Levels', 'bcn-wp-theme'),
    );

    $args = array(
        'hierarchical'      => false,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'membership'),
        'show_in_rest'      => true,
    );

    register_taxonomy('bcn_membership_level', array('bcn_member'), $args);
}
add_action('init', 'bcn_register_membership_level_taxonomy', 0);

/**
 * Ensure starter membership level terms exist.
 */
function bcn_seed_membership_levels() {
    $levels = array(
        'premier-member' => __('Premier Member', 'bcn-wp-theme'),
        'pro-member'     => __('Pro Member', 'bcn-wp-theme'),
        'community-partner' => __('Community Partner', 'bcn-wp-theme'),
    );

    foreach ($levels as $slug => $label) {
        if (!term_exists($slug, 'bcn_membership_level')) {
            wp_insert_term($label, 'bcn_membership_level', array('slug' => $slug));
        }
    }
}
add_action('init', 'bcn_seed_membership_levels', 15);

/**
 * Register meta fields for members.
 */
function bcn_register_member_meta() {
    $meta_fields = array(
        'bcn_member_website' => array(
            'type'         => 'string',
            'description'  => __('Member website URL', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_email' => array(
            'type'         => 'string',
            'description'  => __('Primary contact email', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_phone' => array(
            'type'         => 'string',
            'description'  => __('Primary contact phone number', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_address' => array(
            'type'         => 'string',
            'description'  => __('Mailing or street address', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_featured' => array(
            'type'         => 'boolean',
            'description'  => __('Feature this member more prominently', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
            'default'      => false,
        ),
        'bcn_member_can_submit_content' => array(
            'type'         => 'boolean',
            'description'  => __('Allow the member to submit blog posts or photos', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
            'default'      => false,
        ),
        'bcn_member_submission_key' => array(
            'type'         => 'string',
            'description'  => __('Private key used to access the member submission form', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => false,
        ),
        'bcn_member_instagram' => array(
            'type'         => 'string',
            'description'  => __('Instagram profile URL', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_facebook' => array(
            'type'         => 'string',
            'description'  => __('Facebook page URL', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_twitter' => array(
            'type'         => 'string',
            'description'  => __('Twitter profile URL', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_linkedin' => array(
            'type'         => 'string',
            'description'  => __('LinkedIn company page URL', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_youtube' => array(
            'type'         => 'string',
            'description'  => __('YouTube channel URL', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_testimonials' => array(
            'type'         => 'array',
            'description'  => __('Member testimonials and reviews', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        ),
        'bcn_member_last_activity' => array(
            'type'         => 'string',
            'description'  => __('Last activity timestamp', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => false,
        ),
    );

    foreach ($meta_fields as $meta_key => $args) {
        register_post_meta('bcn_member', $meta_key, $args);
    }

    register_post_meta(
        'post',
        'bcn_member_submission_member_id',
        array(
            'type'         => 'integer',
            'description'  => __('Linked member profile for a submitted story or photo', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        )
    );

    register_post_meta(
        'post',
        'bcn_member_submission_type',
        array(
            'type'         => 'string',
            'description'  => __('The type of submission provided by a member', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => true,
        )
    );

    register_post_meta(
        'post',
        'bcn_member_submission_contact_name',
        array(
            'type'         => 'string',
            'description'  => __('Contact name provided with a member submission', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => false,
        )
    );

    register_post_meta(
        'post',
        'bcn_member_submission_contact_email',
        array(
            'type'         => 'string',
            'description'  => __('Contact email provided with a member submission', 'bcn-wp-theme'),
            'single'       => true,
            'show_in_rest' => false,
        )
    );
}
add_action('init', 'bcn_register_member_meta');

/**
 * Add custom meta boxes.
 */
function bcn_member_add_meta_boxes() {
    add_meta_box(
        'bcn-member-details',
        __('Member Details', 'bcn-wp-theme'),
        'bcn_member_details_meta_box',
        'bcn_member',
        'normal',
        'default'
    );

    add_meta_box(
        'bcn-member-featured',
        __('Directory Display Options', 'bcn-wp-theme'),
        'bcn_member_featured_meta_box',
        'bcn_member',
        'side',
        'default'
    );

    add_meta_box(
        'bcn-member-contributions',
        __('Member Contributions', 'bcn-wp-theme'),
        'bcn_member_contributions_meta_box',
        'bcn_member',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'bcn_member_add_meta_boxes');

/**
 * Render the member details meta box.
 *
 * @param WP_Post $post Current post object.
 */
function bcn_member_details_meta_box($post) {
    wp_nonce_field('bcn_member_details_nonce', 'bcn_member_details_nonce');

    $website = get_post_meta($post->ID, 'bcn_member_website', true);
    $email   = get_post_meta($post->ID, 'bcn_member_email', true);
    $phone   = get_post_meta($post->ID, 'bcn_member_phone', true);
    $address = get_post_meta($post->ID, 'bcn_member_address', true);
    ?>
    <p>
        <label for="bcn_member_website"><strong><?php esc_html_e('Website URL', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="url" class="widefat" name="bcn_member_website" id="bcn_member_website" value="<?php echo esc_attr($website); ?>" placeholder="https://example.com" />
    </p>
    <p>
        <label for="bcn_member_email"><strong><?php esc_html_e('Contact Email', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="email" class="widefat" name="bcn_member_email" id="bcn_member_email" value="<?php echo esc_attr($email); ?>" />
    </p>
    <p>
        <label for="bcn_member_phone"><strong><?php esc_html_e('Contact Phone', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="text" class="widefat" name="bcn_member_phone" id="bcn_member_phone" value="<?php echo esc_attr($phone); ?>" />
    </p>
    <p>
        <label for="bcn_member_address"><strong><?php esc_html_e('Address', 'bcn-wp-theme'); ?></strong></label><br />
        <textarea class="widefat" rows="3" name="bcn_member_address" id="bcn_member_address"><?php echo esc_textarea($address); ?></textarea>
    </p>
    
    <h4><?php esc_html_e('Social Media Links', 'bcn-wp-theme'); ?></h4>
    <p>
        <label for="bcn_member_instagram"><strong><?php esc_html_e('Instagram', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="url" class="widefat" name="bcn_member_instagram" id="bcn_member_instagram" value="<?php echo esc_attr(get_post_meta($post->ID, 'bcn_member_instagram', true)); ?>" placeholder="https://instagram.com/username" />
    </p>
    <p>
        <label for="bcn_member_facebook"><strong><?php esc_html_e('Facebook', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="url" class="widefat" name="bcn_member_facebook" id="bcn_member_facebook" value="<?php echo esc_attr(get_post_meta($post->ID, 'bcn_member_facebook', true)); ?>" placeholder="https://facebook.com/pagename" />
    </p>
    <p>
        <label for="bcn_member_twitter"><strong><?php esc_html_e('Twitter', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="url" class="widefat" name="bcn_member_twitter" id="bcn_member_twitter" value="<?php echo esc_attr(get_post_meta($post->ID, 'bcn_member_twitter', true)); ?>" placeholder="https://twitter.com/username" />
    </p>
    <p>
        <label for="bcn_member_linkedin"><strong><?php esc_html_e('LinkedIn', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="url" class="widefat" name="bcn_member_linkedin" id="bcn_member_linkedin" value="<?php echo esc_attr(get_post_meta($post->ID, 'bcn_member_linkedin', true)); ?>" placeholder="https://linkedin.com/company/companyname" />
    </p>
    <p>
        <label for="bcn_member_youtube"><strong><?php esc_html_e('YouTube', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="url" class="widefat" name="bcn_member_youtube" id="bcn_member_youtube" value="<?php echo esc_attr(get_post_meta($post->ID, 'bcn_member_youtube', true)); ?>" placeholder="https://youtube.com/channel/channelid" />
    </p>
    <?php
}

/**
 * Render the featured options meta box.
 *
 * @param WP_Post $post Current post object.
 */
function bcn_member_featured_meta_box($post) {
    $featured = (bool) get_post_meta($post->ID, 'bcn_member_featured', true);
    ?>
    <p>
        <label for="bcn_member_featured">
            <input type="checkbox" name="bcn_member_featured" id="bcn_member_featured" value="1" <?php checked($featured); ?> />
            <?php esc_html_e('Highlight this member in featured placements', 'bcn-wp-theme'); ?>
        </label>
    </p>
    <?php
}

/**
 * Render the member contribution settings meta box.
 *
 * @param WP_Post $post Current post object.
 */
function bcn_member_contributions_meta_box($post) {
    $can_submit = (bool) get_post_meta($post->ID, 'bcn_member_can_submit_content', true);
    $key        = get_post_meta($post->ID, 'bcn_member_submission_key', true);
    $submission_url = '';

    if ($can_submit && $key) {
        $submission_url = add_query_arg(
            array(
                'submission_key' => rawurlencode($key),
            ),
            home_url('/member-submissions/')
        );
    }
    ?>
    <p>
        <label for="bcn_member_can_submit_content">
            <input type="checkbox" name="bcn_member_can_submit_content" id="bcn_member_can_submit_content" value="1" <?php checked($can_submit); ?> />
            <?php esc_html_e('Allow this member to submit stories or media from the front-end form.', 'bcn-wp-theme'); ?>
        </label>
    </p>
    <p>
        <label for="bcn_member_submission_key"><strong><?php esc_html_e('Submission Key', 'bcn-wp-theme'); ?></strong></label><br />
        <input type="text" class="regular-text" name="bcn_member_submission_key" id="bcn_member_submission_key" value="<?php echo esc_attr($key); ?>" placeholder="<?php esc_attr_e('Automatically generated when enabled', 'bcn-wp-theme'); ?>" />
    </p>
    <p class="description">
        <?php esc_html_e('Share the submission link with trusted contacts. They will use it to upload drafts that arrive in the pending posts queue.', 'bcn-wp-theme'); ?>
    </p>
    <?php if ($submission_url) : ?>
        <p>
            <strong><?php esc_html_e('Suggested submission URL', 'bcn-wp-theme'); ?>:</strong><br />
            <code><?php echo esc_html($submission_url); ?></code>
        </p>
    <?php endif; ?>
    <?php
}

/**
 * Save custom meta when a member post is saved.
 *
 * @param int $post_id The ID of the current post.
 */
function bcn_save_member_meta($post_id) {
    if (!isset($_POST['bcn_member_details_nonce']) || !wp_verify_nonce($_POST['bcn_member_details_nonce'], 'bcn_member_details_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        'bcn_member_website' => FILTER_SANITIZE_URL,
        'bcn_member_email'   => FILTER_SANITIZE_EMAIL,
        'bcn_member_phone'   => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    );

    foreach ($fields as $meta_key => $filter) {
        $value = filter_input(INPUT_POST, $meta_key, $filter);
        if ($value) {
            update_post_meta($post_id, $meta_key, $value);
        } else {
            delete_post_meta($post_id, $meta_key);
        }
    }

    if (isset($_POST['bcn_member_address'])) {
        $address = sanitize_textarea_field(wp_unslash($_POST['bcn_member_address']));
        if ($address) {
            update_post_meta($post_id, 'bcn_member_address', $address);
        } else {
            delete_post_meta($post_id, 'bcn_member_address');
        }
    }

    $featured = isset($_POST['bcn_member_featured']) ? '1' : '';
    if ($featured) {
        update_post_meta($post_id, 'bcn_member_featured', 1);
    } else {
        delete_post_meta($post_id, 'bcn_member_featured');
    }

    $can_submit = isset($_POST['bcn_member_can_submit_content']);
    if ($can_submit) {
        update_post_meta($post_id, 'bcn_member_can_submit_content', 1);
        $submitted_key = '';
        if (isset($_POST['bcn_member_submission_key'])) {
            $submitted_key = sanitize_text_field(wp_unslash($_POST['bcn_member_submission_key']));
        }

        if (empty($submitted_key)) {
            $submitted_key = wp_generate_password(20, false, false);
        }

        update_post_meta($post_id, 'bcn_member_submission_key', $submitted_key);
    } else {
        delete_post_meta($post_id, 'bcn_member_can_submit_content');
        delete_post_meta($post_id, 'bcn_member_submission_key');
    }
}
add_action('save_post_bcn_member', 'bcn_save_member_meta');

/**
 * Filter member archives with query parameters.
 *
 * @param WP_Query $query Current query object.
 */
function bcn_filter_member_archive($query) {
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if ($query->is_post_type_archive('bcn_member')) {
        if (!empty($_GET['membership_level'])) {
            $level = sanitize_key(wp_unslash($_GET['membership_level']));
            $query->set(
                'tax_query',
                array(
                    array(
                        'taxonomy' => 'bcn_membership_level',
                        'field'    => 'slug',
                        'terms'    => $level,
                    ),
                )
            );
        }

        if (!empty($_GET['featured_only'])) {
            $query->set(
                'meta_query',
                array(
                    array(
                        'key'   => 'bcn_member_featured',
                        'value' => 1,
                    ),
                )
            );
        }
    }
}
add_action('pre_get_posts', 'bcn_filter_member_archive');

/**
 * Output admin notices for onboarding page submissions.
 */
function bcn_member_onboarding_admin_notices() {
    if (!function_exists('get_current_screen')) {
        return;
    }

    $screen = get_current_screen();
    if (empty($screen) || 'bcn_member_page_bcn-member-onboarding' !== $screen->id) {
        return;
    }

    settings_errors('bcn_member_onboarding');
}
add_action('admin_notices', 'bcn_member_onboarding_admin_notices');

/**
 * Register the onboarding admin page.
 */
function bcn_register_member_onboarding_page() {
    add_submenu_page(
        'edit.php?post_type=bcn_member',
        __('Onboard Member', 'bcn-wp-theme'),
        __('Onboard Member', 'bcn-wp-theme'),
        'manage_options',
        'bcn-member-onboarding',
        'bcn_render_member_onboarding_page'
    );
}
add_action('admin_menu', 'bcn_register_member_onboarding_page');

/**
 * Render the onboarding admin page and process submissions.
 */
function bcn_render_member_onboarding_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $post_created = null;
    if (!empty($_POST['bcn_member_onboarding_submitted'])) {
        $post_created = bcn_handle_member_onboarding_form();
        if ($post_created) {
            $_POST = array();
        }
    }

    $levels = get_terms(
        array(
            'taxonomy'   => 'bcn_membership_level',
            'hide_empty' => false,
        )
    );
    if (is_wp_error($levels)) {
        $levels = array();
    }

    $submitted = array(
        'name'        => isset($_POST['bcn_member_name']) ? sanitize_text_field(wp_unslash($_POST['bcn_member_name'])) : '',
        'description' => isset($_POST['bcn_member_description']) ? wp_kses_post(wp_unslash($_POST['bcn_member_description'])) : '',
        'website'     => isset($_POST['bcn_member_website']) ? esc_url_raw(wp_unslash($_POST['bcn_member_website'])) : '',
        'email'       => isset($_POST['bcn_member_email']) ? sanitize_email(wp_unslash($_POST['bcn_member_email'])) : '',
        'phone'       => isset($_POST['bcn_member_phone']) ? sanitize_text_field(wp_unslash($_POST['bcn_member_phone'])) : '',
        'address'     => isset($_POST['bcn_member_address']) ? sanitize_textarea_field(wp_unslash($_POST['bcn_member_address'])) : '',
        'level'       => isset($_POST['bcn_member_level']) ? sanitize_key(wp_unslash($_POST['bcn_member_level'])) : '',
        'featured'    => isset($_POST['bcn_member_featured_onboard']),
    );

    if (empty($submitted['level']) && !empty($levels) && !is_wp_error($levels)) {
        $submitted['level'] = $levels[0]->slug;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Onboard a Member', 'bcn-wp-theme'); ?></h1>
        <p><?php esc_html_e('Use this workflow when a new member joins to automatically create their directory profile and logo placements.', 'bcn-wp-theme'); ?></p>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('bcn_member_onboarding', 'bcn_member_onboarding_nonce'); ?>
            <input type="hidden" name="bcn_member_onboarding_submitted" value="1" />
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label for="bcn_member_name"><?php esc_html_e('Member Name', 'bcn-wp-theme'); ?></label></th>
                        <td><input type="text" required class="regular-text" name="bcn_member_name" id="bcn_member_name" value="<?php echo esc_attr($submitted['name']); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bcn_member_description"><?php esc_html_e('Directory Description', 'bcn-wp-theme'); ?></label></th>
                        <td>
                            <textarea name="bcn_member_description" id="bcn_member_description" class="large-text" rows="6" placeholder="<?php esc_attr_e('Tell visitors about this member, their services, and differentiators.', 'bcn-wp-theme'); ?>"><?php echo esc_textarea($submitted['description']); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bcn_member_website"><?php esc_html_e('Website URL', 'bcn-wp-theme'); ?></label></th>
                        <td><input type="url" class="regular-text" name="bcn_member_website" id="bcn_member_website" placeholder="https://example.com" value="<?php echo esc_attr($submitted['website']); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bcn_member_email"><?php esc_html_e('Contact Email', 'bcn-wp-theme'); ?></label></th>
                        <td><input type="email" class="regular-text" name="bcn_member_email" id="bcn_member_email" value="<?php echo esc_attr($submitted['email']); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bcn_member_phone"><?php esc_html_e('Contact Phone', 'bcn-wp-theme'); ?></label></th>
                        <td><input type="text" class="regular-text" name="bcn_member_phone" id="bcn_member_phone" value="<?php echo esc_attr($submitted['phone']); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bcn_member_address"><?php esc_html_e('Address', 'bcn-wp-theme'); ?></label></th>
                        <td><textarea name="bcn_member_address" id="bcn_member_address" class="large-text" rows="3"><?php echo esc_textarea($submitted['address']); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Membership Level', 'bcn-wp-theme'); ?></th>
                        <td>
                            <?php if (!empty($levels)) : ?>
                                <?php foreach ($levels as $level) : ?>
                                    <label style="display:block;margin-bottom:4px;">
                                        <input type="radio" name="bcn_member_level" value="<?php echo esc_attr($level->slug); ?>" <?php checked($submitted['level'], $level->slug); ?> />
                                        <?php echo esc_html($level->name); ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p class="description"><?php esc_html_e('No membership levels found. Add levels in the “Membership Levels” taxonomy screen.', 'bcn-wp-theme'); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bcn_member_logo"><?php esc_html_e('Member Logo', 'bcn-wp-theme'); ?></label></th>
                        <td>
                            <input type="file" name="bcn_member_logo" id="bcn_member_logo" accept="image/*" />
                            <p class="description"><?php esc_html_e('Upload a high-quality logo (transparent PNG preferred).', 'bcn-wp-theme'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="bcn_member_featured_onboard"><?php esc_html_e('Feature this member', 'bcn-wp-theme'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="bcn_member_featured_onboard" id="bcn_member_featured_onboard" value="1" <?php checked($submitted['featured']); ?> />
                                <?php esc_html_e('Prioritize this member in featured placements.', 'bcn-wp-theme'); ?>
                            </label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button(__('Create Member Profile', 'bcn-wp-theme')); ?>
        </form>
        <hr />
        <h2><?php esc_html_e('Next steps', 'bcn-wp-theme'); ?></h2>
        <ol>
            <li><?php esc_html_e('Review the generated member page for accuracy and add any supporting media.', 'bcn-wp-theme'); ?></li>
            <li><?php esc_html_e('Share the directory link with the member for approval.', 'bcn-wp-theme'); ?></li>
            <li><?php esc_html_e('Schedule optional announcements or newsletter placements.', 'bcn-wp-theme'); ?></li>
        </ol>
    </div>
    <?php
}

/**
 * Handle onboarding form submission.
 */
function bcn_handle_member_onboarding_form() {
    if (!isset($_POST['bcn_member_onboarding_nonce']) || !wp_verify_nonce($_POST['bcn_member_onboarding_nonce'], 'bcn_member_onboarding')) {
        add_settings_error('bcn_member_onboarding', 'bcn-member-onboarding-nonce', __('Security check failed. Please try again.', 'bcn-wp-theme'), 'error');
        return false;
    }

    $name        = isset($_POST['bcn_member_name']) ? sanitize_text_field(wp_unslash($_POST['bcn_member_name'])) : '';
    $description = isset($_POST['bcn_member_description']) ? wp_kses_post(wp_unslash($_POST['bcn_member_description'])) : '';
    $website     = isset($_POST['bcn_member_website']) ? esc_url_raw(wp_unslash($_POST['bcn_member_website'])) : '';
    $email       = isset($_POST['bcn_member_email']) ? sanitize_email(wp_unslash($_POST['bcn_member_email'])) : '';
    $phone       = isset($_POST['bcn_member_phone']) ? sanitize_text_field(wp_unslash($_POST['bcn_member_phone'])) : '';
    $address     = isset($_POST['bcn_member_address']) ? sanitize_textarea_field(wp_unslash($_POST['bcn_member_address'])) : '';
    $level       = isset($_POST['bcn_member_level']) ? sanitize_key(wp_unslash($_POST['bcn_member_level'])) : '';
    $featured    = isset($_POST['bcn_member_featured_onboard']);

    if (empty($name)) {
        add_settings_error('bcn_member_onboarding', 'bcn-member-onboarding-name', __('Member name is required.', 'bcn-wp-theme'), 'error');
        return false;
    }

    $post_id = wp_insert_post(
        array(
            'post_type'   => 'bcn_member',
            'post_title'  => $name,
            'post_status' => 'publish',
            'post_content'=> $description,
        )
    );

    if (is_wp_error($post_id)) {
        add_settings_error('bcn_member_onboarding', 'bcn-member-onboarding-insert', __('Unable to create member. Please try again.', 'bcn-wp-theme'), 'error');
        return false;
    }

    if ($website) {
        update_post_meta($post_id, 'bcn_member_website', $website);
    }
    if ($email) {
        update_post_meta($post_id, 'bcn_member_email', $email);
    }
    if ($phone) {
        update_post_meta($post_id, 'bcn_member_phone', $phone);
    }
    if ($address) {
        update_post_meta($post_id, 'bcn_member_address', $address);
    }
    if ($featured) {
        update_post_meta($post_id, 'bcn_member_featured', 1);
    }

    if ($level) {
        wp_set_object_terms($post_id, $level, 'bcn_membership_level', false);
    }

    if (!empty($_FILES['bcn_member_logo']['name'])) {
        // Basic validation before handing off to WP media handlers.
        $uploaded = isset($_FILES['bcn_member_logo']) ? $_FILES['bcn_member_logo'] : null;
        $validation = bcn_validate_uploaded_image($uploaded);
        if (is_wp_error($validation)) {
            add_settings_error('bcn_member_onboarding', 'bcn-member-logo', $validation->get_error_message(), 'error');
        } else {
            // Load WordPress admin functions
            if (!function_exists('media_handle_upload')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
                require_once ABSPATH . 'wp-admin/includes/image.php';
            }

            $attachment_id = media_handle_upload('bcn_member_logo', $post_id);
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
            } else {
                add_settings_error('bcn_member_onboarding', 'bcn-member-logo', __('The member was created, but the logo upload failed.', 'bcn-wp-theme'), 'error');
            }
        }
    }

    add_settings_error(
        'bcn_member_onboarding',
        'bcn-member-onboarding-success',
        sprintf(
            /* translators: %s is the member admin edit link */
            __('Success! View or continue editing the <a href="%s">new member profile</a>.', 'bcn-wp-theme'),
            esc_url(get_edit_post_link($post_id))
        ),
        'updated'
    );
    return $post_id;
}

/**
 * Shortcode for rendering member logo grids.
 *
 * Usage: [member_logo_grid level="premier-member" limit="12" featured="false"]
 *
 * @param array $atts Shortcode attributes.
 *
 * @return string
 */
function bcn_member_logo_grid_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'level'    => '',
            'limit'    => 12,
            'featured' => 'false',
            'columns'  => 4,
        ),
        $atts,
        'member_logo_grid'
    );

    $level    = sanitize_key($atts['level']);
    $limit    = absint($atts['limit']);
    $featured = filter_var($atts['featured'], FILTER_VALIDATE_BOOLEAN);
    $columns  = min(6, max(2, absint($atts['columns'])));

    $query_args = array(
        'post_type'      => 'bcn_member',
        'posts_per_page' => $limit > 0 ? $limit : -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    );

    $tax_query = array();
    if (!empty($level)) {
        $tax_query[] = array(
            'taxonomy' => 'bcn_membership_level',
            'field'    => 'slug',
            'terms'    => $level,
        );
    }

    if (!empty($tax_query)) {
        $query_args['tax_query'] = $tax_query;
    }

    if ($featured) {
        $query_args['meta_query'] = array(
            array(
                'key'   => 'bcn_member_featured',
                'value' => 1,
            ),
        );
    }

    $query = new WP_Query($query_args);

    if (!$query->have_posts()) {
        wp_reset_postdata();
        return '';
    }

    $column_class = 'columns-' . $columns;
    $output       = '<div class="bcn-member-logo-grid ' . esc_attr($column_class) . '">';

    while ($query->have_posts()) {
        $query->the_post();
        $logo_html = get_the_post_thumbnail(get_the_ID(), 'medium', array(
            'class' => 'bcn-member-logo-image',
            'alt'   => get_the_title(),
        ));

        if (empty($logo_html)) {
            $logo_html = '<div class="bcn-member-logo-placeholder">' . esc_html(get_the_title()) . '</div>';
        }

        $output .= '<a class="bcn-member-logo" href="' . esc_url(get_permalink()) . '" aria-label="' . esc_attr(get_the_title()) . '">';
        $output .= $logo_html;
        $output .= '</a>';
    }

    wp_reset_postdata();

    $output .= '</div>';
    return $output;
}
add_shortcode('member_logo_grid', 'bcn_member_logo_grid_shortcode');

/**
 * Retrieve a member that has been granted submission permissions by key.
 *
 * @param string $key Submission key provided by the member.
 *
 * @return WP_Post|null
 */
function bcn_get_member_by_submission_key($key) {
    $key = trim($key);

    if ('' === $key) {
        return null;
    }

    $query = new WP_Query(
        array(
            'post_type'      => 'bcn_member',
            'post_status'    => array('publish', 'pending', 'draft'),
            'posts_per_page' => 1,
            'no_found_rows'  => true,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'   => 'bcn_member_submission_key',
                    'value' => $key,
                ),
                array(
                    'key'   => 'bcn_member_can_submit_content',
                    'value' => 1,
                ),
            ),
        )
    );

    if (!$query->have_posts()) {
        wp_reset_postdata();
        return null;
    }

    $member = $query->posts[0];
    wp_reset_postdata();

    return $member;
}

/**
 * Send a notification email when a member submission is recorded.
 *
 * @param int     $post_id Newly created post ID.
 * @param WP_Post $member  Member post that originated the submission.
 * @param string  $type    Submission type.
 * @param array   $fields  Sanitized submission fields.
 */
function bcn_notify_member_submission($post_id, $member, $type, $fields) {
    $admin_email = get_option('admin_email');

    if (!$admin_email) {
        return;
    }

    $member_name = get_the_title($member);
    $submission_label = ('photo' === $type) ? __('photo', 'bcn-wp-theme') : __('blog post', 'bcn-wp-theme');

    $lines = array(
        sprintf(__('A new %1$s submission is ready for review.', 'bcn-wp-theme'), $submission_label),
        '',
        sprintf(__('Member: %s', 'bcn-wp-theme'), $member_name),
        sprintf(__('Title: %s', 'bcn-wp-theme'), get_the_title($post_id)),
    );

    if (!empty($fields['contact_name'])) {
        $lines[] = sprintf(__('Submitted by: %s', 'bcn-wp-theme'), $fields['contact_name']);
    }

    if (!empty($fields['contact_email'])) {
        $lines[] = sprintf(__('Contact email: %s', 'bcn-wp-theme'), $fields['contact_email']);
    }

    $lines[] = '';
    $lines[] = admin_url('post.php?post=' . $post_id . '&action=edit');

    wp_mail(
        $admin_email,
        sprintf(__('New %1$s submission from %2$s', 'bcn-wp-theme'), $submission_label, $member_name),
        implode("\n", $lines)
    );
}

/**
 * Shortcode for displaying the member submission form.
 *
 * Usage: [member_submission_form] or [member_submission_form key="ABC123" redirect="/thank-you/"]
 *
 * @param array $atts Shortcode attributes.
 *
 * @return string
 */
function bcn_member_submission_form_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'key'      => '',
            'redirect' => '',
        ),
        $atts,
        'member_submission_form'
    );

    $provided_key = '';

    if (!empty($_REQUEST['submission_key'])) {
        $provided_key = sanitize_text_field(wp_unslash($_REQUEST['submission_key']));
    }

    if (!$provided_key && !empty($atts['key'])) {
        $provided_key = sanitize_text_field($atts['key']);
    }

    if (!$provided_key) {
        return '<div class="bcn-member-submission-form bcn-member-submission-form--error"><p>' . esc_html__('You need a valid submission key to share content.', 'bcn-wp-theme') . '</p></div>';
    }

    $member = bcn_get_member_by_submission_key($provided_key);

    if (!$member) {
        return '<div class="bcn-member-submission-form bcn-member-submission-form--error"><p>' . esc_html__('This submission link is no longer active. Please contact the BCN team for assistance.', 'bcn-wp-theme') . '</p></div>';
    }

    $fields = array(
        'title'         => '',
        'type'          => 'blog',
        'content'       => '',
        'caption'       => '',
        'contact_name'  => '',
        'contact_email' => '',
    );

    $errors          = array();
    $success_message = '';

    if (!empty($_POST['bcn_member_submission_form_submitted'])) {
        if (!isset($_POST['bcn_member_submission_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bcn_member_submission_nonce'])), 'bcn_member_submit_content')) {
            $errors[] = __('Your session expired. Please try submitting the form again.', 'bcn-wp-theme');
        } else {
            $fields['title'] = isset($_POST['bcn_member_submission_title']) ? sanitize_text_field(wp_unslash($_POST['bcn_member_submission_title'])) : '';
            $fields['type']  = isset($_POST['bcn_member_submission_type']) ? sanitize_key(wp_unslash($_POST['bcn_member_submission_type'])) : 'blog';
            $fields['content'] = isset($_POST['bcn_member_submission_content']) ? wp_kses_post(wp_unslash($_POST['bcn_member_submission_content'])) : '';
            $fields['caption'] = isset($_POST['bcn_member_submission_caption']) ? wp_kses_post(wp_unslash($_POST['bcn_member_submission_caption'])) : '';
            $fields['contact_name'] = isset($_POST['bcn_member_submission_contact_name']) ? sanitize_text_field(wp_unslash($_POST['bcn_member_submission_contact_name'])) : '';

            $raw_contact_email = isset($_POST['bcn_member_submission_contact_email']) ? wp_unslash($_POST['bcn_member_submission_contact_email']) : '';
            $fields['contact_email'] = sanitize_email($raw_contact_email);

            if (empty($fields['title'])) {
                $errors[] = __('Please add a descriptive title.', 'bcn-wp-theme');
            }

            if (!in_array($fields['type'], array('blog', 'photo'), true)) {
                $fields['type'] = 'blog';
            }

            if ('blog' === $fields['type'] && empty($fields['content'])) {
                $errors[] = __('Tell us about your update in the story field.', 'bcn-wp-theme');
            }

            if ('photo' === $fields['type']) {
                $uploaded_file = isset($_FILES['bcn_member_submission_file']) ? $_FILES['bcn_member_submission_file'] : null;

                if (empty($uploaded_file) || empty($uploaded_file['name'])) {
                    $errors[] = __('Please upload an image to accompany your submission.', 'bcn-wp-theme');
                } else {
                    $filetype = wp_check_filetype($uploaded_file['name']);
                    if (empty($filetype['type']) || 0 !== strpos($filetype['type'], 'image/')) {
                        $errors[] = __('Upload a JPG, PNG, or GIF image.', 'bcn-wp-theme');
                    }
                }
            }

            if (!empty($raw_contact_email) && empty($fields['contact_email'])) {
                $errors[] = __('The contact email looks invalid. Double-check it and try again.', 'bcn-wp-theme');
            }

            if (empty($errors)) {
                $post_content = ('photo' === $fields['type']) ? $fields['caption'] : $fields['content'];

                $postarr = array(
                    'post_type'    => 'post',
                    'post_title'   => $fields['title'],
                    'post_content' => $post_content,
                    'post_status'  => 'pending',
                    'meta_input'   => array(
                        'bcn_member_submission_member_id' => $member->ID,
                        'bcn_member_submission_type'      => $fields['type'],
                    ),
                );

                if (!empty($fields['contact_name'])) {
                    $postarr['meta_input']['bcn_member_submission_contact_name'] = $fields['contact_name'];
                }

                if (!empty($fields['contact_email'])) {
                    $postarr['meta_input']['bcn_member_submission_contact_email'] = $fields['contact_email'];
                }

                $post_id = wp_insert_post($postarr, true);

                if (is_wp_error($post_id)) {
                    $errors[] = __('We could not save your submission. Please try again in a moment.', 'bcn-wp-theme');
                } else {
                    $uploaded_file = isset($_FILES['bcn_member_submission_file']) ? $_FILES['bcn_member_submission_file'] : null;

                    if ('photo' === $fields['type'] && !empty($uploaded_file) && !empty($uploaded_file['name'])) {
                        // Validate upload before processing.
                        $validation = bcn_validate_uploaded_image($uploaded_file);
                        if (is_wp_error($validation)) {
                            $errors[] = $validation->get_error_message();
                            wp_delete_post($post_id, true);
                        } else {
                            // Load WordPress admin functions
                            if (!function_exists('media_handle_upload')) {
                                require_once ABSPATH . 'wp-admin/includes/file.php';
                                require_once ABSPATH . 'wp-admin/includes/media.php';
                                require_once ABSPATH . 'wp-admin/includes/image.php';
                            }

                            $attachment_id = media_handle_upload('bcn_member_submission_file', $post_id);

                            if (is_wp_error($attachment_id)) {
                                $errors[] = __('Your image could not be uploaded. Please try again or reduce the file size.', 'bcn-wp-theme');
                                wp_delete_post($post_id, true);
                            } else {
                                set_post_thumbnail($post_id, $attachment_id);
                            }
                        }
                    }

                    if (empty($errors)) {
                        bcn_notify_member_submission($post_id, $member, $fields['type'], $fields);

                        $success_message = __('Thanks for sharing! Our editorial team will review your submission shortly.', 'bcn-wp-theme');
                        $fields = array(
                            'title'         => '',
                            'type'          => $fields['type'],
                            'content'       => '',
                            'caption'       => '',
                            'contact_name'  => '',
                            'contact_email' => '',
                        );

                        if (!empty($atts['redirect'])) {
                            $redirect_url = esc_url_raw($atts['redirect']);
                            if ($redirect_url) {
                                wp_safe_redirect($redirect_url);
                                exit;
                            }
                        }
                    }
                }
            }
        }
    }

    $output  = '<div class="bcn-member-submission-form">';
    $output .= '<h2 class="bcn-member-submission-form__heading">' . esc_html(sprintf(__('Submit a story on behalf of %s', 'bcn-wp-theme'), get_the_title($member))) . '</h2>';

    if (!empty($success_message)) {
        $output .= '<div class="bcn-member-submission-form__notice bcn-member-submission-form__notice--success"><p>' . esc_html($success_message) . '</p></div>';
    }

    if (!empty($errors)) {
        $output .= '<div class="bcn-member-submission-form__notice bcn-member-submission-form__notice--error"><ul>';
        foreach ($errors as $error) {
            $output .= '<li>' . esc_html($error) . '</li>';
        }
        $output .= '</ul></div>';
    }

    $output .= '<form method="post" enctype="multipart/form-data" class="bcn-member-submission-form__fields">';
    $output .= '<input type="hidden" name="bcn_member_submission_form_submitted" value="1" />';
    $output .= '<input type="hidden" name="submission_key" value="' . esc_attr($provided_key) . '" />';
    $output .= wp_nonce_field('bcn_member_submit_content', 'bcn_member_submission_nonce', true, false);

    $output .= '<p class="bcn-member-submission-form__field">';
    $output .= '<label for="bcn_member_submission_title"><strong>' . esc_html__('Title', 'bcn-wp-theme') . '</strong></label>';
    $output .= '<input type="text" name="bcn_member_submission_title" id="bcn_member_submission_title" value="' . esc_attr($fields['title']) . '" required />';
    $output .= '</p>';

    $output .= '<div class="bcn-member-submission-form__field bcn-member-submission-form__field--options">';
    $output .= '<fieldset>';
    $output .= '<legend><strong>' . esc_html__('What are you submitting?', 'bcn-wp-theme') . '</strong></legend>';
    $output .= '<label><input type="radio" name="bcn_member_submission_type" value="blog" ' . checked('blog', $fields['type'], false) . ' /> ' . esc_html__('A story or blog update', 'bcn-wp-theme') . '</label>';
    $output .= '<label><input type="radio" name="bcn_member_submission_type" value="photo" ' . checked('photo', $fields['type'], false) . ' /> ' . esc_html__('A photo for the gallery', 'bcn-wp-theme') . '</label>';
    $output .= '</fieldset>';
    $output .= '</div>';

    $output .= '<p class="bcn-member-submission-form__field">';
    $output .= '<label for="bcn_member_submission_contact_name"><strong>' . esc_html__('Your name', 'bcn-wp-theme') . '</strong> <span class="optional">' . esc_html__('(optional)', 'bcn-wp-theme') . '</span></label>';
    $output .= '<input type="text" name="bcn_member_submission_contact_name" id="bcn_member_submission_contact_name" value="' . esc_attr($fields['contact_name']) . '" />';
    $output .= '</p>';

    $output .= '<p class="bcn-member-submission-form__field">';
    $output .= '<label for="bcn_member_submission_contact_email"><strong>' . esc_html__('Contact email', 'bcn-wp-theme') . '</strong> <span class="optional">' . esc_html__('(optional)', 'bcn-wp-theme') . '</span></label>';
    $output .= '<input type="email" name="bcn_member_submission_contact_email" id="bcn_member_submission_contact_email" value="' . esc_attr($fields['contact_email']) . '" />';
    $output .= '</p>';

    $output .= '<div class="bcn-member-submission-form__field bcn-member-submission-form__field--content">';
    $output .= '<label for="bcn_member_submission_content"><strong>' . esc_html__('Story details', 'bcn-wp-theme') . '</strong></label>';
    $output .= '<textarea name="bcn_member_submission_content" id="bcn_member_submission_content" rows="8" placeholder="' . esc_attr__('Share your update, announcement, or event recap.', 'bcn-wp-theme') . '">' . esc_textarea($fields['content']) . '</textarea>';
    $output .= '</div>';

    $output .= '<div class="bcn-member-submission-form__field bcn-member-submission-form__field--caption">';
    $output .= '<label for="bcn_member_submission_caption"><strong>' . esc_html__('Photo caption or context', 'bcn-wp-theme') . '</strong> <span class="optional">' . esc_html__('(optional)', 'bcn-wp-theme') . '</span></label>';
    $output .= '<textarea name="bcn_member_submission_caption" id="bcn_member_submission_caption" rows="4" placeholder="' . esc_attr__('Tell us who or what is featured in the photo.', 'bcn-wp-theme') . '">' . esc_textarea($fields['caption']) . '</textarea>';
    $output .= '</div>';

    $output .= '<p class="bcn-member-submission-form__field">';
    $output .= '<label for="bcn_member_submission_file"><strong>' . esc_html__('Upload an image', 'bcn-wp-theme') . '</strong> <span class="optional">' . esc_html__('(required for photo submissions)', 'bcn-wp-theme') . '</span></label>';
    $output .= '<input type="file" name="bcn_member_submission_file" id="bcn_member_submission_file" accept="image/*" />';
    $output .= '</p>';

    $output .= '<p class="bcn-member-submission-form__submit"><button type="submit" class="button button-primary">' . esc_html__('Send to BCN', 'bcn-wp-theme') . '</button></p>';

    $output .= '</form>';
    $output .= '</div>';

    return $output;
}
add_shortcode('member_submission_form', 'bcn_member_submission_form_shortcode');

/**
 * Helper to build attribute arrays for templates.
 *
 * @param int $post_id Member post ID.
 *
 * @return array
 */
function bcn_get_member_profile_fields($post_id) {
    return array(
        'website' => get_post_meta($post_id, 'bcn_member_website', true),
        'email'   => get_post_meta($post_id, 'bcn_member_email', true),
        'phone'   => get_post_meta($post_id, 'bcn_member_phone', true),
        'address' => get_post_meta($post_id, 'bcn_member_address', true),
        'levels'  => wp_get_post_terms($post_id, 'bcn_membership_level', array('fields' => 'all')),
    );
}

/**
 * Validate an uploaded image array from $_FILES.
 *
 * @param array|null $file The uploaded file item (from $_FILES) or null.
 * @return true|WP_Error True if valid, WP_Error on failure.
 */
function bcn_validate_uploaded_image($file) {
    if (empty($file) || !is_array($file) || empty($file['name'])) {
        return new WP_Error('no_file', __('No file was uploaded.', 'bcn-wp-theme'));
    }

    if (!empty($file['error'])) {
        return new WP_Error('upload_error', __('There was an error during the file upload. Please try again.', 'bcn-wp-theme'));
    }

    // Max file size: 5MB
    $max_bytes = 5 * 1024 * 1024;
    if (!empty($file['size']) && $file['size'] > $max_bytes) {
        return new WP_Error('file_too_large', __('The uploaded file is too large. Please use an image smaller than 5 MB.', 'bcn-wp-theme'));
    }

    // Basic MIME/type check using the filename and WP helper.
    $filetype = wp_check_filetype($file['name']);
    if (empty($filetype['type']) || 0 !== strpos($filetype['type'], 'image/')) {
        return new WP_Error('invalid_type', __('Please upload a JPG, PNG, or GIF image.', 'bcn-wp-theme'));
    }

    return true;
}
