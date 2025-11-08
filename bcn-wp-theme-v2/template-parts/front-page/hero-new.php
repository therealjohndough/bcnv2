<?php
/**
 * Hero Section - New Copy Version
 * 
 * @package BCN_Theme
 * @since 1.0.0
 */

// Get customizer options
$hero_title = get_theme_mod('bcn_hero_title', 'Buffalo Cannabis Network — Events, Education & Industry Connections');
$hero_subtitle = get_theme_mod('bcn_hero_subtitle', 'Connect. Learn. Grow.');
$hero_description = get_theme_mod('bcn_hero_description', 'Buffalo Cannabis Network (BCN) is the premier Buffalo cannabis industry network, empowering brands, operators, and advocates through events, education, and resources. We connect Western New York\'s cannabis community to learn, collaborate, and thrive in New York\'s evolving market.');
$hero_primary_button_text = get_theme_mod('bcn_hero_primary_button_text', 'Become a Member');
$hero_primary_button_url = get_theme_mod('bcn_hero_primary_button_url', '#membership');
$hero_secondary_button_text = get_theme_mod('bcn_hero_secondary_button_text', 'View Events');
$hero_secondary_button_url = get_theme_mod('bcn_hero_secondary_button_url', '/events');
$hero_background_image = get_theme_mod('bcn_hero_background_image');
$hero_overlay_opacity = get_theme_mod('bcn_hero_overlay_opacity', 50);
$hero_height = get_theme_mod('bcn_hero_height', '100vh');
$hero_text_align = get_theme_mod('bcn_hero_text_align', 'center');
$show_search_form = get_theme_mod('bcn_hero_show_search', false);
?>

<section class="bcn-hero-section" style="
    background-image: url('<?php echo esc_url($hero_background_image); ?>');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    height: <?php echo esc_attr($hero_height); ?>;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: <?php echo esc_attr($hero_text_align); ?>;
">
    <!-- Overlay -->
    <div class="bcn-hero-overlay" style="
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, <?php echo esc_attr($hero_overlay_opacity / 100); ?>);
        z-index: 1;
    "></div>
    
    <!-- Content -->
    <div class="bcn-hero-content" style="
        position: relative;
        z-index: 2;
        max-width: 1200px;
        padding: 0 2rem;
        color: white;
    ">
        <div class="bcn-hero-badge" style="
            display: inline-block;
            background: rgba(52, 152, 219, 0.9);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        ">
            Western New York's Premier Cannabis Network
        </div>
        
        <h1 class="bcn-hero-title" style="
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin: 0 0 1.5rem 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        ">
            <?php echo esc_html($hero_title); ?>
        </h1>
        
        <p class="bcn-hero-description" style="
            font-size: 1.25rem;
            line-height: 1.6;
            margin: 0 0 2rem 0;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        ">
            <?php echo esc_html($hero_description); ?>
        </p>
        
        <!-- Key Benefits -->
        <div class="bcn-hero-benefits" style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        ">
            <div class="bcn-benefit-item" style="
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                padding: 1.5rem;
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            ">
                <div class="bcn-benefit-icon" style="
                    font-size: 2rem;
                    margin-bottom: 1rem;
                    color: #3498db;
                ">🤝</div>
                <h3 style="
                    font-size: 1.25rem;
                    font-weight: 600;
                    margin: 0 0 0.5rem 0;
                    color: white;
                ">Connect & Collaborate</h3>
                <p style="
                    font-size: 0.95rem;
                    margin: 0;
                    opacity: 0.9;
                ">Meet growers, retailers, service providers, and professionals shaping the future of cannabis in Buffalo.</p>
            </div>
            
            <div class="bcn-benefit-item" style="
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                padding: 1.5rem;
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            ">
                <div class="bcn-benefit-icon" style="
                    font-size: 2rem;
                    margin-bottom: 1rem;
                    color: #27ae60;
                ">📚</div>
                <h3 style="
                    font-size: 1.25rem;
                    font-weight: 600;
                    margin: 0 0 0.5rem 0;
                    color: white;
                ">Learn & Grow</h3>
                <p style="
                    font-size: 0.95rem;
                    margin: 0;
                    opacity: 0.9;
                ">Access educational programs, workshops, and expert-led sessions to stay ahead of compliance, marketing, and business trends.</p>
            </div>
            
            <div class="bcn-benefit-item" style="
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                padding: 1.5rem;
                border-radius: 12px;
                border: 1px solid rgba(255, 255, 255, 0.2);
            ">
                <div class="bcn-benefit-icon" style="
                    font-size: 2rem;
                    margin-bottom: 1rem;
                    color: #e74c3c;
                ">🎉</div>
                <h3 style="
                    font-size: 1.25rem;
                    font-weight: 600;
                    margin: 0 0 0.5rem 0;
                    color: white;
                ">Attend Exclusive Events</h3>
                <p style="
                    font-size: 0.95rem;
                    margin: 0;
                    opacity: 0.9;
                ">From mixers and networking nights to panel discussions and policy updates, BCN hosts Buffalo cannabis events that drive real connections.</p>
            </div>
        </div>
        
        <!-- CTA Buttons -->
        <div class="bcn-hero-cta" style="
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        ">
            <a href="<?php echo esc_url($hero_primary_button_url); ?>" class="bcn-btn bcn-btn-primary bcn-btn-lg" style="
                background: #3498db;
                color: white;
                padding: 1rem 2rem;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 1.125rem;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
            " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(52, 152, 219, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(52, 152, 219, 0.3)'">
                <?php echo esc_html($hero_primary_button_text); ?>
            </a>
            
            <a href="<?php echo esc_url($hero_secondary_button_url); ?>" class="bcn-btn bcn-btn-secondary bcn-btn-lg" style="
                background: rgba(255, 255, 255, 0.1);
                color: white;
                padding: 1rem 2rem;
                border-radius: 8px;
                text-decoration: none;
                font-weight: 600;
                font-size: 1.125rem;
                transition: all 0.3s ease;
                border: 2px solid rgba(255, 255, 255, 0.3);
                backdrop-filter: blur(10px);
            " onmouseover="this.style.background='rgba(255, 255, 255, 0.2)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.1)'; this.style.transform='translateY(0)'">
                <?php echo esc_html($hero_secondary_button_text); ?>
            </a>
        </div>
        
        <!-- Search Form (if enabled) -->
        <?php if ($show_search_form): ?>
        <div class="bcn-hero-search" style="
            margin-top: 3rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        ">
            <?php get_search_form(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Mobile Responsive Styles -->
<style>
@media (max-width: 768px) {
    .bcn-hero-title {
        font-size: 2.5rem !important;
    }
    
    .bcn-hero-description {
        font-size: 1.125rem !important;
    }
    
    .bcn-hero-benefits {
        grid-template-columns: 1fr !important;
        gap: 1rem !important;
    }
    
    .bcn-benefit-item {
        padding: 1rem !important;
    }
    
    .bcn-hero-cta {
        flex-direction: column !important;
        align-items: center !important;
    }
    
    .bcn-btn {
        width: 100% !important;
        max-width: 300px !important;
    }
}
</style>
