<?php
/**
 * Hero Section Template Part
 * 
 * Customizable hero section with background image, text, and buttons
 * All options controlled via WordPress Customizer
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Get customizer options
$hero_title = get_theme_mod('bcn_hero_title', 'Buffalo Cannabis Network');
$hero_subtitle = get_theme_mod('bcn_hero_subtitle', 'Building The Industry Together');
$hero_description = get_theme_mod('bcn_hero_description', 'Join Western New York\'s premier cannabis industry organization and help shape the future of our industry.');
$hero_primary_button_text = get_theme_mod('bcn_hero_primary_button_text', 'Join Our Network');
$hero_primary_button_url = get_theme_mod('bcn_hero_primary_button_url', '/membership/');
$hero_secondary_button_text = get_theme_mod('bcn_hero_secondary_button_text', 'View Events');
$hero_secondary_button_url = get_theme_mod('bcn_hero_secondary_button_url', '/events/');
$hero_background_image = get_theme_mod('bcn_hero_background_image');
$hero_overlay_opacity = get_theme_mod('bcn_hero_overlay_opacity', 0.4);
$hero_height = get_theme_mod('bcn_hero_height', '100vh');
$hero_text_align = get_theme_mod('bcn_hero_text_align', 'center');
$hero_show_search = get_theme_mod('bcn_hero_show_search', false);
?>

<section class="bcn-hero-section" style="
    background: <?php echo $hero_background_image ? 'url(' . esc_url($hero_background_image) . ') center/cover' : 'linear-gradient(135deg, var(--primary-color), var(--secondary-color))'; ?>;
    color: white;
    padding: 4rem 0;
    min-height: <?php echo esc_attr($hero_height); ?>;
    text-align: <?php echo esc_attr($hero_text_align); ?>;
    position: relative;
    display: flex;
    align-items: center;
">
    <?php if ($hero_background_image) : ?>
    <div class="hero-overlay" style="
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,<?php echo esc_attr($hero_overlay_opacity); ?>);
    "></div>
    <?php endif; ?>
    
    <div class="container" style="
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 2;
        width: 100%;
    ">
        <div class="hero-content">
            <h1 class="hero-title" style="
                font-size: 3.5rem;
                margin-bottom: 1rem;
                font-weight: 700;
                line-height: 1.2;
            "><?php echo esc_html($hero_title); ?></h1>
            
            <p class="hero-subtitle" style="
                font-size: 1.5rem;
                margin-bottom: 1rem;
                opacity: 0.9;
                font-weight: 300;
            "><?php echo esc_html($hero_subtitle); ?></p>
            
            <?php if ($hero_description) : ?>
            <p class="hero-description" style="
                font-size: 1.1rem;
                margin-bottom: 2rem;
                opacity: 0.8;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
            "><?php echo esc_html($hero_description); ?></p>
            <?php endif; ?>
            
            <div class="hero-actions" style="
                display: flex;
                gap: 1rem;
                justify-content: <?php echo $hero_text_align === 'center' ? 'center' : ($hero_text_align === 'left' ? 'flex-start' : 'flex-end'); ?>;
                flex-wrap: wrap;
                margin-bottom: 2rem;
            ">
                <a href="<?php echo esc_url($hero_primary_button_url); ?>" 
                   class="bcn-button bcn-button-primary" 
                   style="
                       background: var(--accent-color);
                       color: white;
                       padding: 1rem 2rem;
                       border-radius: 8px;
                       text-decoration: none;
                       font-weight: 600;
                       transition: all 0.3s ease;
                       display: inline-block;
                   "><?php echo esc_html($hero_primary_button_text); ?></a>
                
                <a href="<?php echo esc_url($hero_secondary_button_url); ?>" 
                   class="bcn-button bcn-button-secondary" 
                   style="
                       background: transparent;
                       color: white;
                       padding: 1rem 2rem;
                       border: 2px solid white;
                       border-radius: 8px;
                       text-decoration: none;
                       font-weight: 600;
                       transition: all 0.3s ease;
                       display: inline-block;
                   "><?php echo esc_html($hero_secondary_button_text); ?></a>
            </div>
            
            <?php if ($hero_show_search) : ?>
            <div class="hero-search" style="max-width: 500px; margin: 0 auto;">
                <?php get_search_form(); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<style>
.bcn-hero-section .bcn-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
}

.bcn-hero-section .bcn-button-secondary:hover {
    background: white;
    color: var(--primary-color);
}

@media (max-width: 768px) {
    .bcn-hero-section .hero-title {
        font-size: 2.5rem !important;
    }
    
    .bcn-hero-section .hero-subtitle {
        font-size: 1.25rem !important;
    }
    
    .bcn-hero-section .hero-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .bcn-hero-section .bcn-button {
        width: 100%;
        max-width: 300px;
        text-align: center;
    }
}
</style>
