<?php
/**
 * BCN Theme Customizer
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function bcn_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'bcn_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'bcn_customize_partial_blogdescription',
            )
        );
    }

    // Add theme color options
    $wp_customize->add_section(
        'bcn_colors',
        array(
            'title'    => __('Theme Colors', 'bcn-wp-theme'),
            'priority' => 30,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'bcn_primary_color',
        array(
            'default'           => '#2d5016',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'bcn_primary_color',
            array(
                'label'    => __('Primary Color', 'bcn-wp-theme'),
                'section'  => 'bcn_colors',
                'settings' => 'bcn_primary_color',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'bcn_secondary_color',
        array(
            'default'           => '#7cb342',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'bcn_secondary_color',
            array(
                'label'    => __('Secondary Color', 'bcn-wp-theme'),
                'section'  => 'bcn_colors',
                'settings' => 'bcn_secondary_color',
            )
        )
    );
}
add_action('customize_register', 'bcn_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function bcn_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function bcn_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function bcn_customize_preview_js() {
    wp_enqueue_script('bcn-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), '1.0.0', true);
}
add_action('customize_preview_init', 'bcn_customize_preview_js');
