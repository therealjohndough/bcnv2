<?php
/**
 * Custom template tags for this theme
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

if (!function_exists('bcn_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function bcn_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'bcn-wp-theme'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('bcn_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function bcn_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'bcn-wp-theme'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('bcn_entry_footer')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function bcn_entry_footer() {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'bcn-wp-theme'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                echo '<span class="cat-links">' . sprintf( esc_html__( 'Posted in %1$s', 'bcn-wp-theme' ), wp_kses_post( $categories_list ) ) . '</span>';
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'bcn-wp-theme'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                echo '<span class="tags-links"> | ' . sprintf( esc_html__( 'Tagged %1$s', 'bcn-wp-theme' ), wp_kses_post( $tags_list ) ) . '</span>';
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link"> | ';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'bcn-wp-theme'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'bcn-wp-theme'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link"> | ',
            '</span>'
        );
    }
endif;

if (!function_exists('bcn_get_member_profile_fields')) :
    /**
     * Get member profile fields for display in templates.
     *
     * @param int $post_id Member post ID.
     * @return array Array of member profile data.
     */
    function bcn_get_member_profile_fields($post_id) {
        return array(
            'website' => get_post_meta($post_id, 'bcn_member_website', true),
            'email'   => get_post_meta($post_id, 'bcn_member_email', true),
            'phone'   => get_post_meta($post_id, 'bcn_member_phone', true),
            'address' => get_post_meta($post_id, 'bcn_member_address', true),
            'featured' => (bool) get_post_meta($post_id, 'bcn_member_featured', true),
            'can_submit' => (bool) get_post_meta($post_id, 'bcn_member_can_submit_content', true),
            'levels'  => wp_get_post_terms($post_id, 'bcn_membership_level', array('fields' => 'all')),
        );
    }
endif;

if (!function_exists('bcn_get_member_card_data')) :
    /**
     * Get enhanced member card data for modern display.
     *
     * @param int $post_id Member post ID.
     * @return array Enhanced member card data.
     */
    function bcn_get_member_card_data($post_id) {
        $fields = bcn_get_member_profile_fields($post_id);
        $post = get_post($post_id);
        
        return array(
            'id' => $post_id,
            'title' => get_the_title($post_id),
            'excerpt' => get_the_excerpt($post_id),
            'permalink' => get_permalink($post_id),
            'thumbnail' => get_the_post_thumbnail_url($post_id, 'medium'),
            'logo_alt' => get_post_meta($post_id, '_wp_attachment_image_alt', true),
            'website' => $fields['website'],
            'email' => $fields['email'],
            'phone' => $fields['phone'],
            'address' => $fields['address'],
            'featured' => $fields['featured'],
            'levels' => $fields['levels'],
            'social_links' => bcn_get_member_social_links($post_id),
            'testimonials' => bcn_get_member_testimonials($post_id),
            'engagement_score' => bcn_calculate_member_engagement($post_id),
            'last_activity' => bcn_get_member_last_activity($post_id),
        );
    }
endif;

if (!function_exists('bcn_get_member_social_links')) :
    /**
     * Get member social media links.
     *
     * @param int $post_id Member post ID.
     * @return array Social media links.
     */
    function bcn_get_member_social_links($post_id) {
        return array(
            'instagram' => get_post_meta($post_id, 'bcn_member_instagram', true),
            'facebook' => get_post_meta($post_id, 'bcn_member_facebook', true),
            'twitter' => get_post_meta($post_id, 'bcn_member_twitter', true),
            'linkedin' => get_post_meta($post_id, 'bcn_member_linkedin', true),
            'youtube' => get_post_meta($post_id, 'bcn_member_youtube', true),
        );
    }
endif;

if (!function_exists('bcn_get_member_testimonials')) :
    /**
     * Get member testimonials for display.
     *
     * @param int $post_id Member post ID.
     * @return array Testimonials data.
     */
    function bcn_get_member_testimonials($post_id) {
        $testimonials = get_post_meta($post_id, 'bcn_member_testimonials', true);
        return is_array($testimonials) ? $testimonials : array();
    }
endif;

if (!function_exists('bcn_calculate_member_engagement')) :
    /**
     * Calculate member engagement score based on activity.
     *
     * @param int $post_id Member post ID.
     * @return int Engagement score (0-100).
     */
    function bcn_calculate_member_engagement($post_id) {
        $score = 0;
        
        // Base score for having a profile
        $score += 20;
        
        // Points for having complete profile
        $fields = bcn_get_member_profile_fields($post_id);
        if (!empty($fields['website'])) $score += 15;
        if (!empty($fields['email'])) $score += 10;
        if (!empty($fields['phone'])) $score += 10;
        if (!empty($fields['address'])) $score += 10;
        if (has_post_thumbnail($post_id)) $score += 15;
        
        // Points for social media presence
        $social_links = bcn_get_member_social_links($post_id);
        $social_count = count(array_filter($social_links));
        $score += min($social_count * 5, 20);
        
        return min($score, 100);
    }
endif;

if (!function_exists('bcn_get_member_last_activity')) :
    /**
     * Get member's last activity date.
     *
     * @param int $post_id Member post ID.
     * @return string|false Last activity date or false.
     */
    function bcn_get_member_last_activity($post_id) {
        $last_activity = get_post_meta($post_id, 'bcn_member_last_activity', true);
        return $last_activity ? $last_activity : get_the_modified_date('c', $post_id);
    }
endif;

if (!function_exists('bcn_get_social_icon')) :
    /**
     * Get social media icon for a platform.
     *
     * @param string $platform Social media platform name.
     * @return string Icon HTML or SVG.
     */
    function bcn_get_social_icon($platform) {
        $icons = array(
            'instagram' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
            'facebook' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            'twitter' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
            'linkedin' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
            'youtube' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        );
        
        return isset($icons[$platform]) ? $icons[$platform] : '📱';
    }
endif;
