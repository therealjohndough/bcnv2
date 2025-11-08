<?php
/**
 * Front Page Customizer Options
 * 
 * Comprehensive customizer options for the front page
 * Provides dozens of customization options for clients
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Front Page Customizer Options
 */
function bcn_front_page_customizer($wp_customize) {
    
    // Front Page Panel
    $wp_customize->add_panel('bcn_front_page', [
        'title' => __('Front Page Settings', 'buffalo-cannabis-network'),
        'description' => __('Customize your homepage sections and content', 'buffalo-cannabis-network'),
        'priority' => 30,
    ]);
    
    // ========================================
    // HERO SECTION
    // ========================================
    $wp_customize->add_section('bcn_hero_section', [
        'title' => __('Hero Section', 'buffalo-cannabis-network'),
        'description' => __('Customize the hero section at the top of your homepage', 'buffalo-cannabis-network'),
        'panel' => 'bcn_front_page',
        'priority' => 10,
    ]);
    
    // Hero Enable/Disable
    $wp_customize->add_setting('bcn_hero_enabled', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    
    $wp_customize->add_control('bcn_hero_enabled', [
        'label' => __('Enable Hero Section', 'buffalo-cannabis-network'),
        'description' => __('Show or hide the hero section', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'checkbox',
    ]);
    
    // Hero Title
    $wp_customize->add_setting('bcn_hero_title', [
        'default' => 'Buffalo Cannabis Network',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('bcn_hero_title', [
        'label' => __('Hero Title', 'buffalo-cannabis-network'),
        'description' => __('Main headline for the hero section', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'text',
    ]);
    
    // Hero Subtitle
    $wp_customize->add_setting('bcn_hero_subtitle', [
        'default' => 'Building The Industry Together',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('bcn_hero_subtitle', [
        'label' => __('Hero Subtitle', 'buffalo-cannabis-network'),
        'description' => __('Subtitle text below the main title', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'text',
    ]);
    
    // Hero Description
    $wp_customize->add_setting('bcn_hero_description', [
        'default' => 'Join Western New York\'s premier cannabis industry organization and help shape the future of our industry.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    
    $wp_customize->add_control('bcn_hero_description', [
        'label' => __('Hero Description', 'buffalo-cannabis-network'),
        'description' => __('Additional descriptive text for the hero section', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'textarea',
    ]);
    
    // Hero Background Image
    $wp_customize->add_setting('bcn_hero_background_image', [
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'bcn_hero_background_image', [
        'label' => __('Hero Background Image', 'buffalo-cannabis-network'),
        'description' => __('Upload a background image for the hero section', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
    ]));
    
    // Hero Overlay Opacity
    $wp_customize->add_setting('bcn_hero_overlay_opacity', [
        'default' => 0.4,
        'sanitize_callback' => 'bcn_sanitize_float',
    ]);
    
    $wp_customize->add_control('bcn_hero_overlay_opacity', [
        'label' => __('Background Overlay Opacity', 'buffalo-cannabis-network'),
        'description' => __('Adjust the darkness of the overlay on the background image (0-1)', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'range',
        'input_attrs' => [
            'min' => 0,
            'max' => 1,
            'step' => 0.1,
        ],
    ]);
    
    // Hero Height
    $wp_customize->add_setting('bcn_hero_height', [
        'default' => '100vh',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('bcn_hero_height', [
        'label' => __('Hero Height', 'buffalo-cannabis-network'),
        'description' => __('Set the height of the hero section (e.g., 100vh, 80vh, 600px)', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'text',
    ]);
    
    // Hero Text Alignment
    $wp_customize->add_setting('bcn_hero_text_align', [
        'default' => 'center',
        'sanitize_callback' => 'bcn_sanitize_text_align',
    ]);
    
    $wp_customize->add_control('bcn_hero_text_align', [
        'label' => __('Text Alignment', 'buffalo-cannabis-network'),
        'description' => __('Choose how to align the text in the hero section', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'select',
        'choices' => [
            'left' => __('Left', 'buffalo-cannabis-network'),
            'center' => __('Center', 'buffalo-cannabis-network'),
            'right' => __('Right', 'buffalo-cannabis-network'),
        ],
    ]);
    
    // Primary Button Text
    $wp_customize->add_setting('bcn_hero_primary_button_text', [
        'default' => 'Join Our Network',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('bcn_hero_primary_button_text', [
        'label' => __('Primary Button Text', 'buffalo-cannabis-network'),
        'description' => __('Text for the main call-to-action button', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'text',
    ]);
    
    // Primary Button URL
    $wp_customize->add_setting('bcn_hero_primary_button_url', [
        'default' => '/membership/',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('bcn_hero_primary_button_url', [
        'label' => __('Primary Button URL', 'buffalo-cannabis-network'),
        'description' => __('Link destination for the primary button', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'url',
    ]);
    
    // Secondary Button Text
    $wp_customize->add_setting('bcn_hero_secondary_button_text', [
        'default' => 'View Events',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    
    $wp_customize->add_control('bcn_hero_secondary_button_text', [
        'label' => __('Secondary Button Text', 'buffalo-cannabis-network'),
        'description' => __('Text for the secondary button', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'text',
    ]);
    
    // Secondary Button URL
    $wp_customize->add_setting('bcn_hero_secondary_button_url', [
        'default' => '/events/',
        'sanitize_callback' => 'esc_url_raw',
    ]);
    
    $wp_customize->add_control('bcn_hero_secondary_button_url', [
        'label' => __('Secondary Button URL', 'buffalo-cannabis-network'),
        'description' => __('Link destination for the secondary button', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'url',
    ]);
    
    // Show Search in Hero
    $wp_customize->add_setting('bcn_hero_show_search', [
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);
    
    $wp_customize->add_control('bcn_hero_show_search', [
        'label' => __('Show Search Form', 'buffalo-cannabis-network'),
        'description' => __('Display a search form in the hero section', 'buffalo-cannabis-network'),
        'section' => 'bcn_hero_section',
        'type' => 'checkbox',
    ]);
}

/**
 * Sanitization Functions
 */
function bcn_sanitize_float($input) {
    return floatval($input);
}

function bcn_sanitize_text_align($input) {
    $valid = ['left', 'center', 'right'];
    return in_array($input, $valid) ? $input : 'center';
}

// Hook into the customizer
add_action('customize_register', 'bcn_front_page_customizer');
