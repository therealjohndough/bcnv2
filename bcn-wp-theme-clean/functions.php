<?php
/**
 * Buffalo Cannabis Network Theme Functions
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function bcn_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1200, 675, true);

    // Add custom image sizes
    add_image_size('bcn-featured', 800, 450, true);
    add_image_size('bcn-thumbnail', 400, 300, true);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'bcn-wp-theme'),
        'footer'  => __('Footer Menu', 'bcn-wp-theme'),
        'community' => __('Community Menu', 'bcn-wp-theme'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 350,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for align wide
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'bcn_theme_setup');

/**
 * Set the content width in pixels
 */
function bcn_content_width() {
    $GLOBALS['content_width'] = apply_filters('bcn_content_width', 1200);
}
add_action('after_setup_theme', 'bcn_content_width', 0);

/**
 * Register widget areas
 */
function bcn_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'bcn-wp-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'bcn-wp-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'bcn-wp-theme'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'bcn-wp-theme'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Community Widget Area', 'bcn-wp-theme'),
        'id'            => 'community-1',
        'description'   => __('Add widgets here for community features.', 'bcn-wp-theme'),
        'before_widget' => '<div id="%1$s" class="community-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'bcn_widgets_init');

/**
 * Enqueue scripts and styles
 */
function bcn_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style('bcn-style', get_stylesheet_uri(), array(), '1.0.0');

    // Enqueue custom scripts
    wp_enqueue_script('bcn-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '1.0.0', true);
    wp_enqueue_script('bcn-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);

    // Add inline script for smooth scroll
    wp_add_inline_script('bcn-main', 'var bcnTheme = ' . json_encode(array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('bcn-nonce'),
    )), 'before');

    // Enqueue comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'bcn_scripts');

// Load BCN pattern styles
add_action('wp_enqueue_scripts', function() {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_style('bcn-patterns', get_template_directory_uri() . '/assets/css/patterns.css', [], $version);
    
    // Enqueue member portal styles
    if (is_page('member-portal') || is_page_template('page-member-portal.php')) {
        wp_enqueue_style('bcn-member-portal', get_template_directory_uri() . '/assets/css/member-portal.css', [], $version);
        wp_enqueue_style('bcn-member-dashboard', get_template_directory_uri() . '/assets/css/member-dashboard.css', [], $version);
    }
});

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress
 */
require get_template_directory() . '/includes/template-functions.php';

/**
 * Customizer additions
 */
require get_template_directory() . '/includes/customizer.php';

// Load pattern registrations
require_once get_template_directory() . '/includes/patterns.php';

/**
 * Member directory features
 */
require get_template_directory() . '/includes/member-directory.php';

/**
 * Custom post types and taxonomies
 */
require get_template_directory() . '/includes/post-types.php';

/**
 * Community features
 */
require get_template_directory() . '/includes/community-features.php';

/**
 * Custom Admin Theme
 */
require get_template_directory() . '/admin-theme/admin-theme.php';

/**
 * ACF Field Groups
 */
require get_template_directory() . '/includes/acf-fields/acf-field-groups.php';

/**
 * Custom Post Types
 */
require get_template_directory() . '/includes/custom-post-types/custom-post-types.php';

/**
 * Automation Features
 */
require get_template_directory() . '/includes/automation/automation.php';

/**
 * Enhanced Testimonial System
 */
require get_template_directory() . '/includes/enhanced-testimonial-system.php';

/**
 * Blog Submission System
 */
require get_template_directory() . '/includes/blog-submission-system.php';

/**
 * Member Dashboard System
 */
require get_template_directory() . '/includes/member-dashboard-system.php';

/**
 * Submission Workflows
 */
require get_template_directory() . '/includes/submission-workflows.php';

/**
 * Add custom body classes
 */
function bcn_body_classes($classes) {
    // Add class if sidebar is active
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    }

    // Add class for single posts
    if (is_singular()) {
        $classes[] = 'singular';
    }

    return $classes;
}
add_filter('body_class', 'bcn_body_classes');

/**
 * Add excerpt length filter
 */
function bcn_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'bcn_excerpt_length');

/**
 * Add excerpt more filter
 */
function bcn_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'bcn_excerpt_more');
