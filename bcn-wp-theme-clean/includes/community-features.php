<?php
/**
 * Community Features
 *
 * @package BCN_WP_Theme
 * @since 1.0.0
 */

/**
 * Add community section to front page
 */
function bcn_community_section() {
    if (!is_front_page()) {
        return;
    }

    $args = array(
        'post_type'      => 'bcn_event',
        'posts_per_page' => 3,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $events = new WP_Query($args);

    if ($events->have_posts()) :
        ?>
        <section class="community-section">
            <h2><?php esc_html_e('Upcoming Events', 'bcn-wp-theme'); ?></h2>
            <div class="community-grid">
                <?php
                while ($events->have_posts()) :
                    $events->the_post();
                    ?>
                    <article class="event-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="event-thumbnail">
                                <?php the_post_thumbnail('bcn-thumbnail'); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="event-title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <div class="event-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="button">
                            <?php esc_html_e('Learn More', 'bcn-wp-theme'); ?>
                        </a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </section>
        <?php
    endif;
}

/**
 * Register community widget
 */
class BCN_Community_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'bcn_community_widget',
            esc_html__('BCN Community Widget', 'bcn-wp-theme'),
            array('description' => esc_html__('Display community information', 'bcn-wp-theme'))
        );
    }

    /**
     * Front-end display of widget.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        echo '<div class="community-widget-content">';
        echo '<p>' . esc_html__('Join our growing cannabis community!', 'bcn-wp-theme') . '</p>';
        echo '</div>';
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Community', 'bcn-wp-theme');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_attr_e('Title:', 'bcn-wp-theme'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     */
    public function update($new_instance, $old_instance) {
        $instance          = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Register community widget
 */
function bcn_register_community_widget() {
    register_widget('BCN_Community_Widget');
}
add_action('widgets_init', 'bcn_register_community_widget');
