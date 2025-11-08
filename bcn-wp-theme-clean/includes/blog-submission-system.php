<?php
/**
 * Blog Submission System for Authorized Members
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BCN_Blog_Submission_System {
    
    public function __construct() {
        add_action('init', array($this, 'register_blog_submission_fields'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_blog_scripts'));
        add_action('wp_ajax_submit_blog_post', array($this, 'handle_blog_submission'));
        add_action('wp_ajax_get_member_blog_posts', array($this, 'get_member_blog_posts'));
        add_action('wp_ajax_delete_blog_draft', array($this, 'delete_blog_draft'));
        add_shortcode('bcn_blog_submission_form', array($this, 'blog_submission_form_shortcode'));
        add_shortcode('bcn_member_blog_dashboard', array($this, 'member_blog_dashboard_shortcode'));
        add_action('publish_bcn_news', array($this, 'award_blog_publication_points'), 10, 2);
        add_action('save_post_bcn_news', array($this, 'check_blog_achievements'));
    }
    
    /**
     * Register ACF fields for blog submissions
     */
    public function register_blog_submission_fields() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group(array(
                'key' => 'group_bcn_blog_submission',
                'title' => 'Blog Submission Details',
                'fields' => array(
                    array(
                        'key' => 'field_blog_submission_member',
                        'label' => 'Submitting Member',
                        'name' => 'blog_submission_member',
                        'type' => 'post_object',
                        'post_type' => array('bcn_member'),
                        'return_format' => 'object',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_blog_submission_status',
                        'label' => 'Submission Status',
                        'name' => 'blog_submission_status',
                        'type' => 'select',
                        'choices' => array(
                            'draft' => 'Draft',
                            'submitted' => 'Submitted for Review',
                            'under_review' => 'Under Review',
                            'approved' => 'Approved',
                            'published' => 'Published',
                            'rejected' => 'Rejected',
                        ),
                        'default_value' => 'draft',
                    ),
                    array(
                        'key' => 'field_blog_submission_type',
                        'label' => 'Blog Post Type',
                        'name' => 'blog_submission_type',
                        'type' => 'select',
                        'choices' => array(
                            'industry_news' => 'Industry News',
                            'member_spotlight' => 'Member Spotlight',
                            'event_recap' => 'Event Recap',
                            'educational' => 'Educational Content',
                            'opinion' => 'Opinion Piece',
                            'case_study' => 'Case Study',
                            'announcement' => 'Announcement',
                        ),
                        'default_value' => 'industry_news',
                    ),
                    array(
                        'key' => 'field_blog_submission_category',
                        'label' => 'Category',
                        'name' => 'blog_submission_category',
                        'type' => 'taxonomy',
                        'taxonomy' => 'news_category',
                        'field_type' => 'select',
                        'allow_null' => 1,
                    ),
                    array(
                        'key' => 'field_blog_submission_tags',
                        'label' => 'Tags',
                        'name' => 'blog_submission_tags',
                        'type' => 'text',
                        'placeholder' => 'Enter tags separated by commas',
                        'instructions' => 'Add relevant tags to help categorize your post',
                    ),
                    array(
                        'key' => 'field_blog_submission_featured_image',
                        'label' => 'Featured Image',
                        'name' => 'blog_submission_featured_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_blog_submission_gallery',
                        'label' => 'Image Gallery',
                        'name' => 'blog_submission_gallery',
                        'type' => 'gallery',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'library' => 'all',
                    ),
                    array(
                        'key' => 'field_blog_submission_author_bio',
                        'label' => 'Author Bio',
                        'name' => 'blog_submission_author_bio',
                        'type' => 'textarea',
                        'rows' => 3,
                        'placeholder' => 'Brief bio about yourself for the author section',
                    ),
                    array(
                        'key' => 'field_blog_submission_contact_email',
                        'label' => 'Contact Email',
                        'name' => 'blog_submission_contact_email',
                        'type' => 'email',
                        'required' => 1,
                        'placeholder' => 'your.email@example.com',
                    ),
                    array(
                        'key' => 'field_blog_submission_editorial_notes',
                        'label' => 'Editorial Notes',
                        'name' => 'blog_submission_editorial_notes',
                        'type' => 'textarea',
                        'rows' => 3,
                        'placeholder' => 'Any notes for the editorial team',
                        'instructions' => 'Optional notes to help the editorial team understand your submission',
                    ),
                    array(
                        'key' => 'field_blog_submission_reviewer_notes',
                        'label' => 'Reviewer Notes',
                        'name' => 'blog_submission_reviewer_notes',
                        'type' => 'textarea',
                        'rows' => 3,
                        'instructions' => 'Internal notes for reviewers (not visible to submitter)',
                    ),
                    array(
                        'key' => 'field_blog_submission_word_count',
                        'label' => 'Word Count',
                        'name' => 'blog_submission_word_count',
                        'type' => 'number',
                        'readonly' => 1,
                        'instructions' => 'Automatically calculated word count',
                    ),
                    array(
                        'key' => 'field_blog_submission_seo_title',
                        'label' => 'SEO Title',
                        'name' => 'blog_submission_seo_title',
                        'type' => 'text',
                        'maxlength' => 60,
                        'placeholder' => 'Optimized title for search engines',
                        'instructions' => 'Recommended: 50-60 characters',
                    ),
                    array(
                        'key' => 'field_blog_submission_seo_description',
                        'label' => 'SEO Description',
                        'name' => 'blog_submission_seo_description',
                        'type' => 'textarea',
                        'rows' => 3,
                        'maxlength' => 160,
                        'placeholder' => 'Brief description for search engines',
                        'instructions' => 'Recommended: 150-160 characters',
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'bcn_news',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
            ));
        }
    }
    
    /**
     * Enqueue blog submission scripts and styles
     */
    public function enqueue_blog_scripts() {
        wp_enqueue_script('bcn-blog-submission', get_template_directory_uri() . '/assets/js/blog-submission.js', array('jquery'), '1.0.0', true);
        wp_enqueue_style('bcn-blog-submission', get_template_directory_uri() . '/assets/css/blog-submission.css', array(), '1.0.0');
        
        wp_localize_script('bcn-blog-submission', 'bcnBlogSubmission', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bcn_blog_submission_nonce'),
        ));
    }
    
    /**
     * Handle blog post submission
     */
    public function handle_blog_submission() {
        check_ajax_referer('bcn_blog_submission_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        $title = sanitize_text_field($_POST['title']);
        $content = wp_kses_post($_POST['content']);
        $excerpt = sanitize_textarea_field($_POST['excerpt']);
        $type = sanitize_text_field($_POST['type']);
        $category = sanitize_text_field($_POST['category']);
        $tags = sanitize_text_field($_POST['tags']);
        $author_bio = sanitize_textarea_field($_POST['author_bio']);
        $contact_email = sanitize_email($_POST['contact_email']);
        $editorial_notes = sanitize_textarea_field($_POST['editorial_notes']);
        $seo_title = sanitize_text_field($_POST['seo_title']);
        $seo_description = sanitize_textarea_field($_POST['seo_description']);
        $status = sanitize_text_field($_POST['status']);
        
        if (empty($title) || empty($content) || empty($member_id)) {
            wp_send_json_error('Missing required fields');
        }
        
        // Check if member has blog submission permissions
        if (!$this->member_can_submit_blog($member_id)) {
            wp_send_json_error('Member does not have blog submission permissions');
        }
        
        // Calculate word count
        $word_count = str_word_count(strip_tags($content));
        
        // Create blog post
        $post_data = array(
            'post_type' => 'bcn_news',
            'post_title' => $title,
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_status' => $status === 'submitted' ? 'pending' : 'draft',
            'meta_input' => array(
                'blog_submission_status' => $status,
                'blog_submission_type' => $type,
                'blog_submission_word_count' => $word_count,
                'blog_submission_contact_email' => $contact_email,
                'blog_submission_author_bio' => $author_bio,
                'blog_submission_editorial_notes' => $editorial_notes,
                'blog_submission_seo_title' => $seo_title,
                'blog_submission_seo_description' => $seo_description,
            )
        );
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id && !is_wp_error($post_id)) {
            // Update ACF fields
            update_field('blog_submission_member', $member_id, $post_id);
            
            // Set category
            if (!empty($category)) {
                wp_set_object_terms($post_id, $category, 'news_category');
            }
            
            // Set tags
            if (!empty($tags)) {
                wp_set_post_tags($post_id, $tags);
            }
            
            // Handle featured image upload
            if (!empty($_FILES['featured_image']['name'])) {
                $this->handle_image_upload($post_id, 'featured_image', 'blog_submission_featured_image');
            }
            
            // Handle gallery upload
            if (!empty($_FILES['gallery_images']['name'])) {
                $this->handle_gallery_upload($post_id, 'gallery_images', 'blog_submission_gallery');
            }
            
            // Award points for blog submission
            $this->award_blog_submission_points($member_id, $status);
            
            // Send notification email
            $this->send_blog_submission_notification($post_id, $member_id, $status);
            
            wp_send_json_success(array(
                'post_id' => $post_id,
                'message' => $status === 'submitted' ? 'Blog post submitted for review' : 'Blog post saved as draft'
            ));
        } else {
            wp_send_json_error('Failed to save blog post');
        }
    }
    
    /**
     * Get member's blog posts
     */
    public function get_member_blog_posts() {
        check_ajax_referer('bcn_blog_submission_nonce', 'nonce');
        
        $member_id = intval($_POST['member_id']);
        $posts = get_posts(array(
            'post_type' => 'bcn_news',
            'meta_query' => array(
                array(
                    'key' => 'blog_submission_member',
                    'value' => $member_id,
                    'compare' => '='
                )
            ),
            'posts_per_page' => -1,
            'post_status' => array('publish', 'pending', 'draft')
        ));
        
        $formatted_posts = array();
        foreach ($posts as $post) {
            $formatted_posts[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'status' => get_field('blog_submission_status', $post->ID),
                'type' => get_field('blog_submission_type', $post->ID),
                'word_count' => get_field('blog_submission_word_count', $post->ID),
                'date' => $post->post_date,
                'edit_url' => get_edit_post_link($post->ID),
                'view_url' => get_permalink($post->ID)
            );
        }
        
        wp_send_json_success($formatted_posts);
    }
    
    /**
     * Delete blog draft
     */
    public function delete_blog_draft() {
        check_ajax_referer('bcn_blog_submission_nonce', 'nonce');
        
        $post_id = intval($_POST['post_id']);
        $member_id = intval($_POST['member_id']);
        
        // Verify ownership
        $submitting_member = get_field('blog_submission_member', $post_id);
        if ($submitting_member->ID !== $member_id) {
            wp_send_json_error('Unauthorized');
        }
        
        $result = wp_delete_post($post_id, true);
        
        if ($result) {
            wp_send_json_success('Draft deleted successfully');
        } else {
            wp_send_json_error('Failed to delete draft');
        }
    }
    
    /**
     * Blog submission form shortcode
     */
    public function blog_submission_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'member_id' => '',
            'post_id' => '',
        ), $atts);
        
        if (empty($atts['member_id'])) {
            return '<p>Error: Member ID required</p>';
        }
        
        // Check if member can submit blogs
        if (!$this->member_can_submit_blog($atts['member_id'])) {
            return '<p>You do not have permission to submit blog posts. Contact BCN for more information.</p>';
        }
        
        $post_data = null;
        if (!empty($atts['post_id'])) {
            $post = get_post($atts['post_id']);
            if ($post && get_field('blog_submission_member', $post->ID)->ID == $atts['member_id']) {
                $post_data = $post;
            }
        }
        
        ob_start();
        ?>
        <div class="bcn-blog-submission-form" data-member-id="<?php echo esc_attr($atts['member_id']); ?>" data-post-id="<?php echo esc_attr($atts['post_id']); ?>">
            <h3><?php echo $post_data ? 'Edit Blog Post' : 'Submit a Blog Post'; ?></h3>
            <form id="blog-submission-form" enctype="multipart/form-data">
                <div class="blog-form-row">
                    <div class="blog-form-field">
                        <label for="title">Title *</label>
                        <input type="text" name="title" id="title" value="<?php echo $post_data ? esc_attr($post_data->post_title) : ''; ?>" required>
                    </div>
                    
                    <div class="blog-form-field">
                        <label for="type">Post Type</label>
                        <select name="type" id="type">
                            <option value="industry_news" <?php selected(get_field('blog_submission_type', $post_data->ID ?? 0), 'industry_news'); ?>>Industry News</option>
                            <option value="member_spotlight" <?php selected(get_field('blog_submission_type', $post_data->ID ?? 0), 'member_spotlight'); ?>>Member Spotlight</option>
                            <option value="event_recap" <?php selected(get_field('blog_submission_type', $post_data->ID ?? 0), 'event_recap'); ?>>Event Recap</option>
                            <option value="educational" <?php selected(get_field('blog_submission_type', $post_data->ID ?? 0), 'educational'); ?>>Educational Content</option>
                            <option value="opinion" <?php selected(get_field('blog_submission_type', $post_data->ID ?? 0), 'opinion'); ?>>Opinion Piece</option>
                            <option value="case_study" <?php selected(get_field('blog_submission_type', $post_data->ID ?? 0), 'case_study'); ?>>Case Study</option>
                            <option value="announcement" <?php selected(get_field('blog_submission_type', $post_data->ID ?? 0), 'announcement'); ?>>Announcement</option>
                        </select>
                    </div>
                </div>
                
                <div class="blog-form-field">
                    <label for="excerpt">Excerpt</label>
                    <textarea name="excerpt" id="excerpt" rows="3" placeholder="Brief summary of your post..."><?php echo $post_data ? esc_textarea($post_data->post_excerpt) : ''; ?></textarea>
                </div>
                
                <div class="blog-form-field">
                    <label for="content">Content *</label>
                    <textarea name="content" id="content" rows="15" placeholder="Write your blog post here..." required><?php echo $post_data ? esc_textarea($post_data->post_content) : ''; ?></textarea>
                    <div class="word-count">Word count: <span id="word-count">0</span></div>
                </div>
                
                <div class="blog-form-row">
                    <div class="blog-form-field">
                        <label for="category">Category</label>
                        <select name="category" id="category">
                            <option value="">Select a category</option>
                            <?php
                            $categories = get_terms(array(
                                'taxonomy' => 'news_category',
                                'hide_empty' => false,
                            ));
                            foreach ($categories as $category) :
                            ?>
                                <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected(get_field('blog_submission_category', $post_data->ID ?? 0), $category->term_id); ?>>
                                    <?php echo esc_html($category->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="blog-form-field">
                        <label for="tags">Tags</label>
                        <input type="text" name="tags" id="tags" value="<?php echo $post_data ? esc_attr(implode(', ', wp_get_post_tags($post_data->ID, array('fields' => 'names')))) : ''; ?>" placeholder="tag1, tag2, tag3">
                    </div>
                </div>
                
                <div class="blog-form-field">
                    <label for="featured_image">Featured Image</label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*">
                    <div class="image-preview" id="featured-image-preview"></div>
                </div>
                
                <div class="blog-form-field">
                    <label for="gallery_images">Gallery Images</label>
                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple>
                    <div class="gallery-preview" id="gallery-preview"></div>
                </div>
                
                <div class="blog-form-field">
                    <label for="author_bio">Author Bio</label>
                    <textarea name="author_bio" id="author_bio" rows="3" placeholder="Brief bio about yourself..."><?php echo esc_textarea(get_field('blog_submission_author_bio', $post_data->ID ?? 0)); ?></textarea>
                </div>
                
                <div class="blog-form-row">
                    <div class="blog-form-field">
                        <label for="contact_email">Contact Email *</label>
                        <input type="email" name="contact_email" id="contact_email" value="<?php echo $post_data ? esc_attr(get_field('blog_submission_contact_email', $post_data->ID)) : ''; ?>" required>
                    </div>
                    
                    <div class="blog-form-field">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option value="draft">Save as Draft</option>
                            <option value="submitted">Submit for Review</option>
                        </select>
                    </div>
                </div>
                
                <div class="blog-form-field">
                    <label for="editorial_notes">Editorial Notes</label>
                    <textarea name="editorial_notes" id="editorial_notes" rows="3" placeholder="Any notes for the editorial team..."><?php echo esc_textarea(get_field('blog_submission_editorial_notes', $post_data->ID ?? 0)); ?></textarea>
                </div>
                
                <div class="blog-form-row">
                    <div class="blog-form-field">
                        <label for="seo_title">SEO Title</label>
                        <input type="text" name="seo_title" id="seo_title" value="<?php echo $post_data ? esc_attr(get_field('blog_submission_seo_title', $post_data->ID)) : ''; ?>" maxlength="60" placeholder="Optimized title for search engines">
                        <div class="seo-counter">Characters: <span id="seo-title-count">0</span>/60</div>
                    </div>
                    
                    <div class="blog-form-field">
                        <label for="seo_description">SEO Description</label>
                        <textarea name="seo_description" id="seo_description" rows="3" maxlength="160" placeholder="Brief description for search engines"><?php echo esc_textarea(get_field('blog_submission_seo_description', $post_data->ID ?? 0)); ?></textarea>
                        <div class="seo-counter">Characters: <span id="seo-desc-count">0</span>/160</div>
                    </div>
                </div>
                
                <div class="blog-form-actions">
                    <button type="submit" class="button button-primary"><?php echo $post_data ? 'Update Post' : 'Submit Post'; ?></button>
                    <button type="button" class="button button-secondary" id="save-draft">Save Draft</button>
                    <?php if ($post_data) : ?>
                        <button type="button" class="button button-link-delete" id="delete-draft">Delete Draft</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Member blog dashboard shortcode
     */
    public function member_blog_dashboard_shortcode($atts) {
        $atts = shortcode_atts(array(
            'member_id' => '',
        ), $atts);
        
        if (empty($atts['member_id'])) {
            return '<p>Error: Member ID required</p>';
        }
        
        ob_start();
        ?>
        <div class="bcn-member-blog-dashboard" data-member-id="<?php echo esc_attr($atts['member_id']); ?>">
            <h3>Your Blog Posts</h3>
            <div class="blog-stats">
                <div class="stat-item">
                    <span class="stat-number" id="total-posts">0</span>
                    <span class="stat-label">Total Posts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="published-posts">0</span>
                    <span class="stat-label">Published</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="pending-posts">0</span>
                    <span class="stat-label">Pending</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="draft-posts">0</span>
                    <span class="stat-label">Drafts</span>
                </div>
            </div>
            
            <div class="blog-actions">
                <a href="#" class="button button-primary" id="new-blog-post">Write New Post</a>
            </div>
            
            <div class="blog-posts-list" id="blog-posts-list">
                <div class="loading">Loading blog posts...</div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Check if member can submit blogs
     */
    private function member_can_submit_blog($member_id) {
        $can_submit = get_post_meta($member_id, 'bcn_member_can_submit_content', true);
        $member_levels = wp_get_post_terms($member_id, 'bcn_membership_level', array('fields' => 'slugs'));
        
        // Pro and Premier members can submit blogs
        return $can_submit && (in_array('pro-member', $member_levels) || in_array('premier-member', $member_levels));
    }
    
    /**
     * Handle image upload
     */
    private function handle_image_upload($post_id, $field_name, $meta_key) {
        if (!empty($_FILES[$field_name]['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            
            $attachment_id = media_handle_upload($field_name, $post_id);
            
            if (!is_wp_error($attachment_id)) {
                update_field($meta_key, $attachment_id, $post_id);
            }
        }
    }
    
    /**
     * Handle gallery upload
     */
    private function handle_gallery_upload($post_id, $field_name, $meta_key) {
        if (!empty($_FILES[$field_name]['name'][0])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            
            $attachment_ids = array();
            $files = $_FILES[$field_name];
            
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    
                    $attachment_id = media_handle_sideload($file, $post_id);
                    if (!is_wp_error($attachment_id)) {
                        $attachment_ids[] = $attachment_id;
                    }
                }
            }
            
            if (!empty($attachment_ids)) {
                update_field($meta_key, $attachment_ids, $post_id);
            }
        }
    }
    
    /**
     * Award points for blog submission
     */
    private function award_blog_submission_points($member_id, $status) {
        $points = 0;
        
        switch ($status) {
            case 'draft':
                $points = 5;
                break;
            case 'submitted':
                $points = 15;
                break;
        }
        
        if ($points > 0) {
            $current_points = get_post_meta($member_id, 'member_points', true) ?: 0;
            update_post_meta($member_id, 'member_points', $current_points + $points);
        }
    }
    
    /**
     * Award points when blog is published
     */
    public function award_blog_publication_points($post_id, $post) {
        $member = get_field('blog_submission_member', $post_id);
        
        if ($member) {
            $points = 50; // Bonus points for publication
            $current_points = get_post_meta($member->ID, 'member_points', true) ?: 0;
            update_post_meta($member->ID, 'member_points', $current_points + $points);
            
            // Check for blog achievements
            $this->check_blog_achievements($post_id);
        }
    }
    
    /**
     * Check for blog-related achievements
     */
    public function check_blog_achievements($post_id) {
        $member = get_field('blog_submission_member', $post_id);
        
        if (!$member) return;
        
        $published_posts = get_posts(array(
            'post_type' => 'bcn_news',
            'meta_query' => array(
                array(
                    'key' => 'blog_submission_member',
                    'value' => $member->ID,
                    'compare' => '='
                )
            ),
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'fields' => 'ids'
        ));
        
        $count = count($published_posts);
        
        // Award achievements based on published blog count
        $achievements = array(
            1 => 'first_blog_post',
            3 => 'blog_contributor',
            5 => 'blog_writer',
            10 => 'blog_author',
            25 => 'blog_expert'
        );
        
        foreach ($achievements as $threshold => $achievement) {
            if ($count >= $threshold) {
                $this->award_achievement($member->ID, $achievement);
            }
        }
    }
    
    /**
     * Award achievement to member
     */
    private function award_achievement($member_id, $achievement_slug) {
        $current_achievements = get_post_meta($member_id, 'member_achievements', true) ?: array();
        
        if (!in_array($achievement_slug, $current_achievements)) {
            $current_achievements[] = $achievement_slug;
            update_post_meta($member_id, 'member_achievements', $current_achievements);
        }
    }
    
    /**
     * Send blog submission notification email
     */
    private function send_blog_submission_notification($post_id, $member_id, $status) {
        $admin_email = get_option('admin_email');
        $member_name = get_the_title($member_id);
        
        if ($status === 'submitted') {
            $subject = 'New Blog Post Submitted for Review - ' . get_the_title($post_id);
            $message = "A new blog post has been submitted for review by {$member_name}.\n\n";
            $message .= "Title: " . get_the_title($post_id) . "\n";
            $message .= "Review it at: " . admin_url("post.php?post={$post_id}&action=edit");
        } else {
            $subject = 'Blog Post Draft Saved - ' . get_the_title($post_id);
            $message = "A blog post draft has been saved by {$member_name}.\n\n";
            $message .= "Title: " . get_the_title($post_id);
        }
        
        wp_mail($admin_email, $subject, $message);
    }
}

// Initialize the blog submission system
new BCN_Blog_Submission_System();
