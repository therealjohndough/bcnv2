<?php
/**
 * Events Section Template Part
 * 
 * Displays upcoming events in a responsive grid
 * Customizable via WordPress Customizer
 * 
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

// Get customizer options
$events_title = get_theme_mod('bcn_events_title', 'Upcoming Events');
$events_subtitle = get_theme_mod('bcn_events_subtitle', 'Join us for networking, education, and industry insights');
$events_count = get_theme_mod('bcn_events_count', 6);
$events_show_filters = get_theme_mod('bcn_events_show_filters', true);
$events_button_text = get_theme_mod('bcn_events_button_text', 'View All Events');
$events_button_url = get_theme_mod('bcn_events_button_url', '/events/');
$events_layout = get_theme_mod('bcn_events_layout', 'grid'); // grid, carousel, list
$events_columns = get_theme_mod('bcn_events_columns', 3);

// Query upcoming events
$events_query = new WP_Query([
    'post_type' => 'bcn_event',
    'posts_per_page' => $events_count,
    'meta_key' => 'event_date',
    'orderby' => 'meta_value',
    'meta_type' => 'DATE',
    'order' => 'ASC',
    'meta_query' => [[
        'key' => 'event_date',
        'value' => date('Y-m-d'),
        'compare' => '>=',
        'type' => 'DATE',
    ]]
]);
?>

<section class="bcn-events-section" style="padding: 4rem 0; background: var(--light-bg, #f8f9fa);">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        
        <div class="section-header" style="text-align: center; margin-bottom: 3rem;">
            <h2 class="section-title" style="
                font-size: 2.5rem;
                margin-bottom: 1rem;
                color: var(--primary-color);
                font-weight: 700;
            "><?php echo esc_html($events_title); ?></h2>
            
            <?php if ($events_subtitle) : ?>
            <p class="section-subtitle" style="
                font-size: 1.1rem;
                color: var(--text-color, #666);
                max-width: 600px;
                margin: 0 auto 2rem;
            "><?php echo esc_html($events_subtitle); ?></p>
            <?php endif; ?>
            
            <?php if ($events_show_filters) : ?>
            <div class="events-filters" style="margin-bottom: 2rem;">
                <?php get_template_part('template-parts/event/filters'); ?>
            </div>
            <?php endif; ?>
        </div>
        
        <?php if ($events_query->have_posts()) : ?>
            <div class="events-grid events-layout-<?php echo esc_attr($events_layout); ?>" style="
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 2rem;
                margin-bottom: 3rem;
            ">
                <?php while ($events_query->have_posts()) : $events_query->the_post(); ?>
                    <article class="event-card" style="
                        background: white;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                        transition: transform 0.3s ease, box-shadow 0.3s ease;
                    ">
                        <a class="card-link" href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                            
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="event-image" style="
                                height: 200px;
                                background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'bcn-event-card')); ?>');
                                background-size: cover;
                                background-position: center;
                            "></div>
                            <?php else : ?>
                            <div class="event-image" style="
                                height: 200px;
                                background: var(--primary-color);
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                color: white;
                                font-size: 3rem;
                            ">📅</div>
                            <?php endif; ?>
                            
                            <div class="event-content" style="padding: 1.5rem;">
                                <h3 class="event-title" style="
                                    font-size: 1.25rem;
                                    margin-bottom: 1rem;
                                    color: var(--primary-color);
                                    font-weight: 600;
                                    line-height: 1.3;
                                "><?php the_title(); ?></h3>
                                
                                <div class="event-meta" style="
                                    display: flex;
                                    flex-wrap: wrap;
                                    gap: 1rem;
                                    margin-bottom: 1rem;
                                    font-size: 0.9rem;
                                    color: #666;
                                ">
                                    <?php
                                    $event_date = function_exists('bcn_get_event_date') ? bcn_get_event_date(get_the_ID()) : '';
                                    $event_time = function_exists('bcn_get_event_time') ? bcn_get_event_time(get_the_ID()) : '';
                                    $event_location = function_exists('bcn_get_event_location') ? bcn_get_event_location(get_the_ID()) : '';
                                    ?>
                                    
                                    <?php if ($event_date) : ?>
                                    <span class="meta-item" style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span class="icon">📅</span>
                                        <?php echo esc_html($event_date); ?>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($event_time) : ?>
                                    <span class="meta-item" style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span class="icon">🕐</span>
                                        <?php echo esc_html($event_time); ?>
                                    </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($event_location) : ?>
                                    <span class="meta-item" style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span class="icon">📍</span>
                                        <?php echo esc_html($event_location); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <p class="event-excerpt" style="
                                    color: var(--text-color, #666);
                                    line-height: 1.6;
                                    margin-bottom: 1rem;
                                "><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                                
                                <span class="read-more" style="
                                    color: var(--secondary-color);
                                    font-weight: 600;
                                    text-decoration: none;
                                ">Learn More →</span>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="events-actions" style="text-align: center;">
                <a href="<?php echo esc_url($events_button_url); ?>" 
                   class="bcn-button bcn-button-primary" 
                   style="
                       background: var(--secondary-color);
                       color: white;
                       padding: 1rem 2rem;
                       border-radius: 8px;
                       text-decoration: none;
                       font-weight: 600;
                       display: inline-block;
                       transition: all 0.3s ease;
                   "><?php echo esc_html($events_button_text); ?></a>
            </div>
            
        <?php else : ?>
            <div class="no-events" style="
                text-align: center;
                padding: 3rem;
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            ">
                <p style="font-size: 1.1rem; color: var(--text-color, #666); margin-bottom: 1rem;">
                    No upcoming events scheduled at this time.
                </p>
                <p style="color: var(--text-color, #999);">
                    Check back soon or <a href="<?php echo esc_url($events_button_url); ?>" style="color: var(--secondary-color);">view past events</a>.
                </p>
            </div>
        <?php endif; ?>
        
    </div>
</section>

<style>
.event-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important;
}

.events-layout-carousel {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    gap: 2rem;
    padding-bottom: 1rem;
}

.events-layout-carousel .event-card {
    flex: 0 0 300px;
    scroll-snap-align: start;
}

.events-layout-list .event-card {
    display: flex;
    flex-direction: row;
    align-items: center;
}

.events-layout-list .event-image {
    width: 200px;
    height: 150px;
    flex-shrink: 0;
}

.events-layout-list .event-content {
    flex: 1;
}

@media (max-width: 768px) {
    .events-grid {
        grid-template-columns: 1fr !important;
    }
    
    .events-layout-list .event-card {
        flex-direction: column;
    }
    
    .events-layout-list .event-image {
        width: 100%;
        height: 200px;
    }
}
</style>

<?php
wp_reset_postdata();
